<?php

namespace App\Services\Marketing;

use App\Services\BaseService;
use App\Models\BannerModel;

class BannerService extends BaseService
{
    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = model(BannerModel::class);
    }

    public function getActive(): array
    {
        return $this->safeCall(
            fn() => $this->bannerModel->getActive(),
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
            fn() => $this->bannerModel->paginatedList(trim($keyword), trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function find(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->bannerModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));

        if ($title === '') {
            return $this->fail('Judul banner wajib diisi');
        }

        $payload = $this->buildPayload($data, $title);

        $id = $this->safeCall(fn() => $this->bannerModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan banner', ['errors' => $this->bannerModel->errors()]);
        }

        return $this->success('Banner berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Banner tidak ditemukan');
        }

        $title = trim((string) ($data['title'] ?? $existing['title']));

        if ($title === '') {
            return $this->fail('Judul banner wajib diisi');
        }

        $payload = $this->buildPayload($data, $title, $existing);

        $updated = $this->safeCall(fn() => $this->bannerModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui banner', ['errors' => $this->bannerModel->errors()]);
        }

        return $this->success('Banner berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Banner tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->bannerModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus banner');
        }

        return $this->success('Banner berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Banner tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->bannerModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status banner');
        }

        return $this->success('Status banner diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $title, array $existing = []): array
    {
        return [
            'title'      => $title,
            'subtitle'   => array_key_exists('subtitle', $data) ? $data['subtitle'] : ($existing['subtitle'] ?? null),
            'image'      => array_key_exists('image', $data) ? $data['image'] : ($existing['image'] ?? null),
            'link'       => array_key_exists('link', $data) ? $data['link'] : ($existing['link'] ?? null),
            'sort'       => (int) ($data['sort'] ?? $existing['sort'] ?? 0),
            'status'     => $this->normalizeStatus($data['status'] ?? ($existing['status'] ?? 'On'), $existing['status'] ?? 'On'),
            'date_start' => array_key_exists('date_start', $data) ? ($data['date_start'] ?: null) : ($existing['date_start'] ?? null),
            'date_end'   => array_key_exists('date_end', $data) ? ($data['date_end'] ?: null) : ($existing['date_end'] ?? null),
        ];
    }

    protected function normalizeStatus($status, string $fallback): string
    {
        $status = (string) $status;

        return in_array($status, ['On', 'Off'], true) ? $status : $fallback;
    }
}
