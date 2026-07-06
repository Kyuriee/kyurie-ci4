<?php

namespace App\Services;

use App\Models\PaymentMethodModel;

class PaymentService extends baseService
{
    protected $paymentMethodModel;

    public function __construct()
    {
        $this->paymentMethodModel = model(PaymentMethodModel::class);
    }

    public function getActiveMethods(): array
    {
        return $this->paymentMethodModel->getActive();
    }

    public function getMethod(int $id): array
    {
        if ($id <= 0) {
            return [];
        }

        return $this->paymentMethodModel->find($id) ?: [];
    }

    public function getActiveMethod(int $id): array
    {
        $method = $this->getMethod($id);

        if (empty($method) || ($method['status'] ?? '') !== 'On') {
            return [];
        }

        return $method;
    }
}
