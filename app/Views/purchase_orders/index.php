<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Orders';
$title = 'Purchase Orders';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Purchase Orders</h4>
    <?php $role = session()->get('role'); ?>
    <?php if ($role !== 'supplier' && $role !== 'branch_manager'): ?>
    <a href="<?= base_url('purchase-orders/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Purchase Order
    </a>
    <?php endif; ?>
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
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($purchase_orders)): ?>
                        <?php foreach ($purchase_orders as $po): ?>
                            <tr>
                                <td><strong><?= $po['po_number'] ?></strong></td>
                                <td><?= $po['supplier_name'] ?></td>
                                <td><?= $po['branch_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($po['order_date'])) ?></td>
                                <td><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : '-' ?></td>
                                <td>₱<?= number_format($po['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $po['status'] == 'completed' ? 'success' : 
                                        ($po['status'] == 'sent' ? 'info' : 
                                        ($po['status'] == 'confirmed' ? 'primary' : 'secondary')) 
                                    ?>">
                                        <?= ucfirst($po['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if (session()->get('role') === 'supplier' && session()->get('supplier_id') && $po['supplier_id'] == session()->get('supplier_id') && $po['status'] == 'confirmed'): ?>
                                        <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/prepare') ?>" class="d-inline ms-1">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="bi bi-check-circle"></i> Mark Prepared
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($po['status'] == 'draft'): ?>
                                        <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/send') ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-send"></i> Send
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No purchase orders found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

