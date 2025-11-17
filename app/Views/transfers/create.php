<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Transfer';
$title = 'Create Transfer';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Inter-Branch Transfer</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('transfers/store') ?>" id="transferForm">
            <?= csrf_field() ?>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>From Branch *</label>
                    <select name="from_branch_id" id="from_branch_id" class="form-select" required>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= ($from_branch_id == $branch['id']) ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>To Branch *</label>
                    <select name="to_branch_id" id="to_branch_id" class="form-select" required>
                        <option value="">Select Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Transfer Items</h6>
                <button type="button" class="btn btn-sm btn-success" id="addItemBtn">
                    <i class="bi bi-plus-circle"></i> Add Product
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Product *</th>
                            <th style="width: 30%;">Available Qty</th>
                            <th style="width: 30%;">Transfer Quantity *</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <tr class="item-row">
                            <td>
                                <select name="products[]" class="form-select product-select" required>
                                    <option value="">Select Product</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['id'] ?>" data-available="0">
                                            <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <span class="available-qty text-muted">-</span>
                            </td>
                            <td>
                                <input type="number" name="quantities[]" class="form-control quantity-input" min="1" step="1" value="1" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes for this transfer"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('transfers') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Transfer Request</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('from_branch_id').addEventListener('change', function() {
    updateAvailableQuantities();
});

document.querySelectorAll('.product-select').forEach(select => {
    select.addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

function updateAvailableQuantities() {
    document.querySelectorAll('.product-select').forEach(select => {
        updateAvailableQuantity(select);
    });
}

function updateAvailableQuantity(selectElement) {
    const fromBranchId = document.getElementById('from_branch_id').value;
    const productId = selectElement.value;
    const row = selectElement.closest('.item-row');
    const availableQtySpan = row.querySelector('.available-qty');
    
    if (!fromBranchId || !productId) {
        availableQtySpan.textContent = '-';
        return;
    }
    
    fetch(`<?= base_url('inventory/get-quantity') ?>?branch_id=${fromBranchId}&product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            availableQtySpan.textContent = data.quantity || 0;
            const quantityInput = row.querySelector('.quantity-input');
            quantityInput.max = data.quantity || 0;
        })
        .catch(() => {
            availableQtySpan.textContent = '-';
        });
}

document.getElementById('addItemBtn').addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const firstRow = tbody.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('.quantity-input').value = 1;
    newRow.querySelector('.available-qty').textContent = '-';
    
    tbody.appendChild(newRow);
    newRow.querySelector('.product-select').addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

updateAvailableQuantities();
</script>
<?= $this->endSection() ?>

