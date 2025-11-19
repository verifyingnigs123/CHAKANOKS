<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Dashboard';
$title = 'Dashboard';
?>

<div class="row mb-4">
    <?php if (in_array($role, ['central_admin', 'central_admin'])): ?>
        <!-- Central Admin Dashboard -->
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('branches') ?>" class="text-decoration-none">
                <div class="card stat-card primary clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('products') ?>" class="text-decoration-none">
                <div class="card stat-card success clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('purchase-requests') ?>" class="text-decoration-none">
                <div class="card stat-card warning clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('inventory/alerts') ?>" class="text-decoration-none">
                <div class="card stat-card danger clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('suppliers') ?>" class="text-decoration-none">
                <div class="card stat-card info clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('purchase-orders') ?>" class="text-decoration-none">
                <div class="card stat-card secondary clickable-card">
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
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('deliveries') ?>" class="text-decoration-none">
                <div class="card stat-card success clickable-card">
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
            </a>
        </div>
    <?php elseif ($role == 'branch_manager'): ?>
        <!-- Branch Manager Dashboard -->
        <!-- First Row: Key Metrics -->
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('inventory') ?>" class="text-decoration-none">
                <div class="card stat-card primary clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Inventory Items</h6>
                                <h3 class="mb-0"><?= $branch_inventory ?? 0 ?></h3>
                                <small class="text-muted">Total Products</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-boxes fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('inventory/alerts') ?>" class="text-decoration-none">
                <div class="card stat-card warning clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Low Stock Items</h6>
                                <h3 class="mb-0"><?= $low_stock_items ?? 0 ?></h3>
                                <small class="text-muted">Needs Attention</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('purchase-requests') ?>" class="text-decoration-none">
                <div class="card stat-card success clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Pending Requests</h6>
                                <h3 class="mb-0"><?= $pending_requests ?? 0 ?></h3>
                                <small class="text-muted">Awaiting Approval</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-cart-plus fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('transfers') ?>" class="text-decoration-none">
                <div class="card stat-card info clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Pending Transfers</h6>
                                <h3 class="mb-0"><?= $pending_transfers ?? 0 ?></h3>
                                <small class="text-muted">Incoming/Outgoing</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-arrow-left-right fs-1 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Second Row: Additional Metrics -->
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('transfers') ?>" class="text-decoration-none">
                <div class="card stat-card secondary clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Pending Approvals</h6>
                                <h3 class="mb-0"><?= $pending_approvals ?? 0 ?></h3>
                                <small class="text-muted">Requires Action</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('deliveries') ?>" class="text-decoration-none">
                <div class="card stat-card danger clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">In Transit</h6>
                                <h3 class="mb-0"><?= $in_transit_deliveries ?? 0 ?></h3>
                                <small class="text-muted">Deliveries</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-truck fs-1 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="<?= base_url('deliveries') ?>" class="text-decoration-none">
                <div class="card stat-card warning clickable-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted mb-1">Scheduled</h6>
                                <h3 class="mb-0"><?= $scheduled_deliveries ?? 0 ?></h3>
                                <small class="text-muted">Upcoming</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-event fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card success clickable-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Inventory Value</h6>
                            <h3 class="mb-0">₱<?= number_format($inventory_value ?? 0, 2) ?></h3>
                            <small class="text-muted">Total Worth</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Approved Purchase Requests (Assigned to You)</h5></div>
                    <div class="card-body">
                        <?php if (!empty($approved_requests_for_supplier)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($approved_requests_for_supplier as $r): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= esc($r['request_number']) ?></strong>
                                            <br><small class="text-muted"><?= $r['approved_at'] ? date('M d, Y H:i', strtotime($r['approved_at'])) : date('M d, Y', strtotime($r['created_at'])) ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('purchase-requests/view/'.$r['id']) ?>" class="btn btn-sm btn-outline-primary me-2">View</a>
                                            <a href="<?= base_url('purchase-orders/create-from-request/'.$r['id']) ?>" class="btn btn-sm btn-success">Create PO</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No approved purchase requests assigned to you.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#purchaseRequestsChartCollapse" aria-expanded="false" aria-controls="purchaseRequestsChartCollapse">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            <span>Purchase Requests (Last 7 Days)</span>
                            <i class="bi bi-chevron-down"></i>
                        </h5>
                    </div>
                    <div class="collapse" id="purchaseRequestsChartCollapse">
                        <div class="card-body">
                            <canvas id="purchaseRequestsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#transfersChartCollapse" aria-expanded="false" aria-controls="transfersChartCollapse">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            <span>Transfer Status</span>
                            <i class="bi bi-chevron-down"></i>
                        </h5>
                    </div>
                    <div class="collapse" id="transfersChartCollapse">
                        <div class="card-body">
                            <canvas id="transfersChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Purchase Requests</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_requests)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recent_requests as $request): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $request['request_number'] ?? 'N/A' ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($request['created_at'])) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $request['status'] == 'approved' ? 'success' : ($request['status'] == 'rejected' ? 'danger' : 'warning') ?>">
                                            <?= ucfirst($request['status']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No recent purchase requests</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Transfers</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_transfers)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recent_transfers as $transfer): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $transfer['transfer_number'] ?? 'N/A' ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($transfer['created_at'])) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $transfer['status'] == 'completed' ? 'success' : ($transfer['status'] == 'rejected' ? 'danger' : ($transfer['status'] == 'approved' ? 'info' : 'warning')) ?>">
                                            <?= ucfirst($transfer['status']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No recent transfers</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Deliveries</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_deliveries)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recent_deliveries as $delivery): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $delivery['delivery_number'] ?? 'N/A' ?></strong>
                                            <br>
                                            <small class="text-muted"><?= date('M d, Y', strtotime($delivery['created_at'])) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $delivery['status'] == 'delivered' ? 'success' : ($delivery['status'] == 'in_transit' ? 'info' : 'warning') ?>">
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
    <?php else: ?>
        <!-- Inventory Staff Dashboard -->
        <div class="col-md-6 mb-3">
            <a href="<?= base_url('inventory') ?>" class="text-decoration-none">
                <div class="card stat-card primary clickable-card">
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
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="<?= base_url('inventory/alerts') ?>" class="text-decoration-none">
                <div class="card stat-card danger clickable-card">
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
            </a>
        </div>
    <?php endif; ?>

    <?php if ($role == 'supplier'): ?>
        <!-- Supplier Dashboard -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Approved Orders Waiting Preparation</h5></div>
                    <div class="card-body">
                        <?php if (!empty($waiting_preparation)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($waiting_preparation as $po): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $po['po_number'] ?></strong>
                                            <br><small class="text-muted"><?= date('M d, Y', strtotime($po['order_date'])) ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No orders waiting preparation</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Orders Being Prepared</h5></div>
                    <div class="card-body">
                        <?php if (!empty($being_prepared)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($being_prepared as $po): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $po['po_number'] ?></strong>
                                            <br><small class="text-muted">Prepared: <?= $po['prepared_at'] ? date('M d, Y H:i', strtotime($po['prepared_at'])) : 'N/A' ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No active preparations</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Completed & Shipped</h5></div>
                    <div class="card-body">
                        <?php if (!empty($completed_orders)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($completed_orders as $po): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $po['po_number'] ?></strong>
                                            <br><small class="text-muted">Completed: <?= $po['updated_at'] ? date('M d, Y', strtotime($po['updated_at'])) : 'N/A' ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No completed orders</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Recent Notifications</h5></div>
                    <div class="card-body">
                        <?php if (!empty($notifications)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($notifications as $note): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $note['title'] ?></strong>
                                            <br><small class="text-muted"><?= date('M d, Y H:i', strtotime($note['created_at'])) ?></small>
                                            <div class="text-muted"><?= $note['message'] ?></div>
                                        </div>
                                        <div>
                                            <a href="<?= $note['link'] ?? '#' ?>" class="btn btn-sm btn-primary">Open</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No notifications</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($role == 'logistics_coordinator'): ?>
        <!-- Logistics Dashboard -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Orders Ready for Shipment</h5></div>
                    <div class="card-body">
                        <?php if (!empty($ready_for_shipment)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($ready_for_shipment as $po): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $po['po_number'] ?></strong>
                                            <br><small class="text-muted">Prepared: <?= $po['prepared_at'] ? date('M d, Y H:i', strtotime($po['prepared_at'])) : 'N/A' ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('purchase-orders/view/'.$po['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No orders ready for shipment</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Shipment Schedules</h5></div>
                    <div class="card-body">
                        <?php if (!empty($shipment_schedules)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($shipment_schedules as $d): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $d['delivery_number'] ?></strong>
                                            <br><small class="text-muted">Scheduled: <?= $d['scheduled_date'] ?? 'N/A' ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('deliveries/view/'.$d['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No scheduled shipments</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Active Deliveries</h5></div>
                    <div class="card-body">
                        <?php if (!empty($active_deliveries)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($active_deliveries as $d): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $d['delivery_number'] ?></strong>
                                            <br><small class="text-muted">Status: <?= ucfirst(str_replace('_', ' ', $d['status'])) ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('deliveries/view/'.$d['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No active deliveries</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Delivery Completion History</h5></div>
                    <div class="card-body">
                        <?php if (!empty($delivery_history)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($delivery_history as $d): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= $d['delivery_number'] ?></strong>
                                            <br><small class="text-muted">Delivered: <?= $d['delivery_date'] ?? 'N/A' ?></small>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('deliveries/view/'.$d['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No completed deliveries</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (in_array($role, ['central_admin', 'central_admin'])): ?>
    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#purchaseOrdersChartCollapse" aria-expanded="false" aria-controls="purchaseOrdersChartCollapse">
                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>Purchase Orders (Last 7 Days)</span>
                        <i class="bi bi-chevron-down"></i>
                    </h5>
                </div>
                <div class="collapse" id="purchaseOrdersChartCollapse">
                    <div class="card-body">
                        <canvas id="purchaseOrdersChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#inventoryValueChartCollapse" aria-expanded="false" aria-controls="inventoryValueChartCollapse">
                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>Inventory Value by Branch</span>
                        <i class="bi bi-chevron-down"></i>
                    </h5>
                </div>
                <div class="collapse" id="inventoryValueChartCollapse">
                    <div class="card-body">
                        <canvas id="inventoryValueChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#deliveriesChartCollapse" aria-expanded="false" aria-controls="deliveriesChartCollapse">
                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>Delivery Status</span>
                        <i class="bi bi-chevron-down"></i>
                    </h5>
                </div>
                <div class="collapse" id="deliveriesChartCollapse">
                    <div class="card-body">
                        <canvas id="deliveriesChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#supplierChartCollapse" aria-expanded="false" aria-controls="supplierChartCollapse">
                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                        <span>Supplier Performance</span>
                        <i class="bi bi-chevron-down"></i>
                    </h5>
                </div>
                <div class="collapse" id="supplierChartCollapse">
                    <div class="card-body">
                        <canvas id="supplierChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (in_array($role, ['central_admin', 'central_admin'])): ?>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
<?php if ($role == 'branch_manager' && isset($purchase_requests_chart)): ?>
// Branch Manager Charts
let purchaseRequestsChart = null;
let transfersChart = null;

// Function to initialize Purchase Requests Chart
function initPurchaseRequestsChart() {
    if (purchaseRequestsChart) return;
    const ctx = document.getElementById('purchaseRequestsChart');
    if (ctx) {
        purchaseRequestsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($purchase_requests_chart['labels']) ?>,
                datasets: [{
                    label: 'Purchase Requests',
                    data: <?= json_encode($purchase_requests_chart['data']) ?>,
                    borderColor: 'rgb(25, 135, 84)',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
}

// Function to initialize Transfers Chart
function initTransfersChart() {
    if (transfersChart) return;
    const ctx = document.getElementById('transfersChart');
    if (ctx) {
        transfersChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($transfers_chart['labels']) ?>,
                datasets: [{
                    data: <?= json_encode($transfers_chart['data']) ?>,
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Handle collapse events for branch manager charts
document.addEventListener('DOMContentLoaded', function() {
    const prCollapse = document.getElementById('purchaseRequestsChartCollapse');
    if (prCollapse) {
        prCollapse.addEventListener('shown.bs.collapse', function() {
            initPurchaseRequestsChart();
        });
    }
    
    const trCollapse = document.getElementById('transfersChartCollapse');
    if (trCollapse) {
        trCollapse.addEventListener('shown.bs.collapse', function() {
            initTransfersChart();
        });
    }
    
    // Rotate chevron icons on collapse/expand
    const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const icon = this.querySelector('i.bi-chevron-down, i.bi-chevron-up');
            if (icon) {
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                if (target) {
                    setTimeout(function() {
                        if (target.classList.contains('show')) {
                            icon.classList.remove('bi-chevron-down');
                            icon.classList.add('bi-chevron-up');
                        } else {
                            icon.classList.remove('bi-chevron-up');
                            icon.classList.add('bi-chevron-down');
                        }
                    }, 100);
                }
            }
        });
    });
});
<?php elseif (in_array($role, ['central_admin', 'central_admin']) && isset($purchase_orders_chart)): ?>
// Store chart instances
let purchaseOrdersChart = null;
let inventoryValueChart = null;
let deliveriesChart = null;
let supplierChart = null;

// Function to initialize Purchase Orders Chart
function initPurchaseOrdersChart() {
    if (purchaseOrdersChart) return; // Already initialized
    const poCtx = document.getElementById('purchaseOrdersChart');
    if (poCtx) {
        purchaseOrdersChart = new Chart(poCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($purchase_orders_chart['labels']) ?>,
                datasets: [{
                    label: 'Purchase Orders',
                    data: <?= json_encode($purchase_orders_chart['data']) ?>,
                    borderColor: 'rgb(13, 110, 253)',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
}

// Function to initialize Inventory Value Chart
function initInventoryValueChart() {
    if (inventoryValueChart) return; // Already initialized
    const invCtx = document.getElementById('inventoryValueChart');
    if (invCtx) {
        inventoryValueChart = new Chart(invCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($inventory_value_chart['labels']) ?>,
                datasets: [{
                    label: 'Inventory Value (₱)',
                    data: <?= json_encode($inventory_value_chart['data']) ?>,
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Value: ₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
}

// Function to initialize Deliveries Chart
function initDeliveriesChart() {
    if (deliveriesChart) return; // Already initialized
    const delCtx = document.getElementById('deliveriesChart');
    if (delCtx) {
        deliveriesChart = new Chart(delCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($deliveries_chart['labels']) ?>,
                datasets: [{
                    data: <?= json_encode($deliveries_chart['data']) ?>,
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Function to initialize Supplier Performance Chart
function initSupplierChart() {
    if (supplierChart) return; // Already initialized
    const supCtx = document.getElementById('supplierChart');
    if (supCtx) {
        const supplierData = <?= json_encode($supplier_performance ?? []) ?>;
        const supplierLabels = supplierData.map(s => s.supplier_name);
        const completionRates = supplierData.map(s => s.completion_rate.toFixed(1));
        
        supplierChart = new Chart(supCtx, {
            type: 'bar',
            data: {
                labels: supplierLabels,
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: completionRates,
                    backgroundColor: 'rgba(25, 135, 84, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Completion Rate: ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }
}

// Handle collapse events and initialize charts when expanded
document.addEventListener('DOMContentLoaded', function() {
    // Purchase Orders Chart
    const poCollapse = document.getElementById('purchaseOrdersChartCollapse');
    if (poCollapse) {
        poCollapse.addEventListener('shown.bs.collapse', function() {
            initPurchaseOrdersChart();
        });
    }
    
    // Inventory Value Chart
    const invCollapse = document.getElementById('inventoryValueChartCollapse');
    if (invCollapse) {
        invCollapse.addEventListener('shown.bs.collapse', function() {
            initInventoryValueChart();
        });
    }
    
    // Deliveries Chart
    const delCollapse = document.getElementById('deliveriesChartCollapse');
    if (delCollapse) {
        delCollapse.addEventListener('shown.bs.collapse', function() {
            initDeliveriesChart();
        });
    }
    
    // Supplier Chart
    const supCollapse = document.getElementById('supplierChartCollapse');
    if (supCollapse) {
        supCollapse.addEventListener('shown.bs.collapse', function() {
            initSupplierChart();
        });
    }
    
    // Rotate chevron icons on collapse/expand
    const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseElements.forEach(function(element) {
        element.addEventListener('click', function() {
            const icon = this.querySelector('i.bi-chevron-down, i.bi-chevron-up');
            if (icon) {
                const target = document.querySelector(this.getAttribute('data-bs-target'));
                if (target) {
                    setTimeout(function() {
                        if (target.classList.contains('show')) {
                            icon.classList.remove('bi-chevron-down');
                            icon.classList.add('bi-chevron-up');
                        } else {
                            icon.classList.remove('bi-chevron-up');
                            icon.classList.add('bi-chevron-down');
                        }
                    }, 100);
                }
            }
        });
    });
});
<?php endif; ?>
</script>
<style>
[data-bs-toggle="collapse"] i {
    transition: transform 0.3s ease;
}

.clickable-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.clickable-card:active {
    transform: translateY(-2px);
}
</style>
<?= $this->endSection() ?>

