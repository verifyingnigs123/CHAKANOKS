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
    'notifications' => 'Notifications',
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
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-2">
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
            <div x-data="notificationDropdown()" x-init="init()" class="relative">
                <button @click="toggle()" 
                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bell text-xl"></i>
                    <span x-show="unreadCount > 0" 
                          x-text="unreadCount > 9 ? '9+' : unreadCount"
                          class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1"></span>
                </button>
                
                <!-- Notifications Dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                    <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                        <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-700">
                            Mark all as read
                        </button>
                    </div>
                    <div class="max-h-80 overflow-y-auto overflow-x-hidden">
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                <p class="text-sm">No notifications</p>
                            </div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <div class="border-l-4 mx-2 my-2 rounded-lg transition-all cursor-pointer hover:shadow-md"
                                 :class="notif.is_read == 0 ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-gray-50'"
                                 @click="handleNotificationClick(notif)">
                                <div class="p-3">
                                    <div class="block">
                                        <p class="text-sm text-gray-800" 
                                           :class="notif.is_read == 0 ? 'font-semibold' : 'font-normal'"
                                           x-text="notif.title"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="notif.message"></p>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-400" x-text="timeAgo(notif.created_at)"></span>
                                        <span x-show="notif.is_read == 0" class="text-xs text-blue-600 font-medium">
                                            <i class="fas fa-circle text-[6px]"></i> New
                                        </span>
                                        <span x-show="notif.is_read == 1" class="text-xs text-gray-400">
                                            <i class="fas fa-check-double"></i> Read
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-2 border-t border-gray-100">
                        <a href="<?= base_url('notifications') ?>" class="text-sm text-blue-600 hover:text-blue-700">View all notifications</a>
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
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                    <!-- Menu Items -->
                    <div>
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
