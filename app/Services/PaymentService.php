<?php

namespace App\Services;

use App\Models\OrderModel;

class PaymentService extends baseService
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = model(OrderModel::class);
    }

    public function getDetailPage(string $token): array
    {
        $row = $this->orderModel->findByTokenWithGame($token);

        if (empty($row)) {
            return [];
        }

        return [
            'order' => $this->mapOrder($row),
            'game'  => $this->mapGame($row),
        ];
    }

    public function checkInvoice(string $invoice): array
    {
        $invoice = trim($invoice);

        if ($invoice === '') {
            return [
                'success' => false,
                'message' => 'Masukkan nomor invoice',
                'data'    => [],
            ];
        }

        $order = $this->orderModel->findByInvoice($invoice);

        if (empty($order)) {
            return [
                'success' => false,
                'message' => 'Invoice tidak ditemukan',
                'data'    => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Invoice ditemukan',
            'data'    => $order,
        ];
    }

    protected function mapOrder(array $row): array
    {
        $gameFields = [
            'game_id',
            'games',
            'slug',
            'image',
            'banner',
            'description',
            'target',
        ];

        return array_diff_key($row, array_flip($gameFields));
    }

    protected function mapGame(array $row): array
    {
        return [
            'id'          => $row['game_id'] ?? null,
            'games'       => $row['games'] ?? null,
            'slug'        => $row['slug'] ?? null,
            'image'       => $row['image'] ?? null,
            'banner'      => $row['banner'] ?? null,
            'description' => $row['description'] ?? null,
            'target'      => $row['target'] ?? null,
        ];
    }
}
