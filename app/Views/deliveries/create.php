<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Schedule Delivery';
$title = 'Schedule Delivery';
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('deliveries') ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back
    </a>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-cyan-500 to-blue-600">
        <h3 class="text-lg font-semibold text-white flex items-center">
            <i class="fas fa-truck mr-2"></i>Delivery Details
        </h3>
    </div>
    
    <form method="post" action="<?= base_url('deliveries/store') ?>">
        <?= csrf_field() ?>
        
        <div class="p-6 space-y-6">
            <!-- Purchase Order Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order <span class="text-red-500">*</span></label>
                <select name="purchase_order_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">Select Purchase Order</option>
                    <?php foreach ($purchase_orders as $po): ?>
                    <option value="<?= $po['id'] ?>" <?= (isset($_GET['po_id']) && $_GET['po_id'] == $po['id']) ? 'selected' : '' ?>>
                        <?= $po['po_number'] ?> - <?= $po['supplier_name'] ?> (<?= $po['branch_name'] ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date <span class="text-red-500">*</span></label>
                    <input type="date" name="scheduled_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Driver <span class="text-red-500">*</span></label>
                    <select name="driver_name" id="driver_name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                        <option value="">Select Driver</option>
                        <?php if (!empty($drivers)): ?>
                        <?php foreach ($drivers as $driver): ?>
                        <option value="<?= esc($driver['name']) ?>" data-vehicle="<?= esc($driver['vehicle_number']) ?>"><?= esc($driver['name']) ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number <span class="text-red-500">*</span></label>
                <input type="text" name="vehicle_number" id="vehicle_number" readonly required placeholder="Vehicle will be auto-filled when driver is selected" class="w-full md:w-1/2 px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 outline-none transition-all">
                <p class="text-xs text-gray-500 mt-1">Automatically filled based on selected driver</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Delivery instructions or notes (optional)"></textarea>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="<?= base_url('deliveries') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fas fa-calendar-check mr-2"></i>Schedule Delivery
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driverSelect = document.getElementById('driver_name');
    const vehicleInput = document.getElementById('vehicle_number');
    
    driverSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const vehicleNumber = selectedOption.getAttribute('data-vehicle');
        
        if (vehicleNumber) {
            vehicleInput.value = vehicleNumber;
            vehicleInput.style.backgroundColor = '#ecfdf5';
        } else {
            vehicleInput.value = '';
            vehicleInput.style.backgroundColor = '';
        }
    });
});
</script>
<?= $this->endSection() ?>
