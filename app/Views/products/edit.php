<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Edit Product';
$title = 'Edit Product';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Product</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('products/update/' . $product['id']) ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label>SKU *</label>
                    <input type="text" name="sku" class="form-control" value="<?= $product['sku'] ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="form-control" value="<?= $product['barcode'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="<?= $product['category'] ?? '' ?>">
                </div>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= $product['description'] ?? '' ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Unit</label>
                    <select name="unit" class="form-select">
                        <option value="pcs" <?= ($product['unit'] == 'pcs') ? 'selected' : '' ?>>Pieces</option>
                        <option value="kg" <?= ($product['unit'] == 'kg') ? 'selected' : '' ?>>Kilogram</option>
                        <option value="g" <?= ($product['unit'] == 'g') ? 'selected' : '' ?>>Gram</option>
                        <option value="L" <?= ($product['unit'] == 'L') ? 'selected' : '' ?>>Liter</option>
                        <option value="mL" <?= ($product['unit'] == 'mL') ? 'selected' : '' ?>>Milliliter</option>
                        <option value="box" <?= ($product['unit'] == 'box') ? 'selected' : '' ?>>Box</option>
                        <option value="pack" <?= ($product['unit'] == 'pack') ? 'selected' : '' ?>>Pack</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Min Stock Level *</label>
                    <input type="number" name="min_stock_level" class="form-control" value="<?= $product['min_stock_level'] ?>" min="0" required>
                </div>
                <div class="col-md-3">
                    <label>Max Stock Level</label>
                    <input type="number" name="max_stock_level" class="form-control" value="<?= $product['max_stock_level'] ?? '' ?>" min="0">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= ($product['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($product['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Cost Price</label>
                    <input type="number" name="cost_price" class="form-control" step="0.01" min="0" value="<?= $product['cost_price'] ?>">
                </div>
                <div class="col-md-4">
                    <label>Selling Price</label>
                    <input type="number" name="selling_price" class="form-control" step="0.01" min="0" value="<?= $product['selling_price'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_perishable" class="form-check-input" value="1" <?= ($product['is_perishable']) ? 'checked' : '' ?>>
                        Is Perishable
                    </label>
                    <div class="mt-2">
                        <label>Shelf Life (Days)</label>
                        <input type="number" name="shelf_life_days" class="form-control" min="1" value="<?= $product['shelf_life_days'] ?? '' ?>">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('products') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

