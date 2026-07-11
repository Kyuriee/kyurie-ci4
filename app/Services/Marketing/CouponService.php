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
}
