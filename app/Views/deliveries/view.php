<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Delivery Details';
$title = 'Delivery Details';
?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Delivery Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Delivery Number:</strong> <?= $delivery['delivery_number'] ?></p>
                <p><strong>PO Number:</strong> <?= $delivery['po_number'] ?></p>
                <p><strong>Supplier:</strong> <?= $delivery['supplier_name'] ?></p>
                <p><strong>Branch:</strong> <?= $delivery['branch_name'] ?></p>
                <p><strong>Scheduled Date:</strong> <?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : '-' ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?= 
                        $delivery['status'] == 'delivered' ? 'success' : 
                        ($delivery['status'] == 'in_transit' ? 'info' : 'warning') 
                    ?>">
                        <?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?>
                    </span>
                </p>
                <p><strong>Delivery Date:</strong> <?= $delivery['delivery_date'] ? date('M d, Y', strtotime($delivery['delivery_date'])) : '-' ?></p>
                <p><strong>Driver:</strong> <?= $delivery['driver_name'] ?? '-' ?></p>
                <p><strong>Vehicle:</strong> <?= $delivery['vehicle_number'] ?? '-' ?></p>
                <?php if ($delivery['received_by_name']): ?>
                    <p><strong>Received By:</strong> <?= $delivery['received_by_name'] ?></p>
                    <p><strong>Received At:</strong> <?= $delivery['received_at'] ? date('M d, Y H:i', strtotime($delivery['received_at'])) : '-' ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($delivery['notes']): ?>
            <div class="mt-3">
                <strong>Notes:</strong>
                <p class="text-muted"><?= $delivery['notes'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($delivery['status'] != 'delivered'): ?>
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Update Status</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label>New Status</label>
                    <select name="status" class="form-select" required>
                        <option value="scheduled" <?= ($delivery['status'] == 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                        <option value="in_transit" <?= ($delivery['status'] == 'in_transit') ? 'selected' : '' ?>>In Transit</option>
                        <option value="delivered" <?= ($delivery['status'] == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if ($delivery['status'] == 'in_transit' || $delivery['status'] == 'scheduled'): ?>
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Receive Delivery</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle"></i> <strong>Note:</strong> Inventory will only be updated when you receive this delivery. 
                Please enter the received quantities below and click "Receive Delivery" to update the inventory.
            </div>
            <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/receive') ?>">
                <?= csrf_field() ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Ordered Qty</th>
                                <th>Received Qty</th>
                                <th>Batch Number</th>
                                <th>Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($po_items as $index => $item): ?>
                                <tr>
                                    <td><?= $item['product_name'] ?></td>
                                    <td><?= $item['sku'] ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>
                                        <input type="hidden" name="products[]" value="<?= $item['product_id'] ?>">
                                        <input type="number" name="quantities[]" class="form-control" min="0" max="<?= $item['quantity'] ?>" value="<?= $item['quantity'] ?>" required>
                                    </td>
                                    <td>
                                        <input type="text" name="batch_numbers[]" class="form-control" placeholder="Optional">
                                    </td>
                                    <td>
                                        <input type="date" name="expiry_dates[]" class="form-control" placeholder="Optional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success mt-3">
                    <i class="bi bi-check-circle"></i> Receive Delivery & Update Inventory
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Order Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($po_items as $item): ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['sku'] ?></td>
                            <td><?= $item['unit'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                            <td>₱<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="<?= base_url('deliveries') ?>" class="btn btn-secondary">Back to List</a>
    <a href="<?= base_url('deliveries/print/' . $delivery['id']) ?>" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer"></i> Print
    </a>
</div>

<?= $this->endSection() ?>

