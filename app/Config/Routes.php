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
$routes->get('auth/logout', 'Auth::logout');

// Admin panel — path comes from ADMIN_PATH (see admin_helper.php). Never
// add this prefix to robots.txt; AdminNoIndexFilter handles no-index via
// the X-Robots-Tag response header instead.
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

$routes->match(['GET', 'POST'], 'payment/check', 'Payment::check');
$routes->get('payment/(:any)', 'Payment::detail/$1');
