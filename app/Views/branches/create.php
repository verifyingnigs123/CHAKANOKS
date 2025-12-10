<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Create Branch';
$title = 'Create Branch';
$managers = $managers ?? [];
?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('branches') ?>" 
       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Back to List
    </a>
</div>

<!-- Error Messages -->
<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-3"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
    <div class="flex items-start">
        <i class="fas fa-exclamation-circle mr-3 mt-0.5"></i>
        <div>
            <p class="font-medium mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-building text-emerald-500 mr-2"></i> Branch Information
        </h3>
    </div>
    <div class="p-6">
        <form method="post" action="<?= base_url('branches/store') ?>">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                           value="<?= old('name') ?>"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                           placeholder="Enter branch name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Branch Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required
                           value="<?= old('code') ?>"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                           placeholder="e.g., BR001">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="2"
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all resize-none"
                          placeholder="Enter full address"><?= old('address') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city"
                           value="<?= old('city') ?>"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                           placeholder="City name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone"
                           value="<?= old('phone') ?>"
                           maxlength="11"
                           pattern="[0-9]{11}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                           placeholder="09XXXXXXXXX">
                    <p class="text-xs text-gray-500 mt-1">11-digit PH mobile number</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                           value="<?= old('email') ?>"
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                           placeholder="branch@example.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                    <select name="manager_id"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                        <option value="">Select Manager</option>
                        <?php if (!empty($managers)): ?>
                            <?php foreach ($managers as $manager): ?>
                            <option value="<?= $manager['id'] ?>" <?= old('manager_id') == $manager['id'] ? 'selected' : '' ?>><?= esc($manager['full_name'] ?? $manager['username']) ?> (<?= esc($manager['email']) ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($managers)): ?>
                    <p class="text-xs text-amber-600 mt-1"><i class="fas fa-info-circle mr-1"></i>No branch managers available. Create a user with Branch Manager role first.</p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                        <option value="active" <?= old('status', 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="pending" <?= old('status') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_franchise" value="1" 
                               <?= old('is_franchise') ? 'checked' : '' ?>
                               class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                        <span class="ml-2 text-gray-700">Is Franchise</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="<?= base_url('branches') ?>" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i> Create Branch
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
