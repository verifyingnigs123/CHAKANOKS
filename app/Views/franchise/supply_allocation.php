<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Supply Allocation';
$title = 'Supply Allocation';
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-boxes text-emerald-600 text-xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Supply Allocation</h1>
    </div>
    <p class="text-gray-500">Allocate supplies to franchise partners</p>
</div>

<!-- Create New Allocation Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Allocation</h3>
    
    <form method="post" action="<?= base_url('franchise/allocate-supply') ?>" id="allocationForm">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Franchise -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Franchise</label>
                <select name="franchise_id" id="franchise_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">Select franchise</option>
                    <?php if (!empty($franchises)): ?>
                        <?php foreach ($franchises as $franchise): ?>
                            <option value="<?= $franchise['branch_id'] ?>"><?= esc($franchise['business_name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Product -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="product_id" id="product_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">Select product</option>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['product_id'] ?>" data-available="<?= $product['available_qty'] ?>">
                                <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>) - Available: <?= $product['available_qty'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No products available from suppliers</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" id="quantity" min="1" value="0" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
            </div>

            <!-- Allocate Button -->
            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i> Allocate
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Allocations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Franchise</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Allocated</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Delivered</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Pending</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($allocations)): ?>
                    <?php foreach ($allocations as $allocation): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-gray-800 font-medium"><?= esc($allocation['franchise_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700"><?= esc($allocation['product_name']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold bg-blue-100 text-blue-700">
                                <?= $allocation['allocated_qty'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700">
                                <?= $allocation['delivered_qty'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg text-sm font-semibold bg-amber-100 text-amber-700">
                                <?= $allocation['pending_qty'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($allocation['status'] == 'fulfilled'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Fulfilled
                            </span>
                            <?php elseif ($allocation['status'] == 'partial'): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                Partial
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                Pending
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-boxes text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">No allocations yet</p>
                            <p class="text-gray-400 text-sm mt-1">Create your first supply allocation above</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
