<?php

namespace App\Services\Catalog;

use App\Services\BaseService;
use App\Models\GameModel;

class GameService extends BaseService
{
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = model(GameModel::class);
    }

    public function getActiveBySlug(string $slug): array
    {
        $slug = trim($slug);

        if ($slug === '') {
            return [];
        }

        return $this->gameModel->getDetailBySlug($slug);
    }

    public function getById(int $gameId): array
    {
        if ($gameId <= 0) {
            return [];
        }

        return $this->gameModel->find($gameId) ?: [];
    }

    public function searchGames(string $keyword, int $limit = 8): array
    {
        $keyword = trim($keyword);

        if (strlen($keyword) < 2) {
            return [];
        }

        $games = $this->gameModel->searchGames($keyword, $limit);

        foreach ($games as &$game) {
            $game['url']       = base_url('games/' . $game['slug']);
            $game['image_url'] = ! empty($game['image'])
                ? base_url('assets/images/games/icons/' . $game['image'])
                : '';
        }

        return $games;
    }

    public function getPopularGames(int $limit = 12): array
    {
        return $this->safeCall(
            fn() => $this->gameModel->getPopularGames($limit),
            []
        );
    }

    public function getGamesByCategory(int $categoryId): array
    {
        if ($categoryId <= 0) {
            return [];
        }

        return $this->safeCall(
            fn() => $this->gameModel->getGamesByCategory($categoryId),
            []
        );
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD, not restricted to status = 'On'
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', ?int $categoryId = null, string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->gameModel->paginatedList(trim($keyword), $categoryId, trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function findAny(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->gameModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $name = trim((string) ($data['games'] ?? ''));

        if ($name === '') {
            return $this->fail('Nama game wajib diisi');
        }

        if ((int) ($data['game_category_id'] ?? 0) <= 0) {
            return $this->fail('Kategori wajib dipilih');
        }

        $payload = $this->buildPayload($data, $name);

        $id = $this->safeCall(fn() => $this->gameModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan game', ['errors' => $this->gameModel->errors()]);
        }

        return $this->success('Game berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Game tidak ditemukan');
        }

        $name = trim((string) ($data['games'] ?? $existing['games']));

        if ($name === '') {
            return $this->fail('Nama game wajib diisi');
        }

        if ((int) ($data['game_category_id'] ?? $existing['game_category_id']) <= 0) {
            return $this->fail('Kategori wajib dipilih');
        }

        $payload = $this->buildPayload($data, $name, $existing, $id);

        $updated = $this->safeCall(fn() => $this->gameModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui game', ['errors' => $this->gameModel->errors()]);
        }

        return $this->success('Game berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Game tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->gameModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus game');
        }

        return $this->success('Game berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->findAny($id);

        if (empty($existing)) {
            return $this->fail('Game tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->gameModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status game');
        }

        return $this->success('Status game diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $name, array $existing = [], ?int $excludeId = null): array
    {
        return [
            'game_category_id' => (int) ($data['game_category_id'] ?? $existing['game_category_id'] ?? 0),
            'games'            => $name,
            'slug'             => $this->resolveSlug($name, $data['slug'] ?? null, $excludeId),
            'code'             => trim((string) ($data['code'] ?? $existing['code'] ?? '')) ?: null,
            'provider'         => trim((string) ($data['provider'] ?? $existing['provider'] ?? '')) ?: null,
            'publisher'        => trim((string) ($data['publisher'] ?? $existing['publisher'] ?? '')) ?: null,
            'image'            => array_key_exists('image', $data) ? $data['image'] : ($existing['image'] ?? null),
            'banner'           => array_key_exists('banner', $data) ? $data['banner'] : ($existing['banner'] ?? null),
            'description'      => array_key_exists('description', $data) ? $data['description'] : ($existing['description'] ?? null),
            'target'           => trim((string) ($data['target'] ?? $existing['target'] ?? 'default')) ?: 'default',
            'input_custom'     => array_key_exists('input_custom', $data) ? $this->normalizeInputCustom($data['input_custom']) : ($existing['input_custom'] ?? null),
            'is_popular'       => $this->normalizeEnum($data['is_popular'] ?? ($existing['is_popular'] ?? 'N'), ['Y', 'N'], 'N'),
            'sort'             => (int) ($data['sort'] ?? $existing['sort'] ?? 0),
            'status'           => $this->normalizeEnum($data['status'] ?? ($existing['status'] ?? 'On'), ['On', 'Off'], 'On'),
        ];
    }

    protected function normalizeInputCustom($inputCustom): ?string
    {
        if ($inputCustom === null || $inputCustom === '') {
            return null;
        }

        if (is_array($inputCustom)) {
            return json_encode($inputCustom);
        }

        // Already a JSON string (or free text) coming from the form — leave as-is.
        return (string) $inputCustom;
    }

    protected function resolveSlug(string $name, ?string $customSlug, ?int $excludeId = null): string
    {
        helper('text');

        $base = $customSlug && trim($customSlug) !== ''
            ? url_title(trim($customSlug), '-', true)
            : url_title($name, '-', true);

        $slug   = $base;
        $suffix = 1;

        while ($this->gameModel->slugExists($slug, $excludeId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function normalizeEnum($value, array $allowed, string $fallback): string
    {
        $value = (string) $value;

        return in_array($value, $allowed, true) ? $value : $fallback;
    }

    public function mapPublicGame(array $game): array
    {
        return [
            'games'     => $game['games'] ?? '',
            'slug'      => $game['slug'] ?? '',
            'publisher' => $game['publisher'] ?? '',
            'category'  => $game['category'] ?? '',
            'image'     => $game['image'] ?? '',
            'banner'    => $game['banner'] ?? '',
            'target'    => $game['target'] ?? 'default',
        ];
    }
}
