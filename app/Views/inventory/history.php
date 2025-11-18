<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory History';
$title = 'Inventory History';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inventory History</h4>
    <a href="<?= base_url('inventory' . ($current_branch_id ? '?branch_id=' . $current_branch_id : '')) ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Inventory
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('inventory/history') ?>" class="row g-3">
            <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
            <div class="col-md-4">
                <label>Branch</label>
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                            <?= $branch['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-4">
                <label>Product</label>
                <select name="product_id" class="form-select">
                    <option value="">All Products</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>" <?= ($current_product_id == $product['id']) ? 'selected' : '' ?>>
                            <?= $product['name'] ?> (<?= $product['sku'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
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
                        <th>Date & Time</th>
                        <?php if (!$current_branch_id && ($role == 'central_admin' || $role == 'system_admin')): ?>
                        <th>Branch</th>
                        <?php endif; ?>
                        <th>Product</th>
                        <th>Purchase Order</th>
                        <th>Delivery</th>
                        <th>Previous Qty</th>
                        <th>Quantity Added</th>
                        <th>New Quantity</th>
                        <th>Received By</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $item): ?>
                            <tr>
                                <td><?= date('M d, Y h:i A', strtotime($item['created_at'])) ?></td>
                                <?php if (!$current_branch_id && ($role == 'central_admin' || $role == 'system_admin')): ?>
                                <td>
                                    <span class="badge bg-info"><?= esc($item['branch_name']) ?></span>
                                </td>
                                <?php endif; ?>
                                <td>
                                    <strong><?= esc($item['product_name']) ?></strong><br>
                                    <small class="text-muted">SKU: <?= esc($item['sku']) ?></small>
                                </td>
                                <td>
                                    <?php if ($item['po_number']): ?>
                                        <a href="<?= base_url('purchase-orders/view/' . $item['purchase_order_id']) ?>" class="text-decoration-none">
                                            <?= esc($item['po_number']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item['delivery_number']): ?>
                                        <a href="<?= base_url('deliveries/view/' . $item['delivery_id']) ?>" class="text-decoration-none">
                                            <?= esc($item['delivery_number']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item['previous_quantity'] ?></td>
                                <td>
                                    <span class="badge bg-success">+<?= $item['quantity_added'] ?></span>
                                </td>
                                <td>
                                    <strong><?= $item['new_quantity'] ?></strong>
                                </td>
                                <td><?= esc($item['received_by_name'] ?? 'System') ?></td>
                                <td>
                                    <?php
                                    $typeBadge = [
                                        'delivery_received' => 'bg-primary',
                                        'transfer_in' => 'bg-info',
                                        'adjustment' => 'bg-warning',
                                        'manual_update' => 'bg-secondary'
                                    ];
                                    $typeLabel = [
                                        'delivery_received' => 'Delivery',
                                        'transfer_in' => 'Transfer',
                                        'adjustment' => 'Adjustment',
                                        'manual_update' => 'Manual'
                                    ];
                                    $badgeClass = $typeBadge[$item['transaction_type']] ?? 'bg-secondary';
                                    $label = $typeLabel[$item['transaction_type']] ?? ucfirst($item['transaction_type']);
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $label ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= (!$current_branch_id && ($role == 'central_admin' || $role == 'system_admin')) ? '10' : '9' ?>" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No inventory history found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

