<?php

namespace App\Services;

use App\Models\OrderModel;

class OrderService extends baseService
{
    protected $gameService;
    protected $productService;
    protected $orderModel;
    protected $flashsaleService;
    protected $priceService;
    protected $paymentService;
    protected $targetService;

    public function __construct()
    {
        $this->gameService      = new GameService();
        $this->productService   = new ProductService();
        $this->orderModel       = model(OrderModel::class);
        $this->flashsaleService = new FlashsaleService();
        $this->priceService     = new PriceService();
        $this->paymentService   = new PaymentService();
        $this->targetService    = new TargetService();
    }

    public function create(array $payload): array
    {
        $user_id           = (int) ($payload['auth_user_id'] ?? 0);
        $product_id        = (int) ($payload['product_id'] ?? 0);
        $customer_id       = trim($payload['customer_id'] ?? '');
        $zone_id           = trim($payload['zone_id'] ?? '');
        $payment_method_id = (int) ($payload['payment_method_id'] ?? 0);

        if ($product_id <= 0) {
            return ['success' => false, 'message' => 'Produk wajib dipilih'];
        }

        $product = $this->productService->getDetail($product_id);

        if (empty($product)) {
            return ['success' => false, 'message' => 'Produk tidak tersedia'];
        }

        $game = $this->gameService->getById((int) $product['games_id']);

        if (empty($game)) {
            return ['success' => false, 'message' => 'Game tidak ditemukan'];
        }

        $target_validation = $this->targetService->validatePayload($game['target'] ?? 'default', $game['input_custom'] ?? null, array_merge($payload, [
            'customer_id' => $customer_id,
            'zone_id'     => $zone_id,
        ]));

        if (! $target_validation['success']) {
            return ['success' => false, 'message' => $target_validation['message']];
        }

        $customer_id = $target_validation['data']['customer_id'];
        $zone_id     = $target_validation['data']['zone_id'];

        $flashsale_item = $this->flashsaleService->getActiveItemForProduct($product_id);

        if (! $this->flashsaleService->hasAvailableStock($flashsale_item)) {
            return ['success' => false, 'message' => 'Stok flash sale untuk produk ini sudah habis'];
        }

        $payment_method = $this->paymentService->getMethod($payment_method_id);
        $final_price    = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);

        $invoice = $this->orderModel->generateInvoice();

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

    /**
     * Ganti status order. Kalau jadi 'success' dan order ini terikat ke
     * flashsale item, otomatis nambah `sold` dan auto-off kalau stok habis.
     *
     * Belum ada yang manggil method ini — nunggu webhook/confirm-payment
     * dibangun pas kita masuk ke Checkout & Payment flow.
     */
    public function markStatus(int $orderId, string $status, array $extra = []): bool
    {
        $order = $this->orderModel->find($orderId);

        if (empty($order)) {
            return false;
        }

        $updated = $this->orderModel->updateStatus($orderId, $status, $extra);

        if ($updated && $status === 'success' && ! empty($order['flashsale_item_id'])) {
            $stockOk = $this->flashsaleService->incrementSold((int) $order['flashsale_item_id']);

            // incrementSold sekarang atomic (UPDATE ... WHERE sold < stock), jadi
            // false di sini artinya stok flashsale-nya emang udah abis pas order
            // ini confirm — bukan error fatal, tapi worth di-log biar ketauan
            // ada order yang sukses dibayar tapi stok promo-nya udah kehabisan
            // duluan (butuh follow up manual/CS ke customer).
            if (! $stockOk) {
                $this->logWarning(sprintf(
                    'Order #%d (invoice %s) sukses tapi flashsale_item_id %d gagal increment — stok kemungkinan sudah habis.',
                    $orderId,
                    $order['invoice'] ?? '-',
                    (int) $order['flashsale_item_id']
                ));
            }
        }

        return $updated;
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

    public function getDetailPage(string $token): array
    {
        $row = $this->orderModel->findByTokenWithGame($token);

        if (empty($row)) {
            return [];
        }

        return [
            'order' => $this->mapOrder($row),
            'game'  => $this->mapGame($row),
        ];
    }

    public function checkInvoice(string $invoice): array
    {
        $invoice = trim($invoice);

        if ($invoice === '') {
            return [
                'success' => false,
                'message' => 'Masukkan nomor invoice',
                'data'    => [],
            ];
        }

        $order = $this->orderModel->findByInvoice($invoice);

        if (empty($order)) {
            return [
                'success' => false,
                'message' => 'Invoice tidak ditemukan',
                'data'    => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Invoice ditemukan',
            'data'    => $order,
        ];
    }

    protected function mapOrder(array $row): array
    {
        $gameFields = [
            'game_id',
            'games',
            'slug',
            'image',
            'banner',
            'description',
            'target',
            'input_custom',
        ];

        return array_diff_key($row, array_flip($gameFields));
    }

    protected function mapGame(array $row): array
    {
        return [
            'id'          => $row['game_id'] ?? null,
            'games'       => $row['games'] ?? null,
            'slug'        => $row['slug'] ?? null,
            'image'       => $row['image'] ?? null,
            'banner'      => $row['banner'] ?? null,
            'description' => $row['description'] ?? null,
            'target_form' => $this->targetService->getFormConfig($row['target'] ?? 'default', $row['input_custom'] ?? null),
        ];
    }
}