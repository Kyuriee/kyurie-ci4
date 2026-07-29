<?php

namespace App\Controllers\Admin;

use App\Requests\UserBalanceRequest;
use App\Services\User\UserService;
use App\Validation\UserBalanceRequestRules;

class User extends BaseController
{
    protected function service(): UserService
    {
        return service('userService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (string) ($this->request->getGet('level') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar user', $result);
    }

    public function show($id = null)
    {
        $user = $this->service()->findAny((int) $id);

        if (empty($user)) {
            return $this->responseJson(false, 'User tidak ditemukan')->setStatusCode(404);
        }

        unset($user['password']);

        return $this->responseJson(true, 'Detail user', $user);
    }

    public function toggleStatus($id = null)
    {
        $result = $this->service()->toggleStatus((int) $id);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function adjustBalance($id = null)
    {
        $data = UserBalanceRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, UserBalanceRequestRules::adjust())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $amount = $data['type'] === 'deduct' ? -abs($data['amount']) : abs($data['amount']);

        $result = $this->service()->adjustBalance((int) $id, $amount);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }
}
