<?php

namespace App\Services\Orchestrators\Storefront;

use App\Services\BaseService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Catalog\GameAccountInputService;
use App\Services\Payment\PaymentMethodService;

class GameDetailPageOrchestrator extends BaseService
{
    protected $gameService;
    protected $productService;
    protected $paymentMethodService;
    protected $gameAccountInputService;

    public function __construct()
    {
        $this->gameService          = new GameService();
        $this->productService       = new ProductService();
        $this->paymentMethodService = new PaymentMethodService();
        $this->gameAccountInputService        = new GameAccountInputService();
    }

    public function getDetailPage(string $slug): array
    {
        return $this->safeCall(function () use ($slug) {
            $game = $this->gameService->getActiveBySlug($slug);
            if (empty($game)) {
                return [];
            }
            return [
                'game'            => $this->gameService->mapPublicGame($game),
                'target_form'     => $this->gameAccountInputService->getFormConfig($game['target'] ?? 'default', $game['input_custom'] ?? null),
                'products'        => $this->productService->getPublicProductsByGame((int) $game['id']),
                'payment_methods' => $this->paymentMethodService->getActiveMethods(),
            ];
        }, []);
    }
}
