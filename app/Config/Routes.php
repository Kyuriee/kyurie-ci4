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
