<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Suppliers';
$title = 'Suppliers';
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
                       placeholder="Search by name, code, contact person, email..." 
                       value="<?= esc($search ?? '') ?>">
            </div>
            <div class="w-full md:w-48">
                <select id="statusFilter"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <option value="active" <?= ($status ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="button" onclick="openCreateModal()"
               class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Add Supplier
            </button>
        </div>
    </div>
</div>

<!-- Mobile Card View -->
<div class="md:hidden space-y-4" id="mobileCards">
    <?php if (!empty($suppliers)): ?>
        <?php foreach ($suppliers as $supplier): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 data-row"
             data-code="<?= esc(strtolower($supplier['code'])) ?>"
             data-name="<?= esc(strtolower($supplier['name'])) ?>"
             data-contact="<?= esc(strtolower($supplier['contact_person'] ?? '')) ?>"
             data-email="<?= esc(strtolower($supplier['email'] ?? '')) ?>"
             data-phone="<?= esc(strtolower($supplier['phone'] ?? '')) ?>"
             data-status="<?= esc($supplier['status']) ?>">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-truck-loading text-cyan-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800"><?= esc($supplier['name']) ?></h3>
                        <span class="font-mono text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= esc($supplier['code']) ?></span>
                    </div>
                </div>
                <?php if ($supplier['status'] == 'active'): ?>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1"></span> Active
                </span>
                <?php else: ?>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></span> Inactive
                </span>
                <?php endif; ?>
            </div>
            
            <div class="space-y-2 text-sm mb-3">
                <?php if (!empty($supplier['contact_person'])): ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-user w-5 text-gray-400"></i>
                    <span><?= esc($supplier['contact_person']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($supplier['email'])): ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-envelope w-5 text-gray-400"></i>
                    <span class="truncate"><?= esc($supplier['email']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($supplier['phone'])): ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-phone w-5 text-gray-400"></i>
                    <span><?= esc($supplier['phone']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <div>
                    <?php if (!empty($supplier['has_account'])): ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        <i class="fas fa-user-check mr-1"></i> <?= esc($supplier['user_account']['username'] ?? 'Has Login') ?>
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                        <i class="fas fa-user-times mr-1"></i> No Account
                    </span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('suppliers/' . $supplier['id'] . '/products') ?>"
                       class="inline-flex items-center justify-center w-9 h-9 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors">
                        <i class="fas fa-boxes"></i>
                    </a>
                    <?php if (empty($supplier['has_account'])): ?>
                    <button type="button" 
                       onclick="openCreateAccountModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>', '<?= esc($supplier['email'] ?? '') ?>', '<?= esc($supplier['contact_person'] ?? $supplier['name']) ?>')"
                       class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                        <i class="fas fa-user-plus"></i>
                    </button>
                    <?php endif; ?>
                    <button type="button" 
                       onclick="openEditModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>', '<?= esc($supplier['code']) ?>', '<?= esc($supplier['contact_person'] ?? '') ?>', '<?= esc($supplier['email'] ?? '') ?>', '<?= esc($supplier['phone'] ?? '') ?>', '<?= esc($supplier['address'] ?? '') ?>', '<?= esc($supplier['payment_terms'] ?? '') ?>', '<?= esc($supplier['delivery_terms'] ?? '') ?>', '<?= esc($supplier['status']) ?>')"
                       class="inline-flex items-center justify-center w-9 h-9 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" 
                       onclick="openDeleteModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>')"
                       class="inline-flex items-center justify-center w-9 h-9 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="dataTable">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden xl:table-cell">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Account</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($suppliers)): ?>
                    <?php foreach ($suppliers as $supplier): ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row"
                        data-code="<?= esc(strtolower($supplier['code'])) ?>"
                        data-name="<?= esc(strtolower($supplier['name'])) ?>"
                        data-contact="<?= esc(strtolower($supplier['contact_person'] ?? '')) ?>"
                        data-email="<?= esc(strtolower($supplier['email'] ?? '')) ?>"
                        data-phone="<?= esc(strtolower($supplier['phone'] ?? '')) ?>"
                        data-status="<?= esc($supplier['status']) ?>">
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs font-semibold text-gray-800 bg-gray-100 px-2 py-1 rounded"><?= esc($supplier['code']) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-9 h-9 bg-cyan-100 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-truck-loading text-cyan-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-800 text-sm"><?= esc($supplier['name']) ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-sm hidden lg:table-cell"><?= esc($supplier['contact_person'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-500 text-xs hidden xl:table-cell"><?= esc($supplier['email'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-gray-500 text-sm hidden lg:table-cell"><?= esc($supplier['phone'] ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <?php if (!empty($supplier['has_account'])): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <i class="fas fa-user-check mr-1"></i> <?= esc($supplier['user_account']['username'] ?? '') ?>
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                <i class="fas fa-user-times mr-1"></i> No Account
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($supplier['status'] == 'active'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1"></span> Active
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1"></span> Inactive
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <a href="<?= base_url('suppliers/' . $supplier['id'] . '/products') ?>"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors" title="Manage Products">
                                    <i class="fas fa-boxes text-sm"></i>
                                </a>
                                <?php if (empty($supplier['has_account'])): ?>
                                <button type="button" 
                                   onclick="openCreateAccountModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>', '<?= esc($supplier['email'] ?? '') ?>', '<?= esc($supplier['contact_person'] ?? $supplier['name']) ?>')"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Create Login Account">
                                    <i class="fas fa-user-plus text-sm"></i>
                                </button>
                                <?php endif; ?>
                                <button type="button" 
                                   onclick="openEditModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>', '<?= esc($supplier['code']) ?>', '<?= esc($supplier['contact_person'] ?? '') ?>', '<?= esc($supplier['email'] ?? '') ?>', '<?= esc($supplier['phone'] ?? '') ?>', '<?= esc($supplier['address'] ?? '') ?>', '<?= esc($supplier['payment_terms'] ?? '') ?>', '<?= esc($supplier['delivery_terms'] ?? '') ?>', '<?= esc($supplier['status']) ?>')"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button type="button" 
                                   onclick="openDeleteModal(<?= $supplier['id'] ?>, '<?= esc($supplier['name']) ?>')"
                                   class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- No Results -->
<div id="noResults" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 px-6 py-12 text-center">
    <div class="flex flex-col items-center">
        <i class="fas fa-truck-loading text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 font-medium">No suppliers found</p>
        <p class="text-gray-400 text-sm">Add a new supplier to get started</p>
    </div>
</div>

<!-- Create Supplier Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto">
            <form method="post" action="<?= base_url('suppliers/store') ?>" id="createForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-truck-loading text-emerald-500 mr-2"></i>Add New Supplier
                    </h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="createName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Enter supplier name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="e.g., SUP-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input type="text" name="contact_person" id="createContactPerson"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Enter contact person name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="createEmail"
                                   pattern="^[a-zA-Z0-9.@]+$"
                                   title="Only letters, numbers, dots and @ allowed"
                                   oninput="this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, '')"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="example@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone"
                                   maxlength="11"
                                   pattern="[0-9]{11}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="09XXXXXXXXX">
                            <p class="text-xs text-gray-500 mt-1">11-digit PH mobile number</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                            <select name="payment_terms"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="PayPal Online" selected>PayPal Online</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Terms</label>
                            <select name="delivery_terms"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Delivery Terms</option>
                                <option value="Same Day">Same Day Delivery</option>
                                <option value="Next Day">Next Day Delivery</option>
                                <option value="3-5 Days">3-5 Business Days</option>
                                <option value="1-2 Weeks">1-2 Weeks</option>
                            </select>
                        </div>
                        
                        <!-- User Account Section -->
                        <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                            <div class="flex items-center mb-3">
                                <input type="checkbox" name="create_account" id="createAccountCheckbox" value="1"
                                       class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                       onchange="toggleAccountFields()">
                                <label for="createAccountCheckbox" class="ml-2 text-sm font-medium text-gray-700">
                                    <i class="fas fa-user-plus text-emerald-500 mr-1"></i>Create Login Account for Supplier
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">This will allow the supplier to login and manage their orders</p>
                        </div>
                        
                        <div id="accountFields" class="md:col-span-2 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                    <input type="text" name="username" id="supplierUsername"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="e.g., supplier_abc">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" id="supplierPassword"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                           placeholder="Enter password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto">
            <form method="post" id="editForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-edit text-amber-500 mr-2"></i>Edit Supplier
                    </h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="editName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Enter supplier name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Code <span class="text-red-500">*</span></label>
                            <input type="text" name="code" id="editCode" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input type="text" name="contact_person" id="editContactPerson"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Enter contact person name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="editEmail"
                                   pattern="^[a-zA-Z0-9.@]+$"
                                   title="Only letters, numbers, dots and @ allowed"
                                   oninput="this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, '')"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="example@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" id="editPhone"
                                   maxlength="11"
                                   pattern="[0-9]{11}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="09XXXXXXXXX">
                            <p class="text-xs text-gray-500 mt-1">11-digit PH mobile number</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="editStatus"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" id="editAddress" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                            <select name="payment_terms" id="editPaymentTerms"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="PayPal Online">PayPal Online</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Terms</label>
                            <select name="delivery_terms" id="editDeliveryTerms"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Delivery Terms</option>
                                <option value="Same Day">Same Day Delivery</option>
                                <option value="Next Day">Next Day Delivery</option>
                                <option value="3-5 Days">3-5 Business Days</option>
                                <option value="1-2 Weeks">1-2 Weeks</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Supplier Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete Supplier
                </h3>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-truck-loading text-red-500 text-2xl"></i>
                </div>
                <p class="text-gray-600 text-center">Are you sure you want to delete supplier <strong id="deleteSupplierName" class="text-gray-800"></strong>?</p>
                <p class="text-sm text-gray-500 text-center mt-2">This action cannot be undone.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <a href="#" id="deleteSupplierLink"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors inline-flex items-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Create Account Modal -->
<div id="createAccountModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateAccountModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <form method="post" action="<?= base_url('suppliers/create-account') ?>" id="createAccountForm">
                <?= csrf_field() ?>
                <input type="hidden" name="supplier_id" id="accountSupplierId">
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user-plus text-blue-500 mr-2"></i>Create Login Account
                    </h3>
                    <button type="button" onclick="closeCreateAccountModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-800">Creating account for: <strong id="accountSupplierName"></strong></p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="newAccountUsername" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., beefsupplier"
                                   onkeypress="return /[a-zA-Z0-9]/.test(event.key)"
                                   onpaste="return false">
                            <p class="text-xs text-gray-500 mt-1">Only letters and numbers allowed</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="newAccountEmail"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="full_name" id="newAccountFullName"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="newAccountPassword" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter password"
                                   onkeypress="return /[a-zA-Z0-9]/.test(event.key)"
                                   onpaste="return false">
                            <p class="text-xs text-gray-500 mt-1">Min 8 chars: uppercase, lowercase, number (no special chars)</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateAccountModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Create Modal
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('createForm').reset();
    document.getElementById('accountFields').classList.add('hidden');
}

// Toggle account fields visibility
function toggleAccountFields() {
    const checkbox = document.getElementById('createAccountCheckbox');
    const accountFields = document.getElementById('accountFields');
    const usernameField = document.getElementById('supplierUsername');
    const passwordField = document.getElementById('supplierPassword');
    
    if (checkbox.checked) {
        accountFields.classList.remove('hidden');
        usernameField.required = true;
        passwordField.required = true;
    } else {
        accountFields.classList.add('hidden');
        usernameField.required = false;
        passwordField.required = false;
        usernameField.value = '';
        passwordField.value = '';
    }
}

// Edit Modal
function openEditModal(id, name, code, contactPerson, email, phone, address, paymentTerms, deliveryTerms, status) {
    document.getElementById('editForm').action = '<?= base_url('suppliers/update/') ?>' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editCode').value = code;
    document.getElementById('editContactPerson').value = contactPerson;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editAddress').value = address;
    document.getElementById('editPaymentTerms').value = paymentTerms;
    document.getElementById('editDeliveryTerms').value = deliveryTerms;
    document.getElementById('editStatus').value = status;
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Delete Modal
function openDeleteModal(id, name) {
    document.getElementById('deleteSupplierName').textContent = name;
    document.getElementById('deleteSupplierLink').href = '<?= base_url('suppliers/delete/') ?>' + id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Create Account Modal
function openCreateAccountModal(supplierId, supplierName, email, fullName) {
    document.getElementById('accountSupplierId').value = supplierId;
    document.getElementById('accountSupplierName').textContent = supplierName;
    document.getElementById('newAccountEmail').value = email || '';
    document.getElementById('newAccountFullName').value = fullName || '';
    document.getElementById('newAccountUsername').value = '';
    document.getElementById('newAccountPassword').value = '';
    document.getElementById('createAccountModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateAccountModal() {
    document.getElementById('createAccountModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('createAccountForm').reset();
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeEditModal();
        closeDeleteModal();
        closeCreateAccountModal();
    }
});

// Table and Card filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    const mobileCards = document.getElementById('mobileCards');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value.toLowerCase();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const code = row.dataset.code || '';
            const name = row.dataset.name || '';
            const contact = row.dataset.contact || '';
            const email = row.dataset.email || '';
            const phone = row.dataset.phone || '';
            const status = row.dataset.status || '';
            
            const matchesSearch = searchTerm === '' || 
                code.includes(searchTerm) || 
                name.includes(searchTerm) || 
                contact.includes(searchTerm) ||
                email.includes(searchTerm) ||
                phone.includes(searchTerm);
            
            const matchesStatus = statusValue === '' || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
            if (tbody) tbody.classList.add('hidden');
            if (mobileCards) mobileCards.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            if (tbody) tbody.classList.remove('hidden');
            if (mobileCards) mobileCards.classList.remove('hidden');
        }
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    
    if (searchInput.value || statusFilter.value) {
        filterTable();
    }
});
</script>
<?= $this->endSection() ?>
