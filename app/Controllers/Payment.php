<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\PaymentDetailPageOrchestrator;

class Payment extends BaseController
{
    public function detail(string $token)
    {
        $result = $this->paymentDetailPageOrchestrator()->getDetailPage($token);

        if (empty($result)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $order = $result['order'];
        $game  = $result['game'];

        $data = [
            'meta'  => ['title' => 'Pembayaran #' . $order['invoice']],
            'order' => $order,
            'game'  => $game,
        ];

        $this->baseData['page_assets']['css'][] = 'resources/css/pages/payment.css';
        $this->baseData['page_assets']['js'][]  = 'resources/js/pages/payment.js';

        return $this->renderView('Pages/Payment/Detail', $data);
    }

    public function check()
    {
        if ($this->request->is('post')) {
            if (($throttle = $this->throttleOrBack('paymentCheck', 10, 'Terlalu banyak percobaan. Silakan tunggu sebentar lalu coba kembali.')) !== null) {
                return $throttle;
            }

            $invoice = strtoupper(trim((string) $this->request->getPost('invoice')));

            if ($invoice === '' || ! preg_match('/^[A-Z0-9_-]{6,64}$/', $invoice)) {
                return $this->backWithAlert('error', 'Nomor invoice tidak valid', true);
            }

            $result = $this->paymentDetailPageOrchestrator()->checkInvoice($invoice);

            if (! $result['success']) {
                return $this->backWithAlert('error', $result['message'], true);
            }

            $order = $result['data'];

            return redirect()->to('payment/' . $order['payment_token']);
        }

        $data = [
            'meta' => [
                'title' => 'Cek Pembayaran',
            ],
        ];

        $this->baseData['page_assets']['css'][] = 'resources/css/pages/payment.css';
        $this->baseData['page_assets']['js'][]  = 'resources/js/pages/payment.js';

        return $this->renderView('Pages/Payment/Check', $data);
    }

    protected function paymentDetailPageOrchestrator(): PaymentDetailPageOrchestrator
    {
        return single_service('paymentDetailPageOrchestrator');
    }
}
