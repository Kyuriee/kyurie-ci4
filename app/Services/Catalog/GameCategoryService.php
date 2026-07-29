<?php

namespace App\Services\Catalog;

use App\Services\BaseService;
use App\Models\GameCategoryModel;

class GameCategoryService extends BaseService
{
    protected $gameCategoryModel;

    public function __construct()
    {
        $this->gameCategoryModel = model(GameCategoryModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn() => $this->gameCategoryModel->getActive(),
            []
        );
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD, not restricted to status = 'On'
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->gameCategoryModel->paginatedList(trim($keyword), trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function find(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->gameCategoryModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $category = trim((string) ($data['category'] ?? ''));

        if ($category === '') {
            return $this->fail('Nama kategori wajib diisi');
        }

        $payload = [
            'category' => $category,
            'slug'     => $this->resolveSlug($category, $data['slug'] ?? null),
            'image'    => $data['image'] ?? null,
            'sort'     => (int) ($data['sort'] ?? 0),
            'status'   => $this->normalizeStatus($data['status'] ?? 'On', 'On'),
        ];

        $id = $this->safeCall(fn() => $this->gameCategoryModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan kategori', ['errors' => $this->gameCategoryModel->errors()]);
        }

        return $this->success('Kategori berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kategori tidak ditemukan');
        }

        $category = trim((string) ($data['category'] ?? $existing['category']));

        if ($category === '') {
            return $this->fail('Nama kategori wajib diisi');
        }

        $payload = [
            'category' => $category,
            'slug'     => $this->resolveSlug($category, $data['slug'] ?? null, $id),
            'image'    => array_key_exists('image', $data) ? $data['image'] : $existing['image'],
            'sort'     => (int) ($data['sort'] ?? $existing['sort']),
            'status'   => $this->normalizeStatus($data['status'] ?? $existing['status'], $existing['status']),
        ];

        $updated = $this->safeCall(fn() => $this->gameCategoryModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui kategori', ['errors' => $this->gameCategoryModel->errors()]);
        }

        return $this->success('Kategori berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kategori tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->gameCategoryModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus kategori');
        }

        return $this->success('Kategori berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kategori tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->gameCategoryModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status kategori');
        }

        return $this->success('Status kategori diperbarui', ['status' => $newStatus]);
    }

    protected function resolveSlug(string $category, ?string $customSlug, ?int $excludeId = null): string
    {
        helper('text');

        $base = $customSlug && trim($customSlug) !== ''
            ? url_title(trim($customSlug), '-', true)
            : url_title($category, '-', true);

        $slug   = $base;
        $suffix = 1;

        while ($this->gameCategoryModel->slugExists($slug, $excludeId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function normalizeStatus($status, string $fallback): string
    {
        $status = (string) $status;

        return in_array($status, ['On', 'Off'], true) ? $status : $fallback;
    }
}
