<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Branch Details</h1>
            <p class="text-gray-600 mt-1">View branch information</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= base_url('branches') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Branches
            </a>
            <a href="<?= base_url('branches/edit/' . $branch['id']) ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Branch
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Branch Information -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-building text-blue-600"></i>Branch Information
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Branch Name</label>
                        <p class="text-gray-900 font-semibold text-lg"><?= esc($branch['name']) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Branch Code</label>
                        <p class="text-gray-900 font-mono bg-gray-100 px-3 py-1 rounded inline-block"><?= esc($branch['code']) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Type</label>
                        <?php 
                        $typeColors = [
                            'main' => 'bg-purple-100 text-purple-800',
                            'branch' => 'bg-blue-100 text-blue-800',
                            'franchise' => 'bg-emerald-100 text-emerald-800',
                        ];
                        $typeColor = $typeColors[$branch['type'] ?? 'branch'] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $typeColor ?>">
                            <i class="fas fa-<?= ($branch['type'] ?? 'branch') === 'franchise' ? 'handshake' : 'building' ?> mr-1"></i>
                            <?= ucfirst($branch['type'] ?? 'branch') ?>
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <?php 
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-red-100 text-red-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                        ];
                        $statusColor = $statusColors[$branch['status']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusColor ?>">
                            <?= ucfirst($branch['status']) ?>
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                        <p class="text-gray-900"><?= esc($branch['address'] ?: 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                        <p class="text-gray-900"><?= esc($branch['city'] ?: 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                        <p class="text-gray-900"><?= esc($branch['phone'] ?: 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900"><?= esc($branch['email'] ?: 'N/A') ?></p>
                    </div>
                    <?php if (!empty($branch['manager_name'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Manager Name</label>
                        <p class="text-gray-900"><?= esc($branch['manager_name']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Branch Manager Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-user-tie text-emerald-600"></i>Branch Manager
                </h2>
            </div>
            <div class="p-6">
                <?php if ($branch_manager): ?>
                <div class="text-center">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-emerald-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900"><?= esc($branch_manager['full_name']) ?></h3>
                    <p class="text-gray-500 text-sm"><?= esc($branch_manager['email']) ?></p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 mt-2">
                        Branch Manager
                    </span>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status</span>
                        <span class="font-medium <?= $branch_manager['status'] === 'active' ? 'text-green-600' : 'text-red-600' ?>">
                            <?= ucfirst($branch_manager['status']) ?>
                        </span>
                    </div>
                    <?php if (!empty($branch_manager['phone'])): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium text-gray-900"><?= esc($branch_manager['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Created</span>
                        <span class="font-medium text-gray-900"><?= date('M d, Y', strtotime($branch_manager['created_at'])) ?></span>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">No branch manager assigned</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap gap-6 text-sm text-gray-500">
            <div>
                <span class="font-medium">Created:</span>
                <?= date('F d, Y h:i A', strtotime($branch['created_at'])) ?>
            </div>
            <?php if ($branch['updated_at']): ?>
            <div>
                <span class="font-medium">Last Updated:</span>
                <?= date('F d, Y h:i A', strtotime($branch['updated_at'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
