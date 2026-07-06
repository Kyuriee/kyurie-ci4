<?php

namespace App\Controllers;

use App\Services\OrderService;
use App\Services\CheckoutService;

class Order extends baseController
{
    protected $order_service;
    protected $checkout_service;

    public function prepare()
    {
        $payload = $this->requestPayload();
        $payload['game'] = trim($payload['game'] ?? '');
        $payload['product_id'] = (int) ($payload['product_id'] ?? 0);
        $payload['payment_method_id'] = (int) ($payload['payment_method_id'] ?? 0);

        $result = $this->_checkout_service()->prepareOrder($payload);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null);
    }

    public function create()
    {
        $payload = $this->request->getPost() ?? [];
        $payload['auth_user_id'] = $this->session->get('user_id');
        $payload['product_id'] = (int) ($payload['product_id'] ?? 0);
        $payload['payment_method_id'] = (int) ($payload['payment_method_id'] ?? 0);

        $result = $this->_order_service()->create($payload);

        if ($result['success']) {
            return redirect()->to('payment/' . $result['data']['payment_token']);
        }

        $this->session->setFlashdata('alert', [
            'type'    => 'error',
            'message' => $result['message'],
        ]);
        return redirect()->back();
    }

    public function list()
    {
        if (! $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'data'    => [],
            ]);
        }

        $orders = $this->_order_service()->getOrdersByUser(
            $this->session->get('user_id')
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data pesanan ditemukan',
            'data'    => $orders,
        ]);
    }

    public function detail(int $orderId)
    {
        if (! $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
            ]);
        }

        $order = $this->_order_service()->getOrderForUser($orderId, (int) $this->session->get('user_id'));

        if (empty($order)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pesanan ditemukan',
            'data'    => $order,
        ]);
    }

    protected function _order_service(): OrderService
    {
        if (! isset($this->order_service)) {
            $this->order_service = new OrderService();
        }
        return $this->order_service;
    }

    protected function _checkout_service(): CheckoutService
    {
        if (! isset($this->checkout_service)) {
            $this->checkout_service = new CheckoutService();
        }
        return $this->checkout_service;
    }

    protected function requestPayload(): array
    {
        $json = $this->request->getJSON(true);

        if (is_array($json)) {
            return $json;
        }

        return $this->request->getPost() ?? [];
    }
}