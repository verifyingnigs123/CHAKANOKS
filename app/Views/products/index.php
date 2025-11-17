<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Products';
$title = 'Products';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Products</h4>
    <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Product
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Category</th>
                        <th>Min Stock</th>
                        <th>Cost Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['sku'] ?></td>
                                <td><?= $product['barcode'] ?? '-' ?></td>
                                <td><?= $product['category'] ?? '-' ?></td>
                                <td><?= $product['min_stock_level'] ?></td>
                                <td>₱<?= number_format($product['cost_price'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $product['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($product['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('products/delete/' . $product['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

