<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Supplier';
$title = 'Create Supplier';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">New Supplier</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('suppliers/store') ?>">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Supplier Name *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Supplier Code *</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control" placeholder="e.g., Net 30">
                </div>
                <div class="col-md-6">
                    <label>Delivery Terms</label>
                    <input type="text" name="delivery_terms" class="form-control" placeholder="e.g., FOB">
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('suppliers') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Supplier</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

