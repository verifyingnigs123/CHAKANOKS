<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Suppliers';
$title = 'Suppliers';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Suppliers</h4>
    <a href="<?= base_url('suppliers/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Supplier
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td><strong><?= $supplier['code'] ?></strong></td>
                                <td><?= $supplier['name'] ?></td>
                                <td><?= $supplier['contact_person'] ?? '-' ?></td>
                                <td><?= $supplier['email'] ?? '-' ?></td>
                                <td><?= $supplier['phone'] ?? '-' ?></td>
                                <td>
                                    <?php if ($supplier['rating'] > 0): ?>
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-star<?= ($i < $supplier['rating']) ? '-fill text-warning' : '' ?>"></i>
                                        <?php endfor; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No rating</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $supplier['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($supplier['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('suppliers/edit/' . $supplier['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No suppliers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

