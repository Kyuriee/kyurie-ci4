<?php

namespace App\Controllers\Admin;

use App\Requests\OrderStatusRequest;
use App\Services\Order\OrderService;
use App\Services\Order\OrderStatusService;
use App\Validation\OrderStatusRequestRules;

class Order extends BaseController
{
    protected function orderService(): OrderService
    {
        return service('orderService');
    }

    protected function orderStatusService(): OrderStatusService
    {
        return service('orderStatusService');
    }

    public function index()
    {
        $result = $this->orderService()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (string) ($this->request->getGet('date_from') ?? ''),
            (string) ($this->request->getGet('date_to') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar pesanan', $result);
    }

    public function show($id = null)
    {
        $order = $this->orderService()->findAny((int) $id);

        if (empty($order)) {
            return $this->responseJson(false, 'Pesanan tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail pesanan', $order);
    }

    public function updateStatus($id = null)
    {
        $data = OrderStatusRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, OrderStatusRequestRules::updateStatus())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->orderStatusService()->updateStatusByAdmin((int) $id, $data['status'], $data['note']);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }
}
