<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Stock Alerts';
$title = 'Stock Alerts';
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('inventory') ?>" 
       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <?php 
    $outOfStock = 0;
    $lowStock = 0;
    $expiring = 0;
    if (!empty($alerts)) {
        foreach ($alerts as $alert) {
            if ($alert['alert_type'] == 'out_of_stock') $outOfStock++;
            elseif ($alert['alert_type'] == 'low_stock') $lowStock++;
            elseif ($alert['alert_type'] == 'expiring_soon') $expiring++;
        }
    }
    ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Out of Stock</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?= $outOfStock ?></p>
            </div>
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-red-600"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-amber-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Low Stock</p>
                <p class="text-2xl font-bold text-amber-600 mt-1"><?= $lowStock ?></p>
            </div>
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 border-l-4 border-l-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Expiring Soon</p>
                <p class="text-2xl font-bold text-purple-600 mt-1"><?= $expiring ?></p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-bell text-amber-500 mr-2"></i>Alert List
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alert Type</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Qty</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Threshold</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expiry Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($alerts)): ?>
                    <?php foreach ($alerts as $alert): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($alert['branch_name']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cube text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= esc($alert['product_name']) ?></p>
                                    <p class="text-xs text-gray-500">SKU: <?= esc($alert['sku']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($alert['alert_type'] == 'out_of_stock'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i>Out of Stock
                            </span>
                            <?php elseif ($alert['alert_type'] == 'low_stock'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Low Stock
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                <i class="fas fa-clock mr-1"></i>Expiring Soon
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg text-sm font-bold <?= $alert['current_quantity'] == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' ?>">
                                <?= $alert['current_quantity'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600"><?= $alert['threshold'] ?? '-' ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= $alert['expiry_date'] ? date('M d, Y', strtotime($alert['expiry_date'])) : '-' ?></td>
                        <td class="px-6 py-4">
                            <form method="post" action="<?= base_url('inventory/alerts/' . $alert['id'] . '/acknowledge') ?>" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-check mr-1"></i>Acknowledge
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No active alerts</p>
                                <p class="text-gray-400 text-sm">All inventory levels are healthy</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
