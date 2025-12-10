<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Purchase Order';
$title = 'Create Purchase Order';
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('purchase-orders') ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-cyan-600">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-shopping-cart mr-2"></i>Order Details
        </h3>
    </div>
    
    <form method="post" action="<?= base_url('purchase-orders/store') ?>" id="poForm">
        <?= csrf_field() ?>
        
        <div class="p-6 space-y-6">
            <!-- Approved Request Selection (Optional) -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <label class="block text-sm font-medium text-amber-800 mb-2">
                    <i class="fas fa-file-alt mr-1"></i>Create from Approved Request (Optional)
                </label>
                <select name="purchase_request_id" id="purchase_request_id" class="w-full px-4 py-2.5 bg-white border border-amber-300 rounded-lg focus:border-amber-500 outline-none transition-all cursor-pointer">
                    <option value="">-- Create New PO (No Request) --</option>
                    <?php if (!empty($approved_requests)): ?>
                    <?php foreach ($approved_requests as $request): ?>
                    <option value="<?= $request['id'] ?>" data-branch-id="<?= $request['branch_id'] ?>"><?= $request['request_number'] ?> - <?= $request['branch_name'] ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-xs text-amber-600 mt-2"><i class="fas fa-info-circle mr-1"></i>Select a request to auto-load items, or leave empty to manually add items</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="" data-user-id="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['supplier_id'] ?>" data-user-id="<?= $supplier['id'] ?>"><?= esc($supplier['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Products will load based on selected supplier</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span class="text-red-500">*</span></label>
                    <select name="branch_id" id="branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery</label>
                    <input type="date" name="expected_delivery_date" min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <div class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-lg w-full md:w-1/3">
                    <i class="fab fa-paypal text-blue-600 text-xl"></i>
                    <span class="font-medium text-blue-800">PayPal</span>
                </div>
                <input type="hidden" name="payment_method" value="paypal">
                <p class="text-xs text-gray-500 mt-1">Central Admin will process payment after delivery is received</p>
            </div>

            <!-- Order Items -->
            <div class="border-t border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>Order Items
                    </h4>
                    <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>Add Item
                    </button>
                </div>
                <!-- Message for supplier selection -->
                <div id="supplierMessage" class="text-center py-4 text-gray-500 text-sm bg-gray-50 rounded-lg mb-4">
                    <i class="fas fa-info-circle mr-1"></i> Please select a supplier first to load their products
                </div>
                <div class="overflow-x-auto" id="itemsTableContainer" style="display: none;">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase" style="width:40%">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:15%">Quantity</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase" style="width:15%">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-gray-100">
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <th colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-600">Subtotal:</th>
                                <th id="subtotal" class="px-4 py-3 text-right text-sm font-medium text-gray-800">₱0.00</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-600">Tax (12%):</th>
                                <th id="tax" class="px-4 py-3 text-right text-sm font-medium text-gray-800">₱0.00</th>
                            </tr>
                            <tr class="border-t-2 border-gray-300">
                                <th colspan="4" class="px-4 py-3 text-right text-base font-bold text-gray-800">Total Amount:</th>
                                <th id="total-amount" class="px-4 py-3 text-right text-base font-bold text-emerald-600">₱0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Optional notes for this purchase order"></textarea>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="<?= base_url('purchase-orders') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Create Purchase Order
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Current product options (will be updated when supplier is selected)
let productOptionsTemplate = '';
let supplierSelected = false;

function calculateItemTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = (qty * price).toFixed(2);
    row.querySelector('.item-total').textContent = '₱' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2});
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        subtotal += (qty * price);
        calculateItemTotal(row);
    });
    const tax = subtotal * 0.12;
    const totalAmount = subtotal + tax;
    document.getElementById('subtotal').textContent = '₱' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('tax').textContent = '₱' + tax.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('total-amount').textContent = '₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
}

function attachEventListeners(row) {
    row.querySelector('.product-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const costPrice = selectedOption.getAttribute('data-price') || 0;
        row.querySelector('.price-input').value = costPrice;
        calculateItemTotal(row);
        calculateTotals();
    });
    row.querySelector('.quantity-input').addEventListener('input', function() {
        calculateItemTotal(row);
        calculateTotals();
    });
    row.querySelector('.price-input').addEventListener('input', function() {
        calculateItemTotal(row);
        calculateTotals();
    });
    
    // Remove item button
    const removeBtn = row.querySelector('.remove-item');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const rows = document.querySelectorAll('#itemsBody .item-row');
            if (rows.length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('You must have at least one item');
            }
        });
    }
}

function createItemRow(productId = '', quantity = 1, unitPrice = 0) {
    const tbody = document.getElementById('itemsBody');
    
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td class="px-4 py-3">
            <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select text-sm" required>
                <option value="">Select Product</option>
                ${productOptionsTemplate}
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="quantities[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center quantity-input" min="1" step="1" value="${quantity}" required>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="unit_prices[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center price-input" step="0.01" min="0" value="${unitPrice}" required>
        </td>
        <td class="px-4 py-3 text-right font-medium text-gray-800 item-total">₱0.00</td>
        <td class="px-4 py-3 text-center">
            <button type="button" class="remove-item inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    const productSelect = newRow.querySelector('.product-select');
    if (productId) productSelect.value = productId;
    
    tbody.appendChild(newRow);
    attachEventListeners(newRow);
    calculateItemTotal(newRow);
    calculateTotals();
    return newRow;
}

function clearAllItems() {
    const tbody = document.getElementById('itemsBody');
    tbody.innerHTML = '';
    calculateTotals();
}

function showItemsTable() {
    document.getElementById('supplierMessage').style.display = 'none';
    document.getElementById('itemsTableContainer').style.display = 'block';
}

function hideItemsTable() {
    document.getElementById('supplierMessage').style.display = 'block';
    document.getElementById('itemsTableContainer').style.display = 'none';
}

// Add Item button
document.getElementById('addItemBtn').addEventListener('click', function() {
    if (!supplierSelected) {
        alert('Please select a supplier first');
        return;
    }
    createItemRow();
});

// Purchase Request selection
document.getElementById('purchase_request_id').addEventListener('change', function() {
    const requestId = this.value;
    if (!requestId) {
        return;
    }
    window.location.href = `<?= base_url('purchase-orders/create-from-request/') ?>${requestId}`;
});

// Load supplier products when supplier is selected
document.getElementById('supplier_id').addEventListener('change', function() {
    const supplierId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const userId = selectedOption.getAttribute('data-user-id');
    
    if (!supplierId || !userId) {
        supplierSelected = false;
        productOptionsTemplate = '';
        clearAllItems();
        hideItemsTable();
        return;
    }
    
    // Show loading
    document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading supplier products...';
    document.getElementById('supplierMessage').style.display = 'block';
    document.getElementById('itemsTableContainer').style.display = 'none';
    
    // Fetch products for this supplier user (by user ID)
    fetch(`<?= base_url('supplier/user/') ?>${userId}/products-json`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products.length > 0) {
                supplierSelected = true;
                // Build options from supplier's own products with stock info
                let options = '';
                data.products.forEach(product => {
                    const price = product.price || 0;
                    const sku = product.sku || '';
                    const stock = product.stock || 0;
                    const stockLabel = stock > 0 ? ` [Stock: ${stock}]` : ' [Out of Stock]';
                    options += `<option value="${product.id}" data-price="${price}">${product.name}${sku ? ' (' + sku + ')' : ''} - ₱${parseFloat(price).toFixed(2)}${stockLabel}</option>`;
                });
                productOptionsTemplate = options;
                
                // Clear existing items and show table
                clearAllItems();
                showItemsTable();
                
                // Add first item row
                createItemRow();
            } else {
                // No products for this supplier
                supplierSelected = false;
                productOptionsTemplate = '';
                document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-exclamation-circle text-amber-500 mr-1"></i> This supplier has no products yet. Please select a different supplier.';
                document.getElementById('supplierMessage').style.display = 'block';
                document.getElementById('itemsTableContainer').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching supplier products:', error);
            supplierSelected = false;
            document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-exclamation-circle text-red-500 mr-1"></i> Error loading products. Please try again.';
            document.getElementById('supplierMessage').style.display = 'block';
            document.getElementById('itemsTableContainer').style.display = 'none';
        });
});

// Form validation
document.getElementById('poForm').addEventListener('submit', function(e) {
    const productSelects = document.querySelectorAll('#itemsBody .product-select');
    let hasValidProduct = false;
    
    productSelects.forEach(function(sel) {
        if (sel.value && sel.value !== '') {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        alert('Please add at least one product to the order.');
        return false;
    }
    
    if (!document.getElementById('supplier_id').value) {
        e.preventDefault();
        alert('Please select a supplier.');
        return false;
    }
});

calculateTotals();
</script>
<?= $this->endSection() ?>
