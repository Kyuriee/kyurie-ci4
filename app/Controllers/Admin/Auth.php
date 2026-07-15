<?php

namespace App\Controllers\Admin;

use App\Services\Admin\AdminAuthService;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $helpers = ['vite', 'form', 'url', 'admin'];
    protected $session;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = service('session');
    }

    public function login()
    {
        if ($this->session->get('admin_id')) {
            return redirect()->to(admin_url('dashboard'));
        }

        if ($this->request->is('post')) {
            if ($redirect = $this->throttleOrBack('admin_login', 5, 'Terlalu banyak percobaan login. Silakan coba lagi sebentar.')) {
                return $redirect;
            }

            $result = $this->_service()->login(
                (string) $this->request->getPost('username'),
                (string) $this->request->getPost('password')
            );

            if (! $result['success']) {
                return $this->backWithAlert('error', $result['message']);
            }

            $this->session->regenerate();
            $this->session->set([
                'admin_id'       => $result['data']['admin_id'],
                'admin_username' => $result['data']['username'],
                'admin_name'     => $result['data']['name'],
                'admin_level'    => $result['data']['level'],
            ]);

            return redirect()->to(admin_url('dashboard'));
        }

        return view('Pages/Admin/Login', [
            'meta' => ['title' => 'Admin Login'],
        ]);
    }

    public function logout()
    {
        $this->session->remove(['admin_id', 'admin_username', 'admin_name', 'admin_level']);
        $this->session->destroy();

        return redirect()->to(admin_url('login'));
    }

    protected function _service(): AdminAuthService
    {
        return single_service('adminAuthService');
    }

    protected function throttleOrBack(string $key, int $limit, string $message)
    {
        $throttler   = service('throttler');
        $throttleKey = $key . '_' . $this->request->getIPAddress();

        if ($throttler->check($throttleKey, $limit, MINUTE) !== false) {
            return null;
        }

        return $this->backWithAlert('error', $message);
    }

    protected function backWithAlert(string $type, string $message, bool $withInput = false)
    {
        $this->session->setFlashdata('alert', [
            'type'    => $type,
            'message' => $message,
        ]);

        $redirect = redirect()->back();

        return $withInput ? $redirect->withInput() : $redirect;
    }
}
