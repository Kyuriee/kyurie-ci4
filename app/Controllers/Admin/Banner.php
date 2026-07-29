<?php

namespace App\Controllers\Admin;

use App\Requests\BannerRequest;
use App\Services\Marketing\BannerService;
use App\Validation\BannerRequestRules;

class Banner extends BaseController
{
    protected function service(): BannerService
    {
        return service('bannerService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar banner', $result);
    }

    public function show($id = null)
    {
        $banner = $this->service()->find((int) $id);

        if (empty($banner)) {
            return $this->responseJson(false, 'Banner tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail banner', $banner);
    }

    public function store()
    {
        $data = BannerRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, BannerRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = BannerRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, BannerRequestRules::save())) {
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
