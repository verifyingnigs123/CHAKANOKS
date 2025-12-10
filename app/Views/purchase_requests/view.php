<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Request Details';
$title = 'Purchase Request Details';
$grandTotal = 0;
foreach ($items as $item) {
    $grandTotal += $item['total_price'] ?? 0;
}
?>

<!-- Header with Actions -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800"><?= esc($request['request_number']) ?></h1>
        <p class="text-gray-500 text-sm mt-1">Purchase Request Details</p>
    </div>
    <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
        <a href="<?= base_url('purchase-requests') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <a href="<?= base_url('purchase-requests/print/' . $request['id']) ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
            <i class="fas fa-print mr-2"></i> Print
        </a>
        <?php if (($role == 'central_admin') && $request['status'] == 'pending'): ?>
        <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/approve') ?>" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="fas fa-check mr-2"></i> Approve
            </button>
        </form>
        <button onclick="showRejectModal()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-times mr-2"></i> Reject
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- Request Info Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-info-circle text-emerald-500 mr-2"></i>Request Information
        </h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Request Number</p>
                <p class="font-semibold text-gray-800"><?= esc($request['request_number']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Branch</p>
                <p class="font-semibold text-gray-800"><?= esc($request['branch_name']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Requested By</p>
                <p class="font-semibold text-gray-800"><?= esc($request['requested_by_name']) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Date</p>
                <p class="font-semibold text-gray-800"><?= date('M d, Y h:i A', strtotime($request['created_at'])) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Status</p>
                <?php if ($request['status'] == 'approved'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <i class="fas fa-check-circle mr-1"></i> Approved
                </span>
                <?php elseif ($request['status'] == 'rejected'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    <i class="fas fa-times-circle mr-1"></i> Rejected
                </span>
                <?php elseif ($request['status'] == 'pending'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                    <i class="fas fa-clock mr-1"></i> Pending
                </span>
                <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                    <?= ucfirst($request['status']) ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Priority</p>
                <?php if ($request['priority'] == 'urgent'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                    <i class="fas fa-exclamation-circle mr-1"></i> Urgent
                </span>
                <?php elseif ($request['priority'] == 'high'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                    <i class="fas fa-arrow-up mr-1"></i> High
                </span>
                <?php elseif ($request['priority'] == 'normal'): ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                    Normal
                </span>
                <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                    Low
                </span>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                <p class="font-bold text-emerald-600 text-lg">₱<?= number_format($grandTotal, 2) ?></p>
            </div>
            <?php if (!empty($request['approved_at'])): ?>
            <div>
                <p class="text-sm text-gray-500 mb-1">Approved At</p>
                <p class="font-semibold text-gray-800"><?= date('M d, Y h:i A', strtotime($request['approved_at'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($request['notes'])): ?>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-1">Notes</p>
            <p class="text-gray-700"><?= esc($request['notes']) ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($request['rejection_reason'])): ?>
        <div class="mt-6 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-1">Rejection Reason</p>
            <p class="text-red-600"><?= esc($request['rejection_reason']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Items Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-boxes text-emerald-500 mr-2"></i>Requested Items
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Unit Price</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $i = 1; foreach ($items as $item): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500"><?= $i++ ?></td>
                    <td class="px-6 py-4 font-medium text-gray-800"><?= esc($item['product_name']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= esc($item['sku'] ?? '-') ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= esc($item['unit'] ?? 'pcs') ?></td>
                    <td class="px-6 py-4 text-center text-gray-800"><?= $item['quantity'] ?></td>
                    <td class="px-6 py-4 text-right text-gray-600">₱<?= number_format($item['unit_price'] ?? 0, 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-gray-800">₱<?= number_format($item['total_price'] ?? 0, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="6" class="px-6 py-4 text-right font-semibold text-gray-700">Grand Total:</td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-600 text-lg">₱<?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeRejectModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Reject Purchase Request</h3>
                <button onclick="closeRejectModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="post" action="<?= base_url('purchase-requests/' . $request['id'] . '/reject') ?>">
                <?= csrf_field() ?>
                <div class="p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" rows="3" required 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-red-500 outline-none" 
                        placeholder="Please provide a reason..."></textarea>
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRejectModal();
    }
});
</script>
<?= $this->endSection() ?>
