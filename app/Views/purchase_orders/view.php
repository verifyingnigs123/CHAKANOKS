<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Order Details';
$title = 'Purchase Order Details';
?>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Purchase Order Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>PO Number:</strong> <?= $po['po_number'] ?></p>
                <p><strong>Supplier:</strong> <?= $po['supplier_name'] ?></p>
                <p><strong>Branch:</strong> <?= $po['branch_name'] ?></p>
                <p><strong>Order Date:</strong> <?= date('M d, Y', strtotime($po['order_date'])) ?></p>
                <p><strong>Expected Delivery:</strong> <?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : '-' ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong>
                    <span class="badge bg-<?
                        if ($po['status'] == 'completed') echo 'success';
                        elseif ($po['status'] == 'sent') echo 'info';
                        elseif ($po['status'] == 'confirmed') echo 'primary';
                        elseif ($po['status'] == 'prepared') echo 'warning';
                        else echo 'secondary';
                    ?>">
                        <?= ucfirst($po['status']) ?>
                    </span>
                </p>
                <p><strong>Created By:</strong> <?= $po['created_by_name'] ?></p>
                <p><strong>Subtotal:</strong> ₱<?= number_format($po['subtotal'], 2) ?></p>
                <p><strong>Tax:</strong> ₱<?= number_format($po['tax'], 2) ?></p>
                <p><strong>Total Amount:</strong> <strong>₱<?= number_format($po['total_amount'], 2) ?></strong></p>
            </div>
        </div>
        <?php if ($po['notes']): ?>
            <div class="mt-3">
                <strong>Notes:</strong>
                <p class="text-muted"><?= $po['notes'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Order Items</h5>
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
                        <th>Received</th>
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
                        <tr data-product-id="<?= $item['product_id'] ?>">
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['sku'] ?></td>
                            <td><?= $item['unit'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>
                                <span class="badge bg-<?= ($item['quantity_received'] == $item['quantity']) ? 'success' : 'warning' ?>">
                                    <?= $item['quantity_received'] ?> / <?= $item['quantity'] ?>
                                </span>
                            </td>
                            <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                            <td>₱<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Grand Total:</th>
                        <th>₱<?= number_format($grandTotal, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between">
    <div>
        <a href="<?= base_url('purchase-orders') ?>" class="btn btn-secondary">Back to List</a>
        <a href="<?= base_url('purchase-orders/print/' . $po['id']) ?>" target="_blank" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print
        </a>
    </div>
    <div>
        <?php if ($po['status'] == 'draft'): ?>
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/send') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-send"></i> Send to Supplier
                </button>
            </form>
        <?php endif; ?>
        <?php if ($po['status'] == 'sent'): ?>
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/confirm') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check"></i> Mark as Confirmed
                </button>
            </form>
        <?php endif; ?>
        <?php if (in_array($po['status'], ['sent', 'confirmed']) && in_array($role, ['central_admin', 'logistics_coordinator'])): ?>
            <a href="<?= base_url('deliveries/create?po_id=' . $po['id']) ?>" class="btn btn-info">
                <i class="bi bi-truck"></i> Schedule Delivery
            </a>
        <?php endif; ?>

        <?php if ($po['status'] == 'confirmed' && $role === 'supplier'): ?>
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/prepare') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-circle"></i> Mark as Prepared
                </button>
            </form>
        <?php endif; ?>

        <?php // Show Receive button for branch when a delivery has been scheduled/in_transit for this PO ?>
        <?php if (! empty($delivery) && in_array($delivery['status'], ['scheduled', 'in_transit']) && $role === 'branch_manager'): ?>
            <?php if (session()->get('branch_id') == $po['branch_id']): ?>
                <button id="receiveFromPOBtn" data-delivery-id="<?= $delivery['id'] ?>" class="btn btn-success">
                    <i class="bi bi-box-arrow-in-down"></i> Receive Item
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const receiveBtn = document.getElementById('receiveFromPOBtn');
    if (!receiveBtn) return;

    receiveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const deliveryId = this.getAttribute('data-delivery-id');
        if (!deliveryId) return;

        // Gather product ids and remaining quantities from the items table
        const rows = document.querySelectorAll('table.table tbody tr');
        const products = [];
        const quantities = [];

        rows.forEach(row => {
            const productCell = row.querySelector('td');
            if (!productCell) return;
            // rely on order: Product, SKU, Unit, Quantity, Received, Unit Price, Total Price
            const cols = row.querySelectorAll('td');
            if (cols.length < 7) return;
            // We need product id — embed product_id as data attribute on rows if present
            const productId = row.getAttribute('data-product-id');
            const orderedQty = parseInt(cols[3].textContent || '0');
            // Received badge shows "x / y"
            let receivedText = cols[4].textContent || '';
            const match = receivedText.match(/(\d+)\s*\/\s*(\d+)/);
            let receivedQty = 0;
            if (match) receivedQty = parseInt(match[1]);
            const remaining = Math.max(0, orderedQty - receivedQty);
            if (productId && remaining > 0) {
                products.push(productId);
                quantities.push(remaining);
            }
        });

        const purchasesUrl = '<?= base_url('purchase-orders') ?>';
        const deliveriesUrl = '<?= base_url('deliveries') ?>';

        function showAlert(type, message, redirectUrl) {
            // Find actions container to insert alert before
            const actionsContainer = receiveBtn.closest('.d-flex');
            const alertBox = document.createElement('div');
            alertBox.className = 'alert alert-' + (type || 'info') + ' mt-3';
            alertBox.textContent = message;
            if (actionsContainer && actionsContainer.parentElement) {
                actionsContainer.parentElement.insertBefore(alertBox, actionsContainer.nextSibling);
            } else {
                document.body.insertBefore(alertBox, document.body.firstChild);
            }
            if (redirectUrl) {
                setTimeout(() => { window.location.href = redirectUrl; }, 1400);
            }
        }

        if (products.length === 0) {
            showAlert('info', 'No remaining items to receive.', purchasesUrl);
            return;
        }

        const formData = new FormData();
        products.forEach(p => formData.append('products[]', p));
        quantities.forEach(q => formData.append('quantities[]', q));
        // Send empty batch_numbers and expiry_dates arrays
        products.forEach(_ => formData.append('batch_numbers[]', ''));
        products.forEach(_ => formData.append('expiry_dates[]', ''));

        fetch('<?= base_url('deliveries/') ?>' + deliveryId + '/receive', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        }).then(r => r.json())
        .then(res => {
            if (res && res.success) {
                // Update the UI: set received badges to full quantity and update PO status badge
                const poStatusBadge = document.querySelector('p strong + span.badge');
                if (poStatusBadge) {
                    poStatusBadge.classList.remove('bg-secondary','bg-info','bg-warning');
                    if (res.po_status === 'completed') poStatusBadge.classList.add('bg-success');
                    else poStatusBadge.classList.add('bg-warning');
                    poStatusBadge.textContent = res.po_status.charAt(0).toUpperCase() + res.po_status.slice(1);
                }

                // Update each row's received column if item_updates provided
                if (res.item_updates) {
                    const rows = document.querySelectorAll('table.table tbody tr');
                    rows.forEach(row => {
                        const pid = row.getAttribute('data-product-id');
                        if (!pid) return;
                        if (res.item_updates[pid] !== undefined) {
                            const cols = row.querySelectorAll('td');
                            const orderedQty = parseInt(cols[3].textContent || '0');
                            cols[4].innerHTML = '<span class="badge bg-success">' + res.item_updates[pid] + ' / ' + orderedQty + '</span>';
                        }
                    });
                }

                // Replace Receive button with a disabled Received indicator
                receiveBtn.disabled = true;
                receiveBtn.classList.remove('btn-success');
                receiveBtn.classList.add('btn-secondary');
                receiveBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Received';

                // Optionally show a brief alert
                const alertBox = document.createElement('div');
                alertBox.className = 'alert alert-success mt-3';
                alertBox.textContent = res.message || 'Received and inventory updated';
                receiveBtn.parentElement.appendChild(alertBox);
                setTimeout(() => alertBox.remove(), 4000);
            } else {
                showAlert('danger', res.message || 'Failed to receive items');
            }
        }).catch(err => {
            console.error(err);
            showAlert('danger', 'Error receiving items');
        });
    });
});
</script>

<?= $this->endSection() ?>

