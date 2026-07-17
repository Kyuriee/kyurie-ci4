<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $helpers = ['vite', 'url', 'form'];

    protected $session;
    protected $currentUser;
    protected $baseData = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session     = service('session');
        $this->currentUser = $this->resolveCurrentUser();
        $this->baseData    = service('storefrontContextBuilder')->build(
            $this->currentUser,
            $this->getAlert()
        );
    }

    protected function resolveCurrentUser(): ?array
    {
        $userId        = $this->session->get('user_id');
        $rememberToken = null;

        if (! $userId) {
            helper('cookie');
            $rememberToken = get_cookie('remember_me');
            if (! $rememberToken) {
                return null;
            }
        }

        $user = service('userService')->getCurrentUser($userId ? (int) $userId : null, $rememberToken ?: null);

        if (! $user) {
            return null;
        }

        if (! $userId) {
            $this->session->set('user_id', $user['id']);
        }

        return $user;
    }

    protected function getAlert(): ?array
    {
        return $this->session->getFlashdata('alert');
    }

    protected function renderView(string $view, array $data = []): string
    {
        $mergedData = array_replace_recursive($this->baseData, $data);

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

    protected function validateOrBack(array $data, array $rules, ?string $message = null)
    {
        if ($this->validateData($data, $rules)) {
            return null;
        }

        $message ??= implode('<br>', $this->validator->getErrors());

        return $this->backWithAlert('error', $message, true);
    }

    protected function throttleIdentifier(string ...$parts): string
    {
        return implode('|', array_filter($parts, static fn($part) => $part !== ''));
    }

    protected function throttleOrBack(
        string $bucket,
        int $capacity,
        string $message,
        ?string $identifier = null
    ) {
        $throttler = service('throttler');

        $identifier ??= $this->request->getIPAddress();

        $bucketKey = sprintf(
            '%s_%s',
            preg_replace('/[^A-Za-z0-9_]/', '_', $bucket),
            hash('sha256', $identifier)
        );

        if ($throttler->check($bucketKey, $capacity, MINUTE)) {
            return null;
        }

        return $this->backWithAlert('error', $message, true);
    }

    protected function redirectIfAuthenticated()
    {
        return $this->currentUser ? redirect()->to('/') : null;
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
