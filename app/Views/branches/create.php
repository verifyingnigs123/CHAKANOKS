<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Branch';
$title = 'Create Branch';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">New Branch</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('branches/store') ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Branch Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Branch Code *</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Manager</label>
                    <select name="manager_id" class="form-select">
                        <option value="">Select Manager</option>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['id'] ?>"><?= $manager['full_name'] ?> (<?= $manager['email'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-check-label mt-4">
                        <input type="checkbox" name="is_franchise" class="form-check-input" value="1">
                        Is Franchise
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('branches') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Branch</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

