<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Barcode Scanner';
$title = 'Barcode Scanner';
?>

<style>
    .scanner-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .video-container {
        position: relative;
        width: 100%;
        max-width: 640px;
        margin: 0 auto;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }
    #video {
        width: 100%;
        height: auto;
        display: block;
        /* Remove mirror effect - important for barcode scanning */
        transform: scaleX(1);
        -webkit-transform: scaleX(1);
    }
    /* If using front camera, it may be mirrored - this ensures it's not */
    .video-container video {
        object-fit: cover;
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
    .result-card {
        margin-top: 20px;
    }
    .manual-input {
        margin-top: 20px;
    }
</style>

<div class="scanner-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Barcode Scanner</h4>
        <button id="toggleCamera" class="btn btn-primary">
            <i class="bi bi-camera"></i> <span id="cameraStatus">Start Camera</span>
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($role === 'central_admin'): ?>
            <div class="mb-4">
                <label class="form-label fw-bold">Select Branch</label>
                <select id="branchSelect" class="form-select">
                    <option value="">-- Select Branch --</option>
                    <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Select a branch to view and update inventory</small>
            </div>
            <?php else: ?>
            <input type="hidden" id="branchSelect" value="<?= $user_branch_id ?>">
            <?php endif; ?>
            
            <div class="video-container" id="videoContainer" style="display: none;">
                <video id="video" autoplay playsinline></video>
                <div class="scanner-overlay"></div>
            </div>

            <div class="manual-input">
                <label class="form-label">Or Enter Barcode Manually</label>
                <div class="input-group">
                    <input type="text" id="manualBarcode" class="form-control" placeholder="Enter barcode number">
                    <button class="btn btn-primary" onclick="scanManualBarcode()">
                        <i class="bi bi-search"></i> Scan
                    </button>
                </div>
            </div>

            <div id="resultCard" class="result-card" style="display: none;">
                <div class="card">
                    <div class="card-body">
                        <h5 id="productName"></h5>
                        <p class="mb-1"><strong>SKU:</strong> <span id="productSku"></span></p>
                        <p class="mb-1"><strong>Category:</strong> <span id="productCategory"></span></p>
                        <p class="mb-1"><strong>Current Stock:</strong> <span id="currentStock"></span></p>
                        <p class="mb-3"><strong>Min Stock Level:</strong> <span id="minStock"></span></p>
                        
                        <div class="alert alert-warning" id="lowStockAlert" style="display: none;">
                            <i class="bi bi-exclamation-triangle"></i> Low stock alert!
                        </div>

                        <div class="row g-2 mt-3">
                            <div class="col-md-6">
                                <label>Quantity to Add</label>
                                <input type="number" id="addQuantity" class="form-control" min="1" value="1">
                                <button class="btn btn-success w-100 mt-2" onclick="updateInventory('add')">
                                    <i class="bi bi-plus-circle"></i> Add Stock
                                </button>
                            </div>
                            <div class="col-md-6">
                                <label>Quantity to Subtract</label>
                                <input type="number" id="subtractQuantity" class="form-control" min="1" value="1">
                                <button class="btn btn-danger w-100 mt-2" onclick="updateInventory('subtract')">
                                    <i class="bi bi-dash-circle"></i> Subtract Stock
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
let stream = null;
let scanning = false;
let currentProduct = null;

document.getElementById('toggleCamera').addEventListener('click', function() {
    if (!scanning) {
        startCamera();
    } else {
        stopCamera();
    }
});

function startCamera() {
    // Try to use back camera first, fallback to any available camera
    const constraints = {
        video: { 
            facingMode: { ideal: 'environment' }, // Prefer back camera
            width: { ideal: 640 },
            height: { ideal: 480 }
        }
    };
    
    navigator.mediaDevices.getUserMedia(constraints)
    .then(function(mediaStream) {
        stream = mediaStream;
        const video = document.getElementById('video');
        video.srcObject = stream;
        
        // Check if using front camera and apply un-mirror if needed
        const track = stream.getVideoTracks()[0];
        const settings = track.getSettings();
        if (settings.facingMode === 'user') {
            video.style.transform = 'scaleX(-1)';
        } else {
            video.style.transform = 'scaleX(1)';
        }
        document.getElementById('videoContainer').style.display = 'block';
        document.getElementById('cameraStatus').textContent = 'Stop Camera';
        scanning = true;

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
                document.getElementById('errorMessage').textContent = 'Camera initialization failed. Please use manual input.';
                document.getElementById('errorMessage').style.display = 'block';
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
        document.getElementById('errorMessage').textContent = 'Camera access denied. Please use manual input.';
        document.getElementById('errorMessage').style.display = 'block';
    });
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    if (Quagga) {
        Quagga.stop();
    }
    document.getElementById('videoContainer').style.display = 'none';
    document.getElementById('cameraStatus').textContent = 'Start Camera';
    scanning = false;
}

function scanManualBarcode() {
    const barcode = document.getElementById('manualBarcode').value.trim();
    if (barcode) {
        scanBarcode(barcode);
    } else {
        alert('Please enter a barcode');
    }
}

function getSelectedBranch() {
    const branchSelect = document.getElementById('branchSelect');
    return branchSelect ? branchSelect.value : '';
}

function scanBarcode(barcode) {
    const branchId = getSelectedBranch();
    
    fetch('<?= base_url('barcode/scan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(barcode) + '&branch_id=' + encodeURIComponent(branchId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentProduct = data.product;
            currentProduct.branch_id = data.branch_id || branchId;
            displayProduct(data);
        } else {
            document.getElementById('errorMessage').textContent = data.message || 'Product not found';
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('resultCard').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessage').textContent = 'Error scanning barcode';
        document.getElementById('errorMessage').style.display = 'block';
    });
}

function displayProduct(data) {
    document.getElementById('errorMessage').style.display = 'none';
    document.getElementById('resultCard').style.display = 'block';
    
    document.getElementById('productName').textContent = data.product.name;
    document.getElementById('productSku').textContent = data.product.sku;
    document.getElementById('productCategory').textContent = data.product.category || 'N/A';
    
    if (data.inventory) {
        document.getElementById('currentStock').textContent = data.inventory.quantity;
        document.getElementById('minStock').textContent = data.inventory.min_stock_level;
        
        if (data.inventory.quantity <= data.inventory.min_stock_level) {
            document.getElementById('lowStockAlert').style.display = 'block';
        } else {
            document.getElementById('lowStockAlert').style.display = 'none';
        }
    } else {
        document.getElementById('currentStock').textContent = '0 (Not in inventory)';
        document.getElementById('minStock').textContent = data.product.min_stock_level;
        document.getElementById('lowStockAlert').style.display = 'block';
    }
}

function updateInventory(action) {
    if (!currentProduct) return;
    
    const branchId = getSelectedBranch() || currentProduct.branch_id;
    if (!branchId) {
        alert('Please select a branch first');
        return;
    }
    
    const quantity = action === 'add' 
        ? parseInt(document.getElementById('addQuantity').value)
        : parseInt(document.getElementById('subtractQuantity').value);
    
    if (quantity <= 0) {
        alert('Quantity must be greater than 0');
        return;
    }

    fetch('<?= base_url('barcode/update-inventory') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'barcode=' + encodeURIComponent(currentProduct.barcode) +
              '&quantity=' + quantity +
              '&action=' + action +
              '&branch_id=' + encodeURIComponent(branchId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inventory updated successfully! New quantity: ' + data.quantity);
            // Refresh the product display
            scanBarcode(currentProduct.barcode);
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
document.getElementById('manualBarcode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        scanManualBarcode();
    }
});
</script>

<?= $this->endSection() ?>

