<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Activity Logs';
$title = 'Activity Logs';
?>
<style>
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Activity Logs</h4>
    <a href="<?= base_url('activity-logs/export?' . http_build_query($_GET)) ?>" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('activity-logs') ?>" id="filterForm" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= esc($search ?? '') ?>" onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('filterForm').submit(); }">
            </div>
            <div class="col-md-2">
                <select name="user_id" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Users</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= ($filterUser == $user['id']) ? 'selected' : '' ?>>
                            <?= esc($user['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Actions</option>
                    <option value="create" <?= ($filterAction == 'create') ? 'selected' : '' ?>>Create</option>
                    <option value="update" <?= ($filterAction == 'update') ? 'selected' : '' ?>>Update</option>
                    <option value="delete" <?= ($filterAction == 'delete') ? 'selected' : '' ?>>Delete</option>
                    <option value="approve" <?= ($filterAction == 'approve') ? 'selected' : '' ?>>Approve</option>
                    <option value="reject" <?= ($filterAction == 'reject') ? 'selected' : '' ?>>Reject</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="entity_type" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Types</option>
                    <option value="user" <?= ($filterEntity == 'user') ? 'selected' : '' ?>>User</option>
                    <option value="product" <?= ($filterEntity == 'product') ? 'selected' : '' ?>>Product</option>
                    <option value="inventory" <?= ($filterEntity == 'inventory') ? 'selected' : '' ?>>Inventory</option>
                    <option value="purchase_request" <?= ($filterEntity == 'purchase_request') ? 'selected' : '' ?>>Purchase Request</option>
                    <option value="purchase_order" <?= ($filterEntity == 'purchase_order') ? 'selected' : '' ?>>Purchase Order</option>
                    <option value="delivery" <?= ($filterEntity == 'delivery') ? 'selected' : '' ?>>Delivery</option>
                    <option value="transfer" <?= ($filterEntity == 'transfer') ? 'selected' : '' ?>>Transfer</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="row g-2">
                    <div class="col-6">
                        <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom ?? '') ?>" onchange="document.getElementById('filterForm').submit();">
                    </div>
                    <div class="col-6">
                        <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo ?? '') ?>" onchange="document.getElementById('filterForm').submit();">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <a href="<?= base_url('activity-logs') ?>" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Date & Time</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">User</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Action</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Entity Type</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></td>
                                <td><?= esc($log['full_name'] ?? $log['username'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-info"><?= ucfirst($log['action'] ?? 'N/A') ?></span></td>
                                <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $log['module'] ?? 'N/A')) ?></span></td>
                                <td><?= esc($log['description'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No activity logs found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

