<?php

namespace App\Services\Catalog;

use App\Models\ProductModel;
use App\Services\BaseService;
use App\Services\Marketing\FlashsaleService;
use App\Services\Pricing\PriceService;

class ProductService extends BaseService
{
    protected $productModel;
    protected $flashsaleService;
    protected $priceService;

    public function __construct()
    {
        $this->productModel     = model(ProductModel::class);
        $this->flashsaleService = new FlashsaleService();
        $this->priceService     = new PriceService();
    }

    public function getDetail(int $productId): array
    {
        if ($productId <= 0) {
            return [];
        }

        return $this->safeCall(
            fn() => $this->productModel->getDetailProduct($productId),
            []
        );
    }

    public function getPublicProductsByGame(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        return $this->safeCall(function () use ($gameId) {
            $products       = $this->productModel->getProductsByGame($gameId);
            $flashsaleItems = $this->flashsaleService->getActiveItemsForProducts(array_column($products, 'id'));

            foreach ($products as &$product) {
                $flashsaleItem = $flashsaleItems[(int) $product['id']] ?? [];
                $finalPrice    = $this->priceService->getFinalPrice($product, $flashsaleItem ?: null);
                $product       = $this->mapPublicProduct($product, $finalPrice, ! empty($flashsaleItem));
            }
            unset($product);

            return $products;
        }, []);
    }

    protected function mapPublicProduct(array $product, float $finalPrice, bool $isFlashsale): array
    {
        return [
            'id'                    => (int) $product['id'],
            'product'               => $product['product'],
            'is_flashsale'          => $isFlashsale,
            'final_price_formatted' => $this->priceService->formatPrice($finalPrice),
            'price_formatted'       => $this->priceService->formatPrice((float) ($product['price'] ?? 0)),
        ];
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD, not restricted to status = 'On'
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', ?int $gameId = null, string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->productModel->paginatedList(trim($keyword), $gameId, trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function findAny(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->productModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $name = trim((string) ($data['product'] ?? ''));

        if ($name === '') {
            return $this->fail('Nama produk wajib diisi');
        }

        if ((int) ($data['games_id'] ?? 0) <= 0) {
            return $this->fail('Game wajib dipilih');
        }

        $sku = trim((string) ($data['sku'] ?? ''));

        if ($sku === '') {
            return $this->fail('SKU wajib diisi');
        }

        $payload = $this->buildPayload($data, $name, $sku);

        $id = $this->safeCall(fn() => $this->productModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan produk', ['errors' => $this->productModel->errors()]);
        }

        return $this->success('Produk berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Produk tidak ditemukan');
        }

        $name = trim((string) ($data['product'] ?? $existing['product']));

        if ($name === '') {
            return $this->fail('Nama produk wajib diisi');
        }

        if ((int) ($data['games_id'] ?? $existing['games_id']) <= 0) {
            return $this->fail('Game wajib dipilih');
        }

        $sku = trim((string) ($data['sku'] ?? $existing['sku']));

        if ($sku === '') {
            return $this->fail('SKU wajib diisi');
        }

        $payload = $this->buildPayload($data, $name, $sku, $existing);

        $updated = $this->safeCall(fn() => $this->productModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui produk', ['errors' => $this->productModel->errors()]);
        }

        return $this->success('Produk berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Produk tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->productModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus produk');
        }

        return $this->success('Produk berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Produk tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->productModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status produk');
        }

        return $this->success('Status produk diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $name, string $sku, array $existing = []): array
    {
        return [
            'games_id'  => (int) ($data['games_id'] ?? $existing['games_id'] ?? 0),
            'product'   => $name,
            'sku'       => $sku,
            'provider'  => trim((string) ($data['provider'] ?? $existing['provider'] ?? '')) ?: null,
            'raw_price' => (float) ($data['raw_price'] ?? $existing['raw_price'] ?? 0),
            'price'     => (float) ($data['price'] ?? $existing['price'] ?? 0),
            'sort'      => (int) ($data['sort'] ?? $existing['sort'] ?? 0),
            'status'    => $this->normalizeStatus($data['status'] ?? ($existing['status'] ?? 'On'), $existing['status'] ?? 'On'),
        ];
    }

    protected function normalizeStatus($status, string $fallback): string
    {
        $status = (string) $status;

        return in_array($status, ['On', 'Off'], true) ? $status : $fallback;
    }
}
