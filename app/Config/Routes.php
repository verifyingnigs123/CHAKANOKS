<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentication Routes
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'DashboardController::index');

// Inventory Management
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'InventoryController::index');
    $routes->post('update', 'InventoryController::update');
    $routes->post('scan', 'InventoryController::scan');
    $routes->get('alerts', 'InventoryController::alerts');
    $routes->post('alerts/(:num)/acknowledge', 'InventoryController::acknowledgeAlert/$1');
});

// Purchase Requests
$routes->group('purchase-requests', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PurchaseRequestController::index');
    $routes->get('create', 'PurchaseRequestController::create');
    $routes->post('store', 'PurchaseRequestController::store');
    $routes->get('view/(:num)', 'PurchaseRequestController::view/$1');
    $routes->post('(:num)/approve', 'PurchaseRequestController::approve/$1');
    $routes->post('(:num)/reject', 'PurchaseRequestController::reject/$1');
});

// Products
$routes->group('products', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProductController::index');
    $routes->get('create', 'ProductController::create');
    $routes->post('store', 'ProductController::store');
    $routes->get('edit/(:num)', 'ProductController::edit/$1');
    $routes->post('update/(:num)', 'ProductController::update/$1');
    $routes->get('delete/(:num)', 'ProductController::delete/$1');
});

// Branches
$routes->group('branches', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'BranchController::index');
    $routes->get('create', 'BranchController::create');
    $routes->post('store', 'BranchController::store');
    $routes->get('edit/(:num)', 'BranchController::edit/$1');
    $routes->post('update/(:num)', 'BranchController::update/$1');
});

// Suppliers
$routes->group('suppliers', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'SupplierController::index');
    $routes->get('create', 'SupplierController::create');
    $routes->post('store', 'SupplierController::store');
    $routes->get('edit/(:num)', 'SupplierController::edit/$1');
    $routes->post('update/(:num)', 'SupplierController::update/$1');
});

// Purchase Orders
$routes->group('purchase-orders', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PurchaseOrderController::index');
    $routes->get('create', 'PurchaseOrderController::create');
    $routes->get('create-from-request/(:num)', 'PurchaseOrderController::createFromRequest/$1');
    $routes->get('get-request-items/(:num)', 'PurchaseOrderController::getRequestItems/$1');
    $routes->post('store', 'PurchaseOrderController::store');
    $routes->get('view/(:num)', 'PurchaseOrderController::view/$1');
    $routes->post('(:num)/send', 'PurchaseOrderController::send/$1');
    $routes->post('(:num)/confirm', 'PurchaseOrderController::confirm/$1');
});

// Deliveries
$routes->group('deliveries', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'DeliveryController::index');
    $routes->get('create', 'DeliveryController::create');
    $routes->post('store', 'DeliveryController::store');
    $routes->get('view/(:num)', 'DeliveryController::view/$1');
    $routes->post('(:num)/update-status', 'DeliveryController::updateStatus/$1');
    $routes->post('(:num)/receive', 'DeliveryController::receive/$1');
});
