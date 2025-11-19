<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inter-Branch Transfers';
$title = 'Transfers';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inter-Branch Transfers</h4>
    <a href="<?= base_url('transfers/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Transfer
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Transfer Number</th>
                        <th>From Branch</th>
                        <th>To Branch</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transfers)): ?>
                        <?php foreach ($transfers as $transfer): ?>
                            <tr>
                                <td><?= esc($transfer['transfer_number']) ?></td>
                                <td><?= esc($transfer['from_branch_name']) ?></td>
                                <td><?= esc($transfer['to_branch_name']) ?></td>
                                <td><?= esc($transfer['requested_by_name']) ?></td>
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
                                <td><?= date('M d, Y', strtotime($transfer['request_date'])) ?></td>
                                <td>
                                    <a href="<?= base_url('transfers/view/' . $transfer['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if (($role == 'branch_manager' || $role == 'central_admin' || $role == 'central_admin') && $transfer['status'] == 'pending'): ?>
                                        <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/approve') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/reject') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x"></i> Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($transfer['status'] == 'approved'): ?>
                                        <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/complete') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Complete this transfer? Inventory will be updated.')">
                                                <i class="bi bi-check-circle"></i> Complete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No transfers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

