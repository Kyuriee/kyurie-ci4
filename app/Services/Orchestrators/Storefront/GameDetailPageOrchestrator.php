<?php

namespace App\Services\Orchestrators\Storefront;

use App\Services\baseService;
use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Catalog\TargetService;
use App\Services\Order\paymentMethodService;

class GameDetailPageOrchestrator extends baseService
{
    protected $gameService;
    protected $productService;
    protected $paymentMethodService;
    protected $targetService;

    public function __construct()
    {
        $this->gameService          = new GameService();
        $this->productService       = new ProductService();
        $this->paymentMethodService = new PaymentMethodService();
        $this->targetService        = new TargetService();
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
                'target_form'     => $this->targetService->getFormConfig($game['target'] ?? 'default', $game['input_custom'] ?? null),
                'products'        => $this->productService->getPublicProductsByGame((int) $game['id']),
                'payment_methods' => $this->paymentMethodService->getActiveMethods(),
            ];
        }, []);
    }
}
