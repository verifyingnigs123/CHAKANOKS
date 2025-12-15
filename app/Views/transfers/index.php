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
                    <option value="scheduled">Scheduled</option>
                    <option value="in_transit">In Transit</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <?php if ($role === 'central_admin' || $role === 'branch_manager' || $role === 'franchise_manager'): ?>
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
                            <?php elseif ($transfer['status'] == 'scheduled'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700"><i class="fas fa-calendar-check mr-1"></i> Scheduled</span>
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
                                <button onclick="viewTransferDetails(<?= $transfer['id'] ?>)" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-eye mr-1"></i> View</button>
                                
                                <?php if ($role == 'central_admin' && $transfer['status'] == 'pending'): ?>
                                    <!-- Only Central Admin can approve/reject transfers -->
                                    <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/approve') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Approve this transfer request?')"><i class="fas fa-check mr-1"></i> Approve</button></form>
                                    <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/reject') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Reject this transfer request?')"><i class="fas fa-times mr-1"></i> Reject</button></form>
                                <?php endif; ?>
                                
                                <?php if (($transfer['status'] == 'approved' || $transfer['status'] == 'scheduled') && ($role == 'central_admin' || $role == 'logistics_coordinator')): ?>
                                    <!-- Logistics can schedule or dispatch -->
                                    <?php if ($transfer['status'] == 'approved'): ?>
                                        <button onclick="openScheduleModal(<?= $transfer['id'] ?>, '<?= esc($transfer['transfer_number']) ?>')" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors"><i class="fas fa-calendar mr-1"></i> Schedule</button>
                                    <?php endif; ?>
                                    <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/dispatch') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Dispatch this transfer? Inventory will be deducted from source branch.')"><i class="fas fa-truck mr-1"></i> Dispatch</button></form>
                                <?php endif; ?>
                                
                                <?php if ($transfer['status'] == 'in_transit'): ?>
                                    <?php 
                                    // Destination branch can receive the transfer
                                    $userBranchId = session()->get('branch_id');
                                    if (($role == 'branch_manager' && $userBranchId == $transfer['to_branch_id']) || $role == 'central_admin'): 
                                    ?>
                                    <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/receive') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Receive this transfer? Inventory will be added to your branch.')"><i class="fas fa-check-double mr-1"></i> Receive</button></form>
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
                <i class="fas fa-exchange-alt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No transfers found</p>
            </div>
        </div>
    </div>
</div>

<!-- Create Transfer Modal -->
<?php if ($role === 'central_admin' || $role === 'branch_manager' || $role === 'franchise_manager'): ?>
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

<!-- View Transfer Details Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exchange-alt text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Transfer Details</h3>
                    <p class="text-blue-100 text-sm" id="modalTransferNumber">Loading...</p>
                </div>
            </div>
            <button onclick="closeViewModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-180px)]">
            <div class="p-6">
                <!-- Transfer Information Card -->
                <div class="bg-gray-50 rounded-xl p-5 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Transfer Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-hashtag text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Transfer Number</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailTransferNumber">-</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-calendar text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Request Date</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailRequestDate">-</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-arrow-right text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">From Branch</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailFromBranch">-</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-arrow-left text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">To Branch</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailToBranch">-</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-user text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Requested By</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailRequestedBy">-</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-flag text-amber-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                <p class="text-sm font-semibold" id="detailStatus">-</p>
                            </div>
                        </div>
                    </div>
                    <div id="approvalInfo" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-user-check text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Approved By</p>
                                    <p class="text-sm font-semibold text-gray-800" id="detailApprovedBy">-</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-clock text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Approved At</p>
                                    <p class="text-sm font-semibold text-gray-800" id="detailApprovedAt">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="completionInfo" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Completed At</p>
                                <p class="text-sm font-semibold text-gray-800" id="detailCompletedAt">-</p>
                            </div>
                        </div>
                    </div>
                    <div id="notesInfo" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-sticky-note text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Notes</p>
                                <p class="text-sm text-gray-700" id="detailNotes">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Items Card -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-box text-emerald-600 mr-2"></i>
                        Transfer Items
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Received</th>
                                </tr>
                            </thead>
                            <tbody id="detailItems">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                        <p>Loading items...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer with Actions -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
            <div id="modalActions" class="flex items-center gap-2 flex-wrap">
                <!-- Actions will be dynamically added here -->
            </div>
            <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Close
            </button>
        </div>
    </div>
</div>

<!-- Schedule Transfer Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Schedule Transfer</h3>
                    <p class="text-blue-100 text-sm" id="scheduleTransferNumber">-</p>
                </div>
            </div>
            <button onclick="closeScheduleModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="scheduleForm" method="post" action="">
            <?= csrf_field() ?>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Scheduled Date
                    </label>
                    <input type="date" name="scheduled_date" required min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Select the date when this transfer will be dispatched.
                    </p>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>Schedule Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Update To Branch options to hide the selected From Branch
    updateToBranchOptions();
    // Load products if From Branch is already selected
    const fromBranchId = document.getElementById('from_branch_id').value;
    if (fromBranchId) {
        loadBranchProducts(fromBranchId);
    }
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Schedule Modal functions
function openScheduleModal(transferId, transferNumber) {
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('scheduleTransferNumber').textContent = transferNumber;
    document.getElementById('scheduleForm').action = `<?= base_url('transfers/') ?>${transferId}/schedule`;
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeScheduleModal();
        closeViewModal();
    }
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

// Load products based on selected branch
function loadBranchProducts(branchId) {
    if (!branchId) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Select Product</option>';
        });
        return;
    }
    
    fetch(`<?= base_url('inventory/get-branch-products') ?>?branch_id=${branchId}`)
        .then(response => response.json())
        .then(data => {
            const options = '<option value="">Select Product</option>' + 
                data.products.map(p => `<option value="${p.id}">${p.name} (${p.sku}) - Available: ${p.quantity}</option>`).join('');
            
            document.querySelectorAll('.product-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = options;
                select.value = currentValue;
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
}

// Update To Branch dropdown to exclude selected From Branch
function updateToBranchOptions() {
    const fromBranchId = document.getElementById('from_branch_id').value;
    const toBranchSelect = document.getElementById('to_branch_id');
    const allOptions = Array.from(toBranchSelect.querySelectorAll('option'));
    
    allOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = '';
        } else if (option.value === fromBranchId) {
            option.style.display = 'none';
            if (toBranchSelect.value === fromBranchId) {
                toBranchSelect.value = '';
            }
        } else {
            option.style.display = '';
        }
    });
}

// From branch change - load products and update quantities
document.getElementById('from_branch_id')?.addEventListener('change', function() {
    const branchId = this.value;
    loadBranchProducts(branchId);
    updateToBranchOptions();
    updateAllAvailableQuantities();
});

// Product select change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        updateAvailableQuantity(e.target);
    }
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

// View Transfer Details Modal Functions
function viewTransferDetails(transferId) {
    // Show modal
    document.getElementById('viewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Fetch transfer details via AJAX
    fetch(`<?= base_url('transfers/view/') ?>${transferId}`)
        .then(response => response.text())
        .then(html => {
            // Parse the HTML response to extract data
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Extract data from the response (we'll use a JSON endpoint instead)
            // For now, let's fetch JSON data
            return fetch(`<?= base_url('transfers/get-details/') ?>${transferId}`);
        })
        .then(response => response.json())
        .then(data => {
            populateTransferModal(data);
        })
        .catch(error => {
            console.error('Error fetching transfer details:', error);
            alert('Failed to load transfer details');
            closeViewModal();
        });
}

function populateTransferModal(data) {
    // Update header
    document.getElementById('modalTransferNumber').textContent = data.transfer.transfer_number;
    
    // Update transfer information
    document.getElementById('detailTransferNumber').textContent = data.transfer.transfer_number;
    document.getElementById('detailRequestDate').textContent = formatDate(data.transfer.request_date);
    document.getElementById('detailFromBranch').textContent = data.transfer.from_branch_name;
    document.getElementById('detailToBranch').textContent = data.transfer.to_branch_name;
    document.getElementById('detailRequestedBy').textContent = data.transfer.requested_by_name;
    
    // Update status with color
    const statusElement = document.getElementById('detailStatus');
    const statusColors = {
        'pending': 'text-amber-700',
        'approved': 'text-blue-700',
        'in_transit': 'text-purple-700',
        'completed': 'text-emerald-700',
        'rejected': 'text-red-700'
    };
    const statusIcons = {
        'pending': 'fa-clock',
        'approved': 'fa-thumbs-up',
        'in_transit': 'fa-truck',
        'completed': 'fa-check-circle',
        'rejected': 'fa-times-circle'
    };
    statusElement.className = `text-sm font-semibold ${statusColors[data.transfer.status] || 'text-gray-700'}`;
    statusElement.innerHTML = `<i class="fas ${statusIcons[data.transfer.status]} mr-1"></i>${capitalizeFirst(data.transfer.status)}`;
    
    // Show/hide approval info
    if (data.transfer.approved_by_name) {
        document.getElementById('approvalInfo').classList.remove('hidden');
        document.getElementById('detailApprovedBy').textContent = data.transfer.approved_by_name;
        document.getElementById('detailApprovedAt').textContent = formatDateTime(data.transfer.approved_at);
    } else {
        document.getElementById('approvalInfo').classList.add('hidden');
    }
    
    // Show/hide completion info
    if (data.transfer.completed_at) {
        document.getElementById('completionInfo').classList.remove('hidden');
        document.getElementById('detailCompletedAt').textContent = formatDateTime(data.transfer.completed_at);
    } else {
        document.getElementById('completionInfo').classList.add('hidden');
    }
    
    // Show/hide notes
    if (data.transfer.notes) {
        document.getElementById('notesInfo').classList.remove('hidden');
        document.getElementById('detailNotes').textContent = data.transfer.notes;
    } else {
        document.getElementById('notesInfo').classList.add('hidden');
    }
    
    // Populate items table
    const itemsBody = document.getElementById('detailItems');
    if (data.items && data.items.length > 0) {
        itemsBody.innerHTML = data.items.map(item => `
            <tr class="border-b border-gray-100 hover:bg-white transition-colors">
                <td class="px-4 py-3 text-sm text-gray-800">${escapeHtml(item.product_name)}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${escapeHtml(item.sku)}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${escapeHtml(item.unit)}</td>
                <td class="px-4 py-3 text-sm text-center font-semibold text-gray-800">${item.quantity}</td>
                <td class="px-4 py-3 text-sm text-center">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${item.quantity_received > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'}">
                        ${item.quantity_received}
                    </span>
                </td>
            </tr>
        `).join('');
    } else {
        itemsBody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No items found</td></tr>';
    }
    
    // Populate action buttons
    const actionsDiv = document.getElementById('modalActions');
    actionsDiv.innerHTML = '';
    
    const role = '<?= $role ?>';
    const userBranchId = <?= session()->get('branch_id') ?? 'null' ?>;
    
    if (role === 'central_admin' && data.transfer.status === 'pending') {
        actionsDiv.innerHTML = `
            <form method="post" action="<?= base_url('transfers/') ?>${data.transfer.id}/approve" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors" onclick="return confirm('Approve this transfer request?')">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </form>
            <form method="post" action="<?= base_url('transfers/') ?>${data.transfer.id}/reject" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors" onclick="return confirm('Reject this transfer request?')">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
            </form>
        `;
    }
    
    if (data.transfer.status === 'approved' && (role === 'central_admin' || role === 'logistics_coordinator')) {
        actionsDiv.innerHTML += `
            <form method="post" action="<?= base_url('transfers/') ?>${data.transfer.id}/dispatch" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors" onclick="return confirm('Mark this transfer as dispatched?')">
                    <i class="fas fa-truck mr-2"></i>Dispatch
                </button>
            </form>
        `;
    }
    
    if ((data.transfer.status === 'approved' || data.transfer.status === 'in_transit') && 
        ((role === 'branch_manager' && userBranchId == data.transfer.from_branch_id) || role === 'central_admin')) {
        actionsDiv.innerHTML += `
            <form method="post" action="<?= base_url('transfers/') ?>${data.transfer.id}/complete" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors" onclick="return confirm('Complete this transfer? Inventory will be updated.')">
                    <i class="fas fa-check-double mr-2"></i>Complete
                </button>
            </form>
        `;
    }
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Helper functions
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).replace('_', ' ');
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
</script>
<?= $this->endSection() ?>
