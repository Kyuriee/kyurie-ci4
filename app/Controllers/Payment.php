<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\PaymentDetailPageOrchestrator;

class Payment extends BaseController
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

        $this->base_data['page_assets']['css'][] = 'resources/css/pages/payment.css';
        $this->base_data['page_assets']['js'][]  = 'resources/js/pages/payment.js';

        return $this->renderView('Pages/Payment/Detail', $data);
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

        $this->base_data['page_assets']['css'][] = 'resources/css/pages/payment.css';
        $this->base_data['page_assets']['js'][]  = 'resources/js/pages/payment.js';

        return $this->renderView('Pages/Payment/Check', $data);
    }

    protected function _service(): PaymentDetailPageOrchestrator
    {
        return single_service('paymentDetailPageOrchestrator');
    }
}
