<?php

namespace App\Services;

use App\Models\GameModel;
use App\Models\OrderModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductModel;

class OrderService extends baseService
{
    protected $gameModel;
    protected $productModel;
    protected $orderModel;
    protected $paymentMethodModel;
    protected $priceService;

    public function __construct()
    {
        $this->gameModel          = model(GameModel::class);
        $this->productModel       = model(ProductModel::class);
        $this->orderModel         = model(OrderModel::class);
        $this->paymentMethodModel = model(PaymentMethodModel::class);
        $this->priceService       = new PriceService();
    }

    public function create(array $payload): array
    {
        $user_id         = (int) ($payload['user_id'] ?? 0);
        $product_id      = (int) ($payload['product_id'] ?? 0);
        $customer_id     = trim($payload['customer_id'] ?? '');
        $zone_id         = trim($payload['zone_id'] ?? '');
        $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);

        if ($user_id <= 0) {
            return ['success' => false, 'message' => 'User tidak valid'];
        }

        if ($product_id <= 0) {
            return ['success' => false, 'message' => 'Produk wajib dipilih'];
        }

        if ($customer_id === '') {
            return ['success' => false, 'message' => 'ID pemain wajib diisi'];
        }

        $product = $this->productModel->getDetailProduct($product_id);

        if (empty($product)) {
            return ['success' => false, 'message' => 'Produk tidak tersedia'];
        }

        $game = $this->gameModel->find($product['games_id']);

        if (empty($game)) {
            return ['success' => false, 'message' => 'Game tidak ditemukan'];
        }

        $payment_method = $this->paymentMethodModel->find($payment_method_id);
        $final_price    = $this->priceService->getFinalPrice($product);

        $invoice = $this->orderModel->generateInvoice();

        $order_id = $this->orderModel->insert([
            'invoice'           => $invoice,
            'user_id'           => $user_id,
            'product_id'        => $product_id,
            'payment_method_id' => $payment_method_id ?: null,
            'customer_id'       => $customer_id,
            'zone_id'           => $zone_id ?: null,
            'product_name'      => $product['product'],
            'game_name'         => $game['games'],
            'price'             => $final_price,
            'fee'               => 0,
            'total'             => $final_price,
            'status'            => 'pending',
        ]);

        $order = $this->orderModel->find($order_id);

        return [
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data'    => [
                'order_id'      => $order_id,
                'invoice'       => $invoice,
                'payment_token' => $order['payment_token'],
                'total'         => $final_price,
                'payment'       => $payment_method,
            ],
        ];
    }

    public function getByToken(string $token): array
    {
        $order = $this->orderModel->findByToken($token);

        if (empty($order)) {
            return [];
        }

        $product = $this->productModel->find((int) $order['product_id']);
        $game    = empty($product) ? [] : $this->gameModel->find((int) $product['games_id']);

        return [
            'order' => $order,
            'game'  => $game,
        ];
    }

    public function getByInvoice(string $invoice): array
    {
        return $this->orderModel->findByInvoice($invoice);
    }

    public function getOrdersByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->orderModel->getByUser($userId, $limit, $offset);
    }

    public function getOrder(int $orderId): array
    {
        return $this->orderModel->find($orderId);
    }
}
