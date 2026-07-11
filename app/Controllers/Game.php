<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\GameDetailPageOrchestrator;

class Game extends BaseController
{

    public function detail(string $slug)
    {
        $result = $this->_service()->getDetailPage($slug);
        if (empty($result)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $game            = $result['game'];
        $target_form     = $result['target_form'];
        $products        = $result['products'];
        $payment_methods = $result['payment_methods'];
        $current_user    = $this->_get_current_user();
        $data = [
            'meta'            => ['title' => $game['games']],
            'seo'             => ['og_image' => $game['image'] ?? ''],
            'game'            => $game,
            'target_form'     => $target_form,
            'products'        => $products,
            'payment_methods' => $payment_methods,
            'auth_contact'    => $current_user ? [
                'email' => $current_user['email'] ?? '',
                'phone' => $current_user['phone'] ?? '',
            ] : null,
        ];
        $this->base_data['page_assets']['css'][] = 'resources/css/pages/game.css';
        $this->base_data['page_assets']['js'][]  = 'resources/js/pages/game.js';
        return $this->renderView('Pages/Games/Detail', $data);
    }

    protected function _service(): GameDetailPageOrchestrator
    {
        return single_service('gameDetailPageOrchestrator');
    }
}
