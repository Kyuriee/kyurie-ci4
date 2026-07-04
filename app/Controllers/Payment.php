<?php

namespace App\Controllers;

use App\Services\PaymentService;

class Payment extends baseController
{


    public function detail(string $token)
    {
        $result = $this->_service()->getDetailPage($token);

        if (empty($result)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $order = $result['order'];
        $game  = $result['game'];

        $data = [
            'meta'            => ['title' => 'Pembayaran #' . $order['invoice']],
            'order'           => $order,
            'game'            => $game,
        ];

        return $this->renderView('pages/payment/detail', $data);
    }

    public function check()
    {
        if ($this->request->is('post')) {
            $invoice = trim($this->request->getPost('invoice') ?? '');
            $result  = $this->_service()->checkInvoice($invoice);

            if (! $result['success']) {
                $this->session->setFlashdata('alert', [
                    'type'    => 'error',
                    'message' => $result['message'],
                ]);
                return redirect()->back();
            }

            $order = $result['data'];
            return redirect()->to('payment/' . $order['payment_token']);
        }

        $data = ['meta' => ['title' => 'Cek Pembayaran']];
        return $this->renderView('pages/payment/check', $data);
    }

    protected function _service(): PaymentService
    {
        return single_service('paymentService');
    }
}
