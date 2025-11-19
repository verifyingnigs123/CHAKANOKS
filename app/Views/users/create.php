<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create User';
$title = 'Create User';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create New User</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('users/store') ?>">
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
                    <input type="text" name="username" class="form-control" required value="<?= old('username') ?>">
                </div>
                <div class="col-md-6">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" required value="<?= old('email') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" class="form-control" required value="<?= old('full_name') ?>">
                </div>
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                    <small class="text-muted">Minimum 6 characters</small>
                </div>
                <div class="col-md-6">
                    <label>Role *</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $key => $label): ?>
                            <option value="<?= $key ?>" <?= (old('role') == $key) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Branch <span id="branch-required" class="text-danger" style="display: none;">*</span></label>
                    <select name="branch_id" id="branch_id" class="form-select">
                        <option value="">No Branch</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['id'] ?>" <?= (old('branch_id') == $branch['id']) ? 'selected' : '' ?>>
                                <?= esc($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small id="branch-help" class="text-muted" style="display: none;">
                        <i class="bi bi-info-circle"></i> Branch Manager and Inventory Staff must be assigned to a branch.
                    </small>
                </div>
                <div class="col-md-6">
                    <label>Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?= (old('status') == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (old('status') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('users') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const branchSelect = document.getElementById('branch_id');
    const branchRequired = document.getElementById('branch-required');
    const branchHelp = document.getElementById('branch-help');
    
    function updateBranchField() {
        const selectedRole = roleSelect.value;
        const branchRequiredRoles = ['branch_manager', 'inventory_staff'];
        
        if (branchRequiredRoles.includes(selectedRole)) {
            branchSelect.required = true;
            branchRequired.style.display = 'inline';
            branchHelp.style.display = 'block';
            branchSelect.classList.add('border-warning');
        } else {
            branchSelect.required = false;
            branchRequired.style.display = 'none';
            branchHelp.style.display = 'none';
            branchSelect.classList.remove('border-warning');
        }
    }
    
    // Check on page load if role is already selected
    updateBranchField();
    
    // Update when role changes
    roleSelect.addEventListener('change', updateBranchField);
});
</script>

<?= $this->endSection() ?>

