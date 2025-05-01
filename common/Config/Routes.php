<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->get('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->get('/reset-password', 'AuthController::resetPassword');
$routes->get('/terms-and-conditions', 'TermsController::index');

$routes->get('/user-profile', 'ProfileController::index');
$routes->put('/user-profile', 'ProfileController::update');

$routes->get('/products/new', 'ProductController::new');
$routes->get('/products/table-data', 'ProductController::tableData');
$routes->post('/products', 'ProductController::create');
$routes->get('/products', 'ProductController::index');
$routes->get('/products/(:segment)', 'ProductController::show/$1');
$routes->get('/products/(:segment)/edit', 'ProductController::edit/$1');
$routes->put('/products/(:segment)', 'ProductController::update/$1');
$routes->get('/products/(:segment)/remove', 'ProductController::delete/$1');

