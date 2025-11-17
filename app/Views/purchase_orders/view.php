<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Order Details';
$title = 'Purchase Order Details';
?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Purchase Order Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>PO Number:</strong> <?= $po['po_number'] ?></p>
                <p><strong>Supplier:</strong> <?= $po['supplier_name'] ?></p>
                <p><strong>Branch:</strong> <?= $po['branch_name'] ?></p>
                <p><strong>Order Date:</strong> <?= date('M d, Y', strtotime($po['order_date'])) ?></p>
                <p><strong>Expected Delivery:</strong> <?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : '-' ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?= 
                        $po['status'] == 'completed' ? 'success' : 
                        ($po['status'] == 'sent' ? 'info' : 'secondary') 
                    ?>">
                        <?= ucfirst($po['status']) ?>
                    </span>
                </p>
                <p><strong>Created By:</strong> <?= $po['created_by_name'] ?></p>
                <p><strong>Subtotal:</strong> ₱<?= number_format($po['subtotal'], 2) ?></p>
                <p><strong>Tax:</strong> ₱<?= number_format($po['tax'], 2) ?></p>
                <p><strong>Total Amount:</strong> <strong>₱<?= number_format($po['total_amount'], 2) ?></strong></p>
            </div>
        </div>
        <?php if ($po['notes']): ?>
            <div class="mt-3">
                <strong>Notes:</strong>
                <p class="text-muted"><?= $po['notes'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-3">
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
                        <th>Received</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal = 0;
                    foreach ($items as $item): 
                        $grandTotal += $item['total_price'];
                    ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['sku'] ?></td>
                            <td><?= $item['unit'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>
                                <span class="badge bg-<?= ($item['quantity_received'] == $item['quantity']) ? 'success' : 'warning' ?>">
                                    <?= $item['quantity_received'] ?> / <?= $item['quantity'] ?>
                                </span>
                            </td>
                            <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                            <td>₱<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Grand Total:</th>
                        <th>₱<?= number_format($grandTotal, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between">
    <div>
        <a href="<?= base_url('purchase-orders') ?>" class="btn btn-secondary">Back to List</a>
        <a href="<?= base_url('purchase-orders/print/' . $po['id']) ?>" target="_blank" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print
        </a>
    </div>
    <div>
        <?php if ($po['status'] == 'draft'): ?>
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/send') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-send"></i> Send to Supplier
                </button>
            </form>
        <?php endif; ?>
        <?php if ($po['status'] == 'sent'): ?>
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/confirm') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Mark as Confirmed
                </button>
            </form>
        <?php endif; ?>
        <?php if (in_array($po['status'], ['sent', 'confirmed']) && $role != 'supplier'): ?>
            <a href="<?= base_url('deliveries/create?po_id=' . $po['id']) ?>" class="btn btn-info">
                <i class="bi bi-truck"></i> Schedule Delivery
            </a>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

