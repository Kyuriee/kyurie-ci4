<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponUsageModel extends Model
{
    protected $table         = 'coupon_usages';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'coupon_id',
        'order_id',
        'user_id',
        'guest_ip',
        'discount_amount',
        'created_at',
    ];

    public function countByUser(int $couponId, int $userId): int
    {
        return $this->where('coupon_id', $couponId)
            ->where('user_id', $userId)
            ->countAllResults();
    }

    public function countByGuestIp(int $couponId, string $ip): int
    {
        return $this->where('coupon_id', $couponId)
            ->where('guest_ip', $ip)
            ->countAllResults();
    }

    public function countToday(int $couponId): int
    {
        return $this->where('coupon_id', $couponId)
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->where('created_at <=', date('Y-m-d 23:59:59'))
            ->countAllResults();
    }

    public function record(int $couponId, ?int $orderId, ?int $userId, ?string $guestIp, float $discountAmount): bool
    {
        return (bool) $this->insert([
            'coupon_id'       => $couponId,
            'order_id'        => $orderId,
            'user_id'         => $userId,
            'guest_ip'        => $guestIp,
            'discount_amount' => $discountAmount,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);
    }
}
