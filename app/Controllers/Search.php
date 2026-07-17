<?php

namespace App\Controllers;

class Search extends BaseController
{
    protected $gameService;

    public function __construct()
    {
        $this->gameService = single_service('gameService');
    }

    public function games()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $keyword = trim((string) $this->request->getGet('keyword'));

        if (strlen($keyword) < 2) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Minimal 2 karakter',
                'data'    => [],
            ]);
        }

        $games = $this->gameService->searchGames($keyword);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data ditemukan',
            'data'    => $games,
        ]);
    }
}
