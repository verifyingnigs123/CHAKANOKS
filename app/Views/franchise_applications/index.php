<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Franchise Applications';
$title = 'Franchise Applications';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Franchise Applications</h4>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('franchise-applications') ?>" id="filterForm" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone, or address..." value="<?= esc($search ?? '') ?>" onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('filterForm').submit(); }">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Status</option>
                    <option value="pending" <?= (isset($status) && $status == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="reviewing" <?= (isset($status) && $status == 'reviewing') ? 'selected' : '' ?>>Reviewing</option>
                    <option value="approved" <?= (isset($status) && $status == 'approved') ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= (isset($status) && $status == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #adb5bd;"></i>
                <p class="text-muted mt-3">No franchise applications found.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>#<?= $app['id'] ?></td>
                                <td><strong><?= esc($app['full_name']) ?></strong></td>
                                <td><?= esc($app['email']) ?></td>
                                <td><?= esc($app['phone_number']) ?></td>
                                <td><?= esc($app['address']) ?></td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'pending' => 'warning',
                                        'reviewing' => 'info',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                    $statusLabel = [
                                        'pending' => 'Pending',
                                        'reviewing' => 'Reviewing',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected'
                                    ];
                                    $badgeClass = $statusClass[$app['status']] ?? 'secondary';
                                    $label = $statusLabel[$app['status']] ?? ucfirst($app['status']);
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?>"><?= $label ?></span>
                                </td>
                                <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('franchise-applications/view/' . $app['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <?php if ($app['status'] == 'pending' || $app['status'] == 'reviewing'): ?>
                                            <form method="post" action="<?= base_url('franchise-applications/' . $app['id'] . '/approve') ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this application? An email will be sent to the applicant.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Approve
                                                </button>
                                            </form>
                                            <form method="post" action="<?= base_url('franchise-applications/' . $app['id'] . '/reject') ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this application? An email will be sent to the applicant.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

