<?php

namespace App\Services\Order;

use App\Models\OrderModel;
use App\Services\baseService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Pricing\PriceService;
use App\Services\Order\PaymentMethodService;
use App\Services\Catalog\TargetService;

class OrderService extends baseService
{
    protected $gameService;
    protected $productService;
    protected $orderModel;
    protected $flashsaleService;
    protected $priceService;
    protected $paymentMethodService;
    protected $targetService;

    public function __construct()
    {
        $this->orderModel           = model(OrderModel::class);
        $this->gameService          = new GameService();
        $this->productService       = new ProductService();
        $this->flashsaleService     = new FlashsaleService();
        $this->priceService         = new PriceService();
        $this->paymentMethodService = new PaymentMethodService();
        $this->targetService        = new TargetService();
    }

    public function create(array $payload): array
    {
        return $this->safeCall(function () use ($payload) {
            $user_id           = (int) ($payload['auth_user_id'] ?? 0);
            $product_id        = (int) ($payload['product_id'] ?? 0);
            $customer_id       = trim($payload['customer_id'] ?? '');
            $zone_id           = trim($payload['zone_id'] ?? '');
            $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);

            if ($product_id <= 0) {
                return $this->fail('Produk wajib dipilih');
            }

            $product = $this->productService->getDetail($product_id);

            if (empty($product)) {
                return $this->fail('Produk tidak tersedia');
            }

            $game = $this->gameService->getById((int) $product['games_id']);

            if (empty($game)) {
                return $this->fail('Game tidak ditemukan');
            }

            $target_validation = $this->targetService->validatePayload(
                $game['target'] ?? 'default',
                $game['input_custom'] ?? null,
                array_merge($payload, [
                    'customer_id' => $customer_id,
                    'zone_id'     => $zone_id,
                ])
            );

            if (! $target_validation['success']) {
                return $this->fail($target_validation['message']);
            }

            $customer_id = $target_validation['data']['customer_id'];
            $zone_id     = $target_validation['data']['zone_id'];

            $flashsale_item = $this->flashsaleService->getActiveItemForProduct($product_id);

            if (! empty($flashsale_item) && ! $this->flashsaleService->hasAvailableStock($flashsale_item)) {
                return $this->fail('Stok flash sale untuk produk ini sudah habis');
            }

            if ($payment_method_id <= 0) {
                return $this->fail('Metode pembayaran wajib dipilih');
            }

            $payment_method = $this->paymentMethodService->getActiveMethod($payment_method_id);

            if (empty($payment_method)) {
                return $this->fail('Metode pembayaran tidak tersedia');
            }

            $final_price = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);
            $invoice     = $this->orderModel->generateInvoice();

            $order_id = $this->orderModel->insert([
                'invoice'           => $invoice,
                'user_id'           => $user_id > 0 ? $user_id : null,
                'product_id'        => $product_id,
                'flashsale_item_id' => $flashsale_item['id'] ?? null,
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

            if (! $order_id) {
                return $this->fail('Gagal membuat pesanan');
            }

            $order = $this->orderModel->find($order_id);

            if (empty($order)) {
                return $this->fail('Pesanan berhasil dibuat, tetapi data pesanan tidak ditemukan');
            }

            return $this->success('Pesanan berhasil dibuat', [
                'order_id'      => $order_id,
                'invoice'       => $invoice,
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
