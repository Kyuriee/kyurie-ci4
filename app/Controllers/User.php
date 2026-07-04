<?php

namespace App\Controllers;

use App\Services\UserService;

class User extends baseController
{


    public function profile()
    {
        $user_id = $this->session->get('user_id');



        $profile = $this->_service()->getProfile($user_id);

        $data = [
            'meta'    => ['title' => 'Profil Saya'],
            'profile' => $profile,
        ];

        return $this->renderView('pages/user/profile', $data);
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

    protected function _service(): UserService
    {
        return single_service('userService');
    }
}
