<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Pages
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');
$routes->get('franchise-application', 'Home::franchiseApplication');
$routes->post('franchise-application/submit', 'Home::submitFranchiseApplication');

// Authentication Routes
$routes->get('login', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'DashboardController::index');

// Profile
$routes->group('profile', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProfileController::index');
    $routes->post('update', 'ProfileController::update');
    $routes->post('change-password', 'ProfileController::changePassword');
});

// Inventory Management
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'InventoryController::index');
    $routes->get('history', 'InventoryController::history');
    $routes->post('update', 'InventoryController::update');
    $routes->post('scan', 'InventoryController::scan');
    $routes->get('alerts', 'InventoryController::alerts');
    $routes->post('alerts/(:num)/acknowledge', 'InventoryController::acknowledgeAlert/$1');
    $routes->get('get-quantity', 'InventoryController::getQuantity');
    $routes->get('get-branch-products', 'InventoryController::getBranchProducts');
});

// Purchase Requests
$routes->group('purchase-requests', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PurchaseRequestController::index');
    $routes->get('create', 'PurchaseRequestController::create');
    $routes->post('store', 'PurchaseRequestController::store');
    $routes->get('view/(:num)', 'PurchaseRequestController::view/$1');
    $routes->get('print/(:num)', 'PurchaseRequestController::print/$1');
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
    $routes->get('view/(:num)', 'BranchController::view/$1');
    $routes->get('edit/(:num)', 'BranchController::edit/$1');
    $routes->post('update/(:num)', 'BranchController::update/$1');
    $routes->get('delete/(:num)', 'BranchController::delete/$1');
});

// Suppliers
$routes->group('suppliers', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'SupplierController::index');
    $routes->get('create', 'SupplierController::create');
    $routes->post('store', 'SupplierController::store');
    $routes->get('edit/(:num)', 'SupplierController::edit/$1');
    $routes->post('update/(:num)', 'SupplierController::update/$1');
    $routes->get('delete/(:num)', 'SupplierController::delete/$1');
    $routes->post('create-account', 'SupplierController::createAccount');
    // Supplier Products
    $routes->get('(:num)/products', 'SupplierController::products/$1');
    $routes->post('add-product', 'SupplierController::addProduct');
    $routes->post('store-product', 'SupplierController::storeProduct');
    $routes->post('update-product/(:num)', 'SupplierController::updateProduct/$1');
    $routes->get('remove-product/(:num)', 'SupplierController::removeProduct/$1');
    $routes->get('(:num)/products-json', 'SupplierController::getProductsJson/$1');
});

// Supplier Portal (for logged-in suppliers)
$routes->group('supplier', ['filter' => 'auth'], function($routes) {
    $routes->get('my-products', 'SupplierController::myProducts');
    $routes->post('add-product', 'SupplierController::storeMyProduct');
    $routes->post('update-product/(:num)', 'SupplierController::updateMyProduct/$1');
    $routes->get('delete-product/(:num)', 'SupplierController::deleteMyProduct/$1');
    $routes->get('user/(:num)/products-json', 'SupplierController::getProductsByUserJson/$1');
});

// Purchase Orders
$routes->group('purchase-orders', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PurchaseOrderController::index');
    $routes->get('create', 'PurchaseOrderController::create');
    $routes->get('create-from-request/(:num)', 'PurchaseOrderController::createFromRequest/$1');
    $routes->get('get-request-items/(:num)', 'PurchaseOrderController::getRequestItems/$1');
    $routes->post('store', 'PurchaseOrderController::store');
    $routes->get('view/(:num)', 'PurchaseOrderController::view/$1');
    $routes->get('print/(:num)', 'PurchaseOrderController::print/$1');
    $routes->post('(:num)/send', 'PurchaseOrderController::send/$1');
    $routes->post('(:num)/confirm', 'PurchaseOrderController::confirm/$1');
    $routes->post('(:num)/prepare', 'PurchaseOrderController::markPrepared/$1');
    $routes->post('(:num)/update-payment-method', 'PurchaseOrderController::updatePaymentMethod/$1');
    $routes->post('(:num)/update-delivery-status', 'PurchaseOrderController::updateDeliveryStatus/$1');
    $routes->post('(:num)/submit-invoice', 'PurchaseOrderController::submitInvoice/$1');
});

// Deliveries
$routes->group('deliveries', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'DeliveryController::index');
    $routes->get('create', 'DeliveryController::create');
    $routes->post('store', 'DeliveryController::store');
    $routes->get('view/(:num)', 'DeliveryController::view/$1');
    $routes->get('print/(:num)', 'DeliveryController::print/$1');
    $routes->get('(:num)/diagnostics', 'DeliveryController::diagnostics/$1'); // Debug endpoint
    $routes->post('(:num)/update-status', 'DeliveryController::updateStatus/$1');
    $routes->post('(:num)/receive', 'DeliveryController::receive/$1');
    $routes->post('(:num)/process-paypal', 'DeliveryController::processPayPalPayment/$1');
    $routes->post('(:num)/create-paypal-payment', 'DeliveryController::createPayPalPayment/$1');
    $routes->get('paypal-success', 'DeliveryController::paypalSuccess');
    $routes->get('paypal-cancel', 'DeliveryController::paypalCancel');
    $routes->post('(:num)/delete', 'DeliveryController::delete/$1');
});

// Transfers
$routes->group('transfers', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'TransferController::index');
    $routes->get('create', 'TransferController::create');
    $routes->post('store', 'TransferController::store');
    $routes->post('request-store', 'TransferController::requestStore');
    $routes->get('view/(:num)', 'TransferController::view/$1');
    $routes->get('get-details/(:num)', 'TransferController::getDetails/$1');
    $routes->post('(:num)/approve', 'TransferController::approve/$1');
    $routes->post('(:num)/reject', 'TransferController::reject/$1');
    $routes->post('(:num)/schedule', 'TransferController::schedule/$1');
    $routes->post('(:num)/dispatch', 'TransferController::dispatch/$1');
    $routes->post('(:num)/receive', 'TransferController::receive/$1');
});

// Users
$routes->group('users', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('create', 'UserController::create');
    $routes->post('store', 'UserController::store');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
});

// Activity Logs
$routes->group('activity-logs', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ActivityLogController::index');
    $routes->get('export', 'ActivityLogController::export');
});

// Reports
$routes->group('reports', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ReportController::index');
    $routes->get('inventory', 'ReportController::inventory');
    $routes->get('inventory/export', 'ReportController::exportInventory');
    $routes->get('purchase-orders', 'ReportController::purchaseOrders');
    $routes->get('deliveries', 'ReportController::deliveries');
    $routes->get('supplier-performance', 'ReportController::supplierPerformance');
    $routes->get('wastage', 'ReportController::wastage');
});

// Categories
$routes->group('categories', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'CategoryController::index');
    $routes->get('create', 'CategoryController::create');
    $routes->post('store', 'CategoryController::store');
    $routes->get('edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('update/(:num)', 'CategoryController::update/$1');
    $routes->get('delete/(:num)', 'CategoryController::delete/$1');
});

// Barcode Scanner
$routes->group('barcode', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'BarcodeController::index');
    $routes->post('scan', 'BarcodeController::scan');
    $routes->post('update-inventory', 'BarcodeController::updateInventory');
});

// Inventory Adjustments
$routes->group('inventory-adjustments', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'InventoryAdjustmentController::index');
    $routes->get('create', 'InventoryAdjustmentController::create');
    $routes->post('store', 'InventoryAdjustmentController::store');
});

// Notifications
$routes->group('notifications', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'NotificationController::index');
    $routes->get('get-unread-count', 'NotificationController::getUnreadCount');
    $routes->get('get-unread', 'NotificationController::getUnread');
    $routes->post('mark-as-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('mark-all-read', 'NotificationController::markAllAsRead');
    $routes->post('cleanup-duplicates', 'NotificationController::cleanupDuplicates');
});

// Settings
$routes->group('settings', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'SettingController::index');
    $routes->post('update', 'SettingController::update');
    
    // Driver management
    $routes->post('drivers/store', 'SettingController::storeDriver');
    $routes->post('drivers/update/(:num)', 'SettingController::updateDriver/$1');
    $routes->get('drivers/delete/(:num)', 'SettingController::deleteDriver/$1');
});

// Franchise Management
$routes->group('franchise', ['filter' => 'auth'], function($routes) {
    $routes->get('applications', 'FranchiseController::applications');
    $routes->get('applications/data', 'FranchiseController::getApplicationsData');
    $routes->get('applications/view/(:num)', 'FranchiseController::viewApplication/$1');
    $routes->post('applications/(:num)/start-review', 'FranchiseController::startReview/$1');
    $routes->post('applications/(:num)/approve', 'FranchiseController::approve/$1');
    $routes->post('applications/(:num)/reject', 'FranchiseController::reject/$1');
    $routes->post('applications/(:num)/convert', 'FranchiseController::convertToBranch/$1');
    $routes->get('partners', 'FranchiseController::partners');
    $routes->get('supply-allocation', 'FranchiseController::supplyAllocation');
    $routes->post('allocate-supply', 'FranchiseController::allocateSupply');
});
