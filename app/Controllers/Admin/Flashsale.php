<?php

namespace App\Controllers\Admin;

use App\Requests\FlashsaleRequest;
use App\Services\Marketing\FlashsaleService;
use App\Validation\FlashsaleRequestRules;

class Flashsale extends BaseController
{
    protected function service(): FlashsaleService
    {
        return service('flashsaleService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar flashsale', $result);
    }

    public function show($id = null)
    {
        $flashsale = $this->service()->find((int) $id);

        if (empty($flashsale)) {
            return $this->responseJson(false, 'Flashsale tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail flashsale', $flashsale);
    }

    public function store()
    {
        $data = FlashsaleRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, FlashsaleRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = FlashsaleRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, FlashsaleRequestRules::save())) {
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
