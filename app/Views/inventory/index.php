<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Management';
$title = 'Inventory';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Search product, SKU, barcode...">
            </div>
            <div class="w-full md:w-40">
                <select id="stockFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Stock Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <?php if ($can_view_all_branches ?? false): ?>
            <div class="w-full md:w-48">
                <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer"
                        onchange="window.location.href='?branch_id='+this.value">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                        <?= esc($branch['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <a href="<?= base_url('inventory/alerts') ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-700 font-medium rounded-lg transition-colors whitespace-nowrap">
                <i class="fas fa-exclamation-triangle mr-2"></i> Alerts
            </a>
            <a href="<?= base_url('inventory/history' . ($current_branch_id ? '?branch_id=' . $current_branch_id : '')) ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition-colors whitespace-nowrap">
                <i class="fas fa-history mr-2"></i> History
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <?php if ($current_branch_id === null && ($can_view_all_branches ?? false)): ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Available</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Min Level</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($inventory)): ?>
                    <?php foreach ($inventory as $item): 
                        $stockStatus = 'in_stock';
                        if ($item['quantity'] == 0) $stockStatus = 'out_of_stock';
                        elseif ($item['quantity'] <= $item['min_stock_level']) $stockStatus = 'low_stock';
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" data-product="<?= esc(strtolower($item['product_name'])) ?>" data-sku="<?= esc(strtolower($item['sku'])) ?>" data-barcode="<?= esc(strtolower($item['barcode'] ?? '')) ?>" data-branch="<?= esc(strtolower($item['branch_name'] ?? '')) ?>" data-stock="<?= $stockStatus ?>">
                        <?php if ($current_branch_id === null && ($can_view_all_branches ?? false)): ?>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($item['branch_name'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cube text-purple-600"></i>
                                </div>
                                <span class="font-medium text-gray-800"><?= esc($item['product_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['sku']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($item['quantity'] <= $item['min_stock_level']): ?>
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg text-sm font-bold bg-red-100 text-red-700">
                                <?= $item['quantity'] ?>
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg text-sm font-bold bg-emerald-100 text-emerald-700">
                                <?= $item['quantity'] ?>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700"><?= $item['available_quantity'] ?></td>
                        <td class="px-6 py-4 text-center text-gray-500"><?= $item['min_stock_level'] ?></td>
                        <td class="px-6 py-4">
                            <?php if ($item['quantity'] == 0): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                            <?php elseif ($item['quantity'] <= $item['min_stock_level']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Low Stock
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check-circle mr-1"></i> In Stock
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="updateInventory(<?= $item['id'] ?>, <?= $item['branch_id'] ?>, <?= $item['product_id'] ?>, <?= $item['quantity'] ?>)"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-edit mr-1"></i> Update
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-warehouse text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No inventory records found</p>
            </div>
        </div>
    </div>
</div>

<!-- Update Inventory Modal -->
<div id="updateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeUpdateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Update Inventory</h3>
                <button onclick="closeUpdateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateForm" method="post" action="<?= base_url('inventory/update') ?>">
                <?= csrf_field() ?>
                <div class="p-6 space-y-4">
                    <input type="hidden" name="branch_id" id="update_branch_id">
                    <input type="hidden" name="product_id" id="update_product_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select name="action" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <option value="set">Set Quantity</option>
                            <option value="add">Add Quantity</option>
                            <option value="subtract">Subtract Quantity</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" min="0" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                    <button type="button" onclick="closeUpdateModal()"
                            class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateInventory(id, branchId, productId, currentQty) {
    document.getElementById('update_branch_id').value = branchId;
    document.getElementById('update_product_id').value = productId;
    document.getElementById('updateForm').querySelector('input[name="quantity"]').value = currentQty;
    document.getElementById('updateModal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUpdateModal();
    }
});

// Real-time search filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const stockFilter = document.getElementById('stockFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const stock = stockFilter.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const m1 = search === '' || row.dataset.product.includes(search) || row.dataset.sku.includes(search) || row.dataset.barcode.includes(search) || row.dataset.branch.includes(search);
            const m2 = stock === '' || row.dataset.stock === stock;
            
            if (m1 && m2) { row.style.display = ''; count++; } 
            else { row.style.display = 'none'; }
        });
        
        noResults.classList.toggle('hidden', count > 0);
        tbody.classList.toggle('hidden', count === 0);
    }
    
    searchInput.addEventListener('input', filterTable);
    stockFilter.addEventListener('change', filterTable);
});
</script>
<?= $this->endSection() ?>
