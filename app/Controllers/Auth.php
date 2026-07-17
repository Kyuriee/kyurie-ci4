<?php

namespace App\Controllers;

use App\Services\Auth\AuthService;
use App\Validation\AuthRequestRules;
use App\Requests\AuthRequest;

class Auth extends BaseController
{
    public function login()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return $this->renderView('Pages/Auth/Login', [
                'meta' => ['title' => 'Login'],
            ]);
        }

        $post = AuthRequest::login($this->request);

        if (($redirect = $this->validateOrBack(
            $post,
            AuthRequestRules::login()
        )) !== null) {
            return $redirect;
        }

        $data = $this->validator->getValidated();

        if (($redirect = $this->throttleOrBack(
            'login',
            5,
            'Terlalu banyak percobaan login. Silakan coba lagi sebentar.',
            $this->throttleIdentifier(
                $data['username'],
                $this->request->getIPAddress()
            )
        )) !== null) {
            return $redirect;
        }

        $result = $this->authService()->login(
            $data['username'],
            $data['password']
        );

        if (! $result['success']) {
            return $this->backWithAlert('error', $result['message'], true);
        }

        if ($post['remember']) {
            $this->authService()->processRememberMe(
                (int) ($result['data']['user_id'] ?? 0)
            );
        }

        $redirectUrl = $this->session->get('redirect_url');
        $this->session->remove('redirect_url');

        return $this->redirectWithAlert(
            $redirectUrl ?: '/',
            'success',
            'Login berhasil!'
        );
    }

    public function register()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return $this->renderView('Pages/Auth/Register', [
                'meta' => ['title' => 'Daftar Akun'],
            ]);
        }

        $post = AuthRequest::register($this->request);

        if (($redirect = $this->validateOrBack(
            $post,
            AuthRequestRules::register()
        )) !== null) {
            return $redirect;
        }

        $data = $this->validator->getValidated();

        if (($redirect = $this->throttleOrBack(
            'register',
            5,
            'Terlalu banyak percobaan. Silakan coba lagi sebentar.',
            $this->throttleIdentifier(
                $data['email'],
                $this->request->getIPAddress()
            )
        )) !== null) {
            return $redirect;
        }

        $result = $this->authService()->register([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'phone'    => $data['phone'] ?? '',
        ]);

        if (! $result['success']) {
            return $this->backWithAlert('error', $result['message'], true);
        }

        return $this->redirectWithAlert(
            'auth/login',
            'success',
            'Pendaftaran berhasil, silakan login!'
        );
    }

    public function logout()
    {
        $this->authService()->logout();

        return $this->redirectWithAlert('/', 'success', 'Berhasil logout');
    }

    public function forgot()
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return $this->renderView('Pages/Auth/Forgot', [
                'meta' => ['title' => 'Lupa Password'],
            ]);
        }

        $post = AuthRequest::forgot($this->request);

        if (($redirect = $this->validateOrBack(
            $post,
            AuthRequestRules::forgot()
        )) !== null) {
            return $redirect;
        }

        $data = $this->validator->getValidated();

        if (($redirect = $this->throttleOrBack(
            'forgot',
            3,
            'Terlalu banyak percobaan. Silakan coba lagi sebentar.',
            $this->throttleIdentifier(
                $data['email'],
                $this->request->getIPAddress()
            )
        )) !== null) {
            return $redirect;
        }

        $result = $this->authService()->forgotPassword(
            $data['email']
        );

        return $this->backWithAlert(
            $result['success'] ? 'success' : 'error',
            $result['success']
                ? 'Jika email terdaftar, link reset password telah dikirim.'
                : $result['message']
        );
    }

    public function reset(string $token)
    {
        if ($redirect = $this->redirectIfAuthenticated()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return $this->renderView('Pages/Auth/Reset', [
                'meta'  => ['title' => 'Reset Password'],
                'token' => $token,
            ]);
        }

        $post = AuthRequest::reset($this->request);

        if (($redirect = $this->validateOrBack(
            $post,
            AuthRequestRules::reset()
        )) !== null) {
            return $redirect;
        }

        $data = $this->validator->getValidated();

        if (($redirect = $this->throttleOrBack(
            'resetPassword',
            5,
            'Terlalu banyak percobaan.',
            $this->throttleIdentifier(
                $token,
                $this->request->getIPAddress()
            )
        )) !== null) {
            return $redirect;
        }

        $result = $this->authService()->resetPassword(
            $token,
            $data['password']
        );

        if (! $result['success']) {
            return $this->backWithAlert('error', $result['message']);
        }

        return $this->redirectWithAlert(
            'auth/login',
            'success',
            'Password berhasil diubah. Silakan login dengan password baru.'
        );
    }

    protected function authService(): AuthService
    {
        return single_service('authService');
    }
}
