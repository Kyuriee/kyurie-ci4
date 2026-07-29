<?php

namespace App\Controllers\Admin;

use App\Requests\CouponRequest;
use App\Services\Marketing\CouponService;
use App\Validation\CouponRequestRules;

class Coupon extends BaseController
{
    protected function service(): CouponService
    {
        return service('couponService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar kupon', $result);
    }

    public function show($id = null)
    {
        $coupon = $this->service()->find((int) $id);

        if (empty($coupon)) {
            return $this->responseJson(false, 'Kupon tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail kupon', $coupon);
    }

    public function store()
    {
        $data = CouponRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, CouponRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = CouponRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, CouponRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->update((int) $id, $data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function delete($id = null)
    {
        $result = $this->service()->delete((int) $id);

        return $this->responseJson($result['success'], $result['message'])
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function toggleStatus($id = null)
    {
        $result = $this->service()->toggleStatus((int) $id);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }
}
