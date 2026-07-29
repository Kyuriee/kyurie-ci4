<?php

namespace App\Controllers\Admin;

use App\Requests\PaymentMethodRequest;
use App\Services\Payment\PaymentMethodService;
use App\Validation\PaymentMethodRequestRules;

class PaymentMethod extends BaseController
{
    protected function service(): PaymentMethodService
    {
        return service('paymentMethodService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar metode pembayaran', $result);
    }

    public function show($id = null)
    {
        $method = $this->service()->getMethod((int) $id);

        if (empty($method)) {
            return $this->responseJson(false, 'Metode pembayaran tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail metode pembayaran', $method);
    }

    public function store()
    {
        $data = PaymentMethodRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, PaymentMethodRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = PaymentMethodRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, PaymentMethodRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->update((int) $id, $data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function delete($id = null)
    {
        $result = $this->service()->delete((int) $id);

        return $this->responseJson($result['success'], $result['message'])
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function toggleStatus($id = null)
    {
        $result = $this->service()->toggleStatus((int) $id);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }
}
