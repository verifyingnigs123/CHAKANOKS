<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Management';
$title = 'Inventory';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inventory List</h4>
    <div>
        <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
            <select class="form-select d-inline-block" style="width: auto;" onchange="window.location.href='?branch_id='+this.value">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                        <?= $branch['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scanModal">
            <i class="bi bi-upc-scan"></i> Scan Barcode
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <?php if ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')): ?>
                            <th>Branch</th>
                        <?php endif; ?>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>Available</th>
                        <th>Min Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr>
                                <?php if ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')): ?>
                                    <td>
                                        <span class="badge bg-info"><?= $item['branch_name'] ?? 'N/A' ?></span>
                                    </td>
                                <?php endif; ?>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['sku'] ?></td>
                                <td><?= $item['barcode'] ?? '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= ($item['quantity'] <= $item['min_stock_level']) ? 'danger' : 'success' ?>">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </td>
                                <td><?= $item['available_quantity'] ?></td>
                                <td><?= $item['min_stock_level'] ?></td>
                                <td>
                                    <?php if ($item['quantity'] <= $item['min_stock_level']): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php elseif ($item['quantity'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="updateInventory(<?= $item['id'] ?>, <?= $item['branch_id'] ?>, <?= $item['product_id'] ?>, <?= $item['quantity'] ?>)">
                                        <i class="bi bi-pencil"></i> Update
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')) ? '9' : '8' ?>" class="text-center">No inventory records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Inventory Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateForm" method="post" action="<?= base_url('inventory/update') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="branch_id" id="update_branch_id">
                    <input type="hidden" name="product_id" id="update_product_id">
                    <div class="mb-3">
                        <label>Action</label>
                        <select name="action" class="form-select" required>
                            <option value="set">Set Quantity</option>
                            <option value="add">Add Quantity</option>
                            <option value="subtract">Subtract Quantity</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scan Barcode Modal -->
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="scanForm" method="post" action="<?= base_url('inventory/scan') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="branch_id" value="<?= $current_branch_id ?? session()->get('branch_id') ?>">
                    <div class="mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control" autofocus required>
                    </div>
                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Scan & Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function updateInventory(id, branchId, productId, currentQty) {
    document.getElementById('update_branch_id').value = branchId;
    document.getElementById('update_product_id').value = productId;
    document.getElementById('updateForm').querySelector('input[name="quantity"]').value = currentQty;
    new bootstrap.Modal(document.getElementById('updateModal')).show();
}

// Handle barcode scan form submission
document.getElementById('scanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Scanning...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification with product details
            showScanResult(data.product, 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('scanModal'));
            modal.hide();
            
            // Reset form
            form.reset();
            
            // Reload page after a short delay to show updated inventory
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error notification
            showScanResult(null, 'error', data.message || 'Product not found');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showScanResult(null, 'error', 'An error occurred while scanning');
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

function showScanResult(product, type, message) {
    // Remove any existing scan result alert
    const existingAlert = document.getElementById('scanResultAlert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.id = 'scanResultAlert';
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px; max-width: 500px;';
    
    let alertContent = '';
    
    if (type === 'success' && product) {
        const scannedQty = product.scanned_quantity || 1;
        const newTotal = product.new_total_quantity || scannedQty;
        alertContent = `
            <h5 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Barcode Scanned Successfully!</h5>
            <hr>
            <p class="mb-1"><strong>Product:</strong> ${product.name}</p>
            <p class="mb-1"><strong>SKU:</strong> ${product.sku}</p>
            <p class="mb-1"><strong>Barcode:</strong> ${product.barcode}</p>
            <p class="mb-1"><strong>Category:</strong> ${product.category || 'N/A'}</p>
            <p class="mb-1"><strong>Scanned Quantity:</strong> <span class="badge bg-info">+${scannedQty}</span></p>
            <p class="mb-1"><strong>New Total:</strong> <span class="badge bg-${newTotal <= (product.min_stock_level || 0) ? 'danger' : 'success'}">${newTotal}</span></p>
            <p class="mb-0"><strong>Status:</strong> <span class="badge bg-${product.status === 'active' ? 'success' : 'secondary'}">${product.status}</span></p>
            <p class="mt-2 mb-0"><small class="text-muted">Inventory updated successfully!</small></p>
        `;
    } else {
        alertContent = `
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Scan Failed</h5>
            <hr>
            <p class="mb-0">${message || 'Product not found'}</p>
        `;
    }
    
    alertDiv.innerHTML = alertContent + `
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Append to body
    document.body.appendChild(alertDiv);
    
    // Auto dismiss after 5 seconds for success, 7 seconds for error
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }
    }, type === 'success' ? 5000 : 7000);
}
</script>
<?= $this->endSection() ?>

