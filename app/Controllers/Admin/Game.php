<?php

namespace App\Controllers\Admin;

use App\Requests\GameRequest;
use App\Services\Catalog\GameCategoryService;
use App\Services\Catalog\GameService;
use App\Validation\GameRequestRules;

class Game extends BaseController
{
    protected function service(): GameService
    {
        return service('gameService');
    }

    protected function categoryService(): GameCategoryService
    {
        return service('gameCategoryService');
    }

    public function page()
    {
        if ($redirect = $this->redirectIfGuest()) {
            return $redirect;
        }

        $this->baseData['page_assets']['js'][] = 'resources/js/pages/admin/games.js';

        return $this->renderView('Pages/Admin/Games', [
            'meta'       => ['title' => 'Game'],
            'admin'      => $this->currentAdmin,
            'activeMenu' => 'games',
            'categories' => $this->categoryService()->getActive(),
        ]);
    }

    public function index()
    {
        $categoryId = (int) ($this->request->getGet('game_category_id') ?? 0);

        $result = $this->service()->list(
            (string) ($this->request->getGet('q') ?? ''),
            $categoryId > 0 ? $categoryId : null,
            (string) ($this->request->getGet('status') ?? ''),
            (int) ($this->request->getGet('per_page') ?? 20)
        );

        return $this->responseJson(true, 'Daftar game', $result);
    }

    public function show($id = null)
    {
        $game = $this->service()->findAny((int) $id);

        if (empty($game)) {
            return $this->responseJson(false, 'Game tidak ditemukan')->setStatusCode(404);
        }

        return $this->responseJson(true, 'Detail game', $game);
    }

    public function store()
    {
        $data = GameRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, GameRequestRules::save())) {
            return $this->responseJson(false, implode(' ', $this->validator->getErrors()))->setStatusCode(422);
        }

        $result = $this->service()->create($data);

        return $this->responseJson($result['success'], $result['message'], $result['data'] ?? null)
            ->setStatusCode($result['success'] ? 201 : 422);
    }

    public function update($id = null)
    {
        $data = GameRequest::fromPayload($this->requestPayload());

        if (! $this->validateData($data, GameRequestRules::save())) {
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
