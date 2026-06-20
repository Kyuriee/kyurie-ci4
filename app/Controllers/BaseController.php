<?php

namespace App\Controllers;

use App\Models\baseModel;
use App\Models\UserModel;
use App\Services\SettingService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['vite', 'url', 'form'];

    protected $session;
    protected $baseModel;
    protected $setting_service;
    protected $userModel;
    protected $base_data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session         = service('session');
        $this->baseModel       = model(baseModel::class);
        $this->setting_service = new SettingService();
        $this->userModel       = model(UserModel::class);

        $web = $this->setting_service->get_public_utilities();

        $this->base_data = [
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
        ];
    }

    protected function _get_current_user(): ?array
    {
        $user_id = $this->session->get('user_id');

        if (! $user_id) {
            helper('cookie');

            $cookieToken = get_cookie('remember_me');

            if ($cookieToken) {
                $user = $this->userModel->where('remember_token', $cookieToken)->first();

                if ($user && $user['status'] === 'On') {
                    $this->session->set('user_id', $user['id']);

                    unset($user['password'], $user['remember_token'], $user['reset_token']);

                    return $user;
                }
            }

            return null;
        }

        $user = $this->userModel->find($user_id);

        if (! $user || $user['status'] !== 'On') {
            return null;
        }

        unset($user['password'], $user['remember_token'], $user['reset_token']);

        return $user;
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

    protected function responseJson(bool $success, string $message, $data = null)
    {
        return $this->response->setJSON([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ]);
    }
}