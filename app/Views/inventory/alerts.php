<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Stock Alerts';
$title = 'Stock Alerts';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Active Stock Alerts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Branch</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Alert Type</th>
                        <th>Current Quantity</th>
                        <th>Threshold</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alerts)): ?>
                        <?php foreach ($alerts as $alert): ?>
                            <tr>
                                <td><?= $alert['branch_name'] ?></td>
                                <td><?= $alert['product_name'] ?></td>
                                <td><?= $alert['sku'] ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $alert['alert_type'] == 'out_of_stock' ? 'danger' : 
                                        ($alert['alert_type'] == 'low_stock' ? 'warning' : 'info') 
                                    ?>">
                                        <?= ucfirst(str_replace('_', ' ', $alert['alert_type'])) ?>
                                    </span>
                                </td>
                                <td><?= $alert['current_quantity'] ?></td>
                                <td><?= $alert['threshold'] ?? '-' ?></td>
                                <td><?= $alert['expiry_date'] ?? '-' ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('inventory/alerts/' . $alert['id'] . '/acknowledge') ?>" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i> Acknowledge
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No active alerts</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

