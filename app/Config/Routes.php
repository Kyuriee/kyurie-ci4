<?php

use CodeIgniter\Router\RouteCollection;

helper('admin');

/** @var RouteCollection $routes */
$routes->get('sistem/statusopcacheReset', 'Sistem::statusopcacheReset');

$routes->get('/', 'Home::index');
$routes->get('search/games', 'Search::games');

$routes->match(['GET', 'POST'], 'auth/login', 'Auth::login');
$routes->match(['GET', 'POST'], 'auth/register', 'Auth::register');
$routes->match(['GET', 'POST'], 'auth/forgot', 'Auth::forgot');
$routes->match(['GET', 'POST'], 'auth/reset/(:any)', 'Auth::reset/$1');
$routes->post('auth/logout', 'Auth::logout');

$routes->group(admin_path(), ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->match(['GET', 'POST'], 'login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'adminauth']);

    $routes->group('game-categories', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'GameCategory::index');
        $routes->get('(:num)', 'GameCategory::show/$1');
        $routes->post('/', 'GameCategory::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'GameCategory::update/$1');
        $routes->delete('(:num)', 'GameCategory::delete/$1');
        $routes->post('(:num)/toggle-status', 'GameCategory::toggleStatus/$1');
    });

    // ponytail: page shell lives at the bare path, JSON list moved to
    // /list so it doesn't collide with the GET / of the group below.
    $routes->get('games', 'Game::page', ['filter' => 'adminauth']);

    $routes->group('games', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('list', 'Game::index');
        $routes->get('(:num)', 'Game::show/$1');
        $routes->post('/', 'Game::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'Game::update/$1');
        $routes->delete('(:num)', 'Game::delete/$1');
        $routes->post('(:num)/toggle-status', 'Game::toggleStatus/$1');
    });

    $routes->group('products', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'Product::index');
        $routes->get('(:num)', 'Product::show/$1');
        $routes->post('/', 'Product::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'Product::update/$1');
        $routes->delete('(:num)', 'Product::delete/$1');
        $routes->post('(:num)/toggle-status', 'Product::toggleStatus/$1');
    });

    $routes->group('banners', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'Banner::index');
        $routes->get('(:num)', 'Banner::show/$1');
        $routes->post('/', 'Banner::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'Banner::update/$1');
        $routes->delete('(:num)', 'Banner::delete/$1');
        $routes->post('(:num)/toggle-status', 'Banner::toggleStatus/$1');
    });

    $routes->group('flashsales', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'Flashsale::index');
        $routes->get('(:num)', 'Flashsale::show/$1');
        $routes->post('/', 'Flashsale::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'Flashsale::update/$1');
        $routes->delete('(:num)', 'Flashsale::delete/$1');
        $routes->post('(:num)/toggle-status', 'Flashsale::toggleStatus/$1');
        $routes->get('(:num)/items', 'FlashsaleItem::index/$1');
    });

    $routes->group('flashsale-items', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('(:num)', 'FlashsaleItem::show/$1');
        $routes->post('/', 'FlashsaleItem::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'FlashsaleItem::update/$1');
        $routes->delete('(:num)', 'FlashsaleItem::delete/$1');
        $routes->post('(:num)/toggle-status', 'FlashsaleItem::toggleStatus/$1');
    });

    $routes->group('payment-methods', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'PaymentMethod::index');
        $routes->get('(:num)', 'PaymentMethod::show/$1');
        $routes->post('/', 'PaymentMethod::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'PaymentMethod::update/$1');
        $routes->delete('(:num)', 'PaymentMethod::delete/$1');
        $routes->post('(:num)/toggle-status', 'PaymentMethod::toggleStatus/$1');
    });

    $routes->group('coupons', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'Coupon::index');
        $routes->get('(:num)', 'Coupon::show/$1');
        $routes->post('/', 'Coupon::store');
        $routes->match(['PUT', 'POST'], '(:num)', 'Coupon::update/$1');
        $routes->delete('(:num)', 'Coupon::delete/$1');
        $routes->post('(:num)/toggle-status', 'Coupon::toggleStatus/$1');
    });

    $routes->group('orders', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'Order::index');
        $routes->get('(:num)', 'Order::show/$1');
        $routes->post('(:num)/status', 'Order::updateStatus/$1');
    });

    $routes->group('users', ['filter' => 'adminauth'], static function ($routes) {
        $routes->get('/', 'User::index');
        $routes->get('(:num)', 'User::show/$1');
        $routes->post('(:num)/toggle-status', 'User::toggleStatus/$1');
        $routes->post('(:num)/balance', 'User::adjustBalance/$1');
    });
});

$routes->get('user/profile', 'User::profile');
$routes->post('user/update', 'User::update');
$routes->get('user/transactions', 'User::orders');
$routes->get('user/settings', 'User::settings');
$routes->post('user/change-password', 'User::changePassword');

$routes->get('games/(:any)', 'Game::detail/$1');

$routes->post('order/prepare', 'Order::prepare');
$routes->post('order/create', 'Order::create');

$routes->get('payment/check', 'Payment::check');
$routes->post('payment/check', 'Payment::check');
$routes->get('payment/(:hash)', 'Payment::detail/$1');
