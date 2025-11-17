<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Request Details';
$title = 'Purchase Request Details';
?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Request Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Request Number:</strong> <?= $request['request_number'] ?></p>
                <p><strong>Branch:</strong> <?= $request['branch_name'] ?></p>
                <p><strong>Requested By:</strong> <?= $request['requested_by_name'] ?></p>
                <p><strong>Priority:</strong> 
                    <span class="badge bg-<?= 
                        $request['priority'] == 'urgent' ? 'danger' : 
                        ($request['priority'] == 'high' ? 'warning' : 'info') 
                    ?>">
                        <?= ucfirst($request['priority']) ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?= 
                        $request['status'] == 'approved' ? 'success' : 
                        ($request['status'] == 'rejected' ? 'danger' : 'warning') 
                    ?>">
                        <?= ucfirst($request['status']) ?>
                    </span>
                </p>
                <p><strong>Date:</strong> <?= date('M d, Y H:i', strtotime($request['created_at'])) ?></p>
                <?php if ($request['approved_at']): ?>
                    <p><strong>Approved At:</strong> <?= date('M d, Y H:i', strtotime($request['approved_at'])) ?></p>
                <?php endif; ?>
                <?php if ($request['rejection_reason']): ?>
                    <p><strong>Rejection Reason:</strong> <?= $request['rejection_reason'] ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($request['notes']): ?>
            <div class="mt-3">
                <strong>Notes:</strong>
                <p class="text-muted"><?= $request['notes'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Requested Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal = 0;
                    foreach ($items as $item): 
                        $grandTotal += $item['total_price'];
                    ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['sku'] ?></td>
                            <td><?= $item['unit'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                            <td>₱<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total:</th>
                        <th>₱<?= number_format($grandTotal, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="<?= base_url('purchase-requests') ?>" class="btn btn-secondary">Back to List</a>
    <a href="<?= base_url('purchase-requests/print/' . $request['id']) ?>" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer"></i> Print
    </a>
    <?php if (($role == 'central_admin' || $role == 'system_admin') && $request['status'] == 'pending'): ?>
        <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/approve') ?>" class="d-inline">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check"></i> Approve
            </button>
        </form>
        <button class="btn btn-danger" onclick="showRejectModal()">
            <i class="bi bi-x"></i> Reject
        </button>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/reject') ?>">
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
function showRejectModal() {
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
<?= $this->endSection() ?>

