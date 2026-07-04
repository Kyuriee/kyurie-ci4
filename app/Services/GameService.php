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

            $product = $this->mapPublicProduct($product, $final_price, ! empty($flashsale_item));
        }
        unset($product);

        $payment_methods = $this->paymentMethodModel->getActive();

        return [
            'game'            => $this->mapPublicGame($game),
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

    protected function mapPublicGame(array $game): array
    {
        return [
            'games'     => $game['games'],
            'slug'      => $game['slug'],
            'publisher' => $game['publisher'] ?? '',
            'category'  => $game['category'] ?? '',
            'image'     => $game['image'] ?? '',
            'banner'    => $game['banner'] ?? '',
            'target'    => $game['target'] ?? 'default',
        ];
    }

    protected function mapPublicProduct(array $product, float $final_price, bool $is_flashsale): array
    {
        return [
            'id'                    => (int) $product['id'],
            'product'               => $product['product'],
            'is_flashsale'          => $is_flashsale,
            'final_price_formatted' => $this->priceService->formatPrice($final_price),
            'price_formatted'       => $this->priceService->formatPrice((float) ($product['price'] ?? 0)),
        ];
    }
}
