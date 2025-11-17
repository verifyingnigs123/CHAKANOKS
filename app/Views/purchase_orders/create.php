<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Purchase Order';
$title = 'Create Purchase Order';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create New Purchase Order</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('purchase-orders/store') ?>" id="poForm">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Supplier *</label>
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Branch *</label>
                    <select name="branch_id" id="branch_id" class="form-select" required>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>"><?= $branch['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" class="form-control" min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6">
                    <label>Create from Approved Request *</label>
                    <select name="purchase_request_id" id="purchase_request_id" class="form-select" required>
                        <option value="">Select Approved Request</option>
                        <?php if (!empty($approved_requests)): ?>
                            <?php foreach ($approved_requests as $request): ?>
                                <option value="<?= $request['id'] ?>" data-branch-id="<?= $request['branch_id'] ?>">
                                    <?= $request['request_number'] ?> - <?= $request['branch_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Items will be automatically loaded from the selected approved request.</small>
                </div>
            </div>

            <hr>
            <div class="mb-3">
                <h6 class="mb-0">Order Items</h6>
                <small class="text-muted">Items are loaded from the approved purchase request. Please select an approved request above.</small>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Product *</th>
                            <th style="width: 20%;">Quantity *</th>
                            <th style="width: 25%;">Unit Price *</th>
                            <th style="width: 20%;">Total</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr id="noItemsRow">
                            <td colspan="4" class="text-center text-muted py-4">
                                <em>Please select an approved purchase request to load items.</em>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Subtotal:</th>
                            <th id="subtotal">₱0.00</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Tax (12%):</th>
                            <th id="tax">₱0.00</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Total Amount:</th>
                            <th id="total-amount">₱0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes for this purchase order"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('purchase-orders') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Purchase Order</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Product options template for creating rows
const productOptionsTemplate = `<?php 
    $options = '';
    foreach ($allProducts as $product) {
        $options .= '<option value="' . $product['id'] . '" data-price="' . ($product['cost_price'] ?? 0) . '">' . 
                    htmlspecialchars($product['name']) . ' (' . htmlspecialchars($product['sku']) . ')</option>';
    }
    echo $options;
?>`;

// Calculate item total
function calculateItemTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = (qty * price).toFixed(2);
    row.querySelector('.item-total').textContent = '₱' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Calculate all totals
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

// Attach event listeners to a row
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

// Attach listeners to existing rows
document.querySelectorAll('.item-row').forEach(row => {
    attachEventListeners(row);
});

// Function to create a new item row with product options
function createItemRow(productId = '', productName = '', sku = '', quantity = 1, unitPrice = 0) {
    const tbody = document.getElementById('itemsBody');
    const noItemsRow = document.getElementById('noItemsRow');
    
    // Hide "no items" message
    if (noItemsRow) {
        noItemsRow.style.display = 'none';
    }
    
    // Create new row
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td>
            <select name="products[]" class="form-select product-select" required>
                <option value="">Select Product</option>
                ${productOptionsTemplate}
            </select>
        </td>
        <td>
            <input type="number" name="quantities[]" class="form-control quantity-input" min="1" step="1" value="${quantity}" required>
        </td>
        <td>
            <input type="number" name="unit_prices[]" class="form-control price-input" step="0.01" min="0" value="${unitPrice}" required>
        </td>
        <td class="item-total">₱0.00</td>
    `;
    
    const productSelect = newRow.querySelector('.product-select');
    if (productId) {
        productSelect.value = productId;
    }
    
    tbody.appendChild(newRow);
    attachEventListeners(newRow);
    calculateItemTotal(newRow);
    calculateTotals();
    
    return newRow;
}


// Function to clear all items
function clearAllItems() {
    const tbody = document.getElementById('itemsBody');
    const rows = tbody.querySelectorAll('.item-row');
    
    // Remove all item rows
    rows.forEach(row => row.remove());
    
    // Show "no items" message
    const noItemsRow = document.getElementById('noItemsRow');
    if (noItemsRow) {
        noItemsRow.style.display = '';
    }
    
    calculateTotals();
}

// Auto-fill branch and items when purchase request is selected
document.getElementById('purchase_request_id').addEventListener('change', function() {
    const requestId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    
    if (!requestId) {
        // Clear items if no request selected
        clearAllItems();
        document.getElementById('branch_id').value = '';
        // Remove hidden purchase_request_id input if exists
        const hiddenInput = document.querySelector('input[name="purchase_request_id"]');
        if (hiddenInput) {
            hiddenInput.remove();
        }
        return;
    }
    
    // Set branch
    const branchId = selectedOption.getAttribute('data-branch-id');
    document.getElementById('branch_id').value = branchId;
    
    // Add hidden input for purchase_request_id
    let hiddenInput = document.querySelector('input[name="purchase_request_id"]');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'purchase_request_id';
        document.getElementById('poForm').appendChild(hiddenInput);
    }
    hiddenInput.value = requestId;
    
    // Fetch request items via AJAX
    fetch(`<?= base_url('purchase-orders/get-request-items/') ?>${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.items) {
                // Clear existing items
                clearAllItems();
                
                // Create rows for all items from the request
                data.items.forEach((item) => {
                    createItemRow(
                        item.product_id,
                        item.product_name,
                        item.sku,
                        item.quantity,
                        item.unit_price || item.cost_price || 0
                    );
                });
                
                calculateTotals();
            } else {
                alert('Failed to load request items: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error fetching request items:', error);
            alert('Error loading request items. Please try again.');
        });
});

// Initial calculation
calculateTotals();
</script>
<?= $this->endSection() ?>

