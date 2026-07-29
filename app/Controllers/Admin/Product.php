<?php

namespace App\Controllers\Admin;

use App\Requests\ProductRequest;
use App\Services\Catalog\ProductService;
use App\Validation\ProductRequestRules;

class Product extends BaseController
{
    protected function service(): ProductService
    {
        return service('productService');
    }

    public function index()
    {
        $gameId = (int) ($this->request->getGet('games_id') ?? 0);

        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            $gameId > 0 ? $gameId : null,
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar produk', $result);
    }

    public function show($id = null)
    {
        $product = $this->service()->findAny((int) $id);

        if (empty($product)) {
            return $this->responseJson(false, 'Produk tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail produk', $product);
    }

    public function store()
    {
        $data = ProductRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, ProductRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = ProductRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, ProductRequestRules::save())) {
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
