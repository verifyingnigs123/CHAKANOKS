<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Deliveries';
$title = 'Deliveries';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Deliveries</h4>
    <a href="<?= base_url('deliveries/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Schedule Delivery
    </a>
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
                        <th>Scheduled Date</th>
                        <th>Delivery Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($deliveries)): ?>
                        <?php foreach ($deliveries as $delivery): ?>
                            <tr>
                                <td><strong><?= $delivery['delivery_number'] ?></strong></td>
                                <td><?= $delivery['po_number'] ?></td>
                                <td><?= $delivery['supplier_name'] ?></td>
                                <td><?= $delivery['branch_name'] ?></td>
                                <td><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : '-' ?></td>
                                <td><?= $delivery['delivery_date'] ? date('M d, Y', strtotime($delivery['delivery_date'])) : '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $delivery['status'] == 'delivered' ? 'success' : 
                                        ($delivery['status'] == 'in_transit' ? 'info' : 
                                        ($delivery['status'] == 'scheduled' ? 'warning' : 'secondary')) 
                                    ?>">
                                        <?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('deliveries/view/' . $delivery['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
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

