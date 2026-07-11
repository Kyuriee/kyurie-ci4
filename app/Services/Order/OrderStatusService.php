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
}
