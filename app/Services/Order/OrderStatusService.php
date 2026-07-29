<?php

namespace App\Services\Order;


use App\Models\OrderModel;
use App\Services\BaseService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Marketing\CouponService;

class OrderStatusService extends BaseService
{
    protected $orderModel;
    protected $flashsaleService;
    protected $couponService;

    public function __construct()
    {
        $this->orderModel       = model(OrderModel::class);
        $this->flashsaleService = new FlashsaleService();
        $this->couponService    = new CouponService();
    }

    public function markStatus(int $orderId, string $status, array $extra = []): bool
    {
        $order = $this->orderModel->find($orderId);

        if (empty($order)) {
            return false;
        }

        if ($status === 'success' && ($order['status'] ?? '') === 'success') {
            return true;
        }

        $hasFlashsale = ! empty($order['flashsale_item_id']);
        $hasCoupon    = ! empty($order['coupon_id']);

        if ($status === 'success' && ($hasFlashsale || $hasCoupon)) {
            return $this->safeCall(function () use ($orderId, $status, $extra, $order, $hasFlashsale, $hasCoupon) {
                $db = \Config\Database::connect();
                $db->transBegin();

                try {
                    $updated = $this->orderModel->updateStatusIfNot($orderId, $status, 'success', $extra);

                    if (! $updated) {
                        $db->transRollback();
                        return true;
                    }

                    if ($hasFlashsale) {
                        $stockConsumed = $this->flashsaleService->consumeStock((int) $order['flashsale_item_id']);

                        if (! $stockConsumed) {
                            $db->transRollback();
                            return false;
                        }
                    }

                    if ($hasCoupon) {
                        $couponConsumed = $this->couponService->consumeUsage(
                            (int) $order['coupon_id'],
                            $orderId,
                            ! empty($order['user_id']) ? (int) $order['user_id'] : null,
                            $order['guest_ip'] ?? null,
                            (float) ($order['coupon_discount'] ?? 0)
                        );

                        if (! $couponConsumed) {
                            $db->transRollback();
                            return false;
                        }
                    }

                    $db->transCommit();

                    return $db->transStatus();
                } catch (\Throwable $e) {
                    $db->transRollback();
                    throw $e;
                }
            }, false);
        }

        $updated = $this->orderModel->updateStatus($orderId, $status, $extra);

        return $updated;
    }

    /**
     * Admin-facing wrapper around markStatus() — validates the target
     * status and returns a structured success/fail response instead of
     * a bare bool, for use by the backoffice controller.
     */
    public function updateStatusByAdmin(int $orderId, string $status, ?string $note = null): array
    {
        $order = $this->orderModel->find($orderId);

        if (empty($order)) {
            return $this->fail('Pesanan tidak ditemukan');
        }

        if (! in_array($status, ['pending', 'processing', 'success', 'failed'], true)) {
            return $this->fail('Status tidak valid');
        }

        $extra = [];

        if ($note !== null && trim($note) !== '') {
            $extra['note'] = trim($note);
        }

        $updated = $this->markStatus($orderId, $status, $extra);

        if (! $updated) {
            return $this->fail('Gagal mengubah status pesanan');
        }

        return $this->success('Status pesanan diperbarui', ['status' => $status]);
    }
}
