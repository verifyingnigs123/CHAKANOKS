<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Orders';
$title = 'Purchase Orders';
$role = session()->get('role');

// Calculate stats for supplier dashboard
$stats = ['total' => 0, 'pending' => 0, 'confirmed' => 0, 'prepared' => 0, 'completed' => 0, 'total_amount' => 0];
if (!empty($purchase_orders)) {
    foreach ($purchase_orders as $po) {
        $stats['total']++;
        $stats['total_amount'] += $po['total_amount'];
        if ($po['status'] == 'sent') $stats['pending']++;
        elseif ($po['status'] == 'confirmed') $stats['confirmed']++;
        elseif ($po['status'] == 'prepared') $stats['prepared']++;
        elseif ($po['status'] == 'completed') $stats['completed']++;
    }
}
?>

<?php if ($role === 'supplier'): ?>
<!-- Quick Actions for Supplier (Stats are on Dashboard) -->
<?php if ($stats['pending'] > 0 || $stats['confirmed'] > 0): ?>
<div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-bell text-amber-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-medium text-gray-800">You have pending actions</p>
            <p class="text-sm text-gray-600">
                <?php if ($stats['pending'] > 0): ?>
                    <span class="text-amber-600 font-medium"><?= $stats['pending'] ?> order(s)</span> waiting for confirmation
                <?php endif; ?>
                <?php if ($stats['pending'] > 0 && $stats['confirmed'] > 0): ?> • <?php endif; ?>
                <?php if ($stats['confirmed'] > 0): ?>
                    <span class="text-blue-600 font-medium"><?= $stats['confirmed'] ?> order(s)</span> ready to prepare
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($role === 'logistics_coordinator'): ?>
<!-- Quick Actions for Logistics (Stats are on Dashboard) -->
<?php if ($stats['prepared'] > 0): ?>
<div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
            <i class="fas fa-truck text-purple-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-medium text-gray-800">Ready for Delivery Scheduling</p>
            <p class="text-sm text-gray-600">
                <span class="text-purple-600 font-medium"><?= $stats['prepared'] ?> PO(s)</span> are prepared and waiting for delivery scheduling
            </p>
        </div>
        <a href="<?= base_url('deliveries') ?>" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white hover:bg-purple-700 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
            <i class="fas fa-calendar-check mr-2"></i>Schedule Deliveries
        </a>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Search PO, supplier...">
            </div>
            <div class="w-full md:w-36">
                <select id="statusFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="prepared">Prepared</option>
                    <option value="completed">Completed</option>
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
            <?php if ($role === 'central_admin'): ?>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Create PO
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">PO Number</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Supplier</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Branch</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Payment</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($purchase_orders)): ?>
                    <?php foreach ($purchase_orders as $po): ?>
                    <?php $pm = $po['payment_method'] ?? 'pending'; ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" 
                        data-ponumber="<?= esc(strtolower($po['po_number'])) ?>" 
                        data-supplier="<?= esc(strtolower($po['supplier_name'])) ?>" 
                        data-branch="<?= esc(strtolower($po['branch_name'])) ?>" 
                        data-status="<?= esc($po['status']) ?>" 
                        data-payment="<?= esc($pm) ?>">
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-800 text-sm"><?= esc($po['po_number']) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-gray-600 text-sm truncate block max-w-[120px]" title="<?= esc($po['supplier_name']) ?>"><?= esc($po['supplier_name']) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-gray-600 text-sm truncate block max-w-[100px]" title="<?= esc($po['branch_name']) ?>"><?= esc($po['branch_name']) ?></span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-sm whitespace-nowrap"><?= date('M d, Y', strtotime($po['order_date'])) ?></td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800 text-sm whitespace-nowrap">₱<?= number_format($po['total_amount'], 2) ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($pm == 'cod'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">COD</span>
                            <?php elseif ($pm == 'paypal'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">PayPal</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php if ($po['status'] == 'completed'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Completed</span>
                            <?php elseif ($po['status'] == 'sent'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Sent</span>
                            <?php elseif ($po['status'] == 'confirmed'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Confirmed</span>
                            <?php elseif ($po['status'] == 'prepared'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-700">Prepared</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"><?= ucfirst($po['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <a href="<?= base_url('purchase-orders/view/' . $po['id']) ?>" class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($role === 'supplier'): ?>
                                    <?php if ($po['status'] == 'sent'): ?>
                                    <!-- Accept Order Button for Supplier -->
                                    <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/confirm') ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-emerald-500 text-white hover:bg-emerald-600 rounded text-xs font-medium transition-colors" title="Accept Order">
                                            <i class="fas fa-check mr-1"></i>Accept
                                        </button>
                                    </form>
                                    <?php elseif ($po['status'] == 'confirmed'): ?>
                                    <!-- Mark Prepared Button for Supplier -->
                                    <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/prepare') ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-purple-500 text-white hover:bg-purple-600 rounded text-xs font-medium transition-colors" title="Mark as Prepared">
                                            <i class="fas fa-box mr-1"></i>Prepare
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                <?php elseif ($role === 'logistics_coordinator'): ?>
                                    <?php if ($po['status'] == 'prepared'): ?>
                                        <?php
                                        // Check if delivery already exists for this PO
                                        $deliveryModel = new \App\Models\DeliveryModel();
                                        $existingDelivery = $deliveryModel->where('purchase_order_id', $po['id'])->first();
                                        if (!$existingDelivery): ?>
                                            <!-- Schedule Delivery Button for Logistics -->
                                            <a href="<?= base_url('deliveries?schedule=' . $po['id']) ?>" class="inline-flex items-center px-2.5 py-1 bg-purple-500 text-white hover:bg-purple-600 rounded text-xs font-medium transition-colors" title="Schedule Delivery">
                                                <i class="fas fa-truck mr-1"></i>Schedule
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($role === 'central_admin'): ?>
                                    <?php if ($po['status'] == 'draft'): ?>
                                    <!-- Send PO Button for Admin -->
                                    <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/send') ?>" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded text-xs font-medium transition-colors" title="Send">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                    <?php elseif ($po['status'] == 'prepared'): ?>
                                        <?php
                                        // Check if delivery already exists for this PO
                                        $deliveryModel = new \App\Models\DeliveryModel();
                                        $existingDelivery = $deliveryModel->where('purchase_order_id', $po['id'])->first();
                                        if (!$existingDelivery): ?>
                                            <!-- Schedule Delivery Button for Admin -->
                                            <a href="<?= base_url('deliveries?schedule=' . $po['id']) ?>" class="inline-flex items-center px-2.5 py-1 bg-purple-500 text-white hover:bg-purple-600 rounded text-xs font-medium transition-colors" title="Schedule Delivery">
                                                <i class="fas fa-truck mr-1"></i>Schedule
                                            </a>
                                        <?php endif; ?>
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
                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No purchase orders found</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Purchase Order Modal -->
<?php if ($role === 'central_admin'): ?>
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-4xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" action="<?= base_url('purchase-orders/store') ?>" id="createForm">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-shopping-cart text-emerald-500 mr-2"></i>Create Purchase Order
                    </h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Approved Request Selection -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-amber-800 mb-2">
                            <i class="fas fa-file-alt mr-1"></i>Create from Approved Request
                        </label>
                        <select name="purchase_request_id" id="purchase_request_id" class="w-full px-4 py-2.5 bg-white border border-amber-300 rounded-lg focus:border-amber-500 outline-none transition-all cursor-pointer">
                            <option value="">-- Select Approved Request (Optional) --</option>
                            <?php if (!empty($approved_requests)): ?>
                            <?php foreach ($approved_requests as $request): ?>
                            <option value="<?= $request['id'] ?>" data-branch-id="<?= $request['branch_id'] ?>"><?= esc($request['request_number']) ?> - <?= esc($request['branch_name']) ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <p class="text-xs text-amber-600 mt-2"><i class="fas fa-info-circle mr-1"></i>Items will be automatically loaded from the selected request</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" id="supplier_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="" data-user-id="">Select Supplier</option>
                                <?php if (!empty($suppliers)): ?>
                                <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['supplier_id'] ?>" data-user-id="<?= $supplier['id'] ?>"><?= esc($supplier['username']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Products will load based on selected supplier</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span class="text-red-500">*</span></label>
                            <select name="branch_id" id="branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">Select Branch</option>
                                <?php if (!empty($branches)): ?>
                                <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <div class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-lg">
                                <i class="fab fa-paypal text-blue-600 text-xl"></i>
                                <span class="font-medium text-blue-800">PayPal</span>
                            </div>
                            <input type="hidden" name="payment_method" value="paypal">
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-list text-emerald-500 mr-2"></i>Order Items
                            </h4>
                            <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button>
                        </div>
                        <!-- Message for supplier selection -->
                        <div id="supplierMessage" class="text-center py-4 text-gray-500 text-sm bg-gray-50 rounded-lg mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Please select a supplier first to load their products
                        </div>
                        <div class="overflow-x-auto" id="itemsTableContainer" style="display: none;">
                            <table class="w-full" id="itemsTable">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase" style="width:40%">Product</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:15%">Qty</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Unit Price</th>
                                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase" style="width:15%">Total</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody" class="divide-y divide-gray-100">
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <th colspan="3" class="px-3 py-2 text-right text-sm font-medium text-gray-600">Subtotal:</th>
                                        <th id="subtotal" class="px-3 py-2 text-right text-sm font-medium text-gray-800">₱0.00</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="px-3 py-2 text-right text-sm font-medium text-gray-600">Tax (12%):</th>
                                        <th id="tax" class="px-3 py-2 text-right text-sm font-medium text-gray-800">₱0.00</th>
                                        <th></th>
                                    </tr>
                                    <tr class="border-t-2 border-gray-300">
                                        <th colspan="3" class="px-3 py-2 text-right text-base font-bold text-gray-800">Total:</th>
                                        <th id="total-amount" class="px-3 py-2 text-right text-base font-bold text-emerald-600">₱0.00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Optional notes"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create PO
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
// Current product options (will be updated when supplier is selected)
let productOptionsTemplate = '';
let supplierSelected = false;

// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCreateModal();
});

// Calculate item total
function calculateItemTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = (qty * price).toFixed(2);
    row.querySelector('.item-total').textContent = '₱' + parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Calculate all totals
function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        subtotal += (qty * price);
        calculateItemTotal(row);
    });
    const tax = subtotal * 0.12;
    const totalAmount = subtotal + tax;
    document.getElementById('subtotal').textContent = '₱' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('tax').textContent = '₱' + tax.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('total-amount').textContent = '₱' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
}

// Attach event listeners to row
function attachRowListeners(row) {
    row.querySelector('.product-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const costPrice = selectedOption.getAttribute('data-price') || 0;
        row.querySelector('.price-input').value = costPrice;
        calculateItemTotal(row);
        calculateTotals();
    });
    row.querySelector('.quantity-input').addEventListener('input', function() {
        calculateItemTotal(row);
        calculateTotals();
    });
    row.querySelector('.price-input').addEventListener('input', function() {
        calculateItemTotal(row);
        calculateTotals();
    });
    
    // Remove item button
    const removeBtn = row.querySelector('.remove-item');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const rows = document.querySelectorAll('#itemsBody .item-row');
            if (rows.length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('You must have at least one item');
            }
        });
    }
}

// Create new item row
function createItemRow(productId = '', quantity = 1, unitPrice = 0) {
    const tbody = document.getElementById('itemsBody');
    
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td class="px-3 py-2">
            <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select text-sm" required>
                <option value="">Select Product</option>
                ${productOptionsTemplate}
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="quantities[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center quantity-input text-sm" min="1" value="${quantity}" required>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="unit_prices[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center price-input text-sm" step="0.01" min="0" value="${unitPrice}" required>
        </td>
        <td class="px-3 py-2 text-right font-medium text-gray-800 item-total text-sm">₱0.00</td>
        <td class="px-3 py-2 text-center">
            <button type="button" class="remove-item inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    const productSelect = newRow.querySelector('.product-select');
    if (productId) productSelect.value = productId;
    
    tbody.appendChild(newRow);
    attachRowListeners(newRow);
    calculateItemTotal(newRow);
    calculateTotals();
    return newRow;
}

// Clear all items
function clearAllItems() {
    const tbody = document.getElementById('itemsBody');
    tbody.innerHTML = '';
    calculateTotals();
}

// Show/hide items table
function showItemsTable() {
    document.getElementById('supplierMessage').style.display = 'none';
    document.getElementById('itemsTableContainer').style.display = 'block';
}

function hideItemsTable() {
    document.getElementById('supplierMessage').style.display = 'block';
    document.getElementById('itemsTableContainer').style.display = 'none';
}

// Add item row button
document.getElementById('addItemBtn')?.addEventListener('click', function() {
    if (!supplierSelected) {
        alert('Please select a supplier first');
        return;
    }
    createItemRow();
});

// Supplier selection - load products
document.getElementById('supplier_id')?.addEventListener('change', function() {
    const supplierId = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const userId = selectedOption.getAttribute('data-user-id');
    
    if (!supplierId || !userId) {
        supplierSelected = false;
        productOptionsTemplate = '';
        clearAllItems();
        hideItemsTable();
        return;
    }
    
    // Show loading
    document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading supplier products...';
    document.getElementById('supplierMessage').style.display = 'block';
    document.getElementById('itemsTableContainer').style.display = 'none';
    
    // Fetch products for this supplier user (by user ID)
    fetch(`<?= base_url('supplier/user/') ?>${userId}/products-json`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products.length > 0) {
                supplierSelected = true;
                // Build options from supplier's own products with stock info
                let options = '';
                data.products.forEach(product => {
                    const price = product.price || 0;
                    const sku = product.sku || '';
                    const stock = product.stock || 0;
                    const stockLabel = stock > 0 ? ` [Stock: ${stock}]` : ' [Out of Stock]';
                    options += `<option value="${product.id}" data-price="${price}">${product.name}${sku ? ' (' + sku + ')' : ''} - ₱${parseFloat(price).toFixed(2)}${stockLabel}</option>`;
                });
                productOptionsTemplate = options;
                
                // Clear existing items and show table
                clearAllItems();
                showItemsTable();
                
                // Add first item row
                createItemRow();
            } else {
                // No products for this supplier
                supplierSelected = false;
                productOptionsTemplate = '';
                document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-exclamation-circle text-amber-500 mr-1"></i> This supplier has no products yet. Please select a different supplier.';
                document.getElementById('supplierMessage').style.display = 'block';
                document.getElementById('itemsTableContainer').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching supplier products:', error);
            supplierSelected = false;
            document.getElementById('supplierMessage').innerHTML = '<i class="fas fa-exclamation-circle text-red-500 mr-1"></i> Error loading products. Please try again.';
            document.getElementById('supplierMessage').style.display = 'block';
            document.getElementById('itemsTableContainer').style.display = 'none';
        });
});

// Purchase request selection - auto-fill branch
document.getElementById('purchase_request_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const branchId = selectedOption.getAttribute('data-branch-id');
    if (branchId) {
        document.getElementById('branch_id').value = branchId;
    }
});

// Form validation
document.getElementById('createForm')?.addEventListener('submit', function(e) {
    const productSelects = document.querySelectorAll('#itemsBody .product-select');
    let hasValidProduct = false;
    
    productSelects.forEach(function(sel) {
        if (sel.value && sel.value !== '') {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        alert('Please add at least one product to the order.');
        return false;
    }
    
    if (!document.getElementById('supplier_id').value) {
        e.preventDefault();
        alert('Please select a supplier.');
        return false;
    }
});

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
            const m1 = search === '' || row.dataset.ponumber.includes(search) || row.dataset.supplier.includes(search) || row.dataset.branch.includes(search);
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
