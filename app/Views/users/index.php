<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'User Management';
$title = 'Users';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>User Management</h4>
    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create User
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('users') ?>" id="filterForm" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, username, email..." value="<?= esc($search ?? '') ?>" onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('filterForm').submit(); }">
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Roles</option>
                    <?php foreach ($roles as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filterRole == $key) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Status</option>
                    <option value="active" <?= ($filterStatus == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($filterStatus == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['full_name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><span class="badge bg-info"><?= esc($roles[$user['role']] ?? $user['role']) ?></span></td>
                                <td><?= esc($user['branch_name'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

