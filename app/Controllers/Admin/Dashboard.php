<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

class Dashboard extends BaseController
{

    public function index()
    {
        if ($redirect = $this->redirectIfGuest()) {
            return $redirect;
        }

        return $this->renderView('Pages/Admin/Dashboard', [
            'meta'          => ['title' => 'Dashboard',],
            'admin'         => $this->currentAdmin,
            'activeMenu'    => 'dashboard',
        ]);
    }
}
