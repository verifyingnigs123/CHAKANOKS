<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>



<!-- Reports Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- Inventory Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-boxes text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Inventory Report</h3>
            <p class="text-gray-500 text-sm mb-4">View inventory levels by branch, category, and product</p>
            <a href="<?= base_url('reports/inventory') ?>" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <span>View Report</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Purchase Orders Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-emerald-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Purchase Orders</h3>
            <p class="text-gray-500 text-sm mb-4">Track purchase orders by status, supplier, and date</p>
            <a href="<?= base_url('reports/purchase-orders') ?>" 
               class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                <span>View Report</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Delivery Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-truck text-cyan-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Delivery Report</h3>
            <p class="text-gray-500 text-sm mb-4">Monitor delivery status and performance metrics</p>
            <a href="<?= base_url('reports/deliveries') ?>" 
               class="inline-flex items-center px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
                <span>View Report</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Supplier Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-chart-line text-amber-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Supplier Performance</h3>
            <p class="text-gray-500 text-sm mb-4">Analyze supplier delivery times and completion rates</p>
            <a href="<?= base_url('reports/supplier-performance') ?>" 
               class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                <span>View Report</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Wastage Report -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Wastage Report</h3>
            <p class="text-gray-500 text-sm mb-4">Track expired items and inventory wastage</p>
            <a href="<?= base_url('reports/wastage') ?>" 
               class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <span>View Report</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
