<?php

namespace App\Services\Order;

use App\Models\OrderModel;
use App\Services\BaseService;

class OrderService extends BaseService
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = model(OrderModel::class);
    }

    /**
     * Persists an order from data already validated by
     * CheckoutOrchestrator::prepareOrder(). Does not re-derive or
     * re-check product/game/payment/price — caller is responsible for
     * passing a trusted, validated payload.
     */
    public function create(array $validated, int $userId = 0): array
    {
        return $this->safeCall(function () use ($validated, $userId) {
            $product         = $validated['product'];
            $game            = $validated['game'];
            $payment_method  = $validated['payment_method'];
            $flashsale_item  = $validated['flashsale_item'] ?? null;
            $coupon          = $validated['coupon'] ?? null;
            $coupon_discount = $validated['coupon_discount'] ?? 0;
            $customer_id     = $validated['customer_id'];
            $zone_id         = $validated['zone_id'];
            $final_price     = $validated['final_price'];

            $order_id = $this->orderModel->insert([
                'user_id'           => $userId > 0 ? $userId : null,
                'guest_ip'          => $userId > 0 ? null : ($validated['guest_ip'] ?? null),
                'product_id'        => (int) $product['id'],
                'flashsale_item_id' => $flashsale_item['id'] ?? null,
                'coupon_id'         => $coupon['id'] ?? null,
                'coupon_discount'   => $coupon_discount,
                'payment_method_id' => (int) $payment_method['id'],
                'customer_id'       => $customer_id,
                'zone_id'           => $zone_id ?: null,
                'contact_email'     => $validated['contact_email'] ?? null,
                'contact_phone'     => $validated['contact_phone'] ?? null,
                'product_name'      => $product['product'],
                'game_name'         => $game['games'],
                'price'             => $final_price,
                'fee'               => 0,
                'total'             => $final_price,
                'status'            => 'pending',
            ]);

            if (! $order_id) {
                return $this->fail('Gagal membuat pesanan');
            }

            $order = $this->orderModel->find($order_id);

            if (empty($order)) {
                return $this->fail('Pesanan berhasil dibuat, tetapi data pesanan tidak ditemukan');
            }

            return $this->success('Pesanan berhasil dibuat', [
                'order_id'      => $order_id,
                'invoice'       => $order['invoice'],
                'payment_token' => $order['payment_token'],
                'total'         => $final_price,
                'payment'       => $payment_method,
            ]);
        }, $this->fail('Gagal membuat pesanan'));
    }

    public function getOrdersByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->orderModel->getByUser($userId, $limit, $offset);
    }

    public function getOrderForUser(int $orderId, int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $order = $this->orderModel->find($orderId);

        if (empty($order) || (int) ($order['user_id'] ?? 0) !== $userId) {
            return [];
        }

        return $order;
    }
}
