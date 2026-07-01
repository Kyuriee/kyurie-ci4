<?php

namespace App\Controllers;

use App\Services\AuthService;

class Auth extends BaseController
{


    public function login()
    {
        if ($this->session->get('user_id')) {
            return redirect()->to('/');
        }

        if ($this->request->is('post')) {
            // 1. Rate Limiting (Anti Brute-Force) - Max 5x login gagal per menit per IP
            $throttler = service('throttler');
            if ($throttler->check('login_' . $this->request->getIPAddress(), 5, MINUTE) === false) {
                $this->session->setFlashdata('alert', [
                    'type'    => 'error',
                    'message' => 'Terlalu banyak percobaan login. Silakan coba lagi sebentar.',
                ]);
                return redirect()->back();
            }

            // 2. Validasi Input dengan Custom Error Messages
            $rules = [
                'username' => [
                    'rules'  => 'required|alpha_numeric|min_length[3]|max_length[100]',
                    'errors' => [
                        'required'      => 'Username wajib diisi.',
                        'alpha_numeric' => 'Username hanya boleh berisi huruf dan angka.',
                        'min_length'    => 'Username minimal 3 karakter.',
                    ],
                ],
                'password' => [
                    'rules'  => 'required|min_length[6]',
                    'errors' => [
                        'required'   => 'Password wajib diisi.',
                        'min_length' => 'Password minimal 6 karakter.',
                    ],
                ],
            ];

            if (! $this->validate($rules)) {
                $errors = $this->validator->getErrors();
                $this->session->setFlashdata('alert', [
                    'type'    => 'error',
                    'message' => implode('<br>', $errors),
                ]);
                return redirect()->back()->withInput();
            }

            $result = $this->_service()->login(
                $this->request->getPost('username'),
                $this->request->getPost('password')
            );

            if ($result['success']) {
                // Handle Remember Me
                if ($this->request->getPost('remember')) {
                    $this->_service()->processRememberMe($result['user_id']);
                }

                $this->session->setFlashdata('alert', [
                    'type'    => 'success',
                    'message' => 'Login berhasil!',
                ]);
                return redirect()->to('/');
            }

            $this->session->setFlashdata('alert', [
                'type'    => 'error',
                'message' => $result['message'],
            ]);
            return redirect()->back();
        }

        $data = ['meta' => ['title' => 'Login']];
        $this->base_data['page_assets']['js'][] = 'resources/js/auth.js';
        return $this->renderView('Pages/Auth/Login', $data);
    }

    public function register()
    {
        if ($this->session->get('user_id')) {
            return redirect()->to('/');
        }

        if ($this->request->is('post')) {
            $rules = [
                'username' => [
                    'rules'  => 'required|alpha_numeric|min_length[4]|max_length[100]|is_unique[users.username]',
                    'errors' => [
                        'required'      => 'Username wajib diisi.',
                        'alpha_numeric' => 'Username hanya boleh berisi huruf dan angka.',
                        'min_length'    => 'Username minimal 4 karakter.',
                        'is_unique'     => 'Username ini sudah terdaftar, gunakan yang lain.',
                    ],
                ],
                'email' => [
                    'rules'  => 'required|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required'    => 'Email wajib diisi.',
                        'valid_email' => 'Format email tidak valid.',
                        'is_unique'   => 'Email ini sudah terdaftar.',
                    ],
                ],
                'password' => [
                    'rules'  => 'required|min_length[8]',
                    'errors' => [
                        'required'   => 'Password wajib diisi.',
                        'min_length' => 'Password minimal 8 karakter.',
                    ],
                ],
                'password_confirm' => [
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Konfirmasi password wajib diisi.',
                        'matches'  => 'Konfirmasi password tidak sama dengan password.',
                    ],
                ],
                'phone' => [
                    'rules'  => 'permit_empty|numeric|min_length[10]|max_length[15]',
                    'errors' => [
                        'numeric'    => 'Nomor telepon harus berupa angka.',
                        'min_length' => 'Nomor telepon minimal 10 angka.',
                        'max_length' => 'Nomor telepon maksimal 15 angka.',
                    ],
                ],
            ];

            if (! $this->validate($rules)) {
                $errors = $this->validator->getErrors();
                $this->session->setFlashdata('error', implode('<br>', $errors));
                return redirect()->back()->withInput();
            }

            $result = $this->_service()->register([
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'phone'    => $this->request->getPost('phone'),
            ]);

            if ($result['success']) {
                $this->session->setFlashdata('success', 'Pendaftaran berhasil, silakan login!');
                return redirect()->to('auth');
            }

            $this->session->setFlashdata('error', $result['message']);
            return redirect()->back()->withInput();
        }

        $data = ['meta' => ['title' => 'Daftar Akun']];
        return $this->renderView('Pages/Auth/register', $data); 
    }

    public function logout()
    {
        $this->_service()->logout();

        $this->session->setFlashdata('alert', [
            'type'    => 'success',
            'message' => 'Berhasil logout',
        ]);
        return redirect()->to('/');
    }

    public function forgot()
    {
        if ($this->session->get('user_id')) {
            return redirect()->to('/');
        }

        if ($this->request->is('post')) {
            $rules = [
                'email' => 'required|valid_email'
            ];

            if (! $this->validate($rules)) {
                $this->session->setFlashdata('alert', [
                    'type'    => 'error',
                    'message' => 'Format email tidak valid.',
                ]);
                return redirect()->back()->withInput();
            }

            $result = $this->_service()->forgotPassword($this->request->getPost('email'));

            $this->session->setFlashdata('alert', [
                'type'    => $result['success'] ? 'success' : 'error',
                'message' => $result['success'] ? 'Jika email terdaftar, link reset password telah dikirim.' : $result['message'],
            ]);
            
            return redirect()->back();
        }

        $data = ['meta' => ['title' => 'Lupa Password']];
        $this->base_data['page_assets']['js'][] = 'resources/js/auth.js';
        return $this->renderView('Pages/Auth/Forgot', $data);
    }

    public function reset(string $token)
    {
        if ($this->session->get('user_id')) {
            return redirect()->to('/');
        }

        if ($this->request->is('post')) {
            $rules = [
                'password'         => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (! $this->validate($rules)) {
                $this->session->setFlashdata('alert', [
                    'type'    => 'error',
                    'message' => 'Password minimal 8 karakter dan harus sama persis.',
                ]);
                return redirect()->back();
            }

            $result = $this->_service()->resetPassword($token, $this->request->getPost('password'));

            if ($result['success']) {
                $this->session->setFlashdata('alert', [
                    'type'    => 'success',
                    'message' => 'Password berhasil diubah. Silakan login dengan password baru.',
                ]);
                return redirect()->to('Auth/Login');
            }

            $this->session->setFlashdata('alert', [
                'type'    => 'error',
                'message' => $result['message'],
            ]);
            return redirect()->back();
        }

        $data = [
            'meta'  => ['title' => 'Reset Password'],
            'token' => $token
        ];
        $this->base_data['page_assets']['js'][] = 'resources/js/auth.js';
        return $this->renderView('Pages/Auth/Reset', $data);
    }

   protected function _service(): \App\Services\AuthService
    {
        if (! isset($this->authService)) {
            $this->authService = new \App\Services\AuthService();
        }
        
        return $this->authService;
    }
}
