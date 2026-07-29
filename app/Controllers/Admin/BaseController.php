<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController as AppBaseController;
use App\Services\Admin\AdminAuthService;

abstract class BaseController extends AppBaseController
{

    protected $helpers = [
        'vite',
        'form',
        'url',
        'admin',
    ];

    protected function adminService(): AdminAuthService
    {
        return single_service('adminAuthService');
    }

    protected function redirectIfAuthenticated()
    {
        if (! $this->session->get('admin_id')) {
            return null;
        }

        return redirect()->to(admin_url('dashboard'));
    }

    protected function redirectIfGuest()
    {
        if ($this->session->get('admin_id')) {
            return null;
        }

        return redirect()->to(admin_url('login'));
    }

    protected function requestPayload(): array
    {
        $json = $this->request->getJSON(true);

        if (is_array($json)) {
            return $json;
        }

        return $this->request->getPost() ?? [];
    }

    protected function setAdminSession(array $admin): void
    {
        $this->session->regenerate();

        $this->session->set([
            'admin_id'       => $admin['admin_id'],
            'admin_username' => $admin['username'],
            'admin_name'     => $admin['name'],
            'admin_level'    => $admin['level'],
        ]);
    }

    protected function destroyAdminSession(): void
    {
        $this->session->remove([
            'admin_id',
            'admin_username',
            'admin_name',
            'admin_level',
        ]);

        $this->session->destroy();
    }
}
