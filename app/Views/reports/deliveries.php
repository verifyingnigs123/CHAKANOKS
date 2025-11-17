<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Deliveries Report';
$title = 'Deliveries Report';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Deliveries Report</h4>
    <a href="<?= base_url('reports') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('reports/deliveries') ?>" class="row g-3">
            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="scheduled" <?= ($status == 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                    <option value="in_transit" <?= ($status == 'in_transit') ? 'selected' : '' ?>>In Transit</option>
                    <option value="delivered" <?= ($status == 'delivered') ? 'selected' : '' ?>>Delivered</option>
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
                <a href="<?= base_url('reports/deliveries') ?>" class="btn btn-secondary">Clear</a>
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
                        <th>Delivery Number</th>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Scheduled Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($deliveries)): ?>
                        <?php foreach ($deliveries as $delivery): ?>
                            <tr>
                                <td><?= esc($delivery['delivery_number']) ?></td>
                                <td><?= esc($delivery['po_number']) ?></td>
                                <td><?= esc($delivery['supplier_name']) ?></td>
                                <td><?= esc($delivery['branch_name']) ?></td>
                                <td><?= esc($delivery['driver_name'] ?? 'N/A') ?></td>
                                <td><?= esc($delivery['vehicle_number'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'scheduled' => 'info',
                                        'in_transit' => 'primary',
                                        'delivered' => 'success'
                                    ];
                                    $color = $statusColors[$delivery['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?></span>
                                </td>
                                <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No deliveries found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

