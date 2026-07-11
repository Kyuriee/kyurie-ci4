<?php

namespace App\Services\Orchestrators\Storefront;

use App\Services\BaseService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Marketing\CouponService;
use App\Services\Pricing\PriceService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Catalog\GameAccountInputService;

class CheckoutOrchestrator extends BaseService
{
    protected $gameService;
    protected $productService;
    protected $flashsaleService;
    protected $couponService;
    protected $priceService;
    protected $paymentMethodService;
    protected $gameAccountInputService;

    public function __construct()
    {
        $this->gameService              = new GameService();
        $this->productService           = new ProductService();
        $this->flashsaleService         = new FlashsaleService();
        $this->couponService            = new CouponService();
        $this->priceService             = new PriceService();
        $this->paymentMethodService     = new PaymentMethodService();
        $this->gameAccountInputService  = new GameAccountInputService();
    }

    public function prepareOrder(array $payload): array
    {
        return $this->safeCall(function () use ($payload) {
            $game_slug         = trim($payload['game'] ?? '');
            $product_id        = (int) ($payload['product_id'] ?? 0);
            $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);
            $customer_id       = trim($payload['customer_id'] ?? '');
            $zone_id           = trim($payload['zone_id'] ?? '');
            $coupon_code       = trim($payload['coupon_code'] ?? '');
            $contact_email     = trim($payload['contact_email'] ?? '');
            $contact_phone     = trim($payload['contact_phone'] ?? '');

            if ($contact_email !== '' && ! filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
                return $this->fail('Format email tidak valid');
            }

            if ($contact_phone !== '') {
                // Worldwide/E.164-ish: optional leading +, 7-15 digits total.
                $normalizedPhone = preg_replace('/[\s\-()]/', '', $contact_phone);

                if (! preg_match('/^\+?[0-9]{7,15}$/', $normalizedPhone)) {
                    return $this->fail('Format nomor HP/WhatsApp tidak valid');
                }

                $contact_phone = $normalizedPhone;
            }

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

            $target_validation = $this->gameAccountInputService->validatePayload(
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

            $subtotal = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);

            $coupon          = null;
            $coupon_discount = 0.0;

            if ($coupon_code !== '') {
                $coupon_validation = $this->couponService->validateCoupon($coupon_code, [
                    'subtotal'   => $subtotal,
                    'game_id'    => $game['id'],
                    'product_id' => $product['id'],
                    'user_level' => $payload['user_level'] ?? 'guest',
                    'user_id'    => $payload['user_id'] ?? null,
                    'guest_ip'   => $payload['guest_ip'] ?? null,
                ]);

                if (! $coupon_validation['success']) {
                    return $this->fail($coupon_validation['message']);
                }

                $coupon          = $coupon_validation['data']['coupon'];
                $coupon_discount = $coupon_validation['data']['discount'];
            }

            $final_price = max(0, $subtotal - $coupon_discount);

            return $this->success('Pesanan siap', [
                'game'                     => $game,
                'product'                  => $product,
                'payment_method'           => $payment_method,
                'flashsale_item'           => $flashsale_item ?: null,
                'coupon'                   => $coupon,
                'coupon_discount'          => $coupon_discount,
                'coupon_discount_formatted' => $coupon_discount > 0 ? $this->priceService->formatPrice($coupon_discount) : null,
                'guest_ip'                 => $payload['guest_ip'] ?? null,
                'customer_id'              => $target_validation['data']['customer_id'] ?? $customer_id,
                'zone_id'                  => $target_validation['data']['zone_id'] ?? $zone_id,
                'contact_email'            => $contact_email ?: null,
                'contact_phone'            => $contact_phone ?: null,
                'subtotal'                 => $subtotal,
                'subtotal_formatted'       => $this->priceService->formatPrice($subtotal),
                'final_price'              => $final_price,
                'price_formatted'          => $this->priceService->formatPrice($final_price),
            ]);
        }, $this->fail('Gagal menyiapkan pesanan'));
    }
}
