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
}
