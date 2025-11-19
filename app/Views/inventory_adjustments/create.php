<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Inventory Adjustment';
$title = 'New Adjustment';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Inventory Adjustment</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('inventory-adjustments/store') ?>" id="adjustmentForm">
            <?= csrf_field() ?>
            
            <?php if (isset($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product *</label>
                    <select name="product_id" id="product_id" class="form-select" required>
                        <option value="">Select Product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>" data-sku="<?= esc($product['sku']) ?>">
                                <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Branch *</label>
                    <select name="branch_id" id="branch_id" class="form-select" required <?= ($role !== 'central_admin' && $role !== 'central_admin' && $branchId) ? 'readonly' : '' ?>>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= ($branchId == $branch['id']) ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Current Stock</label>
                    <input type="text" id="current_stock" class="form-control" readonly placeholder="Select product and branch first">
                </div>
                <div class="col-md-4">
                    <label>Adjustment Type *</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="increase">Increase</option>
                        <option value="decrease">Decrease</option>
                        <option value="set">Set to Specific Amount</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Quantity *</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                    <small class="text-muted" id="quantityHelp">Enter quantity to adjust</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label>New Quantity (Preview)</label>
                    <input type="text" id="new_quantity_preview" class="form-control" readonly placeholder="Will be calculated">
                </div>
            </div>

            <div class="mb-3">
                <label>Reason for Adjustment *</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this adjustment is being made (e.g., 'Damaged goods', 'Stock count correction', 'Returned items')"></textarea>
                <small class="text-muted">Required for audit trail</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('inventory-adjustments') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Record Adjustment</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const branchSelect = document.getElementById('branch_id');
    const typeSelect = document.getElementById('type');
    const quantityInput = document.getElementById('quantity');
    const currentStockInput = document.getElementById('current_stock');
    const newQuantityPreview = document.getElementById('new_quantity_preview');
    const quantityHelp = document.getElementById('quantityHelp');

    function updatePreview() {
        const productId = productSelect.value;
        const branchId = branchSelect.value;
        const type = typeSelect.value;
        const quantity = parseInt(quantityInput.value) || 0;

        if (!productId || !branchId) {
            currentStockInput.value = '';
            newQuantityPreview.value = '';
            return;
        }

        // Fetch current stock
        fetch(`<?= base_url('inventory/get-quantity') ?>?branch_id=${branchId}&product_id=${productId}`)
            .then(response => response.json())
            .then(data => {
                const currentStock = data.quantity || 0;
                currentStockInput.value = currentStock;

                if (type && quantity > 0) {
                    let newQty = 0;
                    if (type === 'increase') {
                        newQty = currentStock + quantity;
                        quantityHelp.textContent = `Will add ${quantity} to current stock`;
                    } else if (type === 'decrease') {
                        newQty = Math.max(0, currentStock - quantity);
                        quantityHelp.textContent = `Will subtract ${quantity} from current stock`;
                    } else if (type === 'set') {
                        newQty = quantity;
                        quantityHelp.textContent = `Will set stock to ${quantity}`;
                    }
                    newQuantityPreview.value = newQty;
                } else {
                    newQuantityPreview.value = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                currentStockInput.value = 'Error loading';
            });
    }

    productSelect.addEventListener('change', updatePreview);
    branchSelect.addEventListener('change', updatePreview);
    typeSelect.addEventListener('change', function() {
        updatePreview();
        if (this.value === 'set') {
            quantityHelp.textContent = 'Enter the exact quantity to set';
        }
    });
    quantityInput.addEventListener('input', updatePreview);
});
</script>

<?= $this->endSection() ?>

