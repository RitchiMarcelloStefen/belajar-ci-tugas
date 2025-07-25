<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->get('/contact', 'ContactController::index', ['filter' => 'auth']);
$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::cart_add');
    $routes->post('edit', 'TransaksiController::cart_edit');
    $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
    $routes->get('clear', 'TransaksiController::cart_clear');
});

$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);
$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

$routes->get('get-location', 'TransaksiController::getLocation', ['filter' => 'auth']);
$routes->get('get-cost', 'TransaksiController::getCost', ['filter' => 'auth']);

$routes->get('profile', 'Home::profile', ['filter' => 'auth']);

$routes->get('produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);
$routes->get('contact', 'ContactController::index', ['filter' => 'auth']);

$routes->group('', ['filter' => 'auth'], function($routes) {
    // Pastikan nama class Diskon sama persis
    $routes->get('diskon', 'Diskon::index');
    $routes->get('diskon/create', 'Diskon::create');
    $routes->post('diskon/store', 'Diskon::store');
    $routes->get('diskon/edit/(:num)', 'Diskon::edit/$1');
    $routes->post('diskon/update/(:num)', 'Diskon::update/$1');
    $routes->get('diskon/delete/(:num)', 'Diskon::delete/$1');
});

$routes->group('produk-kategori', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProductCategoryController::index');
    $routes->post('store', 'ProductCategoryController::store');
    $routes->get('edit/(:num)', 'ProductCategoryController::edit/$1');
    $routes->post('update/(:num)', 'ProductCategoryController::update/$1');
    $routes->get('delete/(:num)', 'ProductCategoryController::delete/$1');
});

$routes->resource('api', ['controller' => 'apiController']);
