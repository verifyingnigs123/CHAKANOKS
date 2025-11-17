<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Wastage Report';
$title = 'Wastage Report';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Wastage Report</h4>
    <a href="<?= base_url('reports') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('reports/wastage') ?>" class="row g-3">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo ?? '') ?>">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Generate Report</button>
                <a href="<?= base_url('reports/wastage') ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="text-danger">Total Wastage Value: <strong>₱<?= number_format($totalWastage, 2) ?></strong></h5>
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
                        <th>Batch Number</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>Unit Cost</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($wastage)): ?>
                        <?php foreach ($wastage as $item): ?>
                            <tr>
                                <td><?= esc($item['branch_name'] ?? 'N/A') ?></td>
                                <td><?= esc($item['product_name']) ?></td>
                                <td><?= esc($item['sku']) ?></td>
                                <td><?= esc($item['batch_number'] ?? 'N/A') ?></td>
                                <td><?= $item['expiry_date'] ? date('M d, Y', strtotime($item['expiry_date'])) : 'N/A' ?></td>
                                <td><?= number_format($item['quantity']) ?></td>
                                <td>₱<?= number_format($item['cost_price'], 2) ?></td>
                                <td>₱<?= number_format($item['quantity'] * $item['cost_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No wastage data found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

