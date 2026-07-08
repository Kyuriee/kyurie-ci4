<?php

namespace App\Services\Order;


use App\Models\OrderModel;
use App\Services\baseService;
use App\Services\Marketing\FlashsaleService;

class OrderStatusService extends baseService
{
    protected $orderModel;
    protected $flashsaleService;

    public function __construct()
    {
        $this->orderModel       = model(OrderModel::class);
        $this->flashsaleService = new FlashsaleService();
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

        if ($status === 'success' && ! empty($order['flashsale_item_id'])) {
            $db = \Config\Database::connect();

            $db->transBegin();

            $updated = $this->orderModel->updateStatusIfNot($orderId, $status, 'success', $extra);

            if (! $updated) {
                $db->transRollback();
                return true;
            }

            $stockConsumed = $this->flashsaleService->consumeStock((int) $order['flashsale_item_id']);

            if (! $stockConsumed) {
                $db->transRollback();
                return false;
            }

            $db->transCommit();

            return $db->transStatus();
        }

        $updated = $this->orderModel->updateStatus($orderId, $status, $extra);

        return $updated;
    }
}
