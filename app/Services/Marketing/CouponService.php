<?php

namespace App\Services\Marketing;

use App\Models\CouponModel;
use App\Models\CouponUsageModel;
use App\Services\BaseService;

class CouponService extends BaseService
{
    protected $couponModel;
    protected $couponUsageModel;

    public function __construct()
    {
        $this->couponModel      = model(CouponModel::class);
        $this->couponUsageModel = model(CouponUsageModel::class);
    }

    /**
     * Validates a coupon code against the current cart context and returns
     * the discount amount if eligible. This is a soft check (safe to call
     * repeatedly for preview) — the hard, race-free enforcement of
     * max_global happens later in consumeUsage() at payment-success time.
     *
     * $context keys: subtotal (float), game_id (int|null), product_id (int|null),
     * user_level (string|null), user_id (int|null), guest_ip (string|null)
     */
    public function validateCoupon(string $code, array $context): array
    {
        return $this->safeCall(function () use ($code, $context) {
            $code     = trim($code);
            $subtotal = (float) ($context['subtotal'] ?? 0);

            if ($code === '') {
                return $this->fail('Masukkan kode kupon');
            }

            $coupon = $this->couponModel->getActiveByCode($code);

            if (empty($coupon)) {
                return $this->fail('Kupon tidak ditemukan atau sudah tidak berlaku');
            }

            if ($subtotal < (float) $coupon['min_transaction']) {
                return $this->fail('Minimal transaksi untuk kupon ini belum terpenuhi');
            }

            if ($coupon['type'] === 'custom' && ! $this->matchesCustomScope($coupon, $context)) {
                return $this->fail('Kupon tidak berlaku untuk item ini');
            }

            if (! empty($coupon['max_global']) && (int) $coupon['usage_count'] >= (int) $coupon['max_global']) {
                return $this->fail('Kuota kupon sudah habis');
            }

            $userId  = $context['user_id'] ?? null;
            $guestIp = $context['guest_ip'] ?? null;

            if (! empty($coupon['max_per_user']) && $userId) {
                $used = $this->couponUsageModel->countByUser((int) $coupon['id'], (int) $userId);
                if ($used >= (int) $coupon['max_per_user']) {
                    return $this->fail('Kamu sudah mencapai batas pemakaian kupon ini');
                }
            }

            if (! empty($coupon['max_per_guest']) && ! $userId && $guestIp) {
                $used = $this->couponUsageModel->countByGuestIp((int) $coupon['id'], $guestIp);
                if ($used >= (int) $coupon['max_per_guest']) {
                    return $this->fail('Kupon ini sudah pernah kamu pakai');
                }
            }

            if (! empty($coupon['max_per_daily'])) {
                $usedToday = $this->couponUsageModel->countToday((int) $coupon['id']);
                if ($usedToday >= (int) $coupon['max_per_daily']) {
                    return $this->fail('Kuota kupon hari ini sudah habis, coba lagi besok');
                }
            }

            $discount = $this->calculateDiscount($coupon, $subtotal);

            if ($discount <= 0) {
                return $this->fail('Kupon tidak memberikan potongan untuk transaksi ini');
            }

            return $this->success('Kupon valid', [
                'coupon'   => $coupon,
                'discount' => $discount,
            ]);
        }, $this->fail('Gagal memvalidasi kupon'));
    }

    public function calculateDiscount(array $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0;
        }

        if (! empty($coupon['discount_percent']) && (float) $coupon['discount_percent'] > 0) {
            $discount = $subtotal * ((float) $coupon['discount_percent'] / 100);

            if (! empty($coupon['max_discount'])) {
                $discount = min($discount, (float) $coupon['max_discount']);
            }
        } elseif (! empty($coupon['discount_nominal'])) {
            $discount = (float) $coupon['discount_nominal'];
        } else {
            $discount = 0;
        }

        return min($discount, $subtotal);
    }

    /**
     * Atomically consumes global quota and logs the usage row. Must be
     * called from within the same DB transaction as the order-status
     * update that triggers it (payment success), so a failed transaction
     * rolls this back too — same pattern as FlashsaleService::consumeStock.
     */
    public function consumeUsage(int $couponId, ?int $orderId, ?int $userId, ?string $guestIp, float $discountAmount): bool
    {
        $consumed = $this->couponModel->consumeUsage($couponId);

        if (! $consumed) {
            return false;
        }

        return $this->couponUsageModel->record($couponId, $orderId, $userId, $guestIp, $discountAmount);
    }

    private function matchesCustomScope(array $coupon, array $context): bool
    {
        if (! empty($coupon['level_csv'])) {
            $levels     = $this->splitCsv($coupon['level_csv']);
            $userLevel  = strtolower((string) ($context['user_level'] ?? 'guest'));

            if (! in_array($userLevel, $levels, true)) {
                return false;
            }
        }

        if (! empty($coupon['game_csv'])) {
            $games  = $this->splitCsv($coupon['game_csv']);
            $gameId = (string) ($context['game_id'] ?? '');

            if ($gameId === '' || ! in_array($gameId, $games, true)) {
                return false;
            }
        }

        if (! empty($coupon['product_csv'])) {
            $products  = $this->splitCsv($coupon['product_csv']);
            $productId = (string) ($context['product_id'] ?? '');

            if ($productId === '' || ! in_array($productId, $products, true)) {
                return false;
            }
        }

        return true;
    }

    private function splitCsv(string $csv): array
    {
        return array_filter(array_map(fn($v) => strtolower(trim($v)), explode(',', $csv)), fn($v) => $v !== '');
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD, not restricted to status = 'On'
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->couponModel->paginatedList(trim($keyword), trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function find(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->couponModel->find($id) ?: [];
    }

    public function create(array $data): array
    {
        $code = strtoupper(trim((string) ($data['code'] ?? '')));

        if ($code === '') {
            return $this->fail('Kode kupon wajib diisi');
        }

        if ($this->couponModel->codeExists($code)) {
            return $this->fail('Kode kupon sudah dipakai, gunakan kode lain');
        }

        $payload = $this->buildPayload($data, $code);

        $id = $this->safeCall(fn() => $this->couponModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan kupon', ['errors' => $this->couponModel->errors()]);
        }

        return $this->success('Kupon berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kupon tidak ditemukan');
        }

        $code = strtoupper(trim((string) ($data['code'] ?? $existing['code'])));

        if ($code === '') {
            return $this->fail('Kode kupon wajib diisi');
        }

        if ($this->couponModel->codeExists($code, $id)) {
            return $this->fail('Kode kupon sudah dipakai, gunakan kode lain');
        }

        $payload = $this->buildPayload($data, $code, $existing);

        $updated = $this->safeCall(fn() => $this->couponModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui kupon', ['errors' => $this->couponModel->errors()]);
        }

        return $this->success('Kupon berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kupon tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->couponModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus kupon');
        }

        return $this->success('Kupon berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->find($id);

        if (empty($existing)) {
            return $this->fail('Kupon tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->couponModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status kupon');
        }

        return $this->success('Status kupon diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $code, array $existing = []): array
    {
        return [
            'code'              => $code,
            'name'              => trim((string) ($data['name'] ?? $existing['name'] ?? '')),
            'discount_percent'  => array_key_exists('discount_percent', $data) ? (float) $data['discount_percent'] : ($existing['discount_percent'] ?? 0),
            'discount_nominal'  => array_key_exists('discount_nominal', $data) ? (float) $data['discount_nominal'] : ($existing['discount_nominal'] ?? 0),
            'max_discount'      => array_key_exists('max_discount', $data) ? ($data['max_discount'] !== '' ? (float) $data['max_discount'] : null) : ($existing['max_discount'] ?? null),
            'min_transaction'   => (float) ($data['min_transaction'] ?? $existing['min_transaction'] ?? 0),
            'type'              => $this->normalizeEnum($data['type'] ?? ($existing['type'] ?? 'general'), ['general', 'custom'], 'general'),
            'level_csv'         => array_key_exists('level_csv', $data) ? ($data['level_csv'] ?: null) : ($existing['level_csv'] ?? null),
            'game_csv'          => array_key_exists('game_csv', $data) ? ($data['game_csv'] ?: null) : ($existing['game_csv'] ?? null),
            'product_csv'       => array_key_exists('product_csv', $data) ? ($data['product_csv'] ?: null) : ($existing['product_csv'] ?? null),
            'max_per_guest'     => array_key_exists('max_per_guest', $data) ? ($data['max_per_guest'] !== '' ? (int) $data['max_per_guest'] : null) : ($existing['max_per_guest'] ?? null),
            'max_per_user'      => array_key_exists('max_per_user', $data) ? ($data['max_per_user'] !== '' ? (int) $data['max_per_user'] : null) : ($existing['max_per_user'] ?? null),
            'max_global'        => array_key_exists('max_global', $data) ? ($data['max_global'] !== '' ? (int) $data['max_global'] : null) : ($existing['max_global'] ?? null),
            'max_per_daily'     => array_key_exists('max_per_daily', $data) ? ($data['max_per_daily'] !== '' ? (int) $data['max_per_daily'] : null) : ($existing['max_per_daily'] ?? null),
            'valid_from'        => array_key_exists('valid_from', $data) ? ($data['valid_from'] ?: null) : ($existing['valid_from'] ?? null),
            'valid_until'       => array_key_exists('valid_until', $data) ? ($data['valid_until'] ?: null) : ($existing['valid_until'] ?? null),
            'status'            => $this->normalizeEnum($data['status'] ?? ($existing['status'] ?? 'On'), ['On', 'Off'], 'On'),
        ];
    }

    protected function normalizeEnum($value, array $allowed, string $fallback): string
    {
        $value = (string) $value;

        return in_array($value, $allowed, true) ? $value : $fallback;
    }
}
