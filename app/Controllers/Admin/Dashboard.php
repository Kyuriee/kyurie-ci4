<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    protected $helpers = ['url'];

    /**
     * Placeholder only — confirms the auth flow works end-to-end.
     * The real Mazer-based dashboard layout/sidebar is the next phase.
     */
    public function index()
    {
        $session = service('session');

        return view('Pages/Admin/DashboardPlaceholder', [
            'admin_name'  => $session->get('admin_name'),
            'admin_level' => $session->get('admin_level'),
        ]);
    }
}
