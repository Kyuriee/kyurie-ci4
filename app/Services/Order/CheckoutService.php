<?php

namespace App\Services\Order;

use App\Services\baseService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Pricing\PriceService;
use App\Services\Order\PaymentMethodService;
use App\Services\Catalog\TargetService;

class CheckoutService extends baseService
{
    protected $gameService;
    protected $productService;
    protected $flashsaleService;
    protected $priceService;
    protected $paymentMethodService;
    protected $targetService;

    public function __construct()
    {
        $this->gameService          = new GameService();
        $this->productService       = new ProductService();
        $this->flashsaleService     = new FlashsaleService();
        $this->priceService         = new PriceService();
        $this->paymentMethodService = new PaymentMethodService();
        $this->targetService        = new TargetService();
    }

    public function prepareOrder(array $payload): array
    {
        return $this->safeCall(function () use ($payload) {
            $game_slug         = trim($payload['game'] ?? '');
            $product_id        = (int) ($payload['product_id'] ?? 0);
            $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);
            $customer_id       = trim($payload['customer_id'] ?? '');
            $zone_id           = trim($payload['zone_id'] ?? '');

            if ($game_slug === '') {
                return $this->fail('Game tidak valid');
            }

            $game = $this->gameService->getActiveBySlug($game_slug);

            if (empty($game) || ($game['status'] ?? '') !== 'On') {
                return $this->fail('Game tidak tersedia');
            }

            if ($product_id <= 0) {
                return $this->fail('Produk wajib dipilih');
            }

            $product = $this->productService->getDetail($product_id);

            if (empty($product) || ($product['status'] ?? '') !== 'On') {
                return $this->fail('Produk tidak tersedia');
            }

            if ((int) $product['games_id'] !== (int) $game['id']) {
                return $this->fail('Produk tidak sesuai dengan game');
            }

            $flashsale_item = $this->flashsaleService->getActiveItemForProduct($product_id);

            if (! empty($flashsale_item) && ! $this->flashsaleService->hasAvailableStock($flashsale_item)) {
                return $this->fail('Stok flash sale untuk produk ini sudah habis');
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

            if ($payment_method_id <= 0) {
                return $this->fail('Metode pembayaran wajib dipilih');
            }

            $payment_method = $this->paymentMethodService->getActiveMethod($payment_method_id);

            if (empty($payment_method)) {
                return $this->fail('Metode pembayaran tidak tersedia');
            }

            $final_price = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);

            return $this->success('Pesanan siap', [
                'game'             => $game,
                'product'          => $product,
                'payment_method'   => $payment_method,
                'flashsale_item'   => $flashsale_item ?: null,
                'customer_id'      => $target_validation['data']['customer_id'] ?? $customer_id,
                'zone_id'          => $target_validation['data']['zone_id'] ?? $zone_id,
                'final_price'      => $final_price,
                'price_formatted'  => $this->priceService->formatPrice($final_price),
            ]);
        }, $this->fail('Gagal menyiapkan pesanan'));
    }
}
