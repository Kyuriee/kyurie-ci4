<?php

namespace App\Controllers\Admin;

use App\Requests\GameCategoryRequest;
use App\Services\Catalog\GameCategoryService;
use App\Validation\GameCategoryRequestRules;

class GameCategory extends BaseController
{
    protected function service(): GameCategoryService
    {
        return service('gameCategoryService');
    }

    public function index()
    {
        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar kategori game', $result);
    }

    public function show($id = null)
    {
        $category = $this->service()->find((int) $id);

        if (empty($category)) {
            return $this->responseJson(false, 'Kategori tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail kategori game', $category);
    }

    public function store()
    {
        $data = GameCategoryRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, GameCategoryRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = GameCategoryRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, GameCategoryRequestRules::save())) {
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
