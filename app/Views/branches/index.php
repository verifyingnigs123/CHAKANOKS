<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Branches';
$title = 'Branches';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           id="searchInput"
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" 
                           placeholder="Search by name, code, city..." 
                           value="<?= esc($search ?? '') ?>">
                </div>
            </div>
            <div class="w-full md:w-48">
                <select id="statusFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="active" <?= ($status ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <?php if ($role == 'central_admin'): ?>
            <a href="<?= base_url('branches/create') ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Add Branch
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="branchesTable">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">City</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Manager</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <?php if ($role == 'central_admin'): ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="branchesBody">
                <?php if (!empty($branches)): ?>
                    <?php foreach ($branches as $branch): ?>
                    <tr class="hover:bg-gray-50 transition-colors branch-row" 
                        data-code="<?= esc(strtolower($branch['code'])) ?>"
                        data-name="<?= esc(strtolower($branch['name'])) ?>"
                        data-city="<?= esc(strtolower($branch['city'] ?? '')) ?>"
                        data-manager="<?= esc(strtolower($branch['manager_name'] ?? '')) ?>"
                        data-status="<?= esc($branch['status']) ?>">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800"><?= esc($branch['code']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-700"><?= esc($branch['name']) ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= esc($branch['city'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= esc($branch['manager_name'] ?? '-') ?></td>
                        <td class="px-6 py-4">
                            <?php if ($branch['is_franchise']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fas fa-store mr-1"></i> Franchise
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                <i class="fas fa-building mr-1"></i> Corporate
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($branch['status'] == 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span> Active
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span> Inactive
                            </span>
                            <?php endif; ?>
                        </td>
                        <?php if ($role == 'central_admin'): ?>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                   onclick="openEditModal(<?= $branch['id'] ?>, '<?= esc($branch['name']) ?>', '<?= esc($branch['code']) ?>', '<?= esc($branch['address'] ?? '') ?>', '<?= esc($branch['city'] ?? '') ?>', '<?= esc($branch['phone'] ?? '') ?>', '<?= esc($branch['email'] ?? '') ?>', '<?= esc($branch['manager_id'] ?? '') ?>', '<?= esc($branch['status']) ?>', <?= $branch['is_franchise'] ? 1 : 0 ?>)"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                   onclick="openDeleteModal(<?= $branch['id'] ?>, '<?= esc($branch['name']) ?>')"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- No Results Message -->
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-building text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No branches found</p>
                <p class="text-gray-400 text-sm">Try adjusting your search or filters</p>
            </div>
        </div>
    </div>
</div>

<?php 
// Get managers for edit modal
$managers = $managers ?? [];
?>

<!-- Edit Branch Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto">
            <form method="post" id="editBranchForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-building text-blue-500 mr-2"></i>Edit Branch
                    </h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="editName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="editCode" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="editAddress" rows="2"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" id="editCity"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" id="editPhone"
                                   maxlength="11" pattern="[0-9]{11}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="09XXXXXXXXX">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="editEmail"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                            <select name="manager_id" id="editManager"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Manager</option>
                                <?php 
                                $userModel = new \App\Models\UserModel();
                                $managersList = $userModel->whereIn('role', ['branch_manager'])->findAll();
                                foreach ($managersList as $manager): ?>
                                <option value="<?= $manager['id'] ?>"><?= esc($manager['full_name'] ?? $manager['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="editStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_franchise" id="editFranchise" value="1"
                                       class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-gray-700">Is Franchise</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Branch Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete Branch
                </h3>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-building text-red-500 text-2xl"></i>
                </div>
                <p class="text-gray-600 text-center">Are you sure you want to delete branch <strong id="deleteBranchName" class="text-gray-800"></strong>?</p>
                <p class="text-sm text-gray-500 text-center mt-2">This action cannot be undone. All related data may be affected.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <a href="#" id="deleteLink"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors inline-flex items-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Edit Modal Functions
function openEditModal(id, name, code, address, city, phone, email, managerId, status, isFranchise) {
    document.getElementById('editBranchForm').action = '<?= base_url('branches/update/') ?>' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editCode').value = code;
    document.getElementById('editAddress').value = address;
    document.getElementById('editCity').value = city;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editEmail').value = email;
    document.getElementById('editManager').value = managerId;
    document.getElementById('editStatus').value = status;
    document.getElementById('editFranchise').checked = isFranchise == 1;
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Delete Modal Functions
function openDeleteModal(id, name) {
    document.getElementById('deleteBranchName').textContent = name;
    document.getElementById('deleteLink').href = '<?= base_url('branches/delete/') ?>' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.branch-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('branchesBody');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value.toLowerCase();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const code = row.dataset.code || '';
            const name = row.dataset.name || '';
            const city = row.dataset.city || '';
            const manager = row.dataset.manager || '';
            const status = row.dataset.status || '';
            
            // Check search match
            const matchesSearch = searchTerm === '' || 
                code.includes(searchTerm) || 
                name.includes(searchTerm) || 
                city.includes(searchTerm) || 
                manager.includes(searchTerm);
            
            // Check status match
            const matchesStatus = statusValue === '' || status === statusValue;
            
            // Show/hide row
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            tbody.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            tbody.classList.remove('hidden');
        }
    }
    
    // Real-time search on input
    searchInput.addEventListener('input', filterTable);
    
    // Filter on status change
    statusFilter.addEventListener('change', filterTable);
    
    // Initial filter if there's a pre-filled value
    if (searchInput.value || statusFilter.value) {
        filterTable();
    }
});
</script>
<?= $this->endSection() ?>
