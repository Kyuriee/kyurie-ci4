<?php

namespace App\Services;

use App\Models\GameModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductModel;

class GameService extends baseService
{
    protected $gameModel;
    protected $productModel;
    protected $paymentMethodModel;
    protected $priceService;

    public function __construct()
    {
        $this->gameModel          = model(GameModel::class);
        $this->productModel       = model(ProductModel::class);
        $this->paymentMethodModel = model(PaymentMethodModel::class);
        $this->priceService       = new PriceService();
    }

    public function getDetailPage(string $slug): array
    {
        $game = $this->gameModel->getDetailBySlug($slug);

        if (empty($game)) {
            return [];
        }

        $products = $this->productModel->getProductsByGame((int) $game['id']);

        foreach ($products as &$product) {
            $final_price = $this->priceService->getFinalPrice($product);

            $product['final_price']           = $final_price;
            $product['final_price_formatted'] = $this->priceService->formatPrice($final_price);
            $product['price_formatted']       = $this->priceService->formatPrice((float) ($product['price'] ?? 0));
        }

        $payment_methods = $this->paymentMethodModel->getActive();

        return [
            'game'            => $game,
            'products'        => $products,
            'payment_methods' => $payment_methods,
        ];
    }

    public function searchGames(string $keyword, int $limit = 8): array
    {
        $keyword = trim($keyword);

        if (strlen($keyword) < 2) {
            return [];
        }

        $games = $this->gameModel->searchGames($keyword, $limit);

        foreach ($games as &$game) {
            $game['url']       = base_url('games/' . $game['slug']);
            $game['image_url'] = ! empty($game['image'])
                ? base_url('uploads/games/' . $game['image'])
                : '';
        }

        return $games;
    }
}
