<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\Setting\SettingService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['vite', 'url', 'form'];

    protected $session;
    protected $settingService;
    protected $userModel;
    protected $base_data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session         = service('session');
        $this->settingService = new SettingService();
        $this->userModel       = model(UserModel::class);

        $web = $this->settingService->getPublicUtilities();
        $menus = [
            [
                'title' => 'Home',
                'url'   => base_url('/'),
                'icon'  => 'bi-house-door-fill',
                'match' => '',
            ],
            [
                'title' => 'Cek Pembayaran',
                'url'   => base_url('payment/check'),
                'icon'  => 'bi-receipt',
                'match' => 'payment',
            ],
            [
                'title' => 'Promo',
                'url'   => base_url('promo'),
                'icon'  => 'bi-ticket-perforated-fill',
                'match' => 'promo',
            ],
            [
                'title' => 'Bantuan',
                'url'   => base_url('help'),
                'icon'  => 'bi-question-circle-fill',
                'match' => 'help',
            ],
        ];

        $this->base_data = [
            'menus' => $menus,
            'meta' => [
                'title'       => $web['web_title'] ?? 'RRQ & Evos Bersahabat',
                'subtitle'    => $web['web_subtitle'] ?? '',
                'description' => $web['web_description'] ?? '',
                'keywords'    => $web['web_keywords'] ?? '',
                'author'      => $web['web_author'] ?? '',
                'logo'        => $web['web_logo'] ?? '',
                'favicon'     => $web['web_favicon'] ?? '',
            ],
            'seo' => [
                'og_title'       => $web['web_title'] ?? '',
                'og_description' => $web['web_description'] ?? '',
                'og_image'       => $web['og_image'] ?? ($web['web_logo'] ?? ''),
                'og_url'         => current_url(),
                'og_type'        => 'website',
                'twitter_card'   => 'summary_large_image',
            ],
            'user'  => $this->_get_current_user(),
            'alert' => $this->_get_alert(),
            'page_assets' => [
                'css' => [],
                'js'  => [],
            ],
        ];
    }

    protected function _get_current_user(): ?array
    {
        $userId = $this->session->get('user_id');
        if (! $userId) {
            helper('cookie');
            $rememberToken = get_cookie('remember_me');
            if ($rememberToken) {
                $user = $this->userModel
                    ->where('remember_token', $rememberToken)
                    ->first();
                if ($user && $user['status'] === 'On') {
                    $this->session->set('user_id', $user['id']);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
        $user = $this->userModel->find($this->session->get('user_id'));
        if (! $user || $user['status'] !== 'On') {
            return null;
        }
        return [
            'id'       => (int) $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'phone'    => $user['phone'],
            'balance'  => (float) $user['balance'],
            'level'    => $user['level'],
        ];
    }

    protected function _get_alert(): ?array
    {
        return $this->session->getFlashdata('alert');
    }

    protected function renderView(string $view, array $data = []): string
    {
        $mergedData = array_replace_recursive($this->base_data, $data);

        return view($view, $mergedData);
    }

    protected function setAlert(string $type, string $message): void
    {
        $this->session->setFlashdata('alert', [
            'type'    => $type,
            'message' => $message,
        ]);
    }

    protected function backWithAlert(string $type, string $message, bool $withInput = false)
    {
        $this->setAlert($type, $message);

        $redirect = redirect()->back();

        return $withInput ? $redirect->withInput() : $redirect;
    }

    protected function redirectWithAlert(string $to, string $type, string $message)
    {
        $this->setAlert($type, $message);

        return redirect()->to($to);
    }

    protected function validateOrBack(array $rules, ?string $message = null)
    {
        if ($this->validate($rules)) {
            return null;
        }

        $message = $message ?: implode('<br>', $this->validator->getErrors());

        return $this->backWithAlert('error', $message, true);
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

    protected function responseJson(bool $success, string $message, $data = null)
    {
        return $this->response->setJSON([
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
            'csrf_hash' => function_exists('csrf_hash') ? csrf_hash() : null,
        ]);
    }
}
