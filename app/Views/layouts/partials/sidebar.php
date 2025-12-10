<?php
$currentUrl = current_url();
$role = session()->get('role') ?? 'guest';

function isActive($path) {
    return strpos(current_url(), base_url($path)) !== false;
}

function activeClass($path) {
    return isActive($path) ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white';
}
?>

<aside class="fixed inset-y-0 left-0 w-64 bg-slate-800 shadow-xl z-50">
    <!-- Logo/Brand -->
    <div class="flex items-center justify-center h-20 bg-slate-900 border-b border-slate-700">
        <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-link text-emerald-500 text-2xl"></i>
                <span class="text-white font-bold text-xl">ChakaNoks'</span>
            </div>
            <span class="text-emerald-400 text-xs font-medium tracking-wider">SUPPLY CHAIN MANAGEMENT</span>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="mt-4 px-3 space-y-1 overflow-y-auto h-[calc(100vh-5rem)] scrollbar-hide" style="-ms-overflow-style: none; scrollbar-width: none;">
        
        <!-- Dashboard - All roles -->
        <a href="<?= base_url('dashboard') ?>" 
           class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('dashboard') ?>">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span class="ml-3">Dashboard</span>
        </a>
        
        <?php if ($role === 'central_admin'): ?>
        <!-- ========================================== -->
        <!-- CENTRAL ADMIN - Full Access -->
        <!-- ========================================== -->
        
        <!-- Administration -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administration</p>
        </div>
        
        <a href="<?= base_url('branches') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('branches') ?>">
            <i class="fas fa-building w-5"></i>
            <span class="ml-3">Branches</span>
        </a>
        
        <a href="<?= base_url('suppliers') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('suppliers') ?>">
            <i class="fas fa-truck-loading w-5"></i>
            <span class="ml-3">Suppliers</span>
        </a>
        
        <!-- Inventory -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Inventory</p>
        </div>
        
        <a href="<?= base_url('products') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('products') ?>">
            <i class="fas fa-box w-5"></i>
            <span class="ml-3">Products</span>
        </a>
        
        <a href="<?= base_url('categories') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('categories') ?>">
            <i class="fas fa-tags w-5"></i>
            <span class="ml-3">Categories</span>
        </a>
        
        <a href="<?= base_url('inventory') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory') ?>">
            <i class="fas fa-warehouse w-5"></i>
            <span class="ml-3">Stock Levels</span>
        </a>
        
        <a href="<?= base_url('inventory/alerts') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory/alerts') ?>">
            <i class="fas fa-exclamation-triangle w-5"></i>
            <span class="ml-3">Stock Alerts</span>
        </a>
        
        <!-- Purchasing -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Purchasing</p>
        </div>
        
        <a href="<?= base_url('purchase-requests') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('purchase-requests') ?>">
            <i class="fas fa-file-alt w-5"></i>
            <span class="ml-3">Purchase Requests</span>
        </a>
        
        <a href="<?= base_url('purchase-orders') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('purchase-orders') ?>">
            <i class="fas fa-shopping-cart w-5"></i>
            <span class="ml-3">Purchase Orders</span>
        </a>
        
        <a href="<?= base_url('deliveries') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('deliveries') ?>">
            <i class="fas fa-truck w-5"></i>
            <span class="ml-3">Deliveries</span>
        </a>
        
        <a href="<?= base_url('transfers') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('transfers') ?>">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="ml-3">Transfers</span>
        </a>
        
        <!-- Reports & Tools -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Reports & Tools</p>
        </div>
        
        <a href="<?= base_url('reports') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('reports') ?>">
            <i class="fas fa-chart-bar w-5"></i>
            <span class="ml-3">Reports</span>
        </a>
        
        <a href="<?= base_url('barcode/scan') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('barcode') ?>">
            <i class="fas fa-barcode w-5"></i>
            <span class="ml-3">Barcode Scanner</span>
        </a>
        
        <!-- System -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
        </div>
        
        <a href="<?= base_url('users') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('users') ?>">
            <i class="fas fa-users w-5"></i>
            <span class="ml-3">Users</span>
        </a>
        
        <a href="<?= base_url('settings') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('settings') ?>">
            <i class="fas fa-cog w-5"></i>
            <span class="ml-3">Settings</span>
        </a>
        
        <a href="<?= base_url('activity-logs') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('activity-logs') ?>">
            <i class="fas fa-history w-5"></i>
            <span class="ml-3">Activity Logs</span>
        </a>

        <?php elseif ($role === 'system_admin'): ?>
        <!-- ========================================== -->
        <!-- SYSTEM ADMINISTRATOR (IT) -->
        <!-- Manage users, settings, activity logs, backups -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">User Management</p>
        </div>
        
        <a href="<?= base_url('users') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('users') ?>">
            <i class="fas fa-users w-5"></i>
            <span class="ml-3">Users</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
        </div>
        
        <a href="<?= base_url('settings') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('settings') ?>">
            <i class="fas fa-cog w-5"></i>
            <span class="ml-3">Settings</span>
        </a>
        
        <a href="<?= base_url('activity-logs') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('activity-logs') ?>">
            <i class="fas fa-history w-5"></i>
            <span class="ml-3">Activity Logs</span>
        </a>
        
        <a href="<?= base_url('backups') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('backups') ?>">
            <i class="fas fa-database w-5"></i>
            <span class="ml-3">Backups</span>
        </a>

        <?php elseif ($role === 'branch_manager'): ?>
        <!-- ========================================== -->
        <!-- BRANCH MANAGER -->
        <!-- Create purchase requests, receive deliveries, view branch inventory, create transfers -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Inventory</p>
        </div>
        
        <a href="<?= base_url('inventory') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory') ?>">
            <i class="fas fa-warehouse w-5"></i>
            <span class="ml-3">Stock Levels</span>
        </a>
        
        <a href="<?= base_url('inventory/alerts') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory/alerts') ?>">
            <i class="fas fa-exclamation-triangle w-5"></i>
            <span class="ml-3">Stock Alerts</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Purchasing</p>
        </div>
        
        <a href="<?= base_url('purchase-requests') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('purchase-requests') ?>">
            <i class="fas fa-file-alt w-5"></i>
            <span class="ml-3">Purchase Requests</span>
        </a>
        
        <a href="<?= base_url('deliveries') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('deliveries') ?>">
            <i class="fas fa-truck w-5"></i>
            <span class="ml-3">Deliveries</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transfers</p>
        </div>
        
        <a href="<?= base_url('transfers') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('transfers') ?>">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="ml-3">Branch Transfers</span>
        </a>

        <?php elseif ($role === 'inventory_staff'): ?>
        <!-- ========================================== -->
        <!-- INVENTORY STAFF -->
        <!-- Update stock levels, receive deliveries, report damaged/expired goods -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Inventory Management</p>
        </div>
        
        <a href="<?= base_url('inventory') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory') ?>">
            <i class="fas fa-warehouse w-5"></i>
            <span class="ml-3">Stock Levels</span>
        </a>
        
        <a href="<?= base_url('inventory/alerts') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory/alerts') ?>">
            <i class="fas fa-exclamation-triangle w-5"></i>
            <span class="ml-3">Stock Alerts</span>
        </a>
        
        <a href="<?= base_url('inventory/history') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory/history') ?>">
            <i class="fas fa-history w-5"></i>
            <span class="ml-3">Stock History</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Receiving</p>
        </div>
        
        <a href="<?= base_url('deliveries') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('deliveries') ?>">
            <i class="fas fa-truck w-5"></i>
            <span class="ml-3">Receive Deliveries</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tools</p>
        </div>
        
        <a href="<?= base_url('barcode/scan') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('barcode') ?>">
            <i class="fas fa-barcode w-5"></i>
            <span class="ml-3">Barcode Scanner</span>
        </a>

        <?php elseif ($role === 'supplier'): ?>
        <!-- ========================================== -->
        <!-- SUPPLIER -->
        <!-- View/manage their purchase orders, mark as prepared -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">My Orders</p>
        </div>
        
        <a href="<?= base_url('purchase-orders') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('purchase-orders') ?>">
            <i class="fas fa-shopping-cart w-5"></i>
            <span class="ml-3">Purchase Orders</span>
        </a>
        
        <a href="<?= base_url('deliveries') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('deliveries') ?>">
            <i class="fas fa-truck w-5"></i>
            <span class="ml-3">Deliveries</span>
        </a>

        <?php elseif ($role === 'logistics_coordinator'): ?>
        <!-- ========================================== -->
        <!-- LOGISTICS COORDINATOR -->
        <!-- Schedule deliveries, dispatch, track deliveries -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Logistics</p>
        </div>
        
        <a href="<?= base_url('deliveries') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('deliveries') ?>">
            <i class="fas fa-truck w-5"></i>
            <span class="ml-3">Deliveries</span>
        </a>
        
        <a href="<?= base_url('purchase-orders') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('purchase-orders') ?>">
            <i class="fas fa-shopping-cart w-5"></i>
            <span class="ml-3">Purchase Orders</span>
        </a>
        
        <a href="<?= base_url('transfers') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('transfers') ?>">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="ml-3">Transfers</span>
        </a>

        <?php elseif ($role === 'franchise_manager'): ?>
        <!-- ========================================== -->
        <!-- FRANCHISE MANAGER -->
        <!-- Handle franchise applications, allocate supplies to franchise partners -->
        <!-- ========================================== -->
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Franchise</p>
        </div>
        
        <a href="<?= base_url('franchise/applications') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('franchise/applications') ?>">
            <i class="fas fa-file-signature w-5"></i>
            <span class="ml-3">Applications</span>
        </a>
        
        <a href="<?= base_url('franchise/partners') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('franchise/partners') ?>">
            <i class="fas fa-handshake w-5"></i>
            <span class="ml-3">Partners</span>
        </a>
        
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Supply Allocation</p>
        </div>
        
        <a href="<?= base_url('transfers') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('transfers') ?>">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="ml-3">Allocate Supplies</span>
        </a>
        
        <a href="<?= base_url('inventory') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('inventory') ?>">
            <i class="fas fa-warehouse w-5"></i>
            <span class="ml-3">View Inventory</span>
        </a>

        <?php else: ?>
        <!-- ========================================== -->
        <!-- DEFAULT / GUEST -->
        <!-- ========================================== -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu</p>
        </div>
        
        <a href="<?= base_url('dashboard') ?>" class="flex items-center px-4 py-3 rounded-lg transition-colors <?= activeClass('dashboard') ?>">
            <i class="fas fa-home w-5"></i>
            <span class="ml-3">Home</span>
        </a>
        
        <?php endif; ?>
        
        <!-- Spacer -->
        <div class="pb-20"></div>
    </nav>
</aside>
