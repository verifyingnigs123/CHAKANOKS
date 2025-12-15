<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'My Products';
$title = 'My Product Catalog';
?>

<!-- Header -->
<div class="flex flex-wrap items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">My Product Catalog</h3>
        <p class="text-sm text-gray-500">Manage the products you offer to ChakaNoks</p>
    </div>
    <button type="button" onclick="openAddProductModal()"
       class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
        <i class="fas fa-plus mr-2"></i> Add New Product
    </button>
</div>

<!-- Stats Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-boxes text-emerald-600 text-xl"></i>
        </div>
        <div class="flex-1">
            <p class="text-gray-600">Welcome, <span class="font-semibold"><?= esc($supplier['name']) ?></span></p>
            <p class="text-sm text-gray-500">Supplier Code: <?= esc($supplier['code']) ?></p>
        </div>
        <div class="text-right">
            <span class="text-3xl font-bold text-emerald-600"><?= count($products) ?></span>
            <p class="text-xs text-gray-500">Total Products</p>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h4 class="font-semibold text-gray-800"><i class="fas fa-list text-emerald-500 mr-2"></i>Product List</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Price</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Stock</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Min Order</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-emerald-600"></i>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800"><?= esc($product['name']) ?></span>
                                    <?php if (!empty($product['description'])): ?>
                                    <p class="text-xs text-gray-500 truncate max-w-xs"><?= esc($product['description']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= esc($product['sku']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($product['category'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($product['unit'] ?? 'pcs') ?></td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-semibold text-emerald-600">₱<?= number_format($product['supplier_price'] ?? 0, 2) ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php $stock = $product['stock_quantity'] ?? 0; ?>
                            <?php if ($stock > 10): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><?= $stock ?></span>
                            <?php elseif ($stock > 0): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><?= $stock ?></span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">0</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-600"><?= $product['min_order_qty'] ?? 1 ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                   onclick="openEditProductModal(<?= htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8') ?>)"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" 
                                   onclick="confirmDeleteProduct(<?= $product['id'] ?>, '<?= esc($product['name']) ?>')"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium">No products yet</p>
                            <p class="text-gray-400 text-sm mb-4">Add your first product to start receiving orders</p>
                            <button type="button" onclick="openAddProductModal()"
                               class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i> Add Your First Product
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAddProductModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-2xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" action="<?= base_url('supplier/add-product') ?>">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-plus-circle text-emerald-500 mr-2"></i>Add New Product
                    </h3>
                    <button type="button" onclick="closeAddProductModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="addProductName" required
                               pattern="^[A-Za-z\s]+$"
                               title="Letters and spaces only, no numbers or special characters"
                               oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"
                               placeholder="e.g., Premium Rice">
                        <p class="text-xs text-gray-500 mt-1">Letters and spaces only</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" id="addProductSku" readonly
                                   class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed"
                                   placeholder="Auto-generated">
                            <p class="text-xs text-gray-500 mt-1">Auto-generated by system</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['name']) ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2"
                                  oninput="this.value = this.value.replace(/[^A-Za-z\s,.\-]/g, '')"
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"
                                  placeholder="Product description (optional)"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Letters, spaces, commas, periods only</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="pcs">Pieces (pcs)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="L">Liter (L)</option>
                                <option value="mL">Milliliter (mL)</option>
                                <option value="box">Box</option>
                                <option value="pack">Pack</option>
                                <option value="sack">Sack</option>
                                <option value="case">Case</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) <span class="text-red-500">*</span></label>
                            <input type="number" name="supplier_price" step="0.01" min="0" max="999999.99" required
                                   oninput="if(this.value > 999999.99) this.value = 999999.99"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="stock_quantity" min="0" max="999999" value="0" required
                                   oninput="if(this.value > 999999) this.value = 999999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"
                                   placeholder="Max 999,999">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Qty</label>
                            <input type="number" name="min_order_qty" min="1" max="9999" value="1"
                                   oninput="if(this.value > 9999) this.value = 9999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (days)</label>
                            <input type="number" name="lead_time_days" min="0" max="365"
                                   oninput="if(this.value > 365) this.value = 365"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"
                                   placeholder="Max 365">
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeAddProductModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditProductModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-2xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" id="editProductForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-edit text-amber-500 mr-2"></i>Edit Product
                    </h3>
                    <button type="button" onclick="closeEditProductModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" id="editSku" required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="editCategory" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat['name']) ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="editDescription" rows="2"
                                  class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit" id="editUnit" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="pcs">Pieces (pcs)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="L">Liter (L)</option>
                                <option value="mL">Milliliter (mL)</option>
                                <option value="box">Box</option>
                                <option value="pack">Pack</option>
                                <option value="sack">Sack</option>
                                <option value="case">Case</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) <span class="text-red-500">*</span></label>
                            <input type="number" name="supplier_price" id="editPrice" step="0.01" min="0" max="999999.99" required
                                   oninput="if(this.value > 999999.99) this.value = 999999.99"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="stock_quantity" id="editStock" min="0" max="999999" required
                                   oninput="if(this.value > 999999) this.value = 999999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Qty</label>
                            <input type="number" name="min_order_qty" id="editMinQty" min="1" max="9999" value="1"
                                   oninput="if(this.value > 9999) this.value = 9999"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lead Time (days)</label>
                            <input type="number" name="lead_time_days" id="editLeadTime" min="0" max="365"
                                   oninput="if(this.value > 365) this.value = 365"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeEditProductModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteProductModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete Product
                </h3>
            </div>
            
            <div class="px-6 py-6">
                <p class="text-gray-600 text-center">Are you sure you want to delete <strong id="deleteProductName" class="text-gray-800"></strong>?</p>
                <p class="text-sm text-gray-500 text-center mt-2">This action cannot be undone.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteProductModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <a href="#" id="deleteProductLink"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors inline-flex items-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Generate unique SKU
function generateSKU() {
    const timestamp = Date.now().toString(36).toUpperCase();
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    return 'SKU-' + timestamp.slice(-4) + random;
}

function openAddProductModal() {
    document.getElementById('addProductModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Auto-generate SKU when modal opens
    document.getElementById('addProductSku').value = generateSKU();
}

function closeAddProductModal() {
    document.getElementById('addProductModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openEditProductModal(product) {
    document.getElementById('editProductForm').action = '<?= base_url('supplier/update-product/') ?>' + product.id;
    document.getElementById('editName').value = product.name || '';
    document.getElementById('editSku').value = product.sku || '';
    document.getElementById('editCategory').value = product.category || '';
    document.getElementById('editDescription').value = product.description || '';
    document.getElementById('editUnit').value = product.unit || 'pcs';
    document.getElementById('editPrice').value = product.supplier_price || 0;
    document.getElementById('editStock').value = product.stock_quantity || 0;
    document.getElementById('editMinQty').value = product.min_order_qty || 1;
    document.getElementById('editLeadTime').value = product.lead_time_days || '';
    document.getElementById('editProductModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditProductModal() {
    document.getElementById('editProductModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function confirmDeleteProduct(id, name) {
    document.getElementById('deleteProductName').textContent = name;
    document.getElementById('deleteProductLink').href = '<?= base_url('supplier/delete-product/') ?>' + id;
    document.getElementById('deleteProductModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteProductModal() {
    document.getElementById('deleteProductModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddProductModal();
        closeEditProductModal();
        closeDeleteProductModal();
    }
});
</script>

<?= $this->endSection() ?>
