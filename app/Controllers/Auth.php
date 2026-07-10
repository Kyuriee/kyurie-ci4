<?php

namespace App\Controllers;

use App\Services\Auth\AuthService;
use App\Validation\AuthRequestRules;

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

        if ($redirect = $this->throttleOrBack('login', 5, 'Terlalu banyak percobaan login. Silakan coba lagi sebentar.')) {
            return $redirect;
        }

        if ($redirect = $this->validateOrBack(AuthRequestRules::login())) {
            return $redirect;
        }

        $result = $this->_service()->login(
            $this->request->getPost('username'),
            $this->request->getPost('password')
        );

        if (! $result['success']) {
            return $this->backWithAlert('error', $result['message'], true);
        }

        if ($this->request->getPost('remember')) {
            $this->_service()->processRememberMe((int) ($result['data']['user_id'] ?? 0));
        }

        return $this->redirectWithAlert('/', 'success', 'Login berhasil!');
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

        if ($redirect = $this->throttleOrBack('register', 5, 'Terlalu banyak percobaan. Silakan coba lagi sebentar.')) {
            return $redirect;
        }

        if ($redirect = $this->validateOrBack(AuthRequestRules::register())) {
            return $redirect;
        }

        $result = $this->_service()->register([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone'    => $this->request->getPost('phone'),
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
        $this->_service()->logout();

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

        if ($redirect = $this->throttleOrBack('forgot', 3, 'Terlalu banyak percobaan. Silakan coba lagi sebentar.')) {
            return $redirect;
        }

        if ($redirect = $this->validateOrBack(AuthRequestRules::forgot(), 'Format email tidak valid.')) {
            return $redirect;
        }

        $result = $this->_service()->forgotPassword(
            $this->request->getPost('email')
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

        if ($redirect = $this->validateOrBack(AuthRequestRules::reset(), 'Password minimal 8 karakter dan harus sama persis.')) {
            return $redirect;
        }

        $result = $this->_service()->resetPassword(
            $token,
            $this->request->getPost('password')
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

    protected function redirectIfAuthenticated()
    {
        if (! $this->session->get('user_id')) {
            return null;
        }

        return redirect()->to('/');
    }

    protected function _service(): AuthService
    {
        return single_service('authService');
    }
}
