<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Requests\AdminAuthRequest;
use App\Validation\AdminAuthRequestRules;

class Auth extends BaseController
{
    public function login()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return view('Pages/Admin/Login', [
                'meta' => ['title' => 'Admin Login'],
            ]);
        }

        $post = AdminAuthRequest::login($this->request);

        if (($redirect = $this->validateOrBack(
            $post,
            AdminAuthRequestRules::login()
        )) !== null) {
            return $redirect;
        }

        $data = $this->validator->getValidated();

        if (($redirect = $this->throttleOrBack(
            'admin_login',
            5,
            'Terlalu banyak percobaan login. Silakan coba lagi sebentar.',
            $this->throttleIdentifier(
                $data['username'],
                $this->request->getIPAddress()
            )
        )) !== null) {
            return $redirect;
        }

        $result = $this->adminService()->login(
            $data['username'],
            $data['password']
        );

        if (! $result['success']) {
            return $this->backWithAlert('error', $result['message'], true);
        }

        $this->setAdminSession($result['data']);

        return redirect()->to(admin_url('dashboard'));
    }

    public function logout()
    {
        $this->destroyAdminSession();

        return redirect()->to(admin_url('login'));
    }
}
