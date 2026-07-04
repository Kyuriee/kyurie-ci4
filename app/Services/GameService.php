<?php

namespace App\Services;

use App\Models\FlashsaleItemModel;
use App\Models\GameModel;
use App\Models\PaymentMethodModel;
use App\Models\ProductModel;

class GameService extends baseService
{
    protected $gameModel;
    protected $productModel;
    protected $paymentMethodModel;
    protected $flashsaleItemModel;
    protected $priceService;

    public function __construct()
    {
        $this->gameModel          = model(GameModel::class);
        $this->productModel       = model(ProductModel::class);
        $this->paymentMethodModel = model(PaymentMethodModel::class);
        $this->flashsaleItemModel = model(FlashsaleItemModel::class);
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
            $flashsale_item = $this->flashsaleItemModel->getActiveForProduct((int) $product['id']);
            $final_price    = $this->priceService->getFinalPrice($product, $flashsale_item ?: null);

            $product['is_flashsale']           = ! empty($flashsale_item);
            $product['final_price']            = $final_price;
            $product['final_price_formatted']  = $this->priceService->formatPrice($final_price);
            $product['price_formatted']        = $this->priceService->formatPrice((float) ($product['price'] ?? 0));

            if (! empty($flashsale_item)) {
                $product['flashsale_item_id'] = (int) $flashsale_item['id'];
                $product['flashsale_stock']   = (int) $flashsale_item['stock'];
                $product['flashsale_sold']    = (int) $flashsale_item['sold'];
                $product['flashsale_left']    = max(0, (int) $flashsale_item['stock'] - (int) $flashsale_item['sold']);
            }
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
                ? base_url('assets/images/games/icons/' . $game['image'])
                : '';
        }

        return $games;
    }
}