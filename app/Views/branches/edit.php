<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Edit Branch';
$title = 'Edit Branch';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Branch</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('branches/update/' . $branch['id']) ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Branch Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= $branch['name'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Branch Code *</label>
                    <input type="text" name="code" class="form-control" value="<?= $branch['code'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"><?= $branch['address'] ?? '' ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?= $branch['city'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= $branch['phone'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $branch['email'] ?? '' ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Manager</label>
                    <select name="manager_id" class="form-select">
                        <option value="">Select Manager</option>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['id'] ?>" <?= ($branch['manager_id'] == $manager['id']) ? 'selected' : '' ?>>
                                <?= $manager['full_name'] ?> (<?= $manager['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= ($branch['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($branch['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="pending" <?= ($branch['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-check-label mt-4">
                        <input type="checkbox" name="is_franchise" class="form-check-input" value="1" <?= ($branch['is_franchise']) ? 'checked' : '' ?>>
                        Is Franchise
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('branches') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Branch</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

