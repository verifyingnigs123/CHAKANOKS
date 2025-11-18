<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Inventory Management';
$title = 'Inventory';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Inventory List</h4>
    <div>
        <?php if ($role == 'central_admin' || $role == 'system_admin'): ?>
            <select class="form-select d-inline-block" style="width: auto;" onchange="window.location.href='?branch_id='+this.value">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($current_branch_id == $branch['id']) ? 'selected' : '' ?>>
                        <?= $branch['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scanModal">
            <i class="bi bi-upc-scan"></i> Scan Barcode
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <?php if ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')): ?>
                            <th>Branch</th>
                        <?php endif; ?>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>Available</th>
                        <th>Min Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($inventory)): ?>
                        <?php foreach ($inventory as $item): ?>
                            <tr>
                                <?php if ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')): ?>
                                    <td>
                                        <span class="badge bg-info"><?= $item['branch_name'] ?? 'N/A' ?></span>
                                    </td>
                                <?php endif; ?>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['sku'] ?></td>
                                <td><?= $item['barcode'] ?? '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= ($item['quantity'] <= $item['min_stock_level']) ? 'danger' : 'success' ?>">
                                        <?= $item['quantity'] ?>
                                    </span>
                                </td>
                                <td><?= $item['available_quantity'] ?></td>
                                <td><?= $item['min_stock_level'] ?></td>
                                <td>
                                    <?php if ($item['quantity'] <= $item['min_stock_level']): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php elseif ($item['quantity'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="updateInventory(<?= $item['id'] ?>, <?= $item['branch_id'] ?>, <?= $item['product_id'] ?>, <?= $item['quantity'] ?>)">
                                        <i class="bi bi-pencil"></i> Update
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= ($current_branch_id === null && ($role == 'central_admin' || $role == 'system_admin')) ? '9' : '8' ?>" class="text-center">No inventory records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Inventory Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateForm" method="post" action="<?= base_url('inventory/update') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="branch_id" id="update_branch_id">
                    <input type="hidden" name="product_id" id="update_product_id">
                    <div class="mb-3">
                        <label>Action</label>
                        <select name="action" class="form-select" required>
                            <option value="set">Set Quantity</option>
                            <option value="add">Add Quantity</option>
                            <option value="subtract">Subtract Quantity</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scan Barcode Modal with Camera Support -->
<div class="modal fade" id="scanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Barcode Scanner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopCameraOnClose()"></button>
            </div>
            <div class="modal-body">
                <style>
                    .scanner-video-container {
                        position: relative;
                        width: 100%;
                        max-width: 640px;
                        margin: 0 auto 20px;
                        background: #000;
                        border-radius: 8px;
                        overflow: hidden;
                    }
                    #scannerVideo {
                        width: 100%;
                        height: auto;
                        display: block;
                    }
                    .scanner-overlay {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 250px;
                        height: 250px;
                        border: 2px solid #0d6efd;
                        border-radius: 8px;
                        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
                    }
                    .scanner-overlay::before,
                    .scanner-overlay::after {
                        content: '';
                        position: absolute;
                        width: 20px;
                        height: 20px;
                        border: 3px solid #0d6efd;
                    }
                    .scanner-overlay::before {
                        top: -3px;
                        left: -3px;
                        border-right: none;
                        border-bottom: none;
                    }
                    .scanner-overlay::after {
                        bottom: -3px;
                        right: -3px;
                        border-left: none;
                        border-top: none;
                    }
                </style>
                
                <div class="text-center mb-3">
                    <button id="toggleCameraBtn" class="btn btn-primary">
                        <i class="bi bi-camera"></i> <span id="cameraStatus">Start Camera</span>
                    </button>
                </div>

                <div class="scanner-video-container" id="videoContainer" style="display: none;">
                    <video id="scannerVideo" autoplay playsinline></video>
                    <div class="scanner-overlay"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Or Enter Barcode Manually</label>
                    <div class="input-group">
                        <input type="text" id="manualBarcodeInput" class="form-control" placeholder="Enter barcode number" autofocus>
                        <button class="btn btn-primary" onclick="scanManualBarcode()">
                            <i class="bi bi-search"></i> Scan
                        </button>
                    </div>
                </div>

                <div id="scanResultCard" style="display: none;">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 id="scannedProductName" class="card-title"></h5>
                            <p class="mb-1"><strong>SKU:</strong> <span id="scannedProductSku"></span></p>
                            <p class="mb-1"><strong>Category:</strong> <span id="scannedProductCategory"></span></p>
                            <p class="mb-1"><strong>Current Stock:</strong> <span id="scannedCurrentStock"></span></p>
                            <p class="mb-3"><strong>Min Stock Level:</strong> <span id="scannedMinStock"></span></p>
                            
                            <div class="alert alert-warning" id="lowStockAlert" style="display: none;">
                                <i class="bi bi-exclamation-triangle"></i> Low stock alert!
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-md-6">
                                    <label>Quantity to Add</label>
                                    <input type="number" id="addQuantityInput" class="form-control" min="1" value="1">
                                    <button class="btn btn-success w-100 mt-2" onclick="updateInventoryFromScan('add')">
                                        <i class="bi bi-plus-circle"></i> Add Stock
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <label>Quantity to Subtract</label>
                                    <input type="number" id="subtractQuantityInput" class="form-control" min="1" value="1">
                                    <button class="btn btn-danger w-100 mt-2" onclick="updateInventoryFromScan('subtract')">
                                        <i class="bi bi-dash-circle"></i> Subtract Stock
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="scanErrorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="stopCameraOnClose()">Close</button>
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
    new bootstrap.Modal(document.getElementById('updateModal')).show();
}

// Barcode Scanner Functions
document.getElementById('toggleCameraBtn').addEventListener('click', function() {
    if (!scannerScanning) {
        startCamera();
    } else {
        stopCamera();
    }
});

function startCamera() {
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'environment' // Use back camera on mobile
        } 
    })
    .then(function(mediaStream) {
        scannerStream = mediaStream;
        const video = document.getElementById('scannerVideo');
        video.srcObject = mediaStream;
        document.getElementById('videoContainer').style.display = 'block';
        document.getElementById('cameraStatus').textContent = 'Stop Camera';
        scannerScanning = true;

        // Initialize QuaggaJS for barcode scanning
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: video,
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader"]
            }
        }, function(err) {
            if (err) {
                console.error('Error initializing Quagga:', err);
                document.getElementById('scanErrorMessage').textContent = 'Camera initialization failed. Please use manual input.';
                document.getElementById('scanErrorMessage').style.display = 'block';
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            scanBarcode(code);
            Quagga.stop();
        });
    })
    .catch(function(err) {
        console.error('Error accessing camera:', err);
        document.getElementById('scanErrorMessage').textContent = 'Camera access denied. Please use manual input.';
        document.getElementById('scanErrorMessage').style.display = 'block';
    });
}

function stopCamera() {
    if (scannerStream) {
        scannerStream.getTracks().forEach(track => track.stop());
        scannerStream = null;
    }
    if (typeof Quagga !== 'undefined' && Quagga) {
        Quagga.stop();
    }
    document.getElementById('videoContainer').style.display = 'none';
    document.getElementById('cameraStatus').textContent = 'Start Camera';
    scannerScanning = false;
}

function stopCameraOnClose() {
    stopCamera();
    // Reset scan result
    document.getElementById('scanResultCard').style.display = 'none';
    document.getElementById('scanErrorMessage').style.display = 'none';
    document.getElementById('manualBarcodeInput').value = '';
    currentScannedProduct = null;
}

function scanManualBarcode() {
    const barcode = document.getElementById('manualBarcodeInput').value.trim();
    if (barcode) {
        scanBarcode(barcode);
    } else {
        alert('Please enter a barcode');
    }
}

function scanBarcode(barcode) {
    fetch('<?= base_url('barcode/scan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(barcode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentScannedProduct = data.product;
            displayScannedProduct(data);
        } else {
            document.getElementById('scanErrorMessage').textContent = data.message || 'Product not found';
            document.getElementById('scanErrorMessage').style.display = 'block';
            document.getElementById('scanResultCard').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('scanErrorMessage').textContent = 'Error scanning barcode';
        document.getElementById('scanErrorMessage').style.display = 'block';
    });
}

function displayScannedProduct(data) {
    document.getElementById('scanErrorMessage').style.display = 'none';
    document.getElementById('scanResultCard').style.display = 'block';
    
    document.getElementById('scannedProductName').textContent = data.product.name;
    document.getElementById('scannedProductSku').textContent = data.product.sku;
    document.getElementById('scannedProductCategory').textContent = data.product.category || 'N/A';
    
    if (data.inventory) {
        document.getElementById('scannedCurrentStock').textContent = data.inventory.quantity;
        document.getElementById('scannedMinStock').textContent = data.inventory.min_stock_level;
        
        if (data.inventory.quantity <= data.inventory.min_stock_level) {
            document.getElementById('lowStockAlert').style.display = 'block';
        } else {
            document.getElementById('lowStockAlert').style.display = 'none';
        }
    } else {
        document.getElementById('scannedCurrentStock').textContent = '0 (Not in inventory)';
        document.getElementById('scannedMinStock').textContent = data.product.min_stock_level;
        document.getElementById('lowStockAlert').style.display = 'block';
    }
}

function updateInventoryFromScan(action) {
    if (!currentScannedProduct) return;
    
    const quantity = action === 'add' 
        ? parseInt(document.getElementById('addQuantityInput').value)
        : parseInt(document.getElementById('subtractQuantityInput').value);
    
    if (quantity <= 0) {
        alert('Quantity must be greater than 0');
        return;
    }

    fetch('<?= base_url('barcode/update-inventory') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(currentScannedProduct.barcode) +
              '&quantity=' + quantity +
              '&action=' + action
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inventory updated successfully! New quantity: ' + data.quantity);
            // Refresh the product display
            scanBarcode(currentScannedProduct.barcode);
            // Reload page after a short delay to show updated inventory
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('Error: ' + (data.error || 'Failed to update inventory'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating inventory');
    });
}

// Allow Enter key on manual barcode input
document.getElementById('manualBarcodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        scanManualBarcode();
    }
});

// Stop camera when modal is closed
document.getElementById('scanModal').addEventListener('hidden.bs.modal', function() {
    stopCameraOnClose();
});
</script>
<?= $this->endSection() ?>

