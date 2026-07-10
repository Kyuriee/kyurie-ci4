<?php

namespace App\Services\Orchestrators\Storefront;

use App\Models\OrderModel;
use App\Services\BaseService;
use App\Services\Catalog\GameAccountInputService;

class PaymentDetailPageOrchestrator extends BaseService
{
    protected $orderModel;
    protected $gameAccountInputService;

    public function __construct()
    {
        $this->orderModel    = model(OrderModel::class);
        $this->gameAccountInputService = new GameAccountInputService();
    }

    public function checkInvoice(string $invoice): array
    {
        return $this->safeCall(function () use ($invoice) {
            $invoice = trim($invoice);

            if ($invoice === '') {
                return $this->fail('Masukkan nomor invoice');
            }

            $order = $this->orderModel->findByInvoice($invoice);

            if (empty($order)) {
                return $this->fail('Invoice tidak ditemukan');
            }

            return $this->success('Invoice ditemukan', $order);
        }, $this->fail('Gagal memeriksa invoice'));
    }

    public function getDetailPage(string $token): array
    {
        return $this->safeCall(function () use ($token) {
            $row = $this->orderModel->findByTokenWithGame($token);

            if (empty($row)) {
                return [];
            }

            return [
                'order' => $this->mapOrder($row),
                'game'  => $this->mapGame($row),
            ];
        }, []);
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
            'input_custom',
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
            'target_form' => $this->gameAccountInputService->getFormConfig(
                $row['target'] ?? 'default',
                $row['input_custom'] ?? null
            ),
        ];
    }
}
