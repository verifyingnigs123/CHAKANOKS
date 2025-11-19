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
                    <label>Branch</label>
                    <?php 
                    $role = session()->get('role');
                    $isAdmin = ($role === 'central_admin' || $role === 'central_admin');
                    ?>
                    <select name="branch_id" class="form-select" required <?= ($branch_id && !$isAdmin) ? 'disabled' : '' ?>>
                        <?php if ($branch_id && !$isAdmin): ?>
                            <?php foreach ($branches as $branch): ?>
                                <?php if ($branch['id'] == $branch_id): ?>
                                    <option value="<?= $branch['id'] ?>" selected><?= $branch['name'] ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <!-- Hidden input to submit the branch_id when disabled -->
                            <input type="hidden" name="branch_id" value="<?= $branch_id ?>">
                        <?php else: ?>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= ($branch_id && $branch['id'] == $branch_id) ? 'selected' : '' ?>>
                                    <?= $branch['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if ($branch_id && !$isAdmin): ?>
                        <small class="text-muted">You can only create requests for your assigned branch.</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label>Priority</label>
                    <select name="priority" class="form-select" required>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                        <option value="low">Low</option>
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
                                <option value="<?= $product['id'] ?>" data-price="<?= $product['cost_price'] ?>">
                                    <?= $product['name'] ?> (<?= $product['sku'] ?>)
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
</script>
<?= $this->endSection() ?>

