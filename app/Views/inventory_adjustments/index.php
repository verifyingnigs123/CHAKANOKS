<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Adjustment History';
$title = 'Inventory Adjustments';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inventory Adjustment History</h4>
    <a href="<?= base_url('inventory-adjustments/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Adjustment
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('inventory-adjustments') ?>" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by product, SKU, reason..." value="<?= esc($search ?? '') ?>">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="increase" <?= ($type == 'increase') ? 'selected' : '' ?>>Increase</option>
                    <option value="decrease" <?= ($type == 'decrease') ? 'selected' : '' ?>>Decrease</option>
                    <option value="set" <?= ($type == 'set') ? 'selected' : '' ?>>Set</option>
                </select>
            </div>
            <?php if ($role == 'system_admin' || $role == 'central_admin'): ?>
            <div class="col-md-2">
                <select name="branch_id" class="form-select">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= ($filterBranch == $branch['id']) ? 'selected' : '' ?>>
                            <?= esc($branch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom ?? '') ?>" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo ?? '') ?>" placeholder="To">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Product</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Branch</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Type</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Old Qty</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">New Qty</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Change</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Reason</th>
                        <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 10;">Adjusted By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($adjustments)): ?>
                        <?php foreach ($adjustments as $adj): ?>
                            <tr>
                                <td><?= date('M d, Y H:i', strtotime($adj['created_at'])) ?></td>
                                <td>
                                    <strong><?= esc($adj['product_name']) ?></strong><br>
                                    <small class="text-muted"><?= esc($adj['sku']) ?></small>
                                </td>
                                <td><?= esc($adj['branch_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $typeColors = [
                                        'increase' => 'success',
                                        'decrease' => 'danger',
                                        'set' => 'info'
                                    ];
                                    $color = $typeColors[$adj['type']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= ucfirst($adj['type']) ?></span>
                                </td>
                                <td><?= number_format($adj['old_quantity']) ?></td>
                                <td><strong><?= number_format($adj['new_quantity']) ?></strong></td>
                                <td>
                                    <?php
                                    $change = $adj['quantity_change'];
                                    $changeColor = $change > 0 ? 'text-success' : ($change < 0 ? 'text-danger' : 'text-muted');
                                    ?>
                                    <span class="<?= $changeColor ?>">
                                        <?= $change > 0 ? '+' : '' ?><?= number_format($change) ?>
                                    </span>
                                </td>
                                <td><?= esc($adj['reason']) ?></td>
                                <td><?= esc($adj['adjusted_by_name'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">No inventory adjustments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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

<?= $this->endSection() ?>

