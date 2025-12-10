<?php
$user = session()->get('user') ?? [];
$username = $user['username'] ?? session()->get('username') ?? 'User';
$role = session()->get('role') ?? 'guest';
$branch_name = session()->get('branch_name') ?? '';
?>

<?php
// Determine page title from URL
$currentUrl = current_url();
$segment = uri_string();
$pageTitle = 'Dashboard';

// Map URL segments to page titles (more specific paths first)
$pageTitles = [
    'inventory/alerts' => 'Stock Alerts',
    'inventory/history' => 'Inventory History',
    'inventory' => 'Inventory',
    'branches/create' => 'Create Branch',
    'branches' => 'Branches',
    'purchase-requests' => 'Purchase Requests',
    'purchase-orders/create' => 'Create Purchase Order',
    'purchase-orders/view' => 'Purchase Order Details',
    'purchase-orders' => 'Purchase Orders',
    'deliveries/create' => 'Schedule Delivery',
    'deliveries/view' => 'Delivery Details',
    'deliveries' => 'Deliveries',
    'transfers/create' => 'Create Transfer',
    'transfers/view' => 'Transfer Details',
    'transfers' => 'Inter-Branch Transfers',
    'users/create' => 'Create User',
    'users/edit' => 'Edit User',
    'users' => 'User Management',
    'products' => 'Products',
    'categories' => 'Categories',
    'suppliers' => 'Suppliers',
    'settings' => 'Settings & Users',
    'reports' => 'Reports',
    'activity-logs' => 'Activity Logs',
    'profile' => 'My Profile',
    'dashboard' => 'Dashboard',
];

foreach ($pageTitles as $path => $title) {
    if (strpos($segment, $path) === 0) {
        $pageTitle = $title;
        break;
    }
}
?>

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
    <div class="flex items-center justify-between h-16 px-6">
        
        <!-- Left: Page Title / Breadcrumb -->
        <div class="flex items-center space-x-4">
            <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div>
                <h1 class="text-xl font-semibold text-gray-800"><?= $pageTitle ?></h1>
                <?php if ($branch_name): ?>
                <p class="text-sm text-gray-500"><?= esc($branch_name) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Right: Actions & User Menu -->
        <div class="flex items-center space-x-4">
            
            <!-- Notifications -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-box text-blue-500 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800">New delivery arrived</p>
                                    <p class="text-xs text-gray-500">2 minutes ago</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-yellow-500 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800">Low stock alert</p>
                                    <p class="text-xs text-gray-500">1 hour ago</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View all notifications</a>
                    </div>
                </div>
            </div>
            
            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-medium text-sm"><?= strtoupper(substr($username, 0, 1)) ?></span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700"><?= esc($username) ?></p>
                        <p class="text-xs text-gray-500"><?= ucfirst(str_replace('_', ' ', $role)) ?></p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                </button>
                
                <!-- User Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 max-h-80 flex flex-col">
                    <!-- Scrollable Menu Items -->
                    <div class="overflow-y-auto scrollbar-hide flex-1" style="-ms-overflow-style: none; scrollbar-width: none;">
                        <a href="<?= base_url('profile') ?>" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-user w-5 text-gray-400"></i>
                            <span class="ml-2">Profile</span>
                        </a>
                        <?php if ($role == 'central_admin'): ?>
                        <hr class="my-1 border-gray-100">
                        <a href="<?= base_url('reports') ?>" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chart-bar w-5 text-gray-400"></i>
                            <span class="ml-2">Reports</span>
                        </a>
                        <a href="<?= base_url('activity-logs') ?>" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-history w-5 text-gray-400"></i>
                            <span class="ml-2">Activity Logs</span>
                        </a>
                        <a href="<?= base_url('settings') ?>" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-cog w-5 text-gray-400"></i>
                            <span class="ml-2">Settings & Users</span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <!-- Logout - Always Visible -->
                    <div class="border-t border-gray-100 mt-1 pt-1">
                        <a href="<?= base_url('auth/logout') ?>" 
                           class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-2">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
