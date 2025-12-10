<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inter-Branch Transfers';
$title = 'Transfers';
$role = session()->get('role');
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Search transfer #, branch...">
            </div>
            <div class="w-full md:w-40">
                <select id="statusFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <?php if ($role === 'central_admin' || $role === 'branch_manager'): ?>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Create Transfer
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Transfer #</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">From Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">To Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Requested By</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($transfers)): ?>
                    <?php foreach ($transfers as $transfer): ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" data-number="<?= esc(strtolower($transfer['transfer_number'])) ?>" data-from="<?= esc(strtolower($transfer['from_branch_name'])) ?>" data-to="<?= esc(strtolower($transfer['to_branch_name'])) ?>" data-requestedby="<?= esc(strtolower($transfer['requested_by_name'])) ?>" data-status="<?= esc($transfer['status']) ?>">
                        <td class="px-6 py-4"><span class="font-semibold text-gray-800"><?= esc($transfer['transfer_number']) ?></span></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-2"><i class="fas fa-arrow-right text-red-500 text-xs"></i></div>
                                <span class="text-gray-700"><?= esc($transfer['from_branch_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-2"><i class="fas fa-arrow-left text-emerald-500 text-xs"></i></div>
                                <span class="text-gray-700"><?= esc($transfer['to_branch_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($transfer['requested_by_name']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($transfer['status'] == 'completed'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                            <?php elseif ($transfer['status'] == 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><i class="fas fa-thumbs-up mr-1"></i> Approved</span>
                            <?php elseif ($transfer['status'] == 'in_transit'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700"><i class="fas fa-truck mr-1"></i> In Transit</span>
                            <?php elseif ($transfer['status'] == 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i class="fas fa-clock mr-1"></i> Pending</span>
                            <?php elseif ($transfer['status'] == 'rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"><?= ucfirst($transfer['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm"><?= date('M d, Y', strtotime($transfer['request_date'])) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="<?= base_url('transfers/view/' . $transfer['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-eye mr-1"></i> View</a>
                                <?php if (($role == 'branch_manager' || $role == 'central_admin') && $transfer['status'] == 'pending'): ?>
                                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/approve') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-check mr-1"></i> Approve</button></form>
                                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/reject') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-times mr-1"></i> Reject</button></form>
                                <?php endif; ?>
                                <?php if ($transfer['status'] == 'approved'): ?>
                                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/complete') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Complete this transfer? Inventory will be updated.')"><i class="fas fa-check-double mr-1"></i> Complete</button></form>
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
                <i class="fas fa-exchange-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No transfers found</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Transfer Modal -->
<?php if ($role === 'central_admin' || $role === 'branch_manager'): ?>
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-3xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" action="<?= base_url('transfers/store') ?>" id="createForm">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-exchange-alt text-emerald-500 mr-2"></i>Create Inter-Branch Transfer
                    </h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <!-- Branch Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Branch <span class="text-red-500">*</span></label>
                            <select name="from_branch_id" id="from_branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">Select Source Branch</option>
                                <?php if (!empty($branches)): ?>
                                <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= ($from_branch_id == $branch['id']) ? 'selected' : '' ?>><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To Branch <span class="text-red-500">*</span></label>
                            <select name="to_branch_id" id="to_branch_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">Select Destination Branch</option>
                                <?php if (!empty($branches)): ?>
                                <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Transfer Items -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-boxes text-emerald-500 mr-2"></i>Transfer Items
                            </h4>
                            <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full" id="itemsTable">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase" style="width:50%">Product</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Available</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:20%">Transfer Qty</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:10%"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody" class="divide-y divide-gray-100">
                                    <tr class="item-row">
                                        <td class="px-3 py-2">
                                            <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select text-sm" required>
                                                <option value="">Select Product</option>
                                                <?php if (!empty($products)): ?>
                                                <?php foreach ($products as $product): ?>
                                                <option value="<?= $product['id'] ?>"><?= esc($product['name']) ?> (<?= esc($product['sku']) ?>)</option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <span class="available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600">-</span>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="quantities[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center quantity-input text-sm" min="1" value="1" required>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" class="remove-item inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Transfer notes (optional)"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Create Transfer
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

// Update available quantity when branch or product changes
function updateAvailableQuantity(selectElement) {
    const fromBranchId = document.getElementById('from_branch_id').value;
    const productId = selectElement.value;
    const row = selectElement.closest('.item-row');
    const availableQtySpan = row.querySelector('.available-qty');
    
    if (!fromBranchId || !productId) {
        availableQtySpan.textContent = '-';
        availableQtySpan.className = 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600';
        return;
    }
    
    fetch(`<?= base_url('inventory/get-quantity') ?>?branch_id=${fromBranchId}&product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const qty = data.quantity || 0;
            availableQtySpan.textContent = qty;
            availableQtySpan.className = qty > 0 
                ? 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-bold bg-emerald-100 text-emerald-700'
                : 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-bold bg-red-100 text-red-700';
            const quantityInput = row.querySelector('.quantity-input');
            quantityInput.max = qty;
        })
        .catch(() => {
            availableQtySpan.textContent = '-';
            availableQtySpan.className = 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600';
        });
}

function updateAllAvailableQuantities() {
    document.querySelectorAll('.product-select').forEach(select => {
        updateAvailableQuantity(select);
    });
}

// From branch change - update all quantities
document.getElementById('from_branch_id')?.addEventListener('change', updateAllAvailableQuantities);

// Product select change
document.querySelectorAll('.product-select').forEach(select => {
    select.addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

// Add item row
document.getElementById('addItemBtn')?.addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const firstRow = tbody.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('.quantity-input').value = 1;
    newRow.querySelector('.available-qty').textContent = '-';
    newRow.querySelector('.available-qty').className = 'available-qty inline-flex items-center justify-center w-16 h-8 rounded-lg text-sm font-medium bg-gray-100 text-gray-600';
    tbody.appendChild(newRow);
    newRow.querySelector('.product-select').addEventListener('change', function() {
        updateAvailableQuantity(this);
    });
});

// Remove item row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        const rows = document.querySelectorAll('#itemsBody .item-row');
        if (rows.length > 1) {
            e.target.closest('.item-row').remove();
        } else {
            alert('You must have at least one product');
        }
    }
});

// Table filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const m1 = search === '' || row.dataset.number.includes(search) || row.dataset.from.includes(search) || row.dataset.to.includes(search) || row.dataset.requestedby.includes(search);
            const m2 = status === '' || row.dataset.status === status;
            
            if (m1 && m2) { row.style.display = ''; count++; } 
            else { row.style.display = 'none'; }
        });
        
        noResults.classList.toggle('hidden', count > 0);
        tbody.classList.toggle('hidden', count === 0);
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
<?= $this->endSection() ?>
