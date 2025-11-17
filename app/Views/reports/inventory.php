<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Report';
$title = 'Inventory Report';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inventory Report</h4>
    <a href="<?= base_url('reports/inventory/export?' . http_build_query($_GET)) ?>" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('reports/inventory') ?>" class="row g-3">
            <div class="col-md-3">
                <label>Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= ($branchId == $branch['id']) ? 'selected' : '' ?>>
                            <?= esc($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat['category']) ?>" <?= ($category == $cat['category']) ? 'selected' : '' ?>>
                            <?= esc($cat['category']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo ?? '') ?>">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Generate Report</button>
                <a href="<?= base_url('reports/inventory') ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <div class="card stat-card primary">
            <div class="card-body">
                <h6 class="text-muted">Total Items</h6>
                <h3><?= number_format($totalItems) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted">Total Value</h6>
                <h3>₱<?= number_format($totalValue, 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card info">
            <div class="card-body">
                <h6 class="text-muted">Products</h6>
                <h3><?= count($inventory) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr>
                                <td><?= esc($item['branch_name'] ?? 'N/A') ?></td>
                                <td><?= esc($item['product_name']) ?></td>
                                <td><?= esc($item['sku']) ?></td>
                                <td><?= esc($item['category']) ?></td>
                                <td><?= number_format($item['quantity']) ?></td>
                                <td>₱<?= number_format($item['cost_price'], 2) ?></td>
                                <td>₱<?= number_format($item['quantity'] * $item['cost_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No inventory data found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

