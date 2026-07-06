<?php

namespace App\Services;

class GameDetailService extends baseService
{
    protected $gameService;
    protected $productService;
    protected $paymentService;
    protected $targetService;

    public function __construct()
    {
        $this->gameService    = new GameService();
        $this->productService = new ProductService();
        $this->paymentService = new PaymentService();
        $this->targetService  = new TargetService();
    }

    public function getDetailPage(string $slug): array
    {
        $game = $this->gameService->getActiveBySlug($slug);

        if (empty($game)) {
            return [];
        }

        return [
            'game'            => $this->gameService->mapPublicGame($game),
            'target_form'     => $this->targetService->getFormConfig($game['target'] ?? 'default', $game['input_custom'] ?? null),
            'products'        => $this->productService->getPublicProductsByGame((int) $game['id']),
            'payment_methods' => $this->paymentService->getActiveMethods(),
        ];
    }
}
