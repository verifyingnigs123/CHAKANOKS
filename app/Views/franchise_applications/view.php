<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'View Franchise Application';
$title = 'View Franchise Application';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Franchise Application Details</h4>
    <a href="<?= base_url('franchise-applications') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Application #<?= $application['id'] ?></h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Full Name</h6>
                <p class="fs-5"><strong><?= esc($application['full_name']) ?></strong></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Email Address</h6>
                <p class="fs-5"><?= esc($application['email']) ?></p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Phone Number</h6>
                <p class="fs-5"><?= esc($application['phone_number']) ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Status</h6>
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
                $badgeClass = $statusClass[$application['status']] ?? 'secondary';
                $label = $statusLabel[$application['status']] ?? ucfirst($application['status']);
                ?>
                <p><span class="badge bg-<?= $badgeClass ?> fs-6"><?= $label ?></span></p>
            </div>
        </div>
        
        <div class="mb-4">
            <h6 class="text-muted mb-2">Address / Location of Proposed Branch</h6>
            <p class="fs-5"><?= esc($application['address']) ?></p>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Submitted On</h6>
                <p><?= date('F d, Y h:i A', strtotime($application['created_at'])) ?></p>
            </div>
            <?php if ($application['updated_at']): ?>
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Last Updated</h6>
                <p><?= date('F d, Y h:i A', strtotime($application['updated_at'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($application['notes']): ?>
        <div class="mb-4">
            <h6 class="text-muted mb-2">Admin Notes</h6>
            <p><?= esc($application['notes']) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if ($application['status'] == 'pending' || $application['status'] == 'reviewing'): ?>
        <div class="border-top pt-4 mt-4">
            <h5 class="mb-3">Actions</h5>
            <div class="d-flex gap-2">
                <form method="post" action="<?= base_url('franchise-applications/' . $application['id'] . '/approve') ?>" onsubmit="return confirm('Are you sure you want to approve this application? An email notification will be sent to the applicant.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Approve Application
                    </button>
                </form>
                <form method="post" action="<?= base_url('franchise-applications/' . $application['id'] . '/reject') ?>" onsubmit="return confirm('Are you sure you want to reject this application? An email notification will be sent to the applicant.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="bi bi-x-circle"></i> Reject Application
                    </button>
                </form>
            </div>
            <p class="text-muted mt-3">
                <i class="bi bi-info-circle"></i> 
                An email notification will be automatically sent to <strong><?= esc($application['email']) ?></strong> when you approve or reject this application.
            </p>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            This application has been <?= $application['status'] ?>. 
            <?php if ($application['status'] == 'approved'): ?>
                An approval email was sent to the applicant.
            <?php else: ?>
                A rejection email was sent to the applicant.
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

