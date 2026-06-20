<?php

namespace App\Services;

use App\Models\AuthModel;

class AuthService extends baseService
{
    protected $authModel;

    public function __construct()
    {
        $this->authModel = model(AuthModel::class);
    }

    public function login(string $username, string $password): array
    {
        $user = $this->authModel->verifyLogin($username, $password);

        if (empty($user)) {
            return [
                'success' => false,
                'message' => 'Username atau password salah',
            ];
        }

        // Regenerate session ID for security
        service('session')->regenerate();
        service('session')->set('user_id', $user['id']);

        return [
            'success' => true,
            'message' => 'Login berhasil',
            'user_id' => $user['id'], // Kembalikan ID untuk remember me logic di Controller
        ];
    }

    public function register(array $data): array
    {
        // Pengecekan unik sudah di-handle oleh validasi Controller (is_unique)

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
            return [
                'success' => false,
                'message' => 'Gagal mendaftar, coba lagi',
            ];
        }

        return [
            'success' => true,
            'message' => 'Pendaftaran berhasil',
        ];
    }

    public function logout(): void
    {
        $userId = service('session')->get('user_id');
        if ($userId) {
            // Hapus remember_token di DB jika ada
            $this->authModel->update($userId, ['remember_token' => null]);
        }
        
        // Hapus cookie
        helper('cookie');
        delete_cookie('remember_me');

        service('session')->remove('user_id');
    }

    public function processRememberMe(int $userId): void
    {
        helper('cookie');
        $token = bin2hex(random_bytes(32));
        
        // Simpan ke DB
        $this->authModel->update($userId, ['remember_token' => $token]);

        // Simpan ke Cookie (30 Hari)
        set_cookie('remember_me', $token, 30 * 24 * 60 * 60);
    }

    public function forgotPassword(string $email): array
    {
        $user = $this->authModel->getByEmail($email);
        if (empty($user)) {
            // Pura-pura sukses demi keamanan (mencegah email enumeration)
            return ['success' => true];
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->authModel->update($user['id'], [
            'reset_token'      => $token,
            'reset_expires_at' => $expires,
        ]);

        return $this->sendResetEmail($user['email'], $token);
    }

    protected function sendResetEmail(string $email, string $token): array
    {
        $emailService = service('email');
        $emailService->setTo($email);
        $emailService->setSubject('Reset Password');
        
        $resetLink = base_url('auth/reset/' . $token);
        
        $message = "Halo,<br><br>Silakan klik link berikut untuk mereset password Anda. Link ini akan kedaluwarsa dalam 1 jam:<br><br>";
        $message .= "<a href='{$resetLink}'>Reset Password Sekarang</a>";
        
        $emailService->setMessage($message);

        if ($emailService->send()) {
            return ['success' => true];
        }

        log_message('error', 'Gagal mengirim email reset: ' . $emailService->printDebugger(['headers']));
        return ['success' => false, 'message' => 'Gagal mengirim email, silakan coba lagi nanti.'];
    }

    public function resetPassword(string $token, string $newPassword): array
    {
        $user = $this->authModel->where('reset_token', $token)
                                ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
                                ->first();

        if (empty($user)) {
            return ['success' => false, 'message' => 'Link reset password tidak valid atau sudah kedaluwarsa.'];
        }

        $this->authModel->update($user['id'], [
            'password'         => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);

        return ['success' => true];
    }
}
