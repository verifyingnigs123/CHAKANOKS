<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Schedule Delivery';
$title = 'Schedule Delivery';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Schedule New Delivery</h5>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('deliveries/store') ?>">
            <div class="mb-3">
                <label>Purchase Order *</label>
                <select name="purchase_order_id" class="form-select" required>
                    <option value="">Select Purchase Order</option>
                    <?php foreach ($purchase_orders as $po): ?>
                        <option value="<?= $po['id'] ?>" <?= (isset($_GET['po_id']) && $_GET['po_id'] == $po['id']) ? 'selected' : '' ?>>
                            <?= $po['po_number'] ?> - <?= $po['supplier_name'] ?> (<?= $po['branch_name'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Scheduled Date *</label>
                    <input type="date" name="scheduled_date" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Driver Name *</label>
                    <select name="driver_name" id="driver_name" class="form-select" required>
                        <option value="">Select Driver</option>
                        <?php if (!empty($drivers)): ?>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?= esc($driver['name']) ?>" data-vehicle="<?= esc($driver['vehicle_number']) ?>">
                                    <?= esc($driver['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Vehicle Number *</label>
                <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" readonly required placeholder="Vehicle will be auto-filled when driver is selected">
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('deliveries') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Schedule Delivery</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driverSelect = document.getElementById('driver_name');
    const vehicleInput = document.getElementById('vehicle_number');
    
    // When driver is selected, automatically fill vehicle number
    driverSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const vehicleNumber = selectedOption.getAttribute('data-vehicle');
        
        if (vehicleNumber) {
            // Automatically fill the vehicle number input
            vehicleInput.value = vehicleNumber;
            vehicleInput.style.backgroundColor = '#e8f5e9'; // Light green to show it's auto-filled
        } else {
            // Clear vehicle if no driver selected
            vehicleInput.value = '';
            vehicleInput.style.backgroundColor = '';
        }
    });
});
</script>
<?= $this->endSection() ?>

