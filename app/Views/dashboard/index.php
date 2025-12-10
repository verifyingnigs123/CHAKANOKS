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
            <a href="<?= base_url('purchase-requests/create') ?>" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
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
<?php endif; ?>

<?= $this->endSection() ?>
