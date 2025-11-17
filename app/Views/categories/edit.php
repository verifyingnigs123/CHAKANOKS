<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Edit Category';
$title = 'Edit Category';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Category: <?= esc($category['name']) ?></h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('categories/update/' . $category['id']) ?>">
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

            <div class="mb-3">
                <label>Category Name *</label>
                <input type="text" name="name" class="form-control" required value="<?= esc($category['name']) ?>">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?= esc($category['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label>Status *</label>
                <select name="status" class="form-select" required>
                    <option value="active" <?= ($category['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($category['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('categories') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

