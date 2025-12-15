<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $page_title = 'View Application'; ?>

<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Application Details</h2>
        <p class="text-gray-500 text-sm mt-1"><?= $application['application_number'] ?></p>
    </div>
    <a href="<?= base_url('franchise/applications') ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
        <i class="fas fa-arrow-left mr-2"></i>Back to List
    </a>
</div>

<!-- Status Banner -->
<div class="bg-gradient-to-r <?= 
    $application['status'] == 'pending' ? 'from-amber-500 to-orange-500' : 
    ($application['status'] == 'under_review' ? 'from-blue-500 to-cyan-500' : 
    ($application['status'] == 'approved' ? 'from-emerald-500 to-teal-500' : 
    ($application['status'] == 'rejected' ? 'from-red-500 to-pink-500' : 'from-purple-500 to-indigo-500')))
?> rounded-xl p-4 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas <?= 
                    $application['status'] == 'pending' ? 'fa-clock' : 
                    ($application['status'] == 'under_review' ? 'fa-search' : 
                    ($application['status'] == 'approved' ? 'fa-check-circle' : 
                    ($application['status'] == 'rejected' ? 'fa-times-circle' : 'fa-store')))
                ?> text-xl"></i>
            </div>
            <div>
                <p class="text-sm opacity-90">Application Status</p>
                <p class="text-xl font-bold"><?= ucfirst(str_replace('_', ' ', $application['status'])) ?></p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm opacity-90">Submitted</p>
            <p class="text-lg font-semibold"><?= date('M d, Y', strtotime($application['created_at'])) ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Applicant Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user text-emerald-500 mr-2"></i>Applicant Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Full Name</p>
                    <p class="font-medium text-gray-800"><?= esc($application['applicant_name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800"><?= esc($application['email']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium text-gray-800"><?= esc($application['phone']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Business Name</p>
                    <p class="font-medium text-gray-800"><?= esc($application['business_name']) ?: '-' ?></p>
                </div>
            </div>
        </div>

        <!-- Location Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Proposed Location
            </h3>
            <div>
                <p class="text-sm text-gray-500">Complete Address</p>
                <p class="font-medium text-gray-800"><?= esc($application['proposed_location']) ?></p>
            </div>
        </div>

        <!-- Business Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase text-purple-500 mr-2"></i>Business Details
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Investment Capital</p>
                    <p class="font-medium text-gray-800 text-lg">
                        <?= $application['investment_capital'] ? '₱' . number_format($application['investment_capital'], 2) : 'Not specified' ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Business Experience</p>
                    <p class="text-gray-800"><?= nl2br(esc($application['business_experience'])) ?: 'Not provided' ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Motivation</p>
                    <p class="text-gray-800"><?= nl2br(esc($application['motivation'])) ?: 'Not provided' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tasks text-amber-500 mr-2"></i>Actions
            </h3>
            
            <?php if ($application['status'] == 'pending'): ?>
            <form method="post" action="<?= base_url('franchise/applications/' . $application['id'] . '/start-review') ?>" class="mb-3">
                <?= csrf_field() ?>
                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-search mr-2"></i>Start Review
                </button>
            </form>
            <?php endif; ?>
            
            <?php if ($role == 'central_admin' && in_array($application['status'], ['pending', 'under_review'])): ?>
            <button type="button" onclick="openApproveModal()" class="w-full px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors mb-3">
                <i class="fas fa-check mr-2"></i>Approve
            </button>
            <button type="button" onclick="openRejectModal()" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-times mr-2"></i>Reject
            </button>
            <?php endif; ?>
            
            <?php if ($role == 'central_admin' && $application['status'] == 'approved'): ?>
            <form method="post" action="<?= base_url('franchise/applications/' . $application['id'] . '/convert') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                    <i class="fas fa-store mr-2"></i>Convert to Branch
                </button>
            </form>
            <?php endif; ?>
            
            <?php if ($application['status'] == 'converted' && $application['branch_id']): ?>
            <a href="<?= base_url('branches/view/' . $application['branch_id']) ?>" class="block w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors text-center">
                <i class="fas fa-building mr-2"></i>View Branch
            </a>
            <?php endif; ?>
        </div>

        <!-- Review Info -->
        <?php if ($application['reviewed_by']): ?>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-sm font-semibold text-blue-800 mb-3">Review Information</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-blue-600">Reviewed By</p>
                    <p class="font-medium text-blue-800"><?= esc($application['reviewed_by_name']) ?></p>
                </div>
                <div>
                    <p class="text-blue-600">Reviewed At</p>
                    <p class="font-medium text-blue-800"><?= date('M d, Y H:i', strtotime($application['reviewed_at'])) ?></p>
                </div>
                <?php if ($application['review_notes']): ?>
                <div>
                    <p class="text-blue-600">Notes</p>
                    <p class="text-blue-800"><?= nl2br(esc($application['review_notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($application['approved_by']): ?>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-6">
            <h3 class="text-sm font-semibold text-emerald-800 mb-3">Approval Information</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-emerald-600">Approved By</p>
                    <p class="font-medium text-emerald-800"><?= esc($application['approved_by_name']) ?></p>
                </div>
                <div>
                    <p class="text-emerald-600">Approved At</p>
                    <p class="font-medium text-emerald-800"><?= date('M d, Y H:i', strtotime($application['approved_at'])) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeApproveModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full">
            <form method="post" action="<?= base_url('franchise/applications/' . $application['id'] . '/approve') ?>">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Approve Application</h3>
                </div>
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea name="review_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeRejectModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full">
            <form method="post" action="<?= base_url('franchise/applications/' . $application['id'] . '/reject') ?>">
                <?= csrf_field() ?>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Reject Application</h3>
                </div>
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                    <textarea name="review_notes" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}
function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
<?= $this->endSection() ?>
