<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Product';
$title = 'Create Product';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">New Product</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('products/store') ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
                </div>
                <div class="col-md-6">
                    <label>SKU *</label>
                    <input type="text" name="sku" class="form-control" value="<?= old('sku') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="<?= old('barcode') ?>">
                </div>
                <div class="col-md-6">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="<?= old('category') ?>">
                </div>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Unit</label>
                    <select name="unit" class="form-select">
                        <option value="pcs" <?= (old('unit') == 'pcs' || !old('unit')) ? 'selected' : '' ?>>Pieces</option>
                        <option value="kg" <?= old('unit') == 'kg' ? 'selected' : '' ?>>Kilogram</option>
                        <option value="g" <?= old('unit') == 'g' ? 'selected' : '' ?>>Gram</option>
                        <option value="L" <?= old('unit') == 'L' ? 'selected' : '' ?>>Liter</option>
                        <option value="mL" <?= old('unit') == 'mL' ? 'selected' : '' ?>>Milliliter</option>
                        <option value="box" <?= old('unit') == 'box' ? 'selected' : '' ?>>Box</option>
                        <option value="pack" <?= old('unit') == 'pack' ? 'selected' : '' ?>>Pack</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Min Stock Level *</label>
                    <input type="number" name="min_stock_level" class="form-control" value="<?= old('min_stock_level', 10) ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label>Max Stock Level</label>
                    <input type="number" name="max_stock_level" class="form-control" value="<?= old('max_stock_level') ?>" min="0">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (old('status') == 'active' || !old('status')) ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Cost Price</label>
                    <input type="number" name="cost_price" class="form-control" step="0.01" min="0" value="<?= old('cost_price', 0) ?>">
                </div>
                <div class="col-md-4">
                    <label>Selling Price</label>
                    <input type="number" name="selling_price" class="form-control" step="0.01" min="0" value="<?= old('selling_price', 0) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_perishable" class="form-check-input" value="1" <?= old('is_perishable') ? 'checked' : '' ?>>
                        Is Perishable
                    </label>
                    <div class="mt-2">
                        <label>Shelf Life (Days)</label>
                        <input type="number" name="shelf_life_days" class="form-control" min="1" value="<?= old('shelf_life_days') ?>">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('products') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Product</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

