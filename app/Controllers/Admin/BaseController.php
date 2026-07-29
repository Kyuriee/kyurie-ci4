<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController as AppBaseController;
use App\Services\Admin\AdminAuthService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends AppBaseController
{

    protected $helpers = [
        'vite',
        'form',
        'url',
        'admin',
    ];

    protected $currentAdmin;

    /**
     * Deliberately skips App\Controllers\BaseController::initController()
     * — that one resolves the storefront *user* and builds storefront
     * context (menus, SEO, etc.), none of which apply here. Admin gets
     * its own currentAdmin + AdminContextBuilder: same shape/pattern
     * (resolve current actor -> build $baseData), different domain.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        Controller::initController($request, $response, $logger);

        $this->session      = service('session');
        $this->currentAdmin = $this->resolveCurrentAdmin();
        $this->baseData     = service('adminContextBuilder')->build(
            $this->currentAdmin,
            $this->getAlert()
        );
    }

    /**
     * Re-resolves the admin from the DB every request (see
     * AdminAuthService::getCurrentAdmin) — the session only holds the id,
     * so this is what actually confirms "is this still a valid admin"
     * rather than just trusting a stale session key.
     */
    protected function resolveCurrentAdmin(): ?array
    {
        $adminId = (int) ($this->session->get('admin_id') ?? 0);

        if ($adminId <= 0) {
            return null;
        }

        return $this->adminService()->getCurrentAdmin($adminId);
    }

    protected function adminService(): AdminAuthService
    {
        return single_service('adminAuthService');
    }

    /**
     * Sama seperti AdminAuthFilter::before() — dijaga dobel di sini karena
     * beberapa method (page(), index()) manggil ini manual selain lewat
     * filter. Kondisinya harus sinkron: AJAX/JSON dapat JSON, page load
     * biasa dapat redirect+alert (reuse helper parent, bukan bikin baru).
     */
    protected function wantsJson(): bool
    {
        return $this->request->isAJAX()
            || str_contains($this->request->getHeaderLine('Accept'), 'application/json');
    }

    protected function redirectIfAuthenticated()
    {
        if (! $this->currentAdmin) {
            return null;
        }

        if ($this->wantsJson()) {
            return $this->responseJson(false, 'Sudah login.')->setStatusCode(409);
        }

        return redirect()->to(admin_url('dashboard'));
    }

    protected function redirectIfGuest()
    {
        if ($this->currentAdmin) {
            return null;
        }

        if ($this->wantsJson()) {
            return $this->responseJson(false, 'Sesi admin habis, silakan login ulang.')->setStatusCode(401);
        }

        return $this->redirectWithAlert(admin_url('login'), 'error', 'Silakan login terlebih dahulu.');
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
