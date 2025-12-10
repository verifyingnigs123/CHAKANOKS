<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory History';
$title = 'Inventory History';
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('inventory' . ($current_branch_id ? '?branch_id=' . $current_branch_id : '')) ?>" 
       class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
    </a>
</div>

<!-- Filters Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <form method="get" action="<?= base_url('inventory/history') ?>" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php if ($role == 'central_admin'): ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                <select name="branch_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                            <?= esc($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="<?= ($role == 'central_admin') ? '' : 'md:col-span-2' ?>">
                <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                <select name="product_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Products</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>" <?= ($current_product_id == $product['id']) ? 'selected' : '' ?>>
                            <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                    <?php if (!$current_branch_id && $role == 'central_admin'): ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">PO / Delivery</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Previous</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Change</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">New Qty</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($history)): ?>
                    <?php foreach ($history as $item): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= date('M d, Y', strtotime($item['created_at'])) ?><br>
                            <span class="text-xs text-gray-400"><?= date('h:i A', strtotime($item['created_at'])) ?></span>
                        </td>
                        <?php if (!$current_branch_id && $role == 'central_admin'): ?>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($item['branch_name']) ?>
                            </span>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cube text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= esc($item['product_name']) ?></p>
                                    <p class="text-xs text-gray-500">SKU: <?= esc($item['sku']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <?php if ($item['po_number']): ?>
                                <a href="<?= base_url('purchase-orders/view/' . $item['purchase_order_id']) ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                                    <?= esc($item['po_number']) ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($item['delivery_number']): ?>
                                <br><a href="<?= base_url('deliveries/view/' . $item['delivery_id']) ?>" class="text-cyan-600 hover:text-cyan-800 text-xs">
                                    <?= esc($item['delivery_number']) ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!$item['po_number'] && !$item['delivery_number']): ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600"><?= $item['previous_quantity'] ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                +<?= $item['quantity_added'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-800"><?= $item['new_quantity'] ?></td>
                        <td class="px-6 py-4">
                            <?php
                            $typeBadge = [
                                'delivery_received' => 'bg-blue-100 text-blue-700',
                                'transfer_in' => 'bg-cyan-100 text-cyan-700',
                                'adjustment' => 'bg-amber-100 text-amber-700',
                                'manual_update' => 'bg-gray-100 text-gray-600'
                            ];
                            $typeLabel = [
                                'delivery_received' => 'Delivery',
                                'transfer_in' => 'Transfer',
                                'adjustment' => 'Adjustment',
                                'manual_update' => 'Manual'
                            ];
                            $badgeClass = $typeBadge[$item['transaction_type']] ?? 'bg-gray-100 text-gray-600';
                            $label = $typeLabel[$item['transaction_type']] ?? ucfirst($item['transaction_type']);
                            ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?= $badgeClass ?>">
                                <?= $label ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= esc($item['received_by_name'] ?? 'System') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= (!$current_branch_id && $role == 'central_admin') ? '9' : '8' ?>" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">No inventory history found</p>
                                <p class="text-gray-400 text-sm">Transactions will appear here when inventory changes</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
