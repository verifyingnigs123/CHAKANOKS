<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Management';
$title = 'Inventory';
?>

<!-- Filters & Action Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all" placeholder="Search product, SKU, barcode...">
            </div>
            <div class="w-full md:w-40">
                <select id="stockFilter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer">
                    <option value="">All Stock Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <?php if ($role == 'central_admin'): ?>
            <div class="w-full md:w-40">
                <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all cursor-pointer"
                        onchange="window.location.href='?branch_id='+this.value">
                    <option value="">All Branches</option>
                    <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                        <?= esc($branch['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <a href="<?= base_url('inventory/history' . ($current_branch_id ? '?branch_id=' . $current_branch_id : '')) ?>" 
               class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition-colors whitespace-nowrap">
                <i class="fas fa-history mr-2"></i> History
            </a>
            <button onclick="openScanModal()" 
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                <i class="fas fa-barcode mr-2"></i> Scan
            </button>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <?php if ($current_branch_id === null && $role == 'central_admin'): ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                    <?php endif; ?>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barcode</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Available</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Min Level</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="tableBody">
                <?php if (!empty($inventory)): ?>
                    <?php foreach ($inventory as $item): 
                        $stockStatus = 'in_stock';
                        if ($item['quantity'] == 0) $stockStatus = 'out_of_stock';
                        elseif ($item['quantity'] <= $item['min_stock_level']) $stockStatus = 'low_stock';
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors data-row" data-product="<?= esc(strtolower($item['product_name'])) ?>" data-sku="<?= esc(strtolower($item['sku'])) ?>" data-barcode="<?= esc(strtolower($item['barcode'] ?? '')) ?>" data-branch="<?= esc(strtolower($item['branch_name'] ?? '')) ?>" data-stock="<?= $stockStatus ?>">
                        <?php if ($current_branch_id === null && $role == 'central_admin'): ?>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                <?= esc($item['branch_name'] ?? 'N/A') ?>
                            </span>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cube text-purple-600"></i>
                                </div>
                                <span class="font-medium text-gray-800"><?= esc($item['product_name']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['sku']) ?></span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm"><?= esc($item['barcode'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($item['quantity'] <= $item['min_stock_level']): ?>
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg text-sm font-bold bg-red-100 text-red-700">
                                <?= $item['quantity'] ?>
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg text-sm font-bold bg-emerald-100 text-emerald-700">
                                <?= $item['quantity'] ?>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-700"><?= $item['available_quantity'] ?></td>
                        <td class="px-6 py-4 text-center text-gray-500"><?= $item['min_stock_level'] ?></td>
                        <td class="px-6 py-4">
                            <?php if ($item['quantity'] == 0): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                            <?php elseif ($item['quantity'] <= $item['min_stock_level']): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Low Stock
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fas fa-check-circle mr-1"></i> In Stock
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="updateInventory(<?= $item['id'] ?>, <?= $item['branch_id'] ?>, <?= $item['product_id'] ?>, <?= $item['quantity'] ?>)"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-edit mr-1"></i> Update
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div id="noResults" class="hidden px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i class="fas fa-warehouse text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">No inventory records found</p>
            </div>
        </div>
    </div>
</div>

<!-- Update Inventory Modal -->
<div id="updateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeUpdateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Update Inventory</h3>
                <button onclick="closeUpdateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateForm" method="post" action="<?= base_url('inventory/update') ?>">
                <?= csrf_field() ?>
                <div class="p-6 space-y-4">
                    <input type="hidden" name="branch_id" id="update_branch_id">
                    <input type="hidden" name="product_id" id="update_product_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select name="action" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                            <option value="set">Set Quantity</option>
                            <option value="add">Add Quantity</option>
                            <option value="subtract">Subtract Quantity</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" min="0" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                    <button type="button" onclick="closeUpdateModal()"
                            class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scan Barcode Modal -->
<div id="scanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeScanModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full mx-auto transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-barcode text-emerald-500 mr-2"></i> Barcode Scanner
                </h3>
                <button onclick="closeScanModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <button id="toggleCameraBtn" onclick="toggleCamera()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-camera mr-2"></i> <span id="cameraStatus">Start Camera</span>
                    </button>
                </div>

                <div id="videoContainer" class="hidden relative w-full max-w-lg mx-auto mb-4 bg-black rounded-lg overflow-hidden">
                    <video id="scannerVideo" autoplay playsinline class="w-full"></video>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-64 h-64 border-2 border-blue-500 rounded-lg" style="box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Or Enter Barcode Manually</label>
                    <div class="flex gap-2">
                        <input type="text" id="manualBarcodeInput" 
                               class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all"
                               placeholder="Enter barcode number">
                        <button onclick="scanManualBarcode()"
                                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div id="scanResultCard" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 id="scannedProductName" class="font-semibold text-gray-800 mb-2"></h4>
                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                        <p><span class="text-gray-500">SKU:</span> <span id="scannedProductSku" class="font-medium"></span></p>
                        <p><span class="text-gray-500">Category:</span> <span id="scannedProductCategory" class="font-medium"></span></p>
                        <p><span class="text-gray-500">Current Stock:</span> <span id="scannedCurrentStock" class="font-medium"></span></p>
                        <p><span class="text-gray-500">Min Level:</span> <span id="scannedMinStock" class="font-medium"></span></p>
                    </div>
                    
                    <div id="lowStockAlert" class="hidden bg-amber-100 border border-amber-200 text-amber-700 px-3 py-2 rounded-lg mb-3 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Low stock alert!
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Add Quantity</label>
                            <input type="number" id="addQuantityInput" min="1" value="1"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg mb-2">
                            <button onclick="updateInventoryFromScan('add')"
                                    class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-1"></i> Add Stock
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Subtract Quantity</label>
                            <input type="number" id="subtractQuantityInput" min="1" value="1"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg mb-2">
                            <button onclick="updateInventoryFromScan('subtract')"
                                    class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-minus mr-1"></i> Subtract
                            </button>
                        </div>
                    </div>
                </div>

                <div id="scanErrorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mt-4"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
let scannerStream = null;
let scannerScanning = false;
let currentScannedProduct = null;

function updateInventory(id, branchId, productId, currentQty) {
    document.getElementById('update_branch_id').value = branchId;
    document.getElementById('update_product_id').value = productId;
    document.getElementById('updateForm').querySelector('input[name="quantity"]').value = currentQty;
    document.getElementById('updateModal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

function openScanModal() {
    document.getElementById('scanModal').classList.remove('hidden');
}

function closeScanModal() {
    stopCamera();
    document.getElementById('scanModal').classList.add('hidden');
    document.getElementById('scanResultCard').classList.add('hidden');
    document.getElementById('scanErrorMessage').classList.add('hidden');
    document.getElementById('manualBarcodeInput').value = '';
    currentScannedProduct = null;
}

function toggleCamera() {
    if (!scannerScanning) {
        startCamera();
    } else {
        stopCamera();
    }
}

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
    .then(function(mediaStream) {
        scannerStream = mediaStream;
        const video = document.getElementById('scannerVideo');
        video.srcObject = mediaStream;
        document.getElementById('videoContainer').classList.remove('hidden');
        document.getElementById('cameraStatus').textContent = 'Stop Camera';
        scannerScanning = true;

        Quagga.init({
            inputStream: { name: "Live", type: "LiveStream", target: video, constraints: { width: 640, height: 480, facingMode: "environment" } },
            decoder: { readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader", "upc_e_reader"] }
        }, function(err) {
            if (err) { console.error('Error:', err); return; }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            scanBarcode(result.codeResult.code);
            Quagga.stop();
        });
    })
    .catch(function(err) {
        document.getElementById('scanErrorMessage').textContent = 'Camera access denied. Please use manual input.';
        document.getElementById('scanErrorMessage').classList.remove('hidden');
    });
}

function stopCamera() {
    if (scannerStream) { scannerStream.getTracks().forEach(track => track.stop()); scannerStream = null; }
    if (typeof Quagga !== 'undefined') { Quagga.stop(); }
    document.getElementById('videoContainer').classList.add('hidden');
    document.getElementById('cameraStatus').textContent = 'Start Camera';
    scannerScanning = false;
}

function scanManualBarcode() {
    const barcode = document.getElementById('manualBarcodeInput').value.trim();
    if (barcode) { scanBarcode(barcode); } else { alert('Please enter a barcode'); }
}

function scanBarcode(barcode) {
    fetch('<?= base_url('barcode/scan') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'barcode=' + encodeURIComponent(barcode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentScannedProduct = data.product;
            displayScannedProduct(data);
        } else {
            document.getElementById('scanErrorMessage').textContent = data.message || 'Product not found';
            document.getElementById('scanErrorMessage').classList.remove('hidden');
            document.getElementById('scanResultCard').classList.add('hidden');
        }
    });
}

function displayScannedProduct(data) {
    document.getElementById('scanErrorMessage').classList.add('hidden');
    document.getElementById('scanResultCard').classList.remove('hidden');
    document.getElementById('scannedProductName').textContent = data.product.name;
    document.getElementById('scannedProductSku').textContent = data.product.sku;
    document.getElementById('scannedProductCategory').textContent = data.product.category || 'N/A';
    
    if (data.inventory) {
        document.getElementById('scannedCurrentStock').textContent = data.inventory.quantity;
        document.getElementById('scannedMinStock').textContent = data.inventory.min_stock_level;
        document.getElementById('lowStockAlert').classList.toggle('hidden', data.inventory.quantity > data.inventory.min_stock_level);
    } else {
        document.getElementById('scannedCurrentStock').textContent = '0 (Not in inventory)';
        document.getElementById('scannedMinStock').textContent = data.product.min_stock_level;
        document.getElementById('lowStockAlert').classList.remove('hidden');
    }
}

function updateInventoryFromScan(action) {
    if (!currentScannedProduct) return;
    const quantity = action === 'add' ? parseInt(document.getElementById('addQuantityInput').value) : parseInt(document.getElementById('subtractQuantityInput').value);
    if (quantity <= 0) { alert('Quantity must be greater than 0'); return; }

    fetch('<?= base_url('barcode/update-inventory') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'barcode=' + encodeURIComponent(currentScannedProduct.barcode) + '&quantity=' + quantity + '&action=' + action
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inventory updated! New quantity: ' + data.quantity);
            scanBarcode(currentScannedProduct.barcode);
            setTimeout(() => { window.location.reload(); }, 1500);
        } else {
            alert('Error: ' + (data.error || 'Failed to update'));
        }
    });
}

document.getElementById('manualBarcodeInput').addEventListener('keypress', function(e) { if (e.key === 'Enter') scanManualBarcode(); });

// Real-time search filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const stockFilter = document.getElementById('stockFilter');
    const rows = document.querySelectorAll('.data-row');
    const noResults = document.getElementById('noResults');
    const tbody = document.getElementById('tableBody');
    
    function filterTable() {
        const search = searchInput.value.toLowerCase().trim();
        const stock = stockFilter.value.toLowerCase();
        let count = 0;
        
        rows.forEach(row => {
            const m1 = search === '' || row.dataset.product.includes(search) || row.dataset.sku.includes(search) || row.dataset.barcode.includes(search) || row.dataset.branch.includes(search);
            const m2 = stock === '' || row.dataset.stock === stock;
            
            if (m1 && m2) { row.style.display = ''; count++; } 
            else { row.style.display = 'none'; }
        });
        
        noResults.classList.toggle('hidden', count > 0);
        tbody.classList.toggle('hidden', count === 0);
    }
    
    searchInput.addEventListener('input', filterTable);
    stockFilter.addEventListener('change', filterTable);
});
</script>
<?= $this->endSection() ?>
