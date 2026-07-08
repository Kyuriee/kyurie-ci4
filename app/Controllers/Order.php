<?php

namespace App\Controllers;

use App\Services\Order\OrderService;
use App\Services\Order\CheckoutService;

class Order extends BaseController
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

    protected function requestPayload(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
        return $this->request->getPost() ?? [];
    }

    protected function _order_service(): orderService
    {
        return single_service('orderService');
    }

    protected function _checkout_service(): CheckoutService
    {
        return single_service('checkoutService');
    }
}
