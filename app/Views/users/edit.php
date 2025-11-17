<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Edit User';
$title = 'Edit User';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit User: <?= esc($user['username']) ?></h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('users/update/' . $user['id']) ?>">
            <?= csrf_field() ?>
            
            <?php if (isset($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Username *</label>
                    <input type="text" name="username" class="form-control" required value="<?= esc($user['username']) ?>">
                </div>
                <div class="col-md-6">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" required value="<?= esc($user['email']) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" class="form-control" required value="<?= esc($user['full_name']) ?>">
                </div>
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= esc($user['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" minlength="6">
                    <small class="text-muted">Leave blank to keep current password</small>
                </div>
                <div class="col-md-6">
                    <label>Role *</label>
                    <select name="role" class="form-select" required>
                        <?php foreach ($roles as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($user['role'] == $key) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">No Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= ($user['branch_id'] == $branch['id']) ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?= ($user['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($user['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('users') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

