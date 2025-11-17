<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Dashboard';
$title = 'Dashboard';
?>

<div class="row mb-4">
    <?php if (in_array($role, ['central_admin', 'system_admin'])): ?>
        <!-- Central Admin Dashboard -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total Branches</h6>
                            <h3 class="mb-0"><?= $total_branches ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-building fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total Products</h6>
                            <h3 class="mb-0"><?= $total_products ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-tags fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Pending Requests</h6>
                            <h3 class="mb-0"><?= $pending_requests ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cart-plus fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Active Alerts</h6>
                            <h3 class="mb-0"><?= $active_alerts ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Total Suppliers</h6>
                            <h3 class="mb-0"><?= $total_suppliers ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Pending Orders</h6>
                            <h3 class="mb-0"><?= $pending_orders ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-file-earmark-text fs-1 text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">In Transit</h6>
                            <h3 class="mb-0"><?= $in_transit_deliveries ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($role == 'branch_manager'): ?>
        <!-- Branch Manager Dashboard -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Inventory Items</h6>
                            <h3 class="mb-0"><?= $branch_inventory ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-boxes fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Low Stock Items</h6>
                            <h3 class="mb-0"><?= $low_stock_items ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Pending Requests</h6>
                            <h3 class="mb-0"><?= $pending_requests ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cart-plus fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Inventory Staff Dashboard -->
        <div class="col-md-6 mb-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Inventory Items</h6>
                            <h3 class="mb-0"><?= $branch_inventory ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-boxes fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Active Alerts</h6>
                            <h3 class="mb-0"><?= $active_alerts ?? 0 ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (in_array($role, ['central_admin', 'system_admin'])): ?>
    <!-- Branch Inventory Report -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Branch Inventory Summary</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Total Items</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($branch_inventory_summary)): ?>
                                    <?php foreach ($branch_inventory_summary as $summary): ?>
                                        <tr>
                                            <td><?= $summary['branch_name'] ?></td>
                                            <td><?= $summary['total_items'] ?></td>
                                            <td>₱<?= number_format($summary['total_value'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Performance Report -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Supplier Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Total Orders</th>
                                    <th>Completed</th>
                                    <th>Completion Rate</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($supplier_performance)): ?>
                                    <?php foreach ($supplier_performance as $perf): ?>
                                        <tr>
                                            <td><?= $perf['supplier_name'] ?></td>
                                            <td><?= $perf['total_orders'] ?></td>
                                            <td><?= $perf['completed_orders'] ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= $perf['completion_rate'] ?>%">
                                                        <?= number_format($perf['completion_rate'], 1) ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>₱<?= number_format($perf['total_value'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Purchase Orders</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_orders)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_orders as $order): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><?= $order['po_number'] ?></span>
                                    <span class="badge bg-secondary"><?= ucfirst($order['status']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No recent orders</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Deliveries</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_deliveries)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recent_deliveries as $delivery): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span><?= $delivery['delivery_number'] ?></span>
                                    <span class="badge bg-<?= $delivery['status'] == 'delivered' ? 'success' : 'warning' ?>">
                                        <?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No recent deliveries</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('inventory') ?>" class="btn btn-outline-primary w-100">
                            <i class="bi bi-boxes"></i> View Inventory
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('purchase-requests/create') ?>" class="btn btn-outline-success w-100">
                            <i class="bi bi-cart-plus"></i> Create Purchase Request
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('inventory/alerts') ?>" class="btn btn-outline-warning w-100">
                            <i class="bi bi-exclamation-triangle"></i> View Alerts
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?= base_url('products') ?>" class="btn btn-outline-info w-100">
                            <i class="bi bi-tags"></i> Manage Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

