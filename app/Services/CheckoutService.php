<?php

namespace App\Services;

use App\Models\FlashsaleItemModel;
use App\Models\GameModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductModel;

class CheckoutService extends baseService
{
    protected $gameModel;
    protected $productModel;
    protected $paymentMethodModel;
    protected $flashsaleItemModel;
    protected $priceService;

    public function __construct()
    {
        $this->gameModel          = model(GameModel::class);
        $this->productModel       = model(ProductModel::class);
        $this->paymentMethodModel = model(PaymentMethodModel::class);
        $this->flashsaleItemModel = model(FlashsaleItemModel::class);
        $this->priceService       = new PriceService();
    }

    public function prepareOrder(array $payload): array
    {
        $game_slug         = trim($payload['game'] ?? '');
        $product_id        = (int) ($payload['product_id'] ?? 0);
        $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);
        $customer_id       = trim($payload['customer_id'] ?? '');
        $zone_id           = trim($payload['zone_id'] ?? '');

        if ($game_slug === '') {
            return $this->fail('Game tidak valid');
        }

        $game = $this->gameModel->getDetailBySlug($game_slug);

        if (empty($game) || $game['status'] !== 'On') {
            return $this->fail('Game tidak tersedia');
        }

        if ($product_id <= 0) {
            return $this->fail('Produk wajib dipilih');
        }

        $product = $this->productModel->getDetailProduct($product_id);

        if (empty($product) || $product['status'] !== 'On') {
            return $this->fail('Produk tidak tersedia');
        }

        if ((int) $product['games_id'] !== (int) $game['id']) {
            return $this->fail('Produk tidak sesuai dengan game');
        }

        // Produk reguler unlimited stock. Kalau lagi flashsale, cek stok flashsale_items.
        $flashsale_item = $this->flashsaleItemModel->getActiveForProduct($product_id);

        if (! empty($flashsale_item) && (int) $flashsale_item['sold'] >= (int) $flashsale_item['stock']) {
            return $this->fail('Stok flash sale untuk produk ini sudah habis');
        }

        if ($customer_id === '') {
            return $this->fail('ID pemain wajib diisi');
        }

        if ($payment_method_id > 0) {
            $payment_method = $this->paymentMethodModel->find($payment_method_id);

            if (empty($payment_method) || $payment_method['status'] !== 'On') {
                return $this->fail('Metode pembayaran tidak tersedia');
            }
        }

        $final_price = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);

        return [
            'success' => true,
            'message' => 'Pesanan siap',
            'data'    => [
                'price_formatted' => $this->priceService->formatPrice($final_price),
            ],
        ];
    }

    protected function fail(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data'    => [],
        ];
    }
}
