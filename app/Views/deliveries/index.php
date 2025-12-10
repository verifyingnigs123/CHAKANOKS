<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Deliveries';
$title = 'Deliveries';

// Calculate stats for logistics dashboard
$stats = ['total' => 0, 'scheduled' => 0, 'in_transit' => 0, 'delivered' => 0, 'pending_schedule' => 0];
if (!empty($deliveries)) {
    foreach ($deliveries as $d) {
        $stats['total']++;
        if ($d['status'] == 'scheduled') $stats['scheduled']++;
        elseif ($d['status'] == 'in_transit') $stats['in_transit']++;
        elseif ($d['status'] == 'delivered') $stats['delivered']++;
    }
}
$stats['pending_schedule'] = count($prepared_pos ?? []);
?>

<?php if (in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
<!-- Logistics Dashboard Summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-100 text-xs font-medium uppercase">Pending Schedule</p>
                <p class="text-2xl font-bold mt-1"><?= $stats['pending_schedule'] ?></p>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-lg"></i>
            </div>
        </div>
        <p class="text-amber-100 text-xs mt-2">POs ready for delivery</p>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-xs font-medium uppercase">Scheduled</p>
                <p class="text-2xl font-bold mt-1"><?= $stats['scheduled'] ?></p>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-lg"></i>
            </div>
        </div>
        <p class="text-blue-100 text-xs mt-2">Awaiting dispatch</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-xs font-medium uppercase">In Transit</p>
                <p class="text-2xl font-bold mt-1"><?= $stats['in_transit'] ?></p>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-lg"></i>
            </div>
        </div>
        <p class="text-purple-100 text-xs mt-2">On the way</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-xs font-medium uppercase">Delivered</p>
                <p class="text-2xl font-bold mt-1"><?= $stats['delivered'] ?></p>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>
        <p class="text-emerald-100 text-xs mt-2">Successfully completed</p>
    </div>
</div>

<!-- Quick Actions Alert -->
<?php if ($stats['pending_schedule'] > 0 || $stats['scheduled'] > 0): ?>
<div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-bell text-amber-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-medium text-gray-800">You have pending actions</p>
            <p class="text-sm text-gray-600">
                <?php if ($stats['pending_schedule'] > 0): ?>
                    <span class="text-amber-600 font-medium"><?= $stats['pending_schedule'] ?> PO(s)</span> ready to schedule
                <?php endif; ?>
                <?php if ($stats['pending_schedule'] > 0 && $stats['scheduled'] > 0): ?> • <?php endif; ?>
                <?php if ($stats['scheduled'] > 0): ?>
                    <span class="text-blue-600 font-medium"><?= $stats['scheduled'] ?> delivery(s)</span> awaiting dispatch
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($prepared_pos) && in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
<!-- Prepared POs Card -->
<div class="bg-white rounded-xl shadow-sm border border-amber-200 mb-6">
    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50 rounded-t-xl">
        <h3 class="font-semibold text-gray-800 flex items-center"><i class="fas fa-clock text-amber-500 mr-2"></i> Prepared Purchase Orders (Ready to Schedule)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">PO Number</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Order Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($prepared_pos as $ppo): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-800"><?= esc($ppo['po_number']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= esc($ppo['supplier_name']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= esc($ppo['branch_name']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= $ppo['order_date'] ? date('M d, Y', strtotime($ppo['order_date'])) : '-' ?></td>
                    <td class="px-6 py-4">
                        <button onclick="openCreateModal(<?= $ppo['id'] ?>)" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-truck mr-1"></i> Schedule</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Search delivery #, PO, supplier...">
            </div>
            <div class="w-full md:w-36">
                <select id="statusFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <div class="w-full md:w-36">
                <select id="paymentFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Payment</option>
                    <option value="cod">COD</option>
                    <option value="paypal">PayPal</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <?php if (in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Schedule Delivery
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Deliveries Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Delivery #</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">PO Number</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Supplier</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Scheduled</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Delivered</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($deliveries)): ?>
                    <?php foreach ($deliveries as $delivery): ?>
                    <?php $pm = $delivery['payment_method'] ?? $delivery['po_payment_method'] ?? 'pending'; ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" data-delivery="<?= esc(strtolower($delivery['delivery_number'])) ?>" data-po="<?= esc(strtolower($delivery['po_number'])) ?>" data-supplier="<?= esc(strtolower($delivery['supplier_name'])) ?>" data-branch="<?= esc(strtolower($delivery['branch_name'])) ?>" data-status="<?= esc($delivery['status']) ?>" data-payment="<?= esc($pm) ?>">
                        <td class="px-6 py-4"><span class="font-semibold text-gray-800"><?= esc($delivery['delivery_number']) ?></span></td>
                        <td class="px-6 py-4"><span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded"><?= esc($delivery['po_number']) ?></span></td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($delivery['supplier_name']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($delivery['branch_name']) ?></td>
                        <td class="px-6 py-4 text-gray-500 text-sm"><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : '-' ?></td>
                        <td class="px-6 py-4 text-gray-500 text-sm"><?= $delivery['delivery_date'] ? date('M d, Y', strtotime($delivery['delivery_date'])) : '-' ?></td>
                        <td class="px-6 py-4">
                            <?php if ($pm == 'cod'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="fas fa-money-bill-wave mr-1"></i> COD</span>
                            <?php elseif ($pm == 'paypal'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="fab fa-paypal mr-1"></i> PayPal</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($delivery['status'] == 'delivered'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="fas fa-check-circle mr-1"></i> Delivered</span>
                            <?php elseif ($delivery['status'] == 'in_transit'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="fas fa-truck mr-1"></i> In Transit</span>
                            <?php elseif ($delivery['status'] == 'scheduled'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i class="fas fa-calendar mr-1"></i> Scheduled</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"><?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1">
                                <a href="<?= base_url('deliveries/view/' . $delivery['id']) ?>" class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if (in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
                                    <?php if ($delivery['status'] == 'scheduled'): ?>
                                    <!-- Mark In Transit Button -->
                                    <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="in_transit">
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-purple-500 text-white hover:bg-purple-600 rounded text-xs font-medium transition-colors" title="Mark In Transit">
                                            <i class="fas fa-truck mr-1"></i>Dispatch
                                        </button>
                                    </form>
                                    <?php elseif ($delivery['status'] == 'in_transit'): ?>
                                    <!-- Mark Delivered Button -->
                                    <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-emerald-500 text-white hover:bg-emerald-600 rounded text-xs font-medium transition-colors" title="Mark Delivered">
                                            <i class="fas fa-check mr-1"></i>Delivered
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-truck text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No deliveries found</p>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Delivery Modal -->
<?php if (in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-2xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" action="<?= base_url('deliveries/store') ?>" id="createForm">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-truck text-emerald-500 mr-2"></i>Schedule New Delivery
                    </h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Order <span class="text-red-500">*</span></label>
                        <select name="purchase_order_id" id="modal_po_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                            <option value="">Select Purchase Order</option>
                            <?php if (!empty($purchase_orders_for_modal)): ?>
                            <?php foreach ($purchase_orders_for_modal as $po): ?>
                            <option value="<?= $po['id'] ?>"><?= esc($po['po_number']) ?> - <?= esc($po['supplier_name']) ?> (<?= esc($po['branch_name']) ?>)</option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Driver <span class="text-red-500">*</span></label>
                            <select name="driver_name" id="modal_driver_name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
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
                        <input type="text" name="vehicle_number" id="modal_vehicle_number" readonly required placeholder="Auto-filled when driver is selected" class="w-full md:w-1/2 px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Delivery instructions (optional)"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Modal functions
function openCreateModal(poId = null) {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    if (poId) {
        document.getElementById('modal_po_id').value = poId;
    }
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCreateModal();
});

// Driver selection - auto-fill vehicle
document.getElementById('modal_driver_name')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const vehicleNumber = selectedOption.getAttribute('data-vehicle');
    const vehicleInput = document.getElementById('modal_vehicle_number');
    if (vehicleNumber) {
        vehicleInput.value = vehicleNumber;
        vehicleInput.style.backgroundColor = '#ecfdf5';
    } else {
        vehicleInput.value = '';
        vehicleInput.style.backgroundColor = '';
    }
});

// Check URL for schedule parameter and auto-open modal
const urlParams = new URLSearchParams(window.location.search);
const schedulePoId = urlParams.get('schedule');
if (schedulePoId) {
    openCreateModal(schedulePoId);
    // Clean URL
    window.history.replaceState({}, document.title, window.location.pathname);
}

// Table filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const paymentFilter = document.getElementById('paymentFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value.toLowerCase();
        const payment = paymentFilter.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const m1 = search === '' || row.dataset.delivery.includes(search) || row.dataset.po.includes(search) || row.dataset.supplier.includes(search) || row.dataset.branch.includes(search);
            const m2 = status === '' || row.dataset.status === status;
            const m3 = payment === '' || row.dataset.payment === payment;
            
            if (m1 && m2 && m3) { row.style.display = ''; count++; } 
            else { row.style.display = 'none'; }
        });
        
        noResults.classList.toggle('hidden', count > 0);
        tbody.classList.toggle('hidden', count === 0);
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    paymentFilter.addEventListener('change', filterTable);
});
</script>
<?= $this->endSection() ?>
