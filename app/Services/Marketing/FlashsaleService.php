<?php

namespace App\Services\Marketing;

use App\Services\BaseService;
use App\Models\FlashsaleItemModel;
use App\Models\FlashsaleModel;

class FlashsaleService extends BaseService
{
    protected $flashsaleItemModel;
    protected $flashsaleModel;

    public function __construct()
    {
        $this->flashsaleItemModel = model(FlashsaleItemModel::class);
        $this->flashsaleModel     = model(FlashsaleModel::class);
    }

    public function getActiveItemForProduct(int $productId): array
    {
        if ($productId <= 0) {
            return [];
        }

        return $this->flashsaleItemModel->getActiveForProduct($productId);
    }

    public function getActiveItemsForProducts(array $productIds): array
    {
        return $this->flashsaleItemModel->getActiveForProducts($productIds);
    }

    public function hasAvailableStock(array $flashsaleItem): bool
    {
        if (empty($flashsaleItem)) {
            return true;
        }

        return (int) ($flashsaleItem['sold'] ?? 0) < (int) ($flashsaleItem['stock'] ?? 0);
    }

    public function consumeStock(int $flashsaleItemId): bool
    {
        if ($flashsaleItemId <= 0) {
            return false;
        }

        return $this->flashsaleItemModel->consumeStock($flashsaleItemId);
    }

    public function getHomeDisplay(): array
    {
        return $this->safeCall(function () {
            $flashsale = $this->flashsaleModel->getActive();

            if (empty($flashsale)) {
                return [];
            }

            $remaining = max(
                0,
                strtotime($flashsale['date_end']) - time()
            );
            $products = $this->flashsaleItemModel->getHomeProductsByFlashsale((int) $flashsale['id']);

            $flashsale['remaining_seconds'] = $remaining;
            $flashsale['countdown'] = [
                'days'    => floor($remaining / 86400),
                'hours'   => floor(($remaining % 86400) / 3600),
                'minutes' => floor(($remaining % 3600) / 60),
                'seconds' => $remaining % 60,
            ];
            $flashsale['products'] = $products;
            $flashsale['progress'] = $this->calculateHomeProgress($products);

            return $flashsale;
        }, []);
    }

    protected function calculateHomeProgress(array $items): int
    {
        $stock = array_sum(array_column($items, 'stock'));
        $sold  = array_sum(array_column($items, 'sold'));

        if ($stock <= 0) {
            return 0;
        }

        return (int) round(($sold / $stock) * 100);
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD for flashsale campaigns
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->flashsaleModel->paginatedList(trim($keyword), trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function find(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->flashsaleModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            return $this->fail('Judul flashsale wajib diisi');
        }

        $payload = $this->buildPayload($data, $title);

        $id = $this->safeCall(fn() => $this->flashsaleModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan flashsale', ['errors' => $this->flashsaleModel->errors()]);
        }

        return $this->success('Flashsale berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Flashsale tidak ditemukan');
        }

        $title = trim((string) ($data['title'] ?? $existing['title']));

        if ($title === '') {
            return $this->fail('Judul flashsale wajib diisi');
        }

        $payload = $this->buildPayload($data, $title, $existing);

        $updated = $this->safeCall(fn() => $this->flashsaleModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui flashsale', ['errors' => $this->flashsaleModel->errors()]);
        }

        return $this->success('Flashsale berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Flashsale tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->flashsaleModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus flashsale');
        }

        return $this->success('Flashsale berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Flashsale tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->flashsaleModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status flashsale');
        }

        return $this->success('Status flashsale diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $title, array $existing = []): array
    {
        return [
            'title'       => $title,
            'description' => array_key_exists('description', $data) ? $data['description'] : ($existing['description'] ?? null),
            'image'       => array_key_exists('image', $data) ? $data['image'] : ($existing['image'] ?? null),
            'date_start'  => array_key_exists('date_start', $data) ? ($data['date_start'] ?: null) : ($existing['date_start'] ?? null),
            'date_end'    => array_key_exists('date_end', $data) ? ($data['date_end'] ?: null) : ($existing['date_end'] ?? null),
            'status'      => $this->normalizeStatus($data['status'] ?? ($existing['status'] ?? 'On'), $existing['status'] ?? 'On'),
        ];
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD for flashsale items (product assignment)
     |--------------------------------------------------------------------
     */

    public function listItems(int $flashsaleId, int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->flashsaleItemModel->paginatedListByFlashsale($flashsaleId, $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function findItem(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->flashsaleItemModel->find($id) ?: [];
    }

    public function createItem(array $data): array
    {
        if ((int) ($data['flashsale_id'] ?? 0) <= 0) {
            return $this->fail('Flashsale wajib dipilih');
        }

        if ((int) ($data['product_id'] ?? 0) <= 0) {
            return $this->fail('Produk wajib dipilih');
        }

        $payload = $this->buildItemPayload($data);

        $id = $this->safeCall(fn() => $this->flashsaleItemModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan item flashsale', ['errors' => $this->flashsaleItemModel->errors()]);
        }

        return $this->success('Item flashsale berhasil dibuat', ['id' => $id]);
    }

    public function updateItem(int $id, array $data): array
    {
        $existing = $this->findItem($id);

        if (empty($existing)) {
            return $this->fail('Item flashsale tidak ditemukan');
        }

        if ((int) ($data['product_id'] ?? $existing['product_id']) <= 0) {
            return $this->fail('Produk wajib dipilih');
        }

        $payload = $this->buildItemPayload($data, $existing);

        $updated = $this->safeCall(fn() => $this->flashsaleItemModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui item flashsale', ['errors' => $this->flashsaleItemModel->errors()]);
        }

        return $this->success('Item flashsale berhasil diperbarui');
    }

    public function deleteItem(int $id): array
    {
        $existing = $this->findItem($id);

        if (empty($existing)) {
            return $this->fail('Item flashsale tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->flashsaleItemModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus item flashsale');
        }

        return $this->success('Item flashsale berhasil dihapus');
    }

    public function toggleItemStatus(int $id): array
    {
        $existing = $this->findItem($id);

        if (empty($existing)) {
            return $this->fail('Item flashsale tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->flashsaleItemModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status item flashsale');
        }

        return $this->success('Status item flashsale diperbarui', ['status' => $newStatus]);
    }

    protected function buildItemPayload(array $data, array $existing = []): array
    {
        return [
            'flashsale_id'   => (int) ($data['flashsale_id'] ?? $existing['flashsale_id'] ?? 0),
            'product_id'     => (int) ($data['product_id'] ?? $existing['product_id'] ?? 0),
            'stock'          => (int) ($data['stock'] ?? $existing['stock'] ?? 0),
            'sold'           => (int) ($data['sold'] ?? $existing['sold'] ?? 0),
            'discount_type'  => $this->normalizeEnum($data['discount_type'] ?? ($existing['discount_type'] ?? 'fixed'), ['percent', 'fixed'], 'fixed'),
            'discount_value' => (float) ($data['discount_value'] ?? $existing['discount_value'] ?? 0),
            'sort_order'     => (int) ($data['sort_order'] ?? $existing['sort_order'] ?? 0),
            'status'         => $this->normalizeStatus($data['status'] ?? ($existing['status'] ?? 'On'), $existing['status'] ?? 'On'),
        ];
    }

    protected function normalizeStatus($status, string $fallback): string
    {
        $status = (string) $status;

        return in_array($status, ['On', 'Off'], true) ? $status : $fallback;
    }

    protected function normalizeEnum($value, array $allowed, string $fallback): string
    {
        $value = (string) $value;

        return in_array($value, $allowed, true) ? $value : $fallback;
    }
}
