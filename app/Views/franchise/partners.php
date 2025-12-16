<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $page_title = 'Franchise Partners'; ?>

<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Franchise Partners</h2>
        <p class="text-gray-500 text-sm mt-1">Manage franchise branches and allocate supplies</p>
    </div>
    <a href="<?= base_url('franchise/applications') ?>" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
        <i class="fas fa-file-alt mr-2"></i>View Applications
    </a>
</div>

<!-- Partners Grid -->
<?php if (empty($partners)): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <i class="fas fa-store text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-lg font-semibold text-gray-600 mb-2">No Franchise Partners Yet</h3>
    <p class="text-gray-500 mb-4">Approved applications will appear here once converted to branches.</p>
    <a href="<?= base_url('franchise/applications') ?>" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600">
        <i class="fas fa-file-alt mr-2"></i>Review Applications
    </a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($partners as $partner): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-store text-white text-xl"></i>
                </div>
                <div class="text-white">
                    <h3 class="font-semibold"><?= esc($partner['name']) ?></h3>
                    <p class="text-sm opacity-90"><?= esc($partner['code']) ?></p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                    <span class="text-gray-600"><?= esc($partner['address']) ?>, <?= esc($partner['city']) ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-gray-400"></i>
                    <span class="text-gray-600"><?= esc($partner['phone']) ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-user text-gray-400"></i>
                    <span class="text-gray-600"><?= esc($partner['manager_name']) ?: 'No manager assigned' ?></span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                <a href="<?= base_url('franchise/supply-allocation') ?>" class="flex-1 px-3 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 text-center text-sm">
                    <i class="fas fa-truck mr-1"></i>Allocate Supplies
                </a>
                <a href="<?= base_url('inventory?branch_id=' . $partner['id']) ?>" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm" title="View Inventory">
                    <i class="fas fa-boxes"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
