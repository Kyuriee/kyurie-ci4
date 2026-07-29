<?php

namespace App\Services\Payment;

use App\Services\BaseService;
use App\Models\PaymentMethodModel;

class PaymentMethodService extends BaseService
{
    protected $paymentMethodModel;

    public function __construct()
    {
        $this->paymentMethodModel = model(PaymentMethodModel::class);
    }

    public function getActiveMethods(): array
    {
        return $this->safeCall(
            fn() => $this->paymentMethodModel->getActive(),
            []
        );
    }

    public function getMethod(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->safeCall(
            fn() => $this->paymentMethodModel->find($id) ?: [],
            []
        );
    }

    public function getActiveMethod(int $id): array
    {
        $method = $this->getMethod($id);

        if (empty($method) || ($method['status'] ?? '') !== 'On') {
            return [];
        }

        return $method;
    }

    /*
     |--------------------------------------------------------------------
     | Admin (backoffice) — CRUD, not restricted to status = 'On'
     |--------------------------------------------------------------------
     */

    public function list(string $keyword = '', string $status = '', int $perPage = 20): array
    {
        return $this->safeCall(
            fn() => $this->paymentMethodModel->paginatedList(trim($keyword), trim($status), $perPage),
            ['items' => [], 'pager' => null]
        );
    }

    public function create(array $data): array
    {
        $name = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            return $this->fail('Nama metode pembayaran wajib diisi');
        }

        $payload = $this->buildPayload($data, $name);

        $id = $this->safeCall(fn() => $this->paymentMethodModel->insert($payload, true), false);

        if ($id === false) {
            return $this->fail('Gagal menyimpan metode pembayaran', ['errors' => $this->paymentMethodModel->errors()]);
        }

        return $this->success('Metode pembayaran berhasil dibuat', ['id' => $id]);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->getMethod($id);

        if (empty($existing)) {
            return $this->fail('Metode pembayaran tidak ditemukan');
        }

        $name = trim((string) ($data['name'] ?? $existing['name']));

        if ($name === '') {
            return $this->fail('Nama metode pembayaran wajib diisi');
        }

        $payload = $this->buildPayload($data, $name, $existing);

        $updated = $this->safeCall(fn() => $this->paymentMethodModel->update($id, $payload), false);

        if (! $updated) {
            return $this->fail('Gagal memperbarui metode pembayaran', ['errors' => $this->paymentMethodModel->errors()]);
        }

        return $this->success('Metode pembayaran berhasil diperbarui');
    }

    public function delete(int $id): array
    {
        $existing = $this->getMethod($id);

        if (empty($existing)) {
            return $this->fail('Metode pembayaran tidak ditemukan');
        }

        $deleted = $this->safeCall(fn() => $this->paymentMethodModel->delete($id), false);

        if (! $deleted) {
            return $this->fail('Gagal menghapus metode pembayaran');
        }

        return $this->success('Metode pembayaran berhasil dihapus');
    }

    public function toggleStatus(int $id): array
    {
        $existing = $this->getMethod($id);

        if (empty($existing)) {
            return $this->fail('Metode pembayaran tidak ditemukan');
        }

        $newStatus = $existing['status'] === 'On' ? 'Off' : 'On';

        $updated = $this->safeCall(fn() => $this->paymentMethodModel->update($id, ['status' => $newStatus]), false);

        if (! $updated) {
            return $this->fail('Gagal mengubah status metode pembayaran');
        }

        return $this->success('Status metode pembayaran diperbarui', ['status' => $newStatus]);
    }

    protected function buildPayload(array $data, string $name, array $existing = []): array
    {
        return [
            'name'     => $name,
            'provider' => trim((string) ($data['provider'] ?? $existing['provider'] ?? '')),
            'code'     => trim((string) ($data['code'] ?? $existing['code'] ?? '')),
            'type'     => trim((string) ($data['type'] ?? $existing['type'] ?? '')),
            'config'   => array_key_exists('config', $data) ? $data['config'] : ($existing['config'] ?? null),
            'image'    => array_key_exists('image', $data) ? $data['image'] : ($existing['image'] ?? null),
            'sort'     => (int) ($data['sort'] ?? $existing['sort'] ?? 0),
            'status'   => $this->normalizeStatus($data['status'] ?? ($existing['status'] ?? 'On'), $existing['status'] ?? 'On'),
        ];
    }

    protected function normalizeStatus($status, string $fallback): string
    {
        $status = (string) $status;

        return in_array($status, ['On', 'Off'], true) ? $status : $fallback;
    }
}
