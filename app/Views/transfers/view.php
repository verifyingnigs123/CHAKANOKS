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
<?php if (($role == 'central_admin' && $transfer['status'] == 'pending') || 
          (($transfer['status'] == 'approved' || $transfer['status'] == 'in_transit') && 
           (($role == 'branch_manager' && session()->get('branch_id') == $transfer['from_branch_id']) || $role == 'central_admin'))): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6">
        <div class="flex items-center gap-3 flex-wrap">
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

            <?php if (($transfer['status'] == 'approved' || $transfer['status'] == 'in_transit') && 
                      (($role == 'branch_manager' && session()->get('branch_id') == $transfer['from_branch_id']) || $role == 'central_admin')): ?>
                <form method="post" action="<?= base_url('transfers/' . $transfer['id'] . '/complete') ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors shadow-sm" onclick="return confirm('Complete this transfer? Inventory will be updated.')">
                        <i class="fas fa-check-double mr-2"></i> Complete Transfer
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

