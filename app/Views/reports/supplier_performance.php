<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Supplier Performance Report';
$title = 'Supplier Performance';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Supplier Performance Report</h4>
    <a href="<?= base_url('reports') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('reports/supplier-performance') ?>" class="row g-3">
            <div class="col-md-4">
                <label>Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">All Suppliers</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['id'] ?>" <?= ($supplierId == $supplier['id']) ? 'selected' : '' ?>>
                            <?= esc($supplier['name']) ?>
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
                <a href="<?= base_url('reports/supplier-performance') ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Total Orders</th>
                        <th>Completed Orders</th>
                        <th>Completion Rate</th>
                        <th>Total Value</th>
                        <th>Avg Delivery Days</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($performance)): ?>
                        <?php foreach ($performance as $perf): ?>
                            <tr>
                                <td><?= esc($perf['name']) ?></td>
                                <td><?= $perf['total_orders'] ?></td>
                                <td><?= $perf['completed_orders'] ?></td>
                                <td>
                                    <?php
                                    $rate = $perf['total_orders'] > 0 ? ($perf['completed_orders'] / $perf['total_orders'] * 100) : 0;
                                    $rateColor = $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger');
                                    ?>
                                    <span class="badge bg-<?= $rateColor ?>"><?= number_format($rate, 1) ?>%</span>
                                </td>
                                <td>₱<?= number_format($perf['total_value'] ?? 0, 2) ?></td>
                                <td><?= $perf['avg_delivery_days'] ? number_format($perf['avg_delivery_days'], 1) : 'N/A' ?> days</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No supplier performance data found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

