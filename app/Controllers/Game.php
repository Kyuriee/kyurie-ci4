<?php

namespace App\Controllers;

use App\Services\Orchestrators\Storefront\GameDetailPageOrchestrator;

class Game extends BaseController
{

    public function detail(string $slug)
    {
        $result = $this->gameDetailPageOrchestrator()->getDetailPage($slug);
        if (empty($result)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $game            = $result['game'];
        $target_form     = $result['target_form'];
        $products        = $result['products'];
        $payment_methods = $result['payment_methods'];
        $current_user    = $this->resolveCurrentUser();
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
        $this->baseData['page_assets']['css'][] = 'resources/css/pages/game.css';
        $this->baseData['page_assets']['js'][]  = 'resources/js/pages/game.js';
        return $this->renderView('Pages/Games/Detail', $data);
    }

    protected function gameDetailPageOrchestrator(): GameDetailPageOrchestrator
    {
        return single_service('gameDetailPageOrchestrator');
    }
}
