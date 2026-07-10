<?php

namespace Config;

use CodeIgniter\Config\BaseService;

use App\Services\Auth\AuthService;
use App\Services\User\UserService;

use App\Services\Catalog\GameService;
use App\Services\Catalog\ProductService;
use App\Services\Catalog\GameCategoryService;
use App\Services\Catalog\GameAccountInputService;

use App\Services\Marketing\BannerService;
use App\Services\Marketing\FlashsaleService;

use App\Services\Order\OrderService;
use App\Services\Order\OrderStatusService;
use App\Services\Order\PaymentMethodService;

use App\Services\Pricing\PriceService;
use App\Services\Setting\SettingService;

use App\Services\Orchestrators\Storefront\HomePageOrchestrator;
use App\Services\Orchestrators\Storefront\GameDetailPageOrchestrator;
use App\Services\Orchestrators\Storefront\PaymentDetailPageOrchestrator;
use App\Services\Orchestrators\Storefront\CheckoutOrchestrator;

class Services extends BaseService
{
    public static function authService(bool $getShared = true): AuthService
    {
        if ($getShared) {
            return static::getSharedInstance('authService');
        }

        return new AuthService();
    }

    public static function userService(bool $getShared = true): UserService
    {
        if ($getShared) {
            return static::getSharedInstance('userService');
        }

        return new UserService();
    }

    public static function gameService(bool $getShared = true): GameService
    {
        if ($getShared) {
            return static::getSharedInstance('gameService');
        }

        return new GameService();
    }

    public static function productService(bool $getShared = true): ProductService
    {
        if ($getShared) {
            return static::getSharedInstance('productService');
        }

        return new ProductService();
    }

    public static function gameCategoryService(bool $getShared = true): GameCategoryService
    {
        if ($getShared) {
            return static::getSharedInstance('gameCategoryService');
        }

        return new GameCategoryService();
    }

    public static function gameAccountInputService(bool $getShared = true): GameAccountInputService
    {
        if ($getShared) {
            return static::getSharedInstance('gameAccountInputService');
        }

        return new GameAccountInputService();
    }

    public static function bannerService(bool $getShared = true): BannerService
    {
        if ($getShared) {
            return static::getSharedInstance('bannerService');
        }

        return new BannerService();
    }

    public static function flashsaleService(bool $getShared = true): FlashsaleService
    {
        if ($getShared) {
            return static::getSharedInstance('flashsaleService');
        }

        return new FlashsaleService();
    }

    public static function orderService(bool $getShared = true): OrderService
    {
        if ($getShared) {
            return static::getSharedInstance('orderService');
        }

        return new OrderService();
    }

    public static function orderStatusService(bool $getShared = true): OrderStatusService
    {
        if ($getShared) {
            return static::getSharedInstance('orderService');
        }

        return new OrderStatusService();
    }

    public static function checkoutOrchestrator(bool $getShared = true): CheckoutOrchestrator
    {
        if ($getShared) {
            return static::getSharedInstance('checkoutOrchestrator');
        }

        return new CheckoutOrchestrator();
    }

    public static function paymentMethodService(bool $getShared = true): PaymentMethodService
    {
        if ($getShared) {
            return static::getSharedInstance('paymentMethodService');
        }

        return new PaymentMethodService();
    }

    public static function priceService(bool $getShared = true): PriceService
    {
        if ($getShared) {
            return static::getSharedInstance('priceService');
        }

        return new PriceService();
    }

    public static function settingService(bool $getShared = true): SettingService
    {
        if ($getShared) {
            return static::getSharedInstance('settingService');
        }

        return new SettingService();
    }

    public static function homePageOrchestrator(bool $getShared = true): HomePageOrchestrator
    {
        if ($getShared) {
            return static::getSharedInstance('homePageOrchestrator');
        }

        return new HomePageOrchestrator();
    }

    public static function gameDetailPageOrchestrator(bool $getShared = true): GameDetailPageOrchestrator
    {
        if ($getShared) {
            return static::getSharedInstance('gameDetailPageOrchestrator');
        }

        return new GameDetailPageOrchestrator();
    }

    public static function paymentDetailPageOrchestrator(bool $getShared = true): PaymentDetailPageOrchestrator
    {
        if ($getShared) {
            return static::getSharedInstance('paymentDetailPageOrchestrator');
        }

        return new PaymentDetailPageOrchestrator();
    }
}
