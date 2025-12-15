<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Transfer';
$title = 'Create Transfer';
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('transfers') ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-500 to-pink-600">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-exchange-alt mr-2"></i>Transfer Details
        </h3>
    </div>
    
    <form method="post" action="<?= base_url('transfers/store') ?>" id="transferForm">
        <?= csrf_field() ?>
        
        <div class="p-6 space-y-6">
            <!-- Branch Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Branch <span class="text-red-500">*</span></label>
                    <select name="from_branch_id" id="from_branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="">Select Source Branch</option>
                        <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= ($from_branch_id == $branch['id']) ? 'selected' : '' ?>><?= esc($branch['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Branch <span class="text-red-500">*</span></label>
                    <select name="to_branch_id" id="to_branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="">Select Destination Branch</option>
                        <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Transfer Items -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-boxes text-purple-500 mr-2"></i>Transfer Items
                    </h4>
                    <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>Add Product
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase" style="width:50%">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Available</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Transfer Qty</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-gray-100">
                            <tr class="item-row">
                                <td class="px-4 py-3">
                                    <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select" required>
                                        <option value="">Select Product</option>
                                        <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" data-available="0"><?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600">-</span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="quantities[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center quantity-input" min="1" step="1" value="1" required>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="remove-item inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Transfer notes or reason (optional)"></textarea>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="<?= base_url('transfers') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>Create Transfer Request
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Filter "To Branch" dropdown when "From Branch" changes
document.getElementById('from_branch_id').addEventListener('change', function() {
    const fromBranchId = this.value;
    const toBranchSelect = document.getElementById('to_branch_id');
    const allOptions = toBranchSelect.querySelectorAll('option');
    
    // Reset and filter "To Branch" options
    allOptions.forEach(option => {
        if (option.value === fromBranchId) {
            option.style.display = 'none'; // Hide selected "From Branch"
        } else {
            option.style.display = 'block'; // Show others
        }
    });
    
    // Reset "To Branch" if it's the same as "From Branch"
    if (toBranchSelect.value === fromBranchId) {
        toBranchSelect.value = '';
    }
    
    // Load products for selected branch
    loadBranchProducts(fromBranchId);
});

// Load products based on selected branch inventory
function loadBranchProducts(branchId) {
    if (!branchId) {
        // Reset to empty if no branch selected
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Select Product</option>';
        });
        return;
    }
    
    // Fetch products available in this branch
    fetch(`<?= base_url('inventory/get-branch-products') ?>?branch_id=${branchId}`)
        .then(response => response.json())
        .then(data => {
            const products = data.products || [];
            
            // Update all product dropdowns
            document.querySelectorAll('.product-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Select Product</option>';
                
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.product_id;
                    option.textContent = `${product.product_name} (${product.sku}) - Available: ${product.quantity}`;
                    option.setAttribute('data-available', product.quantity);
                    if (currentValue == product.product_id) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
                
                // Update available quantity display
                updateAvailableQuantity(select);
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            alert('Failed to load products. Please try again.');
        });
}

document.querySelectorAll('.product-select').forEach(select => {
    select.addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

function updateAvailableQuantity(selectElement) {
    const row = selectElement.closest('.item-row');
    const availableQtySpan = row.querySelector('.available-qty');
    const quantityInput = row.querySelector('.quantity-input');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (!selectElement.value) {
        availableQtySpan.textContent = '-';
        availableQtySpan.className = 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600';
        quantityInput.max = '';
        return;
    }
    
    const available = parseInt(selectedOption.getAttribute('data-available')) || 0;
    availableQtySpan.textContent = available;
    availableQtySpan.className = available > 0 
        ? 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-bold bg-emerald-100 text-emerald-700'
        : 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-bold bg-red-100 text-red-700';
    quantityInput.max = available;
    
    // Adjust quantity if it exceeds available
    if (parseInt(quantityInput.value) > available) {
        quantityInput.value = available;
    }
}

document.getElementById('addItemBtn').addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const firstRow = tbody.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('.quantity-input').value = 1;
    newRow.querySelector('.available-qty').textContent = '-';
    newRow.querySelector('.available-qty').className = 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600';
    
    tbody.appendChild(newRow);
    newRow.querySelector('.product-select').addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            e.target.closest('.item-row').remove();
        } else {
            alert('You must have at least one product');
        }
    }
});

// Initialize on page load
const fromBranchId = document.getElementById('from_branch_id').value;
if (fromBranchId) {
    loadBranchProducts(fromBranchId);
}
</script>
<?= $this->endSection() ?>
