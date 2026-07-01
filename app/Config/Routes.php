<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('sistem/statusopcacheReset', 'Sistem::statusopcacheReset');

$routes->get('/', 'Home::index');
$routes->get('search/games', 'Search::games');

$routes->match(['get', 'post'], 'auth/login', 'Auth::login');
$routes->match(['get', 'post'], 'auth/register', 'Auth::register');
$routes->match(['get', 'post'], 'auth/forgot', 'Auth::forgot');
$routes->match(['get', 'post'], 'auth/reset/(:any)', 'Auth::reset/$1');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('user/profile', 'User::profile');
$routes->post('user/update', 'User::update');

$routes->get('games/(:any)', 'Game::detail/$1');

$routes->post('order/prepare', 'Order::prepare');
$routes->post('order/create', 'Order::create');
$routes->get('order/list', 'Order::list');
$routes->get('order/(:num)', 'Order::detail/$1');

$routes->match(['get', 'post'], 'payment/check', 'Payment::check');
$routes->get('payment/(:any)', 'Payment::detail/$1');
