<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Orders Report';
$title = 'Purchase Orders Report';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Purchase Orders Report</h4>
    <a href="<?= base_url('reports') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Reports
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('reports/purchase-orders') ?>" class="row g-3">
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft" <?= ($status == 'draft') ? 'selected' : '' ?>>Draft</option>
                    <option value="sent" <?= ($status == 'sent') ? 'selected' : '' ?>>Sent</option>
                    <option value="confirmed" <?= ($status == 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                    <option value="partial" <?= ($status == 'partial') ? 'selected' : '' ?>>Partial</option>
                    <option value="completed" <?= ($status == 'completed') ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
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
                <a href="<?= base_url('reports/purchase-orders') ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>Total Order Value: <strong>₱<?= number_format($totalAmount, 2) ?></strong></h5>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= esc($order['po_number']) ?></td>
                                <td><?= esc($order['supplier_name']) ?></td>
                                <td><?= esc($order['branch_name']) ?></td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'sent' => 'info',
                                        'confirmed' => 'primary',
                                        'partial' => 'warning',
                                        'completed' => 'success'
                                    ];
                                    $color = $statusColors[$order['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= ucfirst($order['status']) ?></span>
                                </td>
                                <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No purchase orders found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

