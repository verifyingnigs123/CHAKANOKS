<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Branches';
$title = 'Branches';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Branches</h4>
    <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
        <a href="<?= base_url('branches/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Branch
        </a>
    <?php endif; ?>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('branches') ?>" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by name, code, city..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= ($status == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($status == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                        <th>Code</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Manager</th>
                        <th>Type</th>
                        <th>Status</th>
                        <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($branches)): ?>
                        <?php foreach ($branches as $branch): ?>
                            <tr>
                                <td><strong><?= $branch['code'] ?></strong></td>
                                <td><?= $branch['name'] ?></td>
                                <td><?= $branch['city'] ?? '-' ?></td>
                                <td><?= $branch['manager_name'] ?? '-' ?></td>
                                <td>
                                    <?php if ($branch['is_franchise']): ?>
                                        <span class="badge bg-info">Franchise</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Corporate</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $branch['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($branch['status']) ?>
                                    </span>
                                </td>
                                <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
                                    <td>
                                        <a href="<?= base_url('branches/edit/' . $branch['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No branches found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

