<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Purchase Request';
$title = 'Create Purchase Request';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">New Purchase Request</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('purchase-requests/store') ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <?php 
                    $role = session()->get('role');
                    $isAdmin = ($role === 'central_admin' || $role === 'central_admin');
                    ?>
                    <?php if ($isAdmin): ?>
                        <label>Branch</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= ($branch_id && $branch['id'] == $branch_id) ? 'selected' : '' ?>>
                                    <?= $branch['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <!-- For branch managers/inventory staff: branch is fixed, include hidden input -->
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <div class="mb-2"><strong>Branch:</strong> <?= array_values(array_filter($branches, fn($b) => $b['id'] == $branch_id))[0]['name'] ?? 'N/A' ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label>Supplier</label>
                    <select name="supplier_id" id="supplierSelect" class="form-select">
                        <option value="">-- Select Supplier --</option>
                        <?php if (!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $sup): ?>
                                <option value="<?= $sup['id'] ?>"><?= esc($sup['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <hr>
            <h6>Products</h6>
            <div id="productsContainer">
                <div class="row mb-2 product-row">
                    <div class="col-md-6">
                        <select name="products[]" class="form-select product-select" required>
                            <option value="">Select Product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>" data-price="<?= $product['cost_price'] ?>" data-supplier="<?= $product['supplier_id'] ?? '' ?>">
                                    <?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="quantities[]" class="form-control" placeholder="Quantity" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger remove-product">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="addProduct">
                <i class="bi bi-plus"></i> Add Product
            </button>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('purchase-requests') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('addProduct').addEventListener('click', function() {
    const container = document.getElementById('productsContainer');
    const newRow = container.querySelector('.product-row').cloneNode(true);
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('input[name="quantities[]"]').value = '';
    container.appendChild(newRow);
    // Apply supplier filter to newly added row
    applySupplierFilter();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
        const rows = document.querySelectorAll('.product-row');
        if (rows.length > 1) {
            e.target.closest('.product-row').remove();
        } else {
            alert('You must have at least one product');
        }
    }
});

// Filter products by selected supplier
const supplierSelect = document.getElementById('supplierSelect');
function applySupplierFilter() {
    const supplierId = supplierSelect ? supplierSelect.value : '';
    const productSelects = document.querySelectorAll('.product-select');

    productSelects.forEach(function(sel) {
        // keep current selection
        const current = sel.value;
        let hasVisible = false;
        Array.from(sel.options).forEach(function(opt) {
            const optSupplier = opt.getAttribute('data-supplier') || '';
            if (!supplierId) {
                opt.hidden = false;
                opt.disabled = false;
                hasVisible = hasVisible || opt.value !== '';
            } else {
                if (opt.value === '') {
                    opt.hidden = false;
                    opt.disabled = false;
                } else if (optSupplier === supplierId) {
                    opt.hidden = false;
                    opt.disabled = false;
                    hasVisible = true;
                } else {
                    opt.hidden = true;
                    opt.disabled = true;
                }
            }
        });

        // If the currently selected option is hidden, clear selection
        const selectedOpt = sel.querySelector('option:checked');
        if (selectedOpt && selectedOpt.hidden) {
            sel.value = '';
        }
    });
}

if (supplierSelect) {
    supplierSelect.addEventListener('change', applySupplierFilter);
    // apply on load
    applySupplierFilter();
}
</script>
<?= $this->endSection() ?>

