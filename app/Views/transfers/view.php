<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'View Transfer';
$title = 'Transfer Details';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Transfer Details: <?= esc($transfer['transfer_number']) ?></h5>
        <a href="<?= base_url('transfers') ?>" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Transfer Information</h6>
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Transfer Number:</th>
                        <td><?= esc($transfer['transfer_number']) ?></td>
                    </tr>
                    <tr>
                        <th>From Branch:</th>
                        <td><?= esc($transfer['from_branch_name']) ?></td>
                    </tr>
                    <tr>
                        <th>To Branch:</th>
                        <td><?= esc($transfer['to_branch_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Requested By:</th>
                        <td><?= esc($transfer['requested_by_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'in_transit' => 'primary',
                                'completed' => 'success',
                                'rejected' => 'danger',
                                'cancelled' => 'secondary'
                            ];
                            $color = $statusColors[$transfer['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= ucfirst($transfer['status']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Request Date:</th>
                        <td><?= date('M d, Y', strtotime($transfer['request_date'])) ?></td>
                    </tr>
                    <?php if ($transfer['approved_by_name']): ?>
                    <tr>
                        <th>Approved By:</th>
                        <td><?= esc($transfer['approved_by_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Approved At:</th>
                        <td><?= date('M d, Y H:i', strtotime($transfer['approved_at'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($transfer['completed_at']): ?>
                    <tr>
                        <th>Completed At:</th>
                        <td><?= date('M d, Y H:i', strtotime($transfer['completed_at'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($transfer['notes']): ?>
                    <tr>
                        <th>Notes:</th>
                        <td><?= esc($transfer['notes']) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <hr>

        <h6>Transfer Items</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc($item['product_name']) ?></td>
                                <td><?= esc($item['sku']) ?></td>
                                <td><?= esc($item['unit']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= $item['quantity_received'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No items found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (($role == 'branch_manager' || $role == 'central_admin' || $role == 'central_admin') && $transfer['status'] == 'pending'): ?>
        <div class="mt-3">
            <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/approve') ?>" class="d-inline">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check"></i> Approve Transfer
                </button>
            </form>
            <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/reject') ?>" class="d-inline">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-x"></i> Reject Transfer
                </button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($transfer['status'] == 'approved'): ?>
        <div class="mt-3">
            <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/complete') ?>" class="d-inline">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Complete this transfer? Inventory will be updated on both branches.')">
                    <i class="bi bi-check-circle"></i> Complete Transfer
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

