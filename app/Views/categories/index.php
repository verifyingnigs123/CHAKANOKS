<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Categories';
$title = 'Categories';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Product Categories</h4>
    <a href="<?= base_url('categories/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Category
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('categories') ?>" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search by name, description..." value="<?= esc($search ?? '') ?>">
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><strong><?= esc($category['name']) ?></strong></td>
                                <td><?= esc($category['description'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-<?= $category['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($category['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('categories/delete/' . $category['id']) ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No categories found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

