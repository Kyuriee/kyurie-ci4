<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $protectFields = false;

    public function insert($data = null, bool $returnID = true)
    {
        $data['payment_token'] = bin2hex(random_bytes(16));

        return parent::insert($data, $returnID);
    }

    public function find($id = null): array
    {
        $data = parent::find($id);

        return $data ?? [];
    }

    public function findByInvoice(string $invoice): array
    {
        $data = $this->where('invoice', $invoice)->first();

        return $data ?? [];
    }

    public function findByToken(string $token): array
    {
        $data = $this->where('payment_token', $token)->first();

        return $data ?? [];
    }

    public function findByTokenWithGame(string $token): array
    {
        $data = $this->select('orders.*, games.id as game_id, games.games, games.slug, games.image, games.banner, games.description, games.target')
            ->join('product', 'product.id = orders.product_id', 'left')
            ->join('games', 'games.id = product.games_id', 'left')
            ->where('orders.payment_token', $token)
            ->first();

        return $data ?? [];
    }

    public function updateStatus(int $id, string $status, array $extra = []): bool
    {
        $data = array_merge(['status' => $status], $extra);

        if ($status === 'success' && ! isset($data['paid_at'])) {
            $data['paid_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }

    public function getByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll($limit, $offset);
    }

    public function generateInvoice(): string
    {
        $date  = date('Ymd');
        $count = $this->like('invoice', "INV/$date/", 'after')
            ->countAllResults();

        return sprintf('INV/%s/%04d', $date, $count + 1);
    }
}