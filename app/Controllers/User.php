<?php

namespace App\Controllers;

use App\Services\User\UserService;
use App\Services\Order\OrderService;

class User extends BaseController
{
    public function profile()
    {
        $user_id = $this->session->get('user_id');
        $profile = $this->_service()->getProfile($user_id);
        $data = [
            'meta'    => ['title' => 'Profil Saya'],
            'profile' => $profile,
        ];
        return $this->renderView('Pages/User/Profile', $data);
    }

    public function update()
    {
        $user_id = $this->session->get('user_id');
        if ($this->request->is('post')) {
            $result = $this->_service()->updateProfile($user_id, [
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
            ]);

            $this->session->setFlashdata('alert', [
                'type'    => $result['success'] ? 'success' : 'error',
                'message' => $result['message'],
            ]);
            return redirect()->back();
        }
        return redirect()->to('user/profile');
    }

    public function orders()
    {
        $user_id = (int) $this->session->get('user_id');
        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $limit   = 10;
        $offset  = ($page - 1) * $limit;

        $orders = $this->_order_service()->getOrdersByUser($user_id, $limit, $offset);

        $data = [
            'meta'   => ['title' => 'Riwayat Order'],
            'orders' => $orders,
            'page'   => $page,
        ];
        return $this->renderView('Pages/User/Orders', $data);
    }

    public function settings()
    {
        $data = [
            'meta' => ['title' => 'Pengaturan Akun'],
        ];
        return $this->renderView('Pages/User/Settings', $data);
    }

    public function changePassword()
    {
        $user_id = (int) $this->session->get('user_id');

        $result = $this->_service()->changePassword(
            $user_id,
            (string) $this->request->getPost('current_password'),
            (string) $this->request->getPost('new_password')
        );

        $this->session->setFlashdata('alert', [
            'type'    => $result['success'] ? 'success' : 'error',
            'message' => $result['message'],
        ]);
        return redirect()->back();
    }

    protected function _service(): UserService
    {
        return single_service('userService');
    }

    protected function _order_service(): OrderService
    {
        return single_service('orderService');
    }
}
