<?php

namespace App\Controllers;

use App\Services\Order\OrderService;
use App\Services\Orchestrators\Storefront\CheckoutOrchestrator;

class Order extends BaseController
{

    public function prepare()
    {
        $payload                        = $this->requestPayload();
        $payload['game']                = trim($payload['game'] ?? '');
        $payload['product_id']          = (int) ($payload['product_id'] ?? 0);
        $payload['payment_method_id']   = (int) ($payload['payment_method_id'] ?? 0);
        $payload                        = array_merge($payload, $this->couponContext());

        $result = $this->checkoutOrchestrator()->prepareOrder($payload);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null);
    }

    public function create()
    {
        $payload                        = $this->request->getPost() ?? [];
        $payload['game']                = trim($payload['game'] ?? '');
        $payload['product_id']          = (int) ($payload['product_id'] ?? 0);
        $payload['payment_method_id']   = (int) ($payload['payment_method_id'] ?? 0);
        $payload                        = array_merge($payload, $this->couponContext());

        $validation = $this->checkoutOrchestrator()->prepareOrder($payload);

        if (! $validation['success']) {
            $this->session->setFlashdata('alert', [
                'type'    => 'error',
                'message' => $validation['message'],
            ]);
            return redirect()->back();
        }

        $userId = (int) ($this->session->get('user_id') ?? 0);
        $result = $this->orderService()->create($validation['data'], $userId);

        if ($result['success']) {
            return redirect()->to('payment/' . $result['data']['payment_token']);
        }

        $this->session->setFlashdata('alert', [
            'type'    => 'error',
            'message' => $result['message'],
        ]);
        return redirect()->back();
    }

    protected function couponContext(): array
    {
        $userId = (int) ($this->session->get('user_id') ?? 0);
        $user   = $userId > 0 ? $this->resolveCurrentUser() : null;

        return [
            'user_id'    => $userId > 0 ? $userId : null,
            'user_level' => strtolower($user['level'] ?? 'guest'),
            'guest_ip'   => $this->request->getIPAddress(),
        ];
    }

    protected function requestPayload(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
        return $this->request->getPost() ?? [];
    }

    protected function orderService(): OrderService
    {
        return single_service('orderService');
    }

    protected function checkoutOrchestrator(): CheckoutOrchestrator
    {
        return single_service('checkoutOrchestrator');
    }
}
