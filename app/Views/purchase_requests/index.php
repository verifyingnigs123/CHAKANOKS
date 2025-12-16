<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Requests';
$title = 'Purchase Requests';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <?php if ($role == 'central_admin'): ?>
            <div class="w-full md:w-40">
                <select id="branchFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                    <option value="<?= esc(strtolower($branch['name'])) ?>"><?= esc($branch['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="w-full md:w-36">
                <select id="statusFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="converted">Converted</option>
                </select>
            </div>
            <div class="w-full md:w-36">
                <select id="priorityFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="normal">Normal</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Request number...">
            </div>
            <?php if ($role !== 'central_admin'): ?>
            <button onclick="openCreateModal()" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Create Request
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Request Number</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Requested By</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Priority</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $request): ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" data-number="<?= esc(strtolower($request['request_number'])) ?>" data-branch="<?= esc(strtolower($request['branch_name'])) ?>" data-status="<?= esc($request['status']) ?>" data-priority="<?= esc($request['priority']) ?>">
                        <td class="px-6 py-4"><span class="font-semibold text-gray-800"><?= esc($request['request_number']) ?></span></td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($request['branch_name']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($request['requested_by_name']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($request['status'] == 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"><i class="fas fa-check-circle mr-1"></i> Approved</span>
                            <?php elseif ($request['status'] == 'rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                            <?php elseif ($request['status'] == 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i class="fas fa-clock mr-1"></i> Pending</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"><?= ucfirst($request['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($request['priority'] == 'urgent'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="fas fa-exclamation-circle mr-1"></i> Urgent</span>
                            <?php elseif ($request['priority'] == 'high'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700"><i class="fas fa-arrow-up mr-1"></i> High</span>
                            <?php elseif ($request['priority'] == 'normal'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Normal</span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Low</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm"><?= date('M d, Y', strtotime($request['created_at'])) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="<?= base_url('purchase-requests/view/' . $request['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-eye mr-1"></i> View</a>
                                <?php if ($role == 'central_admin' && $request['status'] == 'pending'): ?>
                                <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/approve') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-check mr-1"></i> Approve</button></form>
                                <button onclick="showRejectModal(<?= $request['id'] ?>)" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-times mr-1"></i> Reject</button>
                                <?php elseif ($role == 'central_admin' && $request['status'] == 'approved'): ?>
                                    <?php
                                    // Check if PO already exists for this request
                                    $poModel = new \App\Models\PurchaseOrderModel();
                                    $existingPO = $poModel->where('purchase_request_id', $request['id'])->first();
                                    if (!$existingPO): ?>
                                        <a href="<?= base_url('purchase-orders/create-from-request/' . $request['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-purple-500 text-white hover:bg-purple-600 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-shopping-cart mr-1"></i> Create PO</a>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> PO Created
                                        </span>
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
                <i class="fas fa-file-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No purchase requests found</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Purchase Request Modal -->
<?php if ($role !== 'central_admin'): ?>
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-3xl sm:w-full mx-auto max-h-[90vh] overflow-y-auto">
            <form method="post" action="<?= base_url('purchase-requests/store') ?>" id="createForm">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-alt text-emerald-500 mr-2"></i>New Purchase Request
                    </h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="hidden" name="branch_id" value="<?= $branch_id ?? '' ?>">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                            <div class="px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700">
                                <?= esc(session()->get('username') ?? 'N/A') ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" id="modalSupplierSelect" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                <option value="">-- Select Supplier --</option>
                                <?php if (!empty($suppliers)): ?>
                                <?php foreach ($suppliers as $sup): ?>
                                <option value="<?= $sup['supplier_id'] ?>" data-user-id="<?= $sup['id'] ?>"><?= esc($sup['username']) ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Additional notes (optional)"></textarea>
                    </div>
                    
                    <!-- Products Section -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-boxes text-emerald-500 mr-2"></i>Products
                            </h4>
                            <button type="button" id="addProductBtn" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i>Add
                            </button>
                        </div>
                        <!-- Message for loading/errors -->
                        <div id="noSupplierMessage" class="text-center py-4 text-gray-500 text-sm" style="display: none;"></div>
                        <div class="overflow-x-auto" id="productsTableContainer">
                            <table class="w-full" id="modalProductsTable">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase" style="width:60%">Product</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:25%">Qty</th>
                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase" style="width:15%"></th>
                                    </tr>
                                </thead>
                                <tbody id="modalProductsBody" class="divide-y divide-gray-100">
                                    <tr class="product-row">
                                        <td class="px-3 py-2">
                                            <select name="products[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all product-select text-sm" required>
                                                <option value="">Select Product</option>
                                                <?php if (!empty($products)): ?>
                                                <?php foreach ($products as $prod): ?>
                                                <option value="<?= $prod['id'] ?>" data-price="<?= $prod['cost_price'] ?? 0 ?>" data-type="system"><?= esc($prod['name']) ?> (<?= esc($prod['sku']) ?>) - ₱<?= number_format($prod['cost_price'] ?? 0, 2) ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="quantities[]" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all text-center text-sm" placeholder="Qty" min="1" value="1" required>
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" class="remove-product inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeRejectModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Reject Purchase Request</h3>
                <button onclick="closeRejectModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form id="rejectForm" method="post"><?= csrf_field() ?>
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-red-500 outline-none" placeholder="Please provide a reason..."></textarea>
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
<?php if ($role !== 'central_admin'): ?>
// No Central Admin products - only supplier products allowed
// Current product type: 'supplier' only
let currentProductType = 'supplier';

// Create Modal Functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Add Product Row
document.getElementById('addProductBtn').addEventListener('click', function() {
    const tbody = document.getElementById('modalProductsBody');
    const firstRow = tbody.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('input[name="quantities[]"]').value = 1;
    tbody.appendChild(newRow);
});

// Remove Product Row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-product') || e.target.closest('.remove-product')) {
        const rows = document.querySelectorAll('#modalProductsBody .product-row');
        if (rows.length > 1) {
            e.target.closest('.product-row').remove();
        } else {
            alert('You must have at least one product');
        }
    }
});

// Load supplier products via AJAX or show Central Admin products
const modalSupplierSelect = document.getElementById('modalSupplierSelect');

function loadProducts(userId) {
    const noSupplierMsg = document.getElementById('noSupplierMessage');
    const tableContainer = document.getElementById('productsTableContainer');
    
    if (!userId) {
        // No supplier selected - show message
        currentProductType = 'supplier';
        noSupplierMsg.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Please select a supplier to see available products.';
        noSupplierMsg.style.display = 'block';
        tableContainer.style.display = 'none';
        return;
    }
    
    // Show loading
    noSupplierMsg.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Loading supplier products...';
    noSupplierMsg.style.display = 'block';
    tableContainer.style.display = 'none';
    
    // Fetch supplier products via AJAX (by user ID)
    fetch('<?= base_url('supplier/user') ?>/' + userId + '/products-json')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products) {
                if (data.products.length === 0) {
                    noSupplierMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> This supplier has no products yet. Please select a different supplier.';
                    noSupplierMsg.style.display = 'block';
                    tableContainer.style.display = 'none';
                    // Clear product dropdowns to prevent form submission with invalid data
                    updateProductDropdownsWithOptions('<option value="">No products available</option>');
                } else {
                    // Build options from supplier products with stock quantity
                    currentProductType = 'supplier';
                    let options = '<option value="">Select Product</option>';
                    data.products.forEach(function(product) {
                        let stock = product.stock || 0;
                        let stockLabel = stock > 0 ? ' [Stock: ' + stock + ']' : ' [Out of Stock]';
                        options += '<option value="' + product.id + '" data-price="' + (product.price || 0) + '" data-stock="' + stock + '" data-type="supplier">' + 
                                   product.name + (product.sku ? ' (' + product.sku + ')' : '') + ' - ₱' + parseFloat(product.price || 0).toFixed(2) + stockLabel + '</option>';
                    });
                    updateProductDropdownsWithOptions(options);
                    noSupplierMsg.style.display = 'none';
                    tableContainer.style.display = 'block';
                }
            } else {
                noSupplierMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Failed to load products';
                noSupplierMsg.style.display = 'block';
                tableContainer.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
            noSupplierMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Error loading products';
            noSupplierMsg.style.display = 'block';
            tableContainer.style.display = 'none';
        });
}

function updateProductDropdownsWithOptions(optionsHtml) {
    const productSelects = document.querySelectorAll('#modalProductsBody .product-select');
    productSelects.forEach(function(sel) {
        sel.innerHTML = optionsHtml;
    });
}

if (modalSupplierSelect) {
    modalSupplierSelect.addEventListener('change', function() {
        // Get the user_id from the selected option's data attribute
        const selectedOption = this.options[this.selectedIndex];
        const userId = selectedOption.getAttribute('data-user-id');
        loadProducts(userId);
    });
}

// Form validation before submit
document.getElementById('createForm').addEventListener('submit', function(e) {
    const productSelects = document.querySelectorAll('#modalProductsBody .product-select');
    let hasValidProduct = false;
    
    productSelects.forEach(function(sel) {
        if (sel.value && sel.value !== '' && sel.value !== 'No products available') {
            hasValidProduct = true;
        }
    });
    
    if (!hasValidProduct) {
        e.preventDefault();
        alert('Please select at least one valid product before submitting.');
        return false;
    }
    
    // Check if supplier is selected but no products are available
    const supplierSelect = document.getElementById('modalSupplierSelect');
    const tableContainer = document.getElementById('productsTableContainer');
    if (supplierSelect.value && tableContainer.style.display === 'none') {
        e.preventDefault();
        alert('The selected supplier has no products. Please select a different supplier.');
        return false;
    }
});
<?php endif; ?>

// Reject Modal Functions
function showRejectModal(id) { document.getElementById('rejectForm').action = '<?= base_url('purchase-requests') ?>/' + id + '/reject'; document.getElementById('rejectModal').classList.remove('hidden'); }
function closeRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }

// Close modals on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        <?php if ($role !== 'central_admin'): ?>
        closeCreateModal();
        <?php endif; ?>
        closeRejectModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const branchFilter = document.getElementById('branchFilter');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const branch = branchFilter ? branchFilter.value.toLowerCase() : '';
        const status = statusFilter.value.toLowerCase();
        const priority = priorityFilter.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const m1 = search === '' || row.dataset.number.includes(search);
            const m2 = branch === '' || row.dataset.branch === branch;
            const m3 = status === '' || row.dataset.status === status;
            const m4 = priority === '' || row.dataset.priority === priority;
            
            if (m1 && m2 && m3 && m4) { row.style.display = ''; count++; } 
            else { row.style.display = 'none'; }
        });
        
        noResults.classList.toggle('hidden', count > 0);
        tbody.classList.toggle('hidden', count === 0);
    }
    
    searchInput.addEventListener('input', filterTable);
    if (branchFilter) branchFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    priorityFilter.addEventListener('change', filterTable);
    
    // Auto-open create modal if ?create=1 is in URL (from dashboard)
    <?php if ($role !== 'central_admin'): ?>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('create') === '1') {
        openCreateModal();
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
