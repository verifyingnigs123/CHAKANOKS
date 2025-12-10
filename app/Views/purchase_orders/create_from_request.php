<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Purchase Order from Request';
$title = 'Create Purchase Order';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Create Purchase Order from Request: <?= $request['request_number'] ?></h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('purchase-orders/store') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="purchase_request_id" value="<?= $request['id'] ?>">
            <input type="hidden" name="branch_id" value="<?= $request['branch_id'] ?>">
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Supplier *</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= $supplier['supplier_id'] ?>"><?= esc($supplier['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Expected Delivery Date</label>
                    <input type="date" name="expected_delivery_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Payment Method</label>
                    <div class="d-flex align-items-center gap-2 px-3 py-2 bg-primary bg-opacity-10 border border-primary rounded">
                        <i class="fab fa-paypal text-primary fs-5"></i>
                        <span class="fw-medium text-primary">PayPal</span>
                    </div>
                    <input type="hidden" name="payment_method" value="paypal">
                    <small class="text-muted">Central Admin will process payment after delivery is received.</small>
                </div>
            </div>

            <hr>
            <h6>Order Items</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Requested Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($request_items as $index => $item): ?>
                            <tr>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['sku'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>
                                    <input type="hidden" name="products[]" value="<?= $item['product_id'] ?>">
                                    <input type="hidden" name="quantities[]" value="<?= $item['quantity'] ?>">
                                    <input type="number" name="unit_prices[]" class="form-control" step="0.01" value="<?= $item['unit_price'] ?>" required>
                                </td>
                                <td class="item-total">₱0.00</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="<?= base_url('purchase-requests') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Purchase Order</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function calculateTotal(index) {
    const qtyInput = document.querySelectorAll('input[name="quantities[]"]')[index];
    const priceInput = document.querySelectorAll('input[name="unit_prices[]"]')[index];
    const totalCell = document.querySelectorAll('.item-total')[index];
    
    const qty = parseFloat(qtyInput.value) || 0;
    const price = parseFloat(priceInput.value) || 0;
    const total = (qty * price).toFixed(2);
    
    totalCell.textContent = '₱' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Calculate totals on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="unit_prices[]"]').forEach((input, index) => {
        calculateTotal(index);
        
        // Recalculate when price changes
        input.addEventListener('input', function() {
            calculateTotal(index);
        });
    });
    
    // Recalculate when quantity changes (if quantities are editable)
    document.querySelectorAll('input[name="quantities[]"]').forEach((input, index) => {
        input.addEventListener('input', function() {
            calculateTotal(index);
        });
    });
});
</script>
<?= $this->endSection() ?>

