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
            <!-- Approved Request Selection -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <label class="block text-sm font-medium text-amber-800 mb-2">
                    <i class="fas fa-file-alt mr-1"></i>Create from Approved Request <span class="text-red-500">*</span>
                </label>
                <select name="purchase_request_id" id="purchase_request_id" required class="w-full px-4 py-2.5 bg-white border border-amber-300 rounded-lg focus:border-amber-500 outline-none transition-all cursor-pointer">
                    <option value="">Select Approved Request</option>
                    <?php if (!empty($approved_requests)): ?>
                    <?php foreach ($approved_requests as $request): ?>
                    <option value="<?= $request['id'] ?>" data-branch-id="<?= $request['branch_id'] ?>"><?= $request['request_number'] ?> - <?= $request['branch_name'] ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <p class="text-xs text-amber-600 mt-2"><i class="fas fa-info-circle mr-1"></i>Items will be automatically loaded from the selected request</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['id'] ?>"><?= esc($supplier['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" id="payment_method" required class="w-full md:w-1/3 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="pending">Select Payment Method</option>
                    <option value="cod">Cash on Delivery (COD)</option>
                    <option value="paypal">PayPal</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Branch can change this when receiving delivery</p>
            </div>

            <!-- Order Items -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-lg font-semibold text-gray-800 flex items-center mb-4">
                    <i class="fas fa-list text-blue-500 mr-2"></i>Order Items
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase" style="width:35%">Product</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Quantity</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase" style="width:25%">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase" style="width:20%">Total</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-gray-100">
                            <tr id="noItemsRow">
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                    <p>Please select an approved purchase request to load items</p>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-600">Subtotal:</th>
                                <th id="subtotal" class="px-4 py-3 text-right text-sm font-medium text-gray-800">₱0.00</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-600">Tax (12%):</th>
                                <th id="tax" class="px-4 py-3 text-right text-sm font-medium text-gray-800">₱0.00</th>
                            </tr>
                            <tr class="border-t-2 border-gray-300">
                                <th colspan="3" class="px-4 py-3 text-right text-base font-bold text-gray-800">Total Amount:</th>
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
const productOptionsTemplate = `<?php 
    $options = '';
    foreach ($allProducts as $product) {
        $options .= '<option value="' . $product['id'] . '" data-price="' . ($product['cost_price'] ?? 0) . '">' . 
                    htmlspecialchars($product['name']) . ' (' . htmlspecialchars($product['sku']) . ')</option>';
    }
    echo $options;
?>`;

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
}

document.querySelectorAll('.item-row').forEach(row => attachEventListeners(row));

function createItemRow(productId = '', productName = '', sku = '', quantity = 1, unitPrice = 0) {
    const tbody = document.getElementById('itemsBody');
    const noItemsRow = document.getElementById('noItemsRow');
    if (noItemsRow) noItemsRow.style.display = 'none';
    
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td class="px-4 py-3">
            <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select" required>
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
    const rows = tbody.querySelectorAll('.item-row');
    rows.forEach(row => row.remove());
    const noItemsRow = document.getElementById('noItemsRow');
    if (noItemsRow) noItemsRow.style.display = '';
    calculateTotals();
}

document.getElementById('purchase_request_id').addEventListener('change', function() {
    const requestId = this.value;
    if (!requestId) {
        clearAllItems();
        document.getElementById('branch_id').value = '';
        return;
    }
    window.location.href = `<?= base_url('purchase-orders/create-from-request/') ?>${requestId}`;
});

calculateTotals();
</script>
<?= $this->endSection() ?>
