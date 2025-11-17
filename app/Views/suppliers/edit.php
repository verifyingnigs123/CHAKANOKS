<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Edit Supplier';
$title = 'Edit Supplier';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Supplier</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('suppliers/update/' . $supplier['id']) ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Supplier Name *</label>
                    <input type="text" name="name" class="form-control" value="<?= $supplier['name'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Supplier Code *</label>
                    <input type="text" name="code" class="form-control" value="<?= $supplier['code'] ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= $supplier['contact_person'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $supplier['email'] ?? '' ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= $supplier['phone'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= ($supplier['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($supplier['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        <option value="suspended" <?= ($supplier['status'] == 'suspended') ? 'selected' : '' ?>>Suspended</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"><?= $supplier['address'] ?? '' ?></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" value="<?= $supplier['payment_terms'] ?? '' ?>">
                </div>
                <div class="col-md-6">
                    <label>Delivery Terms</label>
                    <input type="text" name="delivery_terms" class="form-control" value="<?= $supplier['delivery_terms'] ?? '' ?>">
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('suppliers') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

