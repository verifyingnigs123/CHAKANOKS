<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $page_title = 'Franchise Applications'; ?>

<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Franchise Applications</h2>
        <p class="text-gray-500 text-sm mt-1">Manage franchise applications and partners</p>
    </div>
    <?php if ($pending_count > 0): ?>
    <div class="flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-800 rounded-lg">
        <i class="fas fa-clock"></i>
        <span class="font-medium"><?= $pending_count ?> pending application<?= $pending_count > 1 ? 's' : '' ?></span>
    </div>
    <?php endif; ?>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="<?= base_url('franchise/applications') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= empty($current_status) ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            All
        </a>
        <a href="<?= base_url('franchise/applications?status=pending') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $current_status === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pending
        </a>
        <a href="<?= base_url('franchise/applications?status=under_review') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $current_status === 'under_review' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Under Review
        </a>
        <a href="<?= base_url('franchise/applications?status=approved') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $current_status === 'approved' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Approved
        </a>
        <a href="<?= base_url('franchise/applications?status=rejected') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $current_status === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Rejected
        </a>
    </div>
</div>

<!-- Applications Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Application #</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Applicant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Investment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p>No applications found</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($applications as $app): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-medium text-gray-800"><?= $app['application_number'] ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-800"><?= esc($app['applicant_name']) ?></p>
                            <p class="text-sm text-gray-500"><?= esc($app['email']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-800"><?= esc($app['city']) ?></p>
                        <p class="text-sm text-gray-500"><?= esc($app['province']) ?></p>
                    </td>
                    <td class="px-6 py-4 text-gray-800">
                        <?= $app['investment_capital'] ? '₱' . number_format($app['investment_capital'], 2) : '-' ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'under_review' => 'bg-blue-100 text-blue-700',
                            'approved' => 'bg-emerald-100 text-emerald-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'converted' => 'bg-purple-100 text-purple-700',
                        ];
                        $color = $statusColors[$app['status']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?= $color ?>">
                            <?= ucfirst(str_replace('_', ' ', $app['status'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?= date('M d, Y', strtotime($app['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('franchise/applications/view/' . $app['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
