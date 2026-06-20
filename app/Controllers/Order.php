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
        if (! $this->session->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
            ]);
        }

        $result = $this->_checkout_service()->prepareOrder([
            'game'              => trim($this->request->getPost('game') ?? ''),
            'product_id'        => (int) $this->request->getPost('product_id'),
            'payment_method_id' => (int) $this->request->getPost('payment_method_id'),
            'customer_id'       => trim($this->request->getPost('customer_id') ?? ''),
            'zone_id'           => trim($this->request->getPost('zone_id') ?? ''),
        ]);

        return $this->response->setJSON($result);
    }

    public function create()
    {
        if (! $this->session->get('user_id')) {
            return redirect()->to('auth/login');
        }

        $result = $this->_order_service()->create([
            'user_id'           => $this->session->get('user_id'),
            'product_id'        => (int) $this->request->getPost('product_id'),
            'customer_id'       => trim($this->request->getPost('customer_id') ?? ''),
            'zone_id'           => trim($this->request->getPost('zone_id') ?? ''),
            'payment_method_id' => (int) $this->request->getPost('payment_method_id'),
        ]);

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

        $order = $this->_order_service()->getOrder($orderId);

        if (empty($order) || $order['user_id'] != $this->session->get('user_id')) {
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
}
