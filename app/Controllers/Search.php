<?php

namespace App\Controllers;

use App\Services\GameService;

class Search extends baseController
{
    protected $game_service;

    public function __construct()
    {
        $this->game_service = new GameService();
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

        $games = $this->game_service->searchGames($keyword);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data ditemukan',
            'data'    => $games,
        ]);
    }
}
