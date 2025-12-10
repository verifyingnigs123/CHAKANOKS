<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'User Management';
$title = 'Users';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       id="searchInput"
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                       placeholder="Search by name, username, email..." 
                       value="<?= esc($search ?? '') ?>">
            </div>
            <div class="w-full md:w-40">
                <select id="roleFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Roles</option>
                    <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($filterRole ?? '') == $key ? 'selected' : '' ?>><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-40">
                <select id="statusFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="active" <?= ($filterStatus ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($filterStatus ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <a href="<?= base_url('users/create') ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Create User
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="dataTable">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row"
                        data-name="<?= esc(strtolower($user['full_name'])) ?>"
                        data-username="<?= esc(strtolower($user['username'])) ?>"
                        data-email="<?= esc(strtolower($user['email'])) ?>"
                        data-role="<?= esc($user['role']) ?>"
                        data-branch="<?= esc(strtolower($user['branch_name'] ?? '')) ?>"
                        data-status="<?= esc($user['status']) ?>">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white font-medium"><?= strtoupper(substr($user['username'], 0, 1)) ?></span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800"><?= esc($user['full_name']) ?></p>
                                    <p class="text-sm text-gray-500">@<?= esc($user['username']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?= esc($user['email']) ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($roles[$user['role']] ?? $user['role']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500"><?= esc($user['branch_name'] ?? 'N/A') ?></td>
                        <td class="px-6 py-4">
                            <?php if ($user['status'] == 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span> Active
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span> Inactive
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="<?= base_url('users/edit/' . $user['id']) ?>" 
                                   class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <a href="<?= base_url('users/delete/' . $user['id']) ?>" 
                                   class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors"
                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- No Results -->
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No users found</p>
                <p class="text-gray-400 text-sm">Create a new user to get started</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const name = row.dataset.name || '';
            const username = row.dataset.username || '';
            const email = row.dataset.email || '';
            const role = row.dataset.role || '';
            const branch = row.dataset.branch || '';
            const status = row.dataset.status || '';
            
            const matchesSearch = searchTerm === '' || 
                name.includes(searchTerm) || 
                username.includes(searchTerm) || 
                email.includes(searchTerm) ||
                branch.includes(searchTerm);
            
            const matchesRole = roleValue === '' || role === roleValue;
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesRole && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            tbody.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            tbody.classList.remove('hidden');
        }
    }
    
    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    
    if (searchInput.value || roleFilter.value || statusFilter.value) {
        filterTable();
    }
});
</script>
<?= $this->endSection() ?>
