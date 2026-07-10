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

    /**
     * Inserts the order with a temporary unique invoice placeholder, then
     * rewrites it to the final human-readable INV/{date}/{id} format using
     * the row's own auto-increment id — which the DB guarantees unique,
     * so no two concurrent inserts can ever collide (unlike the previous
     * count-then-increment approach).
     */
    public function insert($data = null, bool $returnID = true)
    {
        $data['payment_token'] = bin2hex(random_bytes(16));
        $data['invoice']       = 'TMP-' . bin2hex(random_bytes(12));

        $id = parent::insert($data, true);

        if (! $id) {
            return false;
        }

        $finalInvoice = sprintf('INV/%s/%06d', date('Ymd'), $id);

        $this->builder()
            ->where('id', $id)
            ->update(['invoice' => $finalInvoice]);

        return $returnID ? $id : true;
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
        $data = $this->select('orders.*, games.id as game_id, games.games, games.slug, games.image, games.banner, games.description, games.target, games.input_custom, payment_methods.name as payment_method_name, payment_methods.image as payment_method_image')
            ->join('product', 'product.id = orders.product_id', 'left')
            ->join('games', 'games.id = product.games_id', 'left')
            ->join('payment_methods', 'payment_methods.id = orders.payment_method_id', 'left')
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

    public function updateStatusIfNot(int $id, string $status, string $excludedStatus, array $extra = []): bool
    {
        $data = array_merge(['status' => $status], $extra);

        if ($status === 'success' && ! isset($data['paid_at'])) {
            $data['paid_at'] = date('Y-m-d H:i:s');
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->builder()
            ->set($data)
            ->where('id', $id)
            ->where('status !=', $excludedStatus)
            ->update();

        return $this->db->affectedRows() > 0;
    }

    public function getByUser(int $userId, int $limit = 20, int $offset = 0): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll($limit, $offset);
    }
}
