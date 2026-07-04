<?php

namespace App\Controllers;

use App\Services\GameService;

class Game extends baseController
{


    public function detail(string $slug)
    {
        $result = $this->_service()->getDetailPage($slug);
        if (empty($result)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $game            = $result['game'];
        $products        = $result['products'];
        $payment_methods = $result['payment_methods'];
        $data = [
            'meta'            => ['title' => $game['games']],
            'seo'             => ['og_image' => $game['banner'] ?? ''],
            'game'            => $game,
            'products'        => $products,
            'payment_methods' => $payment_methods,
        ];
        $this->base_data['page_assets']['css'][] = 'resources/css/pages/game.css';
        $this->base_data['page_assets']['js'][]  = 'resources/js/pages/game.js';
        return $this->renderView('pages/games/detail', $data);
    }

    protected function _service(): GameService
    {
        return single_service('gameService');
    }
}
