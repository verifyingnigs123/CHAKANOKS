<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Dashboard';
$title = 'Dashboard';
?>

<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Welcome back!</h1>
    <p class="text-gray-500 mt-1">Here's what's happening with your supply chain today.</p>
</div>

<?php if (in_array($role, ['central_admin'])): ?>
<!-- Central Admin Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Branches -->
    <a href="<?= base_url('branches') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Branches</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_branches ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Products -->
    <a href="<?= base_url('products') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_products ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-box text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Pending Requests -->
    <a href="<?= base_url('purchase-requests') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $pending_requests ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <i class="fas fa-file-alt text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Active Alerts -->
    <a href="<?= base_url('inventory/alerts') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-red-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Alerts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $active_alerts ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Suppliers -->
    <a href="<?= base_url('suppliers') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-cyan-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Suppliers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_suppliers ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                    <i class="fas fa-truck-loading text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Pending Orders -->
    <a href="<?= base_url('purchase-orders') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-purple-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $pending_orders ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- In Transit -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-teal-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">In Transit</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $in_transit_deliveries ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                    <i class="fas fa-truck text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Low Stock Alerts for Central Admin -->
<?php if (!empty($low_stock_items)): ?>
<div class="bg-white rounded-xl shadow-sm border border-amber-200 mb-8">
    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i> Low Stock Alerts
            </h3>
            <a href="<?= base_url('inventory/alerts') ?>" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach (array_slice($low_stock_items, 0, 6) as $item): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm"><?= esc($item['product_name']) ?></h4>
                        <p class="text-xs text-gray-500 mt-1"><?= esc($item['branch_name']) ?></p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        <?= $item['quantity'] ?>
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-600 mt-2">
                    <span>Min: <?= $item['min_stock_level'] ?></span>
                    <span class="font-mono"><?= esc($item['sku']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php elseif ($role == 'branch_manager'): ?>
<!-- Branch Manager Dashboard -->

<!-- Branch Info Header -->
<div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-store text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold"><?= $branch_name ?? 'Your Branch' ?></h2>
                    <p class="text-emerald-100 text-sm">Branch Manager Dashboard</p>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="<?= base_url('purchase-requests?create=1') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>New Request
            </a>
            <a href="<?= base_url('inventory') ?>" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg text-sm font-medium hover:bg-emerald-50 transition-colors">
                <i class="fas fa-boxes mr-2"></i>View Inventory
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Inventory Items -->
    <a href="<?= base_url('inventory') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inventory</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= $branch_inventory ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-boxes text-blue-600"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Low Stock -->
    <a href="<?= base_url('inventory/alerts') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-amber-300 transition-all <?= ($low_stock_items ?? 0) > 0 ? 'border-amber-200 bg-amber-50/50' : '' ?>">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Low Stock</p>
                    <p class="text-2xl font-bold <?= ($low_stock_items ?? 0) > 0 ? 'text-amber-600' : 'text-gray-800' ?> mt-1"><?= $low_stock_items ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 <?= ($low_stock_items ?? 0) > 0 ? 'bg-amber-100' : 'bg-gray-100' ?> rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle <?= ($low_stock_items ?? 0) > 0 ? 'text-amber-600' : 'text-gray-400' ?>"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Pending Requests -->
    <a href="<?= base_url('purchase-requests') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Requests</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= $pending_requests ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-file-alt text-emerald-600"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- In Transit -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-cyan-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">In Transit</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= $in_transit_deliveries ?? 0 ?></p>
                </div>
                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                    <i class="fas fa-truck text-cyan-600"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Inventory Value & Transfers Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Inventory Value Card -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-100">Total Inventory Value</p>
                <p class="text-3xl font-bold text-white mt-2">₱<?= number_format($inventory_value ?? 0, 2) ?></p>
                <p class="text-xs text-emerald-200 mt-2"><i class="fas fa-chart-line mr-1"></i>Current stock worth</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-peso-sign text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Transfers -->
    <a href="<?= base_url('transfers') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all h-full">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Transfers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $pending_transfers ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-arrow-right mr-1"></i>Incoming stock</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Scheduled Deliveries -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all h-full">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Scheduled Deliveries</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $scheduled_deliveries ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-calendar mr-1"></i>Upcoming</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Low Stock Alerts for Branch Manager -->
<?php if (!empty($low_stock_items) && count($low_stock_items) > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-amber-200 mb-8">
    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i> Low Stock Alerts - <?= count($low_stock_items) ?> Items
            </h3>
            <a href="<?= base_url('purchase-requests?create=1') ?>" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors text-sm font-medium">
                <i class="fas fa-plus-circle mr-2"></i> Create Request
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-sm text-gray-700">
                <i class="fas fa-bell text-amber-500 mr-2"></i>
                <strong><?= count($low_stock_items) ?> products</strong> are running low. Please create a purchase request to restock these items.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach (array_slice($low_stock_items, 0, 6) as $item): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm"><?= esc($item['product_name']) ?></h4>
                        <p class="text-xs text-gray-500 mt-1">Current: <?= $item['quantity'] ?> | Min: <?= $item['min_stock_level'] ?></p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        <?= $item['quantity'] ?>
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-600 mt-2">
                    <span class="font-mono"><?= esc($item['sku']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($low_stock_items) > 6): ?>
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">+ <?= count($low_stock_items) - 6 ?> more items</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Recent Activities Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Purchase Requests -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Purchase Requests</h3>
        </div>
        <div class="p-4">
            <?php if (!empty($recent_requests)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($recent_requests, 0, 5) as $request): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $request['request_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($request['created_at'])) ?></p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full <?= $request['status'] == 'approved' ? 'bg-emerald-100 text-emerald-700' : ($request['status'] == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
                        <?= ucfirst($request['status']) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-sm text-center py-4">No recent purchase requests</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Transfers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Transfers</h3>
        </div>
        <div class="p-4">
            <?php if (!empty($recent_transfers)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($recent_transfers, 0, 5) as $transfer): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $transfer['transfer_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($transfer['created_at'])) ?></p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full <?= $transfer['status'] == 'completed' ? 'bg-emerald-100 text-emerald-700' : ($transfer['status'] == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
                        <?= ucfirst($transfer['status']) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-sm text-center py-4">No recent transfers</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Deliveries</h3>
        </div>
        <div class="p-4">
            <?php if (!empty($recent_deliveries)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($recent_deliveries, 0, 5) as $delivery): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $delivery['delivery_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($delivery['created_at'])) ?></p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full <?= $delivery['status'] == 'delivered' ? 'bg-emerald-100 text-emerald-700' : ($delivery['status'] == 'in_transit' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') ?>">
                        <?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-sm text-center py-4">No recent deliveries</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php elseif ($role == 'supplier'): ?>
<!-- Supplier Dashboard -->

<!-- Supplier Info Header -->
<div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck-loading text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold"><?= $supplier_name ?? 'Supplier Portal' ?></h2>
                    <p class="text-cyan-100 text-sm">Supplier Dashboard</p>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="<?= base_url('purchase-orders') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-list mr-2"></i>View All Orders
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Pending Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-amber-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
                <p class="text-2xl font-bold text-amber-600 mt-1"><?= count($waiting_preparation ?? []) ?></p>
            </div>
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
        </div>
    </div>

    <!-- Preparing -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Preparing</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?= count($being_prepared ?? []) ?></p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-blue-600"></i>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-emerald-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completed</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1"><?= count($completed_orders ?? []) ?></p>
            </div>
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-2xl font-bold text-purple-600 mt-1"><?= count($waiting_preparation ?? []) + count($being_prepared ?? []) + count($completed_orders ?? []) ?></p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Orders Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Waiting Preparation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-amber-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-clock text-amber-500 mr-2"></i>
                Waiting Preparation
            </h3>
            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium"><?= count($waiting_preparation ?? []) ?></span>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            <?php if (!empty($waiting_preparation)): ?>
            <div class="space-y-3">
                <?php foreach ($waiting_preparation as $po): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-amber-50 transition-colors border border-transparent hover:border-amber-200">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $po['po_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= isset($po['order_date']) ? date('M d, Y', strtotime($po['order_date'])) : 'N/A' ?></p>
                        <?php if (!empty($po['total_amount'])): ?>
                        <p class="text-xs text-emerald-600 font-medium mt-1">₱<?= number_format($po['total_amount'], 2) ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" 
                       class="px-3 py-1.5 bg-amber-500 text-white hover:bg-amber-600 rounded-lg text-xs font-medium transition-colors">
                        Process
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No orders waiting</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Being Prepared -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-cog text-blue-500 mr-2"></i>
                Being Prepared
            </h3>
            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium"><?= count($being_prepared ?? []) ?></span>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            <?php if (!empty($being_prepared)): ?>
            <div class="space-y-3">
                <?php foreach ($being_prepared as $po): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-200">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $po['po_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= isset($po['prepared_at']) && $po['prepared_at'] ? date('M d, Y H:i', strtotime($po['prepared_at'])) : 'N/A' ?></p>
                        <?php if (!empty($po['total_amount'])): ?>
                        <p class="text-xs text-emerald-600 font-medium mt-1">₱<?= number_format($po['total_amount'], 2) ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" 
                       class="px-3 py-1.5 bg-blue-500 text-white hover:bg-blue-600 rounded-lg text-xs font-medium transition-colors">
                        View
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No active preparations</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Completed & Shipped -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                Completed
            </h3>
            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium"><?= count($completed_orders ?? []) ?></span>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            <?php if (!empty($completed_orders)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($completed_orders, 0, 10) as $po): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-200">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $po['po_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= isset($po['updated_at']) && $po['updated_at'] ? date('M d, Y', strtotime($po['updated_at'])) : 'N/A' ?></p>
                        <?php if (!empty($po['total_amount'])): ?>
                        <p class="text-xs text-emerald-600 font-medium mt-1">₱<?= number_format($po['total_amount'], 2) ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" 
                       class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg text-xs font-medium transition-colors">
                        View
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No completed orders yet</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php elseif ($role == 'logistics_coordinator'): ?>
<!-- Logistics Coordinator Dashboard -->

<!-- Logistics Info Header -->
<div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-truck text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Logistics Dashboard</h2>
                    <p class="text-purple-100 text-sm">Manage deliveries and shipments</p>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="<?= base_url('deliveries') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-calendar-check mr-2"></i>Schedule Delivery
            </a>
            <a href="<?= base_url('purchase-orders') ?>" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg text-sm font-medium hover:bg-purple-50 transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i>View Orders
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <!-- Ready for Shipment -->
    <a href="<?= base_url('purchase-orders') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-purple-300 transition-all border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ready to Ship</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1"><?= count($ready_for_shipment ?? []) ?></p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                    <i class="fas fa-box text-purple-600"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Scheduled -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-blue-300 transition-all border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Scheduled</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1"><?= count(array_filter($shipment_schedules ?? [], fn($d) => $d['status'] == 'scheduled')) ?></p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- In Transit -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-amber-300 transition-all border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">In Transit</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1"><?= count($active_deliveries ?? []) ?></p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <i class="fas fa-truck text-amber-600"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Delivered -->
    <a href="<?= base_url('deliveries') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-emerald-300 transition-all border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Delivered</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1"><?= count($delivery_history ?? []) ?></p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Quick Action Alert -->
<?php if (count($ready_for_shipment ?? []) > 0): ?>
<div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-bell text-purple-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-medium text-gray-800">Orders Ready for Delivery</p>
            <p class="text-sm text-gray-600">
                <span class="text-purple-600 font-medium"><?= count($ready_for_shipment ?? []) ?> PO(s)</span> are prepared and waiting for delivery scheduling
            </p>
        </div>
        <a href="<?= base_url('deliveries') ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white hover:bg-purple-700 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
            <i class="fas fa-calendar-check mr-2"></i>Schedule Now
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Deliveries Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Ready for Shipment -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-purple-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-box text-purple-500 mr-2"></i>
                Ready for Shipment
            </h3>
            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium"><?= count($ready_for_shipment ?? []) ?></span>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            <?php if (!empty($ready_for_shipment)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($ready_for_shipment, 0, 5) as $po): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors border border-transparent hover:border-purple-200">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $po['po_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= isset($po['prepared_at']) ? date('M d, Y', strtotime($po['prepared_at'])) : 'N/A' ?></p>
                    </div>
                    <a href="<?= base_url('deliveries?schedule=' . $po['id']) ?>" 
                       class="px-3 py-1.5 bg-purple-500 text-white hover:bg-purple-600 rounded-lg text-xs font-medium transition-colors">
                        Schedule
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No orders ready for shipment</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Active Deliveries -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-amber-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-truck text-amber-500 mr-2"></i>
                Active Deliveries
            </h3>
            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium"><?= count($active_deliveries ?? []) ?></span>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
            <?php if (!empty($active_deliveries)): ?>
            <div class="space-y-3">
                <?php foreach ($active_deliveries as $delivery): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-amber-50 transition-colors border border-transparent hover:border-amber-200">
                    <div>
                        <p class="font-medium text-gray-800 text-sm"><?= $delivery['delivery_number'] ?? 'N/A' ?></p>
                        <p class="text-xs text-gray-500"><?= isset($delivery['scheduled_date']) ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?></p>
                    </div>
                    <a href="<?= base_url('deliveries/view/' . $delivery['id']) ?>" 
                       class="px-3 py-1.5 bg-amber-500 text-white hover:bg-amber-600 rounded-lg text-xs font-medium transition-colors">
                        Track
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-truck text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm">No active deliveries</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php elseif ($role == 'franchise_manager'): ?>
<!-- Franchise Manager Dashboard -->

<!-- Franchise Info Header -->
<div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Franchise Management</h2>
                    <p class="text-indigo-100 text-sm">Manage franchise applications and partners</p>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
            <a href="<?= base_url('franchise/applications') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-file-signature mr-2"></i>View Applications
            </a>
            <a href="<?= base_url('franchise/partners') ?>" class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-lg text-sm font-medium hover:bg-indigo-50 transition-colors">
                <i class="fas fa-handshake mr-2"></i>View Partners
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Applications -->
    <a href="<?= base_url('franchise/applications') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-gray-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Applications</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $total_applications ?? 0 ?></p>
                    <p class="text-xs text-gray-400 mt-2">All time</p>
                </div>
                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                    <i class="fas fa-file-alt text-gray-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Pending Review -->
    <a href="<?= base_url('franchise/applications') ?>" class="group">
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-sm border border-amber-200 p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $pending_review ?? 0 ?></p>
                    <p class="text-xs text-gray-500 mt-2">Awaiting decision</p>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <i class="fas fa-clock text-amber-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Approved Franchises -->
    <a href="<?= base_url('franchise/partners') ?>" class="group">
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl shadow-sm border border-emerald-200 p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Approved Franchises</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $approved_franchises ?? 0 ?></p>
                    <p class="text-xs text-gray-500 mt-2">Active franchises</p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Locations -->
    <a href="<?= base_url('inventory') ?>" class="group">
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl shadow-sm border border-indigo-200 p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Locations</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= $total_locations ?? 0 ?></p>
                    <p class="text-xs text-gray-500 mt-2">Across the region</p>
                </div>
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                    <i class="fas fa-store text-indigo-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Recent Applications -->
<?php if (!empty($recent_applications)): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-file-signature text-indigo-500 mr-2"></i> Recent Applications
            </h3>
            <a href="<?= base_url('franchise/applications') ?>" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Applicant</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Business Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach (array_slice($recent_applications, 0, 5) as $app): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-800"><?= esc($app['applicant_name']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-800"><?= esc($app['business_name']) ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= esc($app['city']) ?></td>
                        <td class="px-4 py-3">
                            <?php if ($app['status'] == 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                            <?php elseif ($app['status'] == 'rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                        <td class="px-4 py-3">
                            <a href="<?= base_url('franchise/view-application/' . $app['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg text-xs font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Franchise Partners Grid -->
<?php if (!empty($franchise_partners)): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-handshake text-emerald-500 mr-2"></i> Active Franchise Partners
            </h3>
            <a href="<?= base_url('franchise/partners') ?>" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach (array_slice($franchise_partners, 0, 6) as $partner): ?>
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-lg p-4 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm"><?= esc($partner['business_name']) ?></h4>
                        <p class="text-xs text-gray-600 mt-1"><?= esc($partner['applicant_name']) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-emerald-600"></i>
                    </div>
                </div>
                <div class="space-y-1 text-xs text-gray-600">
                    <p><i class="fas fa-map-marker-alt text-emerald-500 mr-1"></i> <?= esc($partner['city']) ?></p>
                    <p><i class="fas fa-phone text-emerald-500 mr-1"></i> <?= esc($partner['phone']) ?></p>
                    <p class="text-emerald-600 font-medium"><i class="fas fa-calendar-check mr-1"></i> Approved: <?= date('M d, Y', strtotime($partner['approved_at'])) ?></p>
                </div>
                <div class="mt-3 pt-3 border-t border-emerald-200">
                    <a href="<?= base_url('franchise/view-application/' . $partner['id']) ?>" class="inline-flex items-center text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<!-- Inventory Staff Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Inventory Items -->
    <a href="<?= base_url('inventory') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Inventory Items</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $branch_inventory ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Active Alerts -->
    <a href="<?= base_url('inventory/alerts') ?>" class="group">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-red-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Alerts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?= $active_alerts ?? 0 ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Low Stock Alerts for Inventory Staff -->
<?php if (!empty($low_stock_items) && count($low_stock_items) > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-amber-200 mb-8">
    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i> Low Stock Alerts - Action Required
            </h3>
            <a href="<?= base_url('inventory/alerts') ?>" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-sm text-gray-700">
                <i class="fas fa-info-circle text-amber-500 mr-2"></i>
                <strong>Action Required:</strong> The following products are running low. Please notify your Branch Manager to create a purchase request.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach (array_slice($low_stock_items, 0, 9) as $item): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm"><?= esc($item['product_name']) ?></h4>
                        <p class="text-xs text-gray-500 mt-1">Current Stock</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                        <?= $item['quantity'] ?>
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-600 mt-2">
                    <span>Min: <?= $item['min_stock_level'] ?></span>
                    <span class="font-mono"><?= esc($item['sku']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?= $this->endSection() ?>
