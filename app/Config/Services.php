<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\OrderService;
use App\Services\CheckoutService;
use App\Services\GameService;
use App\Services\PaymentService;
use App\Services\HomeService;
use App\Services\PriceService;
use App\Services\SettingService;

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

    public static function orderService(bool $getShared = true): OrderService
    {
        if ($getShared) {
            return static::getSharedInstance('orderService');
        }

        return new OrderService();
    }

    public static function checkoutService(bool $getShared = true): CheckoutService
    {
        if ($getShared) {
            return static::getSharedInstance('checkoutService');
        }

        return new CheckoutService();
    }

    public static function gameService(bool $getShared = true): GameService
    {
        if ($getShared) {
            return static::getSharedInstance('gameService');
        }

        return new GameService();
    }

    public static function paymentService(bool $getShared = true): PaymentService
    {
        if ($getShared) {
            return static::getSharedInstance('paymentService');
        }

        return new PaymentService();
    }

    public static function homeService(bool $getShared = true): HomeService
    {
        if ($getShared) {
            return static::getSharedInstance('homeService');
        }

        return new HomeService();
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
}