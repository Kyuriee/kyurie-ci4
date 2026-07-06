<?php

namespace App\Services;

class CheckoutService extends baseService
{
    protected $gameService;
    protected $productService;
    protected $flashsaleService;
    protected $priceService;
    protected $paymentService;
    protected $targetService;

    public function __construct()
    {
        $this->gameService      = new GameService();
        $this->productService   = new ProductService();
        $this->flashsaleService = new FlashsaleService();
        $this->priceService     = new PriceService();
        $this->paymentService   = new PaymentService();
        $this->targetService    = new TargetService();
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

        $game = $this->gameService->getActiveBySlug($game_slug);

        if (empty($game) || $game['status'] !== 'On') {
            return $this->fail('Game tidak tersedia');
        }

        if ($product_id <= 0) {
            return $this->fail('Produk wajib dipilih');
        }

        $product = $this->productService->getDetail($product_id);

        if (empty($product) || $product['status'] !== 'On') {
            return $this->fail('Produk tidak tersedia');
        }

        if ((int) $product['games_id'] !== (int) $game['id']) {
            return $this->fail('Produk tidak sesuai dengan game');
        }

        // Produk reguler unlimited stock. Kalau lagi flashsale, cek stok flashsale_items.
        $flashsale_item = $this->flashsaleService->getActiveItemForProduct($product_id);

        if (! $this->flashsaleService->hasAvailableStock($flashsale_item)) {
            return $this->fail('Stok flash sale untuk produk ini sudah habis');
        }

        $target_validation = $this->targetService->validatePayload($game['target'] ?? 'default', $game['input_custom'] ?? null, array_merge($payload, [
            'customer_id' => $customer_id,
            'zone_id'     => $zone_id,
        ]));

        if (! $target_validation['success']) {
            return $this->fail($target_validation['message']);
        }

        if ($payment_method_id <= 0) {
            return $this->fail('Metode pembayaran wajib dipilih');
        }

        $payment_method = $this->paymentService->getActiveMethod($payment_method_id);

        if (empty($payment_method)) {
            return $this->fail('Metode pembayaran tidak tersedia');
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
