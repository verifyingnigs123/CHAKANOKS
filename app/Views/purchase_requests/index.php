<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Requests';
$title = 'Purchase Requests';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Purchase Requests</h4>
    <a href="<?= base_url('purchase-requests/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Request
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" action="<?= base_url('purchase-requests') ?>" id="filterForm" class="row g-3">
            <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
            <div class="col-md-3">
                <label class="form-label">Branch</label>
                <select name="branch_id" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= (isset($filter_branch_id) && $filter_branch_id == $branch['id']) ? 'selected' : '' ?>>
                            <?= $branch['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($status == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= ($status == 'approved') ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= ($status == 'rejected') ? 'selected' : '' ?>>Rejected</option>
                    <option value="converted" <?= ($status == 'converted') ? 'selected' : '' ?>>Converted</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select" onchange="document.getElementById('filterForm').submit();">
                    <option value="">All Priorities</option>
                    <option value="urgent" <?= ($priority == 'urgent') ? 'selected' : '' ?>>Urgent</option>
                    <option value="high" <?= ($priority == 'high') ? 'selected' : '' ?>>High</option>
                    <option value="normal" <?= ($priority == 'normal') ? 'selected' : '' ?>>Normal</option>
                    <option value="low" <?= ($priority == 'low') ? 'selected' : '' ?>>Low</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Request number, requester..." value="<?= esc($search ?? '') ?>" onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('filterForm').submit(); }">
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
                        <th>Request Number</th>
                        <th>Branch</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><strong><?= $request['request_number'] ?></strong></td>
                                <td><?= $request['branch_name'] ?></td>
                                <td><?= $request['requested_by_name'] ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $request['status'] == 'approved' ? 'success' : 
                                        ($request['status'] == 'rejected' ? 'danger' : 
                                        ($request['status'] == 'pending' ? 'warning' : 'secondary')) 
                                    ?>">
                                        <?= ucfirst($request['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $request['priority'] == 'urgent' ? 'danger' : 
                                        ($request['priority'] == 'high' ? 'warning' : 'info') 
                                    ?>">
                                        <?= ucfirst($request['priority']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('purchase-requests/view/' . $request['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if (($role == 'central_admin' || $role == 'system_admin') && $request['status'] == 'pending'): ?>
                                        <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/approve') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" onclick="showRejectModal(<?= $request['id'] ?>)">
                                            <i class="bi bi-x"></i> Reject
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No purchase requests found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function showRejectModal(requestId) {
    document.getElementById('rejectForm').action = '<?= base_url('purchase-requests') ?>/' + requestId + '/reject';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?= $this->endSection() ?>

