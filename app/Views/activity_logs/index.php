<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Action Bar -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('activity-logs/export?' . http_build_query($_GET)) ?>" 
       class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
        <i class="fas fa-download mr-2"></i>Export CSV
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <div>
            <input type="text" id="searchFilter" placeholder="Search..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <select id="userFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Users</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= esc(strtolower($user['username'])) ?>"><?= esc($user['username']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <select id="actionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Actions</option>
                <option value="create">Create</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
                <option value="approve">Approve</option>
                <option value="reject">Reject</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
            </select>
        </div>
        <div>
            <select id="moduleFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Modules</option>
                <option value="user">User</option>
                <option value="product">Product</option>
                <option value="inventory">Inventory</option>
                <option value="purchase_request">Purchase Request</option>
                <option value="purchase_order">Purchase Order</option>
                <option value="delivery">Delivery</option>
                <option value="transfer">Transfer</option>
                <option value="auth">Authentication</option>
                <option value="profile">Profile</option>
                <option value="settings">Settings</option>
            </select>
        </div>
        <div>
            <input type="date" id="dateFromFilter" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                   placeholder="From Date">
        </div>
        <div class="flex gap-2">
            <input type="date" id="dateToFilter"
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                   placeholder="To Date">
            <button type="button" id="clearFilters"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
                   title="Clear Filters">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<!-- Activity Logs Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <div class="max-h-[600px] overflow-y-auto scrollbar-hide" style="-ms-overflow-style: none; scrollbar-width: none;">
            <table class="w-full" id="logsTable">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Module</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="logsBody">
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <?php
                            $actionColors = [
                                'create' => 'bg-green-100 text-green-700',
                                'update' => 'bg-blue-100 text-blue-700',
                                'delete' => 'bg-red-100 text-red-700',
                                'approve' => 'bg-emerald-100 text-emerald-700',
                                'reject' => 'bg-orange-100 text-orange-700',
                                'login' => 'bg-cyan-100 text-cyan-700',
                                'logout' => 'bg-gray-100 text-gray-700',
                            ];
                            $actionColor = $actionColors[$log['action'] ?? ''] ?? 'bg-gray-100 text-gray-700';
                            $logDate = date('Y-m-d', strtotime($log['created_at']));
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors log-row"
                                data-search="<?= esc(strtolower(($log['full_name'] ?? '') . ' ' . ($log['username'] ?? '') . ' ' . ($log['action'] ?? '') . ' ' . ($log['module'] ?? '') . ' ' . ($log['description'] ?? ''))) ?>"
                                data-user="<?= esc(strtolower($log['username'] ?? '')) ?>"
                                data-action="<?= esc(strtolower($log['action'] ?? '')) ?>"
                                data-module="<?= esc(strtolower($log['module'] ?? '')) ?>"
                                data-date="<?= $logDate ?>">
                                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>
                                    <?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-emerald-600 font-medium text-xs">
                                                <?= strtoupper(substr($log['username'] ?? 'U', 0, 1)) ?>
                                            </span>
                                        </div>
                                        <span class="text-gray-800"><?= esc($log['full_name'] ?? $log['username'] ?? 'N/A') ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $actionColor ?>">
                                        <?= ucfirst($log['action'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                                        <?= ucfirst(str_replace('_', ' ', $log['module'] ?? 'N/A')) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate" title="<?= esc($log['description'] ?? '') ?>">
                                    <?= esc($log['description'] ?? 'N/A') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- No Results Message -->
            <div id="noResults" class="hidden px-4 py-8 text-center text-gray-500">
                <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                <p>No activity logs match your filters</p>
            </div>
            
            <?php if (empty($logs)): ?>
            <div class="px-4 py-8 text-center text-gray-500">
                <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                <p>No activity logs found</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchFilter = document.getElementById('searchFilter');
    const userFilter = document.getElementById('userFilter');
    const actionFilter = document.getElementById('actionFilter');
    const moduleFilter = document.getElementById('moduleFilter');
    const dateFromFilter = document.getElementById('dateFromFilter');
    const dateToFilter = document.getElementById('dateToFilter');
    const clearFilters = document.getElementById('clearFilters');
    const rows = document.querySelectorAll('.log-row');
    const noResults = document.getElementById('noResults');

    function filterLogs() {
        const search = searchFilter.value.toLowerCase().trim();
        const user = userFilter.value.toLowerCase();
        const action = actionFilter.value.toLowerCase();
        const module = moduleFilter.value.toLowerCase();
        const dateFrom = dateFromFilter.value;
        const dateTo = dateToFilter.value;
        
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearch = row.dataset.search || '';
            const rowUser = row.dataset.user || '';
            const rowAction = row.dataset.action || '';
            const rowModule = row.dataset.module || '';
            const rowDate = row.dataset.date || '';

            let show = true;

            // Search filter
            if (search && !rowSearch.includes(search)) {
                show = false;
            }

            // User filter
            if (user && rowUser !== user) {
                show = false;
            }

            // Action filter
            if (action && rowAction !== action) {
                show = false;
            }

            // Module filter
            if (module && rowModule !== module) {
                show = false;
            }

            // Date from filter
            if (dateFrom && rowDate < dateFrom) {
                show = false;
            }

            // Date to filter
            if (dateTo && rowDate > dateTo) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Show/hide no results message
        if (noResults) {
            noResults.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
        }
    }

    // Add event listeners for real-time filtering
    searchFilter.addEventListener('input', filterLogs);
    userFilter.addEventListener('change', filterLogs);
    actionFilter.addEventListener('change', filterLogs);
    moduleFilter.addEventListener('change', filterLogs);
    dateFromFilter.addEventListener('change', filterLogs);
    dateToFilter.addEventListener('change', filterLogs);

    // Clear filters
    clearFilters.addEventListener('click', function() {
        searchFilter.value = '';
        userFilter.value = '';
        actionFilter.value = '';
        moduleFilter.value = '';
        dateFromFilter.value = '';
        dateToFilter.value = '';
        filterLogs();
    });
});
</script>
<?= $this->endSection() ?>
