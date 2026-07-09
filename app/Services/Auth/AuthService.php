<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Models\AuthModel;

class AuthService extends BaseService
{
    protected $authModel;

    public function __construct()
    {
        $this->authModel = model(AuthModel::class);
    }

    public function login(string $username, string $password): array
    {
        return $this->safeCall(function () use ($username, $password) {
            $user = $this->authModel->verifyLogin($username, $password);

            if (empty($user)) {
                return $this->fail('Username atau password salah');
            }

            service('session')->regenerate();
            service('session')->set('user_id', $user['id']);

            return $this->success('Login berhasil', [
                'user_id' => $user['id'],
            ]);
        }, $this->fail('Gagal login, coba lagi'));
    }

    public function register(array $data): array
    {
        return $this->safeCall(function () use ($data) {
            $saved = $this->authModel->insert([
                'username' => $data['username'],
                'email'    => $data['email'] ?? null,
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'phone'    => $data['phone'] ?? null,
                'balance'  => 0,
                'level'    => 'Member',
                'status'   => 'On',
            ]);

            if (! $saved) {
                return $this->fail('Gagal mendaftar, coba lagi');
            }

            return $this->success('Pendaftaran berhasil');
        }, $this->fail('Gagal mendaftar, coba lagi'));
    }

    public function logout(): void
    {
        $userId = service('session')->get('user_id');

        if ($userId) {
            $this->authModel->update($userId, ['remember_token' => null]);
        }

        helper('cookie');
        delete_cookie('remember_me');

        service('session')->remove('user_id');
    }

    public function processRememberMe(int $userId): void
    {
        helper('cookie');

        $token = bin2hex(random_bytes(32));

        $this->authModel->update($userId, [
            'remember_token' => $token,
        ]);

        set_cookie('remember_me', $token, 30 * 24 * 60 * 60);
    }

    public function forgotPassword(string $email): array
    {
        return $this->safeCall(function () use ($email) {
            $user = $this->authModel->getByEmail($email);

            if (empty($user)) {
                return $this->success('Jika email terdaftar, link reset akan dikirim');
            }

            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->authModel->update($user['id'], [
                'reset_token'      => $token,
                'reset_expires_at' => $expires,
            ]);

            return $this->sendResetEmail($user['email'], $token);
        }, $this->fail('Gagal memproses reset password'));
    }

    protected function sendResetEmail(string $email, string $token): array
    {
        $emailService = service('email');

        $emailService->setTo($email);
        $emailService->setSubject('Reset Password');

        $resetLink = base_url('auth/reset/' . $token);

        $message  = "Halo,<br><br>";
        $message .= "Silakan klik link berikut untuk mereset password Anda. Link ini akan kedaluwarsa dalam 1 jam:<br><br>";
        $message .= "<a href='{$resetLink}'>Reset Password Sekarang</a>";

        $emailService->setMessage($message);

        if ($emailService->send()) {
            return $this->success('Jika email terdaftar, link reset akan dikirim');
        }

        log_message('error', 'Gagal mengirim email reset: ' . $emailService->printDebugger(['headers']));

        return $this->fail('Gagal mengirim email, silakan coba lagi nanti.');
    }

    public function resetPassword(string $token, string $newPassword): array
    {
        return $this->safeCall(function () use ($token, $newPassword) {
            $user = $this->authModel
                ->where('reset_token', $token)
                ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
                ->first();

            if (empty($user)) {
                return $this->fail('Link reset password tidak valid atau sudah kedaluwarsa.');
            }

            $this->authModel->update($user['id'], [
                'password'         => password_hash($newPassword, PASSWORD_DEFAULT),
                'reset_token'      => null,
                'reset_expires_at' => null,
                'remember_token'   => null,
            ]);

            return $this->success('Password berhasil diperbarui');
        }, $this->fail('Gagal mereset password'));
    }
}
