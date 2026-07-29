<?php

namespace App\Controllers\Admin;

use App\Requests\FlashsaleItemRequest;
use App\Services\Marketing\FlashsaleService;
use App\Validation\FlashsaleItemRequestRules;

class FlashsaleItem extends BaseController
{
    protected function service(): FlashsaleService
    {
        return service('flashsaleService');
    }

    public function index($flashsaleId = null)
    {
        $result = $this->service()->listItems(
            (int) $flashsaleId,
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar item flashsale', $result);
    }

    public function show($id = null)
    {
        $item = $this->service()->findItem((int) $id);

        if (empty($item)) {
            return $this->responseJson(false, 'Item flashsale tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail item flashsale', $item);
    }

    public function store()
    {
        $data = FlashsaleItemRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, FlashsaleItemRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->createItem($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = FlashsaleItemRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, FlashsaleItemRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->updateItem((int) $id, $data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function delete($id = null)
    {
        $result = $this->service()->deleteItem((int) $id);

        return $this->responseJson($result['success'], $result['message'])
            ->setStatusCode($result['success'] ? 200 : 422);
    }

    public function toggleStatus($id = null)
    {
        $result = $this->service()->toggleItemStatus((int) $id);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 200 : 422);
    }
}
