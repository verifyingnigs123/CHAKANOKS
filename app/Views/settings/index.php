<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php 
$settingsArray = [];
foreach ($settings as $setting) {
    $settingsArray[$setting['key']] = $setting;
}
$activeTab = $activeTab ?? 'settings';
?>

<!-- Wizard Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex" aria-label="Tabs">
            <button type="button" 
                    onclick="switchTab('settings')"
                    id="tab-settings"
                    class="tab-btn flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors <?= $activeTab === 'settings' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                <i class="fas fa-cog mr-2"></i>System Settings
            </button>
            <button type="button" 
                    onclick="switchTab('users')"
                    id="tab-users"
                    class="tab-btn flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors <?= $activeTab === 'users' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                <i class="fas fa-users mr-2"></i>User Management
            </button>
            <button type="button" 
                    onclick="switchTab('drivers')"
                    id="tab-drivers"
                    class="tab-btn flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors <?= $activeTab === 'drivers' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>">
                <i class="fas fa-truck mr-2"></i>Drivers
            </button>
        </nav>
    </div>
</div>

<!-- Settings Tab Content -->
<div id="content-settings" class="tab-content <?= $activeTab !== 'settings' ? 'hidden' : '' ?>">
    <form method="post" action="<?= base_url('settings/update') ?>">
        <?= csrf_field() ?>
        
        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-building text-emerald-500 mr-2"></i>General Settings
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">System Name</label>
                        <input type="text" name="settings[system_name]" 
                               value="<?= esc($settingsArray['system_name']['value'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="settings[company_name]" 
                               value="<?= esc($settingsArray['company_name']['value'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Email</label>
                        <input type="email" name="settings[company_email]" 
                               value="<?= esc($settingsArray['company_email']['value'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company Phone</label>
                        <input type="text" name="settings[company_phone]" 
                               value="<?= esc($settingsArray['company_phone']['value'] ?? '') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-peso-sign text-emerald-500 mr-2"></i>Financial Settings
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate (%)</label>
                        <input type="number" name="settings[tax_rate]" step="0.01" min="0" max="100"
                               value="<?= esc($settingsArray['tax_rate']['value'] ?? '12') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency Code</label>
                        <input type="text" name="settings[currency]" 
                               value="<?= esc($settingsArray['currency']['value'] ?? 'PHP') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                        <input type="text" name="settings[currency_symbol]" 
                               value="<?= esc($settingsArray['currency_symbol']['value'] ?? '₱') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- PayPal Integration -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fab fa-paypal text-blue-500 mr-2"></i>PayPal Integration
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <?php if ($paypal_configured): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Configured (<?= ucfirst($paypal_mode) ?>)
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i>Not Configured
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- System Preferences -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-sliders-h text-emerald-500 mr-2"></i>System Preferences
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" name="settings[low_stock_alert]" value="1" id="low_stock_alert"
                               <?= ($settingsArray['low_stock_alert']['value'] ?? '1') == '1' ? 'checked' : '' ?>
                               class="mt-1 w-4 h-4 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500">
                        <label for="low_stock_alert" class="text-sm text-gray-700">Enable Low Stock Alerts</label>
                    </div>
                    <div class="flex items-start space-x-3">
                        <input type="checkbox" name="settings[auto_approve_purchase_requests]" value="1" id="auto_approve"
                               <?= ($settingsArray['auto_approve_purchase_requests']['value'] ?? '0') == '1' ? 'checked' : '' ?>
                               class="mt-1 w-4 h-4 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500">
                        <label for="auto_approve" class="text-sm text-gray-700">Auto-Approve Purchase Requests</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Items Per Page</label>
                        <input type="number" name="settings[items_per_page]" min="10" max="100"
                               value="<?= esc($settingsArray['items_per_page']['value'] ?? '20') ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                <i class="fas fa-save mr-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>

<!-- Users Tab Content -->
<div id="content-users" class="tab-content <?= $activeTab !== 'users' ? 'hidden' : '' ?>">
    
    <!-- Users Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <div></div>
        <button type="button" onclick="openCreateUserModal()"
           class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Create User
        </button>
    </div>
    
    <!-- Users Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="userSearchInput" placeholder="Search users..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <select id="userRoleFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Roles</option>
                <?php foreach ($roles as $key => $label): ?>
                <option value="<?= $key ?>"><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="userStatusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="usersTable">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Branch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="usersBody">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition-colors user-row"
                            data-search="<?= esc(strtolower(($user['full_name'] ?? '') . ' ' . $user['username'] . ' ' . $user['email'])) ?>"
                            data-role="<?= esc($user['role']) ?>"
                            data-status="<?= esc($user['status']) ?>">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white font-medium text-sm"><?= strtoupper(substr($user['username'], 0, 1)) ?></span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm"><?= esc($user['full_name'] ?? $user['username']) ?></p>
                                        <p class="text-xs text-gray-500">@<?= esc($user['username']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= esc($user['email']) ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    <?= esc($roles[$user['role']] ?? $user['role']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500"><?= esc($user['branch_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3">
                                <?php if ($user['status'] == 'active'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                                <?php else: ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button type="button" 
                                       onclick="openEditUserModal(<?= $user['id'] ?>, '<?= esc($user['username']) ?>', '<?= esc($user['email']) ?>', '<?= esc($user['full_name'] ?? '') ?>', '<?= esc($user['phone'] ?? '') ?>', '<?= esc($user['role']) ?>', '<?= esc($user['branch_id'] ?? '') ?>', '<?= esc($user['status']) ?>')"
                                       class="p-1.5 text-amber-600 hover:bg-amber-50 rounded" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                       onclick="openDeleteUserModal(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')"
                                       class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div id="noUsersResults" class="hidden px-4 py-8 text-center text-gray-500">
                <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                <p>No users found</p>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateUserModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto">
            <form method="post" action="<?= base_url('users/store') ?>" id="createUserForm">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect_to" value="<?= base_url('settings?tab=users') ?>">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user-plus text-emerald-500 mr-2"></i>Create New User
                    </h3>
                    <button type="button" onclick="closeCreateUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="createPassword" required minlength="8"
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                       oninput="checkPasswordStrength(this.value)"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 pr-10">
                                <button type="button" onclick="togglePassword('createPassword', 'toggleCreatePwdIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="toggleCreatePwdIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="mt-2 hidden">
                                <div class="flex gap-1 mb-1">
                                    <div id="str1" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div id="str2" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div id="str3" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                    <div id="str4" class="h-1 flex-1 bg-gray-200 rounded"></div>
                                </div>
                                <p id="strengthText" class="text-xs text-gray-500"></p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Min 8 chars, uppercase, lowercase & number</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="modalRole" required onchange="updateBranchRequirement()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $key => $label): ?>
                                <option value="<?= $key ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span id="branchRequired" class="text-red-500 hidden">*</span></label>
                            <select name="branch_id" id="modalBranch"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">No Branch (Auto-create)</option>
                                <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p id="branchHelp" class="text-xs text-amber-600 mt-1 hidden">
                                <i class="fas fa-info-circle"></i> Required for Inventory Staff. Leave empty to auto-create for Branch/Franchise Manager.
                            </p>
                            <p id="supplierHelp" class="text-xs text-emerald-600 mt-1 hidden">
                                <i class="fas fa-magic"></i> Supplier account will be auto-created using user info.
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateUserModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditUserModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-lg sm:w-full mx-auto">
            <form method="post" id="editUserForm">
                <?= csrf_field() ?>
                <input type="hidden" name="redirect_to" value="<?= base_url('settings?tab=users') ?>">
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-user-edit text-amber-500 mr-2"></i>Edit User
                    </h3>
                    <button type="button" onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" id="editUsername" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="editEmail" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" id="editFullName" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="editPassword" minlength="8"
                                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 pr-10">
                                <button type="button" onclick="togglePassword('editPassword', 'toggleEditPwdIcon')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="toggleEditPwdIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Leave blank to keep current (min 8 chars if changing)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="editRole" required onchange="updateEditBranchRequirement()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <?php foreach ($roles as $key => $label): ?>
                                <option value="<?= $key ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Branch <span id="editBranchRequired" class="text-red-500 hidden">*</span></label>
                            <select name="branch_id" id="editBranch"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">No Branch</option>
                                <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" id="editStatus" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditUserModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div id="deleteUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteUserModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete User
                </h3>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-user-times text-red-500 text-2xl"></i>
                </div>
                <p class="text-gray-600 text-center">Are you sure you want to delete user <strong id="deleteUserName" class="text-gray-800"></strong>?</p>
                <p class="text-sm text-gray-500 text-center mt-2">This action cannot be undone.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteUserModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <a href="#" id="deleteUserLink"
                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors inline-flex items-center">
                    <i class="fas fa-trash mr-2"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Drivers Tab Content -->
<div id="content-drivers" class="tab-content <?= $activeTab !== 'drivers' ? 'hidden' : '' ?>">
    
    <!-- Drivers Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
        <div></div>
        <button type="button" onclick="openCreateDriverModal()"
           class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Driver
        </button>
    </div>
    
    <!-- Drivers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="driversTable">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Driver Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Vehicle Number</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">License</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="driversBody">
                    <?php if (!empty($drivers)): ?>
                        <?php foreach ($drivers as $driver): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= esc($driver['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm font-mono"><?= esc($driver['vehicle_number']) ?></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= esc($driver['phone'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?= esc($driver['license_number'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3">
                                <?php if ($driver['status'] == 'active'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                                <?php else: ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button type="button" 
                                       onclick="openEditDriverModal(<?= $driver['id'] ?>, '<?= esc($driver['name']) ?>', '<?= esc($driver['vehicle_number']) ?>', '<?= esc($driver['phone'] ?? '') ?>', '<?= esc($driver['license_number'] ?? '') ?>', '<?= esc($driver['status']) ?>')"
                                       class="p-1.5 text-amber-600 hover:bg-amber-50 rounded" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                       onclick="openDeleteDriverModal(<?= $driver['id'] ?>, '<?= esc($driver['name']) ?>')"
                                       class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-truck text-4xl text-gray-300 mb-3"></i>
                                <p>No drivers found. Add your first driver to get started.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Driver Modal -->
<div id="createDriverModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCreateDriverModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <form method="post" action="<?= base_url('settings/drivers/store') ?>" id="createDriverForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-truck text-cyan-500 mr-2"></i>Add New Driver
                    </h3>
                    <button type="button" onclick="closeCreateDriverModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicle_number" required placeholder="e.g., ABC 1234"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" maxlength="11" pattern="[0-9]{11}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="09XXXXXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                        <input type="text" name="license_number"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateDriverModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Add Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Driver Modal -->
<div id="editDriverModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditDriverModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <form method="post" id="editDriverForm">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-edit text-amber-500 mr-2"></i>Edit Driver
                    </h3>
                    <button type="button" onclick="closeEditDriverModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editDriverName" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicle_number" id="editDriverVehicle" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" name="phone" id="editDriverPhone" maxlength="11" pattern="[0-9]{11}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                        <input type="text" name="license_number" id="editDriverLicense"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="editDriverStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditDriverModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Driver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Driver Modal -->
<div id="deleteDriverModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteDriverModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Delete Driver
                </h3>
            </div>
            
            <div class="px-6 py-6">
                <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mx-auto mb-4">
                    <i class="fas fa-truck text-red-500 text-2xl"></i>
                </div>
                <p class="text-gray-600 text-center">Are you sure you want to delete driver <strong id="deleteDriverName" class="text-gray-800"></strong>?</p>
                <p class="text-sm text-gray-500 text-center mt-2">This action cannot be undone.</p>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteDriverModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <a href="#" id="deleteDriverLink"
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
// Password toggle function
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    const bars = [document.getElementById('str1'), document.getElementById('str2'), document.getElementById('str3'), document.getElementById('str4')];
    
    if (password.length === 0) {
        strengthDiv.classList.add('hidden');
        return;
    }
    
    strengthDiv.classList.remove('hidden');
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    // Reset bars
    bars.forEach(bar => bar.className = 'h-1 flex-1 bg-gray-200 rounded');
    
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500'];
    const texts = ['Weak', 'Fair', 'Good', 'Strong'];
    const textColors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-emerald-500'];
    
    const level = Math.min(strength - 1, 3);
    for (let i = 0; i <= level && i < 4; i++) {
        bars[i].classList.remove('bg-gray-200');
        bars[i].classList.add(colors[level]);
    }
    
    strengthText.textContent = texts[level] || 'Too weak';
    strengthText.className = 'text-xs ' + (textColors[level] || 'text-red-500');
}

// Modal functions
function openCreateUserModal() {
    document.getElementById('createUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Reset password strength
    document.getElementById('passwordStrength').classList.add('hidden');
}

function closeCreateUserModal() {
    document.getElementById('createUserModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('createUserForm').reset();
}

function updateBranchRequirement() {
    const role = document.getElementById('modalRole').value;
    const branchSelect = document.getElementById('modalBranch');
    const branchRequired = document.getElementById('branchRequired');
    const branchHelp = document.getElementById('branchHelp');
    const supplierHelp = document.getElementById('supplierHelp');
    
    // Hide all help texts first
    branchHelp.classList.add('hidden');
    supplierHelp.classList.add('hidden');
    branchRequired.classList.add('hidden');
    branchSelect.required = false;
    
    if (role === 'inventory_staff') {
        // Inventory staff requires existing branch
        branchSelect.required = true;
        branchRequired.classList.remove('hidden');
        branchHelp.classList.remove('hidden');
    } else if (role === 'branch_manager' || role === 'franchise_manager') {
        // Branch/Franchise manager - branch optional (auto-create if empty)
        branchHelp.classList.remove('hidden');
    } else if (role === 'supplier') {
        // Supplier - show auto-create message
        supplierHelp.classList.remove('hidden');
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateUserModal();
        closeEditUserModal();
        closeDeleteUserModal();
        closeCreateDriverModal();
        closeEditDriverModal();
        closeDeleteDriverModal();
    }
});

// Edit User Modal
function openEditUserModal(id, username, email, fullName, phone, role, branchId, status) {
    document.getElementById('editUserForm').action = '<?= base_url('users/update/') ?>' + id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editFullName').value = fullName;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editRole').value = role;
    document.getElementById('editBranch').value = branchId;
    document.getElementById('editStatus').value = status;
    updateEditBranchRequirement();
    document.getElementById('editUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditUserModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function updateEditBranchRequirement() {
    const role = document.getElementById('editRole').value;
    const branchSelect = document.getElementById('editBranch');
    const branchRequired = document.getElementById('editBranchRequired');
    
    if (role === 'branch_manager' || role === 'inventory_staff') {
        branchSelect.required = true;
        branchRequired.classList.remove('hidden');
    } else {
        branchSelect.required = false;
        branchRequired.classList.add('hidden');
    }
}

// Delete User Modal
function openDeleteUserModal(id, username) {
    document.getElementById('deleteUserName').textContent = username;
    document.getElementById('deleteUserLink').href = '<?= base_url('users/delete/') ?>' + id + '?redirect_to=<?= urlencode(base_url('settings?tab=users')) ?>';
    document.getElementById('deleteUserModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteUserModal() {
    document.getElementById('deleteUserModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Driver Modal Functions
function openCreateDriverModal() {
    document.getElementById('createDriverModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateDriverModal() {
    document.getElementById('createDriverModal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('createDriverForm').reset();
}

function openEditDriverModal(id, name, vehicleNumber, phone, licenseNumber, status) {
    document.getElementById('editDriverForm').action = '<?= base_url('settings/drivers/update/') ?>' + id;
    document.getElementById('editDriverName').value = name;
    document.getElementById('editDriverVehicle').value = vehicleNumber;
    document.getElementById('editDriverPhone').value = phone;
    document.getElementById('editDriverLicense').value = licenseNumber;
    document.getElementById('editDriverStatus').value = status;
    document.getElementById('editDriverModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditDriverModal() {
    document.getElementById('editDriverModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openDeleteDriverModal(id, name) {
    document.getElementById('deleteDriverName').textContent = name;
    document.getElementById('deleteDriverLink').href = '<?= base_url('settings/drivers/delete/') ?>' + id;
    document.getElementById('deleteDriverModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteDriverModal() {
    document.getElementById('deleteDriverModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Remove active from all tabs
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected content
    document.getElementById('content-' + tab).classList.remove('hidden');
    // Activate selected tab
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
    
    // Update URL without reload
    history.pushState(null, '', '?tab=' + tab);
}

// Users filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    const roleFilter = document.getElementById('userRoleFilter');
    const statusFilter = document.getElementById('userStatusFilter');
    const rows = document.querySelectorAll('.user-row');
    const noResults = document.getElementById('noUsersResults');

    function filterUsers() {
        const search = searchInput.value.toLowerCase().trim();
        const role = roleFilter.value;
        const status = statusFilter.value;
        let visible = 0;

        rows.forEach(row => {
            const matchSearch = !search || row.dataset.search.includes(search);
            const matchRole = !role || row.dataset.role === role;
            const matchStatus = !status || row.dataset.status === status;
            
            if (matchSearch && matchRole && matchStatus) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.classList.toggle('hidden', visible > 0);
    }

    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    statusFilter.addEventListener('change', filterUsers);
});
</script>
<?= $this->endSection() ?>
