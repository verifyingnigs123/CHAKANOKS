<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Products';
$title = 'Products';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput"
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" 
                       placeholder="Search by name, SKU, barcode...">
            </div>
            <div class="w-full md:w-40">
                <select id="categoryFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Categories</option>
                    <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc(strtolower($cat['name'])) ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="w-full md:w-36">
                <select id="statusFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <a href="<?= base_url('suppliers') ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-truck mr-2"></i> Manage Suppliers
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="dataTable">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row"
                        data-name="<?= esc(strtolower($product['name'])) ?>"
                        data-sku="<?= esc(strtolower($product['sku'] ?? '')) ?>"
                        data-category="<?= esc(strtolower($product['category'] ?? '')) ?>"
                        data-supplier="<?= esc(strtolower($product['supplier_name'] ?? '')) ?>"
                        data-status="<?= esc($product['status']) ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-emerald-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800 block"><?= esc($product['name']) ?></span>
                                    <?php if (!empty($product['description'])): ?>
                                    <span class="text-xs text-gray-500 truncate max-w-xs block"><?= esc($product['description']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded"><?= esc($product['sku'] ?? '-') ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if (!empty($product['category'])): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($product['category']) ?>
                            </span>
                            <?php else: ?>
                            <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <span class="font-medium text-gray-800"><?= esc($product['supplier_name'] ?? '-') ?></span>
                                <?php if (!empty($product['supplier_code'])): ?>
                                <span class="text-xs text-gray-500 block"><?= esc($product['supplier_code']) ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-emerald-600">₱<?= number_format($product['supplier_price'] ?? 0, 2) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php $stock = $product['stock_quantity'] ?? 0; ?>
                            <?php if ($stock > 10): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><?= number_format($stock) ?></span>
                            <?php elseif ($stock > 0): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><?= number_format($stock) ?></span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick='openEditModal(<?= json_encode($product) ?>)' 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteModal(<?= $product['id'] ?>, '<?= esc($product['name']) ?>')" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- No Results -->
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No products found</p>
                <p class="text-gray-400 text-sm">Products are managed by suppliers</p>
            </div>
        </div>
    </div>
</div>


<!-- Info Banner -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-500 mr-3"></i>
        <p class="text-sm text-blue-700">Products are managed by suppliers. To add new products, go to <a href="<?= base_url('suppliers') ?>" class="font-medium underline">Suppliers</a> and manage their product catalog.</p>
    </div>
</div>


<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-2xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form id="editForm" method="post" action="">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-edit text-amber-500 mr-2"></i>Edit Product
                    </h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" id="editSku" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="editCategory" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['name']) ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit" id="editUnit" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                                <option value="pcs">Pieces</option>
                                <option value="kg">Kilogram</option>
                                <option value="g">Gram</option>
                                <option value="L">Liter</option>
                                <option value="mL">Milliliter</option>
                                <option value="box">Box</option>
                                <option value="pack">Pack</option>
                                <option value="sack">Sack</option>
                                <option value="case">Case</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="editDescription" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"></textarea>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Price (₱) <span class="text-red-500">*</span></label>
                            <input type="number" name="supplier_price" id="editSupplierPrice" step="0.01" min="0" max="999999.99" required
                                   oninput="if(this.value > 999999.99) this.value = 999999.99"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="editStockQty" min="0" max="999999"
                                   oninput="if(this.value > 999999) this.value = 999999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Qty</label>
                            <input type="number" name="min_order_qty" id="editMinOrderQty" min="1" max="9999"
                                   oninput="if(this.value > 9999) this.value = 9999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (days)</label>
                            <input type="number" name="lead_time_days" id="editLeadTime" min="0" max="365"
                                   oninput="if(this.value > 365) this.value = 365"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <input type="text" id="editSupplierName" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete Product
                </h3>
            </div>
            <div class="px-6 py-6">
                <p class="text-gray-600">Are you sure you want to delete <span id="deleteProductName" class="font-semibold text-gray-800"></span>?</p>
                <p class="text-sm text-red-500 mt-2">This action cannot be undone.</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                <a id="deleteLink" href="#" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
// Modal functions
function openEditModal(product) {
    document.getElementById('editForm').action = '<?= base_url('suppliers/update-product/') ?>' + product.id;
    document.getElementById('editName').value = product.name || '';
    document.getElementById('editSku').value = product.sku || '';
    document.getElementById('editCategory').value = product.category || '';
    document.getElementById('editDescription').value = product.description || '';
    document.getElementById('editUnit').value = product.unit || 'pcs';
    document.getElementById('editSupplierPrice').value = product.supplier_price || 0;
    document.getElementById('editStockQty').value = product.stock_quantity || 0;
    document.getElementById('editMinOrderQty').value = product.min_order_qty || 1;
    document.getElementById('editLeadTime').value = product.lead_time_days || '';
    document.getElementById('editSupplierName').value = product.supplier_name || '-';
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openDeleteModal(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteLink').href = '<?= base_url('suppliers/remove-product/') ?>' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});

// Table filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const categoryValue = categoryFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.dataset.name || '';
            const sku = row.dataset.sku || '';
            const category = row.dataset.category || '';
            const supplier = row.dataset.supplier || '';
            const status = row.dataset.status || '';
            
            const matchesSearch = searchTerm === '' || 
                name.includes(searchTerm) || 
                sku.includes(searchTerm) ||
                supplier.includes(searchTerm);
            
            const matchesCategory = categoryValue === '' || category.includes(categoryValue);
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            tbody.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            tbody.classList.remove('hidden');
        }
    }
    
    searchInput.addEventListener('input', filterTable);
    categoryFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
<?= $this->endSection() ?>
