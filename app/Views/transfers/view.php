<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Transfer Details';
$title = 'Transfer Details';
?>

<!-- Header Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6 flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Transfer Details</h1>
                <p class="text-gray-500 text-sm"><?= esc($transfer['transfer_number']) ?></p>
            </div>
        </div>
        <a href="<?= base_url('transfers') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Transfers
        </a>
    </div>
</div>

<!-- Transfer Information Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Transfer Information
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Transfer Number -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-hashtag text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Transfer Number</p>
                    <p class="text-base font-semibold text-gray-800"><?= esc($transfer['transfer_number']) ?></p>
                </div>
            </div>

            <!-- Request Date -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-calendar text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Request Date</p>
                    <p class="text-base font-semibold text-gray-800"><?= date('M d, Y', strtotime($transfer['request_date'])) ?></p>
                </div>
            </div>

            <!-- From Branch -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-arrow-right text-red-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">From Branch</p>
                    <p class="text-base font-semibold text-gray-800"><?= esc($transfer['from_branch_name']) ?></p>
                </div>
            </div>

            <!-- To Branch -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-arrow-left text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">To Branch</p>
                    <p class="text-base font-semibold text-gray-800"><?= esc($transfer['to_branch_name']) ?></p>
                </div>
            </div>

            <!-- Requested By -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-user text-purple-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Requested By</p>
                    <p class="text-base font-semibold text-gray-800"><?= esc($transfer['requested_by_name']) ?></p>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-flag text-amber-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</p>
                    <?php
                    $statusConfig = [
                        'pending' => ['color' => 'amber', 'icon' => 'clock'],
                        'approved' => ['color' => 'blue', 'icon' => 'thumbs-up'],
                        'in_transit' => ['color' => 'purple', 'icon' => 'truck'],
                        'completed' => ['color' => 'emerald', 'icon' => 'check-circle'],
                        'rejected' => ['color' => 'red', 'icon' => 'times-circle']
                    ];
                    $config = $statusConfig[$transfer['status']] ?? ['color' => 'gray', 'icon' => 'question'];
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-<?= $config['color'] ?>-100 text-<?= $config['color'] ?>-700">
                        <i class="fas fa-<?= $config['icon'] ?> mr-1"></i>
                        <?= ucfirst(str_replace('_', ' ', $transfer['status'])) ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if ($transfer['approved_by_name']): ?>
        <!-- Approval Information -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Approval Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-user-check text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Approved By</p>
                        <p class="text-base font-semibold text-gray-800"><?= esc($transfer['approved_by_name']) ?></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Approved At</p>
                        <p class="text-base font-semibold text-gray-800"><?= date('M d, Y H:i', strtotime($transfer['approved_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($transfer['completed_at']): ?>
        <!-- Completion Information -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-start">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Completed At</p>
                    <p class="text-base font-semibold text-gray-800"><?= date('M d, Y H:i', strtotime($transfer['completed_at'])) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($transfer['notes']): ?>
        <!-- Notes -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-start">
                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-sticky-note text-gray-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Notes</p>
                    <p class="text-base text-gray-700"><?= esc($transfer['notes']) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tracking Timeline -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-route text-blue-600 mr-2"></i>
                Tracking Timeline
            </h3>
            <div class="space-y-4">
                <!-- Step 1: Created -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full <?= $transfer['status'] != 'rejected' ? 'bg-blue-100' : 'bg-gray-100' ?> flex items-center justify-center mr-4">
                        <i class="fas fa-plus <?= $transfer['status'] != 'rejected' ? 'text-blue-600' : 'text-gray-400' ?>"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium <?= $transfer['status'] != 'rejected' ? 'text-gray-800' : 'text-gray-400' ?>">Transfer Created</p>
                        <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y h:i A', strtotime($transfer['created_at'])) ?></p>
                        <p class="text-xs text-gray-600 mt-1">Requested by <?= esc($transfer['requested_by_name']) ?></p>
                    </div>
                </div>

                <!-- Step 2: Approved/Rejected -->
                <?php if ($transfer['approved_at']): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full <?= $transfer['status'] == 'rejected' ? 'bg-red-100' : 'bg-emerald-100' ?> flex items-center justify-center mr-4">
                        <i class="fas <?= $transfer['status'] == 'rejected' ? 'fa-times text-red-600' : 'fa-check text-emerald-600' ?>"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium <?= $transfer['status'] == 'rejected' ? 'text-red-700' : 'text-gray-800' ?>">
                            <?= $transfer['status'] == 'rejected' ? 'Transfer Rejected' : 'Transfer Approved' ?>
                        </p>
                        <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y h:i A', strtotime($transfer['approved_at'])) ?></p>
                        <p class="text-xs text-gray-600 mt-1">By <?= esc($transfer['approved_by_name']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Step 3: Scheduled -->
                <?php if ($transfer['scheduled_at'] && $transfer['status'] != 'rejected'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Transfer Scheduled</p>
                        <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y h:i A', strtotime($transfer['scheduled_at'])) ?></p>
                        <p class="text-xs text-gray-600 mt-1">Scheduled for: <?= date('M d, Y', strtotime($transfer['scheduled_date'])) ?></p>
                    </div>
                </div>
                <?php elseif ($transfer['status'] == 'approved'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-400">Awaiting Schedule</p>
                        <p class="text-xs text-gray-500 mt-1">Logistics will schedule the delivery</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Step 4: Dispatched (In Transit) -->
                <?php if ($transfer['dispatched_at'] && $transfer['status'] != 'rejected'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-4">
                        <i class="fas fa-truck text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Dispatched (In Transit)</p>
                        <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y h:i A', strtotime($transfer['dispatched_at'])) ?></p>
                        <p class="text-xs text-gray-600 mt-1">Inventory deducted from <?= esc($transfer['from_branch_name']) ?></p>
                    </div>
                </div>
                <?php elseif (in_array($transfer['status'], ['scheduled', 'approved']) && $transfer['status'] != 'rejected'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                        <i class="fas fa-truck text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-400">Awaiting Dispatch</p>
                        <p class="text-xs text-gray-500 mt-1">Logistics will dispatch when ready</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Step 5: Received (Completed) -->
                <?php if ($transfer['received_at'] && $transfer['status'] == 'completed'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center mr-4">
                        <i class="fas fa-check-double text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Transfer Received & Completed</p>
                        <p class="text-xs text-gray-500 mt-1"><?= date('M d, Y h:i A', strtotime($transfer['received_at'])) ?></p>
                        <p class="text-xs text-gray-600 mt-1">Inventory added to <?= esc($transfer['to_branch_name']) ?></p>
                    </div>
                </div>
                <?php elseif ($transfer['status'] == 'in_transit'): ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                        <i class="fas fa-check-double text-gray-400"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-400">Awaiting Receipt</p>
                        <p class="text-xs text-gray-500 mt-1">Destination branch will receive the transfer</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Items Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-box text-emerald-600 mr-2"></i>
            Transfer Items
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Product</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">SKU</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Unit</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Quantity</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide">Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= esc($item['product_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= esc($item['sku']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= esc($item['unit']) ?></td>
                                <td class="px-6 py-4 text-sm text-center font-semibold text-gray-800"><?= $item['quantity'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $item['quantity_received'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= $item['quantity_received'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                                <p>No items found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Action Buttons Card -->
<?php 
$showActions = false;
$userBranchId = session()->get('branch_id');

// Determine if user can take action
if ($role == 'central_admin' && $transfer['status'] == 'pending') {
    $showActions = true; // Approve/Reject
} elseif (($role == 'central_admin' || $role == 'logistics_coordinator') && $transfer['status'] == 'approved') {
    $showActions = true; // Schedule or Dispatch
} elseif (($role == 'central_admin' || $role == 'logistics_coordinator') && $transfer['status'] == 'scheduled') {
    $showActions = true; // Dispatch
} elseif ($transfer['status'] == 'in_transit' && (($role == 'branch_manager' && $userBranchId == $transfer['to_branch_id']) || $role == 'central_admin')) {
    $showActions = true; // Receive
}
?>

<?php if ($showActions): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-tasks text-emerald-600 mr-2"></i>
            Actions
        </h3>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Central Admin: Approve/Reject -->
            <?php if ($role == 'central_admin' && $transfer['status'] == 'pending'): ?>
                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/approve') ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm" onclick="return confirm('Approve this transfer request?')">
                        <i class="fas fa-check mr-2"></i> Approve Transfer
                    </button>
                </form>
                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/reject') ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors shadow-sm" onclick="return confirm('Reject this transfer request?')">
                        <i class="fas fa-times mr-2"></i> Reject Transfer
                    </button>
                </form>
            <?php endif; ?>

            <!-- Logistics: Schedule -->
            <?php if (($role == 'central_admin' || $role == 'logistics_coordinator') && $transfer['status'] == 'approved'): ?>
                <button onclick="openScheduleModal(<?= $transfer['id'] ?>, '<?= esc($transfer['transfer_number']) ?>')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-calendar mr-2"></i> Schedule Transfer
                </button>
            <?php endif; ?>

            <!-- Logistics: Dispatch -->
            <?php if (($role == 'central_admin' || $role == 'logistics_coordinator') && ($transfer['status'] == 'approved' || $transfer['status'] == 'scheduled')): ?>
                <button onclick="openDispatchModal(<?= $transfer['id'] ?>, '<?= esc($transfer['transfer_number']) ?>')" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-truck mr-2"></i> Dispatch Now
                </button>
            <?php endif; ?>

            <!-- Destination Branch: Receive -->
            <?php if ($transfer['status'] == 'in_transit' && (($role == 'branch_manager' && $userBranchId == $transfer['to_branch_id']) || $role == 'central_admin')): ?>
                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/receive') ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm" onclick="return confirm('Receive this transfer? Inventory will be added to your branch.')">
                        <i class="fas fa-check-double mr-2"></i> Receive Transfer
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

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
                    <p class="text-blue-100 text-sm" id="scheduleTransferNumber"><?= esc($transfer['transfer_number']) ?></p>
                </div>
            </div>
            <button onclick="closeScheduleModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="scheduleForm" method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/schedule') ?>">
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

<!-- Dispatch Transfer Modal -->
<div id="dispatchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-truck text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Dispatch Transfer</h3>
                    <p class="text-purple-100 text-sm" id="dispatchTransferNumber"><?= esc($transfer['transfer_number']) ?></p>
                </div>
            </div>
            <button onclick="closeDispatchModal()" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="dispatchForm" method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/dispatch') ?>">
            <?= csrf_field() ?>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-600 mr-2"></i>Select Driver <span class="text-red-500">*</span>
                    </label>
                    <select name="driver_id" id="driver_id" required onchange="updateDriverInfo()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select Driver</option>
                        <?php if (!empty($drivers)): ?>
                            <?php foreach ($drivers as $driver): ?>
                                <option value="<?= $driver['id'] ?>" 
                                        data-name="<?= esc($driver['name']) ?>" 
                                        data-phone="<?= esc($driver['phone']) ?>" 
                                        data-vehicle="<?= esc($driver['vehicle_number']) ?>">
                                    <?= esc($driver['name']) ?> - <?= esc($driver['vehicle_number']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="hidden" name="driver_name" id="driver_name">
                    <input type="hidden" name="driver_phone" id="driver_phone">
                    <input type="hidden" name="vehicle_info" id="vehicle_info">
                </div>

                <div id="driverDetails" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-purple-900 mb-2">Driver Details:</p>
                    <div class="space-y-1 text-sm text-purple-800">
                        <p><i class="fas fa-user mr-2"></i><span id="displayDriverName">-</span></p>
                        <p><i class="fas fa-phone mr-2"></i><span id="displayDriverPhone">-</span></p>
                        <p><i class="fas fa-car mr-2"></i><span id="displayVehicle">-</span></p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-amber-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Warning:</strong> Dispatching will deduct inventory from the source branch immediately.
                    </p>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="closeDispatchModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-truck mr-2"></i>Dispatch Now
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openScheduleModal(transferId, transferNumber) {
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openDispatchModal(transferId, transferNumber) {
    document.getElementById('dispatchModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDispatchModal() {
    document.getElementById('dispatchModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function updateDriverInfo() {
    const select = document.getElementById('driver_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const driverName = selectedOption.getAttribute('data-name');
        const driverPhone = selectedOption.getAttribute('data-phone');
        const vehicle = selectedOption.getAttribute('data-vehicle');
        
        // Update hidden fields
        document.getElementById('driver_name').value = driverName;
        document.getElementById('driver_phone').value = driverPhone;
        document.getElementById('vehicle_info').value = vehicle;
        
        // Update display
        document.getElementById('displayDriverName').textContent = driverName;
        document.getElementById('displayDriverPhone').textContent = driverPhone;
        document.getElementById('displayVehicle').textContent = vehicle;
        document.getElementById('driverDetails').classList.remove('hidden');
    } else {
        document.getElementById('driverDetails').classList.add('hidden');
    }
}

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeScheduleModal();
        closeDispatchModal();
    }
});
</script>

<?= $this->endSection() ?>

