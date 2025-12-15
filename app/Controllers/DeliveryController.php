<?php

namespace App\Controllers;

use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\InventoryModel;
use App\Models\InventoryItemModel;
use App\Models\StockAlertModel;
use App\Models\ActivityLogModel;
use App\Models\DriverModel;
use App\Models\BranchModel;
use App\Models\SupplierModel;
use App\Models\InventoryHistoryModel;
use App\Models\PaymentTransactionModel;
use App\Libraries\NotificationService;
use App\Libraries\PayPalService;

class DeliveryController extends BaseController
{
    protected $deliveryModel;
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $inventoryModel;
    protected $inventoryItemModel;
    protected $stockAlertModel;
    protected $activityLogModel;
    protected $driverModel;
    protected $branchModel;
    protected $supplierModel;
    protected $inventoryHistoryModel;
    protected $notificationService;
    protected $paymentTransactionModel;
    protected $paypalService;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryItemModel = new InventoryItemModel();
        $this->stockAlertModel = new StockAlertModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->driverModel = new DriverModel();
        $this->branchModel = new BranchModel();
        $this->supplierModel = new SupplierModel();
        $this->inventoryHistoryModel = new InventoryHistoryModel();
        $this->notificationService = new NotificationService();
        $this->paymentTransactionModel = new PaymentTransactionModel();
        $this->paypalService = new PayPalService();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');

        $builder = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, purchase_orders.payment_method as po_payment_method, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->orderBy('deliveries.created_at', 'DESC');

        // Filter by branch for non-admin roles (except logistics_coordinator who sees all)
        if ($branchId && !in_array($role, ['central_admin', 'logistics_coordinator', 'supplier'])) {
            $builder->where('deliveries.branch_id', $branchId);
        }

        // Filter by supplier for supplier role
        if ($role === 'supplier') {
            $supplierId = $session->get('supplier_id');
            
            // Fallback: get supplier_id from user record if not in session
            if (!$supplierId) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($session->get('user_id'));
                if ($user && !empty($user['supplier_id'])) {
                    $supplierId = $user['supplier_id'];
                    $session->set('supplier_id', $supplierId);
                }
            }
            
            if ($supplierId) {
                $builder->where('deliveries.supplier_id', $supplierId);
            } else {
                // No supplier_id means show nothing
                $builder->where('deliveries.supplier_id', 0);
            }
        }

        $data['deliveries'] = $builder->findAll();
        $data['role'] = $role;

        // For logistics coordinator, also show prepared purchase orders ready to be scheduled
        // Exclude POs that already have a delivery scheduled (not delivered yet)
        $preparedPOs = [];
        if ($role === 'logistics_coordinator' || $role === 'central_admin') {
            $preparedPOs = $this->purchaseOrderModel->select('purchase_orders.id, purchase_orders.po_number, suppliers.name as supplier_name, branches.name as branch_name, purchase_orders.order_date')
                ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
                ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
                ->where('purchase_orders.status', 'prepared')
                ->where("purchase_orders.id NOT IN (SELECT purchase_order_id FROM deliveries WHERE status IN ('scheduled', 'in_transit'))", null, false)
                ->orderBy('purchase_orders.created_at', 'DESC')
                ->findAll();
        }

        $data['prepared_pos'] = $preparedPOs;

        // Data for Schedule Delivery Modal - exclude POs that already have active deliveries
        $data['purchase_orders_for_modal'] = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->whereIn('purchase_orders.status', ['prepared', 'sent', 'confirmed'])
            ->where("purchase_orders.id NOT IN (SELECT purchase_order_id FROM deliveries WHERE status IN ('scheduled', 'in_transit'))", null, false)
            ->findAll();
        $data['drivers'] = $this->driverModel->getActiveDrivers();

        return view('deliveries/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only logistics coordinators and central admins can schedule deliveries
        $role = $session->get('role');
        if (!in_array($role, ['logistics_coordinator', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized to schedule deliveries');
        }

        // Get purchase orders that are prepared, confirmed or sent
        $data['purchase_orders'] = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->whereIn('purchase_orders.status', ['prepared', 'sent', 'confirmed'])
            ->findAll();

        // Get active drivers with their vehicles
        $data['drivers'] = $this->driverModel->getActiveDrivers();

        return view('deliveries/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only logistics coordinators and central admins can schedule deliveries
        $role = $session->get('role');
        if (!in_array($role, ['logistics_coordinator', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized to schedule deliveries');
        }

        $deliveryNumber = $this->deliveryModel->generateDeliveryNumber();
        $purchaseOrderId = $this->request->getPost('purchase_order_id');

        $po = $this->purchaseOrderModel->find($purchaseOrderId);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        $deliveryData = [
            'delivery_number' => $deliveryNumber,
            'purchase_order_id' => $purchaseOrderId,
            'supplier_id' => $po['supplier_id'],
            'branch_id' => $po['branch_id'],
            'status' => 'scheduled',
            'scheduled_date' => $this->request->getPost('scheduled_date'),
            'driver_name' => $this->request->getPost('driver_name'),
            'vehicle_number' => $this->request->getPost('vehicle_number'),
            'notes' => $this->request->getPost('notes'),
            'payment_method' => $po['payment_method'] ?? 'pending',
        ];

        $deliveryId = $this->deliveryModel->insert($deliveryData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'delivery', "Created delivery: $deliveryNumber");

        // Send workflow notification to branch
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $scheduledDate = date('M d, Y', strtotime($deliveryData['scheduled_date']));
        $this->notificationService->notifyDeliveryScheduledWorkflow(
            $deliveryId, 
            $deliveryNumber, 
            $po['branch_id'], 
            $branchName, 
            $po['po_number'], 
            $scheduledDate
        );

        return redirect()->to('/deliveries/view/' . $deliveryId)->with('success', 'Delivery scheduled successfully. Click "Dispatch Now" when ready to send the driver.');
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, purchase_orders.payment_method as po_payment_method, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as received_by_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->join('users', 'users.id = deliveries.received_by', 'left')
            ->find($id);

        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Get PO items - handle both regular products and supplier products
        $poItems = $this->purchaseOrderItemModel->select('purchase_order_items.*, 
            products.name as product_name,
            products.sku as sku,
            products.unit as unit,
            products.id as product_id,
            supplier_products.name as supplier_product_name,
            supplier_products.sku as supplier_product_sku,
            supplier_products.product_id as supplier_mapped_product_id')
            ->join('products', 'products.id = purchase_order_items.product_id', 'left')
            ->join('supplier_products', 'supplier_products.id = purchase_order_items.supplier_product_id', 'left')
            ->where('purchase_order_items.purchase_order_id', $delivery['purchase_order_id'])
            ->findAll();
        
        // Process items to ensure we have product_id for inventory
        foreach ($poItems as &$item) {
            // If this is a supplier product without a product_id
            if (empty($item['product_id']) && !empty($item['supplier_product_id'])) {
                // Check if supplier_product has a mapped product_id
                if (!empty($item['supplier_mapped_product_id'])) {
                    $item['product_id'] = $item['supplier_mapped_product_id'];
                    // Get product details
                    $product = $this->purchaseOrderItemModel->db->table('products')
                        ->where('id', $item['supplier_mapped_product_id'])
                        ->get()->getRowArray();
                    if ($product) {
                        $item['product_name'] = $product['name'];
                        $item['sku'] = $product['sku'];
                        $item['unit'] = $product['unit'];
                    }
                } else {
                    // Use stored product details from PO item
                    $item['product_name'] = $item['supplier_product_name'] ?? $item['product_name'];
                    $item['sku'] = $item['supplier_product_sku'] ?? $item['product_sku'];
                    $item['unit'] = $item['product_unit'] ?? 'pcs';
                }
            }
        }

        // Get PO to access payment method and other details
        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        
        // Use delivery payment_method if set, otherwise use PO payment_method
        $paymentMethod = $delivery['payment_method'] ?? $delivery['po_payment_method'] ?? 'pending';
        if (empty($delivery['payment_method']) && !empty($paymentMethod)) {
            // Update delivery with payment method from PO if not set
            $this->deliveryModel->update($id, ['payment_method' => $paymentMethod]);
            $delivery['payment_method'] = $paymentMethod;
        }
        
        // Get payment transaction if exists
        $paymentTransaction = $this->paymentTransactionModel->getByDelivery($id);

        $data['delivery'] = $delivery;
        $data['po_items'] = $poItems;
        $data['po'] = $po;
        $data['payment_transaction'] = $paymentTransaction;
        $data['role'] = $session->get('role');

        return view('deliveries/view', $data);
    }

    public function print($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, purchase_orders.payment_method as po_payment_method, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as received_by_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->join('users', 'users.id = deliveries.received_by', 'left')
            ->find($id);

        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Use delivery payment_method if set, otherwise use PO payment_method
        $paymentMethod = $delivery['payment_method'] ?? $delivery['po_payment_method'] ?? 'pending';
        if (empty($delivery['payment_method']) && !empty($paymentMethod)) {
            $delivery['payment_method'] = $paymentMethod;
        }

        $poItems = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $delivery['purchase_order_id'])
            ->findAll();

        $data['delivery'] = $delivery;
        $data['po_items'] = $poItems;

        return view('deliveries/print', $data);
    }

    public function updateStatus($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only logistics coordinators and central admins can update delivery status
        $role = $session->get('role');
        if (!in_array($role, ['logistics_coordinator', 'central_admin'])) {
            return redirect()->back()->with('error', 'Unauthorized to update delivery status');
        }

        $status = $this->request->getPost('status');
        $updateData = ['status' => $status];

        if ($status == 'in_transit') {
            $updateData['delivery_date'] = date('Y-m-d');
        } elseif ($status == 'delivered') {
            $updateData['delivery_date'] = date('Y-m-d');
            // Don't set received_by yet - that happens when branch actually receives
        }

        $this->deliveryModel->update($id, $updateData);

        // NEW FLOW: When marked as delivered, notify Central Admin to pay (don't update inventory yet)
        if ($status == 'delivered') {
            $delivery = $this->deliveryModel->find($id);
            $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
            
            // Create pending payment transaction for PayPal
            $existingPayment = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
            if (!$existingPayment) {
                $transactionNumber = $this->paymentTransactionModel->generateTransactionNumber();
                $this->paymentTransactionModel->insert([
                    'transaction_number' => $transactionNumber,
                    'purchase_order_id' => $po['id'],
                    'delivery_id' => $id,
                    'branch_id' => $po['branch_id'],
                    'supplier_id' => $po['supplier_id'],
                    'payment_method' => 'paypal',
                    'amount' => $po['total_amount'],
                    'status' => 'pending',
                    'payment_date' => null,
                    'processed_by' => null,
                    'notes' => "Payment pending for PO {$po['po_number']} - Awaiting Central Admin PayPal payment",
                ]);
                
                // Notify Central Admin that payment is needed BEFORE branch receives
                $this->notificationService->sendToRole('central_admin', 'warning', '💰 Payment Required', "Delivery {$delivery['delivery_number']} arrived at branch. Please process PayPal payment of ₱" . number_format($po['total_amount'], 2) . " to supplier before branch can receive.", base_url("deliveries/view/{$id}"));
            }
            
            $this->activityLogModel->logActivity($session->get('user_id'), 'deliver', 'delivery', "Marked delivery ID: $id as delivered - awaiting payment");
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'delivery', "Updated delivery ID: $id status to $status");

        // Send workflow notification when delivery is in transit
        if ($status == 'in_transit') {
            $delivery = $this->deliveryModel->find($id);
            $branch = $this->branchModel->find($delivery['branch_id']);
            $branchName = $branch ? $branch['name'] : 'Unknown Branch';
            $this->notificationService->notifyDeliveryInTransitWorkflow(
                $id, 
                $delivery['delivery_number'], 
                $delivery['branch_id'], 
                $branchName
            );
        }

        $successMessage = $status == 'delivered' ? 'Delivery marked as delivered. Central Admin will process payment.' : 'Delivery status updated';
        return redirect()->back()->with('success', $successMessage);
    }

    public function receive($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only branch staff, branch managers, inventory staff, and central admin can receive deliveries
        $role = $session->get('role');
        if (!in_array($role, ['central_admin', 'branch_manager', 'inventory_staff'])) {
            return redirect()->back()->with('error', 'Unauthorized to receive deliveries');
        }

        $delivery = $this->deliveryModel->find($id);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Check if delivery has been marked as delivered
        if ($delivery['status'] != 'delivered') {
            return redirect()->back()->with('error', 'Delivery must be marked as "delivered" before you can receive it.');
        }

        // NEW FLOW: Check if payment has been completed before allowing receive
        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        $paymentTransaction = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
        
        log_message('debug', "Payment check - Transaction exists: " . (!empty($paymentTransaction) ? 'Yes' : 'No'));
        if (!empty($paymentTransaction)) {
            log_message('debug', "Payment status: " . $paymentTransaction['status']);
        }
        
        if (!$paymentTransaction || $paymentTransaction['status'] != 'completed') {
            log_message('warning', "Delivery {$id} - Branch attempted to receive before payment completed. Payment status: " . ($paymentTransaction['status'] ?? 'No transaction'));
            return redirect()->back()->with('error', 'Payment must be completed by Central Admin before you can receive this delivery. Please wait for payment confirmation. (Payment Status: ' . ($paymentTransaction['status'] ?? 'Not found') . ')');
        }

        // Check if already received and inventory updated
        $existingHistory = $this->inventoryHistoryModel->where('delivery_id', $id)->countAllResults();
        if ($existingHistory > 0) {
            log_message('info', "Delivery {$id} already received and inventory updated. Skipping duplicate receive.");
            return redirect()->back()->with('info', 'This delivery has already been received and inventory was updated.');
        }

        $branchId = $po['branch_id'];

        // Get received quantities
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $batchNumbers = $this->request->getPost('batch_numbers');
        $expiryDates = $this->request->getPost('expiry_dates');

        // Debug logging
        log_message('debug', 'Receive Delivery - Products: ' . json_encode($products));
        log_message('debug', 'Receive Delivery - Quantities: ' . json_encode($quantities));
        log_message('debug', 'Receive Delivery - Branch ID: ' . $branchId);

        // Validate that we have products to receive
        if (empty($products) || empty($quantities)) {
            log_message('error', 'Receive Delivery - No products or quantities provided');
            log_message('error', 'POST data: ' . json_encode($this->request->getPost()));
            return redirect()->back()->with('error', 'No products to receive. The form did not submit product data. Please try again or contact support.');
        }

        // Start database transaction for data integrity
        $db = \Config\Database::connect();
        $db->transStart();

        $itemUpdates = [];
        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                $quantity = (int) $quantities[$index];
                log_message('debug', "Processing Product ID: {$productId}, Quantity: {$quantity}");
                
                // If product_id is empty, this is a supplier product - create it in products table
                if (empty($productId) || $productId == 0) {
                    $productNames = $this->request->getPost('product_names');
                    $productSkus = $this->request->getPost('product_skus');
                    
                    $productName = $productNames[$index] ?? '';
                    $productSku = $productSkus[$index] ?? '';
                    
                    log_message('warning', "Product ID is empty at index {$index} - Name: {$productName}, SKU: {$productSku}");
                    
                    if (empty($productName)) {
                        log_message('error', "Cannot process item at index {$index} - no product name");
                        continue;
                    }
                    
                    // Try to find existing product by SKU or name
                    $productModel = new \App\Models\ProductModel();
                    $existingProduct = null;
                    
                    if (!empty($productSku)) {
                        $existingProduct = $productModel->where('sku', $productSku)->first();
                    }
                    
                    if (!$existingProduct) {
                        $existingProduct = $productModel->where('name', $productName)->first();
                    }
                    
                    if ($existingProduct) {
                        $productId = $existingProduct['id'];
                        log_message('info', "Found existing product ID: {$productId} for {$productName}");
                    } else {
                        // Create new product from supplier product
                        log_message('info', "Creating new product: {$productName}");
                        $newProductId = $productModel->insert([
                            'name' => $productName,
                            'sku' => $productSku ?: 'AUTO-' . time() . '-' . $index,
                            'category_id' => 1, // Default category
                            'unit' => 'pcs',
                            'description' => "Auto-created from supplier product during delivery receive",
                            'status' => 'active',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        if ($newProductId) {
                            $productId = $newProductId;
                            log_message('info', "Created new product ID: {$productId}");
                        } else {
                            log_message('error', "Failed to create product: {$productName}");
                            continue;
                        }
                    }
                }
                
                if ($quantity > 0) {
                    // Get current inventory before update
                    $inventory = $this->inventoryModel->where('branch_id', $branchId)
                        ->where('product_id', $productId)
                        ->first();

                    $previousQuantity = $inventory ? $inventory['quantity'] : 0;
                    log_message('debug', "Inventory found: " . ($inventory ? 'Yes' : 'No') . ", Previous Quantity: {$previousQuantity}");

                    // Update inventory
                    if ($inventory) {
                        $newQuantity = $inventory['quantity'] + $quantity;
                        log_message('debug', "Updating existing inventory - New Quantity: {$newQuantity}");
                        $this->inventoryModel->updateQuantity($branchId, $productId, $newQuantity, $session->get('user_id'));
                    } else {
                        log_message('debug', "Creating new inventory - Quantity: {$quantity}");
                        $this->inventoryModel->updateQuantity($branchId, $productId, $quantity, $session->get('user_id'));
                        $inventory = $this->inventoryModel->where('branch_id', $branchId)
                            ->where('product_id', $productId)
                            ->first();
                        $newQuantity = $quantity;
                    }
                    
                    log_message('debug', "Inventory update completed for Product ID: {$productId}");

                    // Record inventory history
                    $paymentMethod = $po['payment_method'] ?? 'pending';
                    $this->inventoryHistoryModel->insert([
                        'branch_id' => $branchId,
                        'product_id' => $productId,
                        'purchase_order_id' => $po['id'],
                        'delivery_id' => $id,
                        'po_number' => $po['po_number'],
                        'delivery_number' => $delivery['delivery_number'],
                        'quantity_added' => $quantity,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $newQuantity,
                        'transaction_type' => 'delivery_received',
                        'payment_method' => $paymentMethod,
                        'received_by' => $session->get('user_id'),
                        'notes' => "Received from Purchase Order {$po['po_number']} via Delivery {$delivery['delivery_number']} (Payment: PayPal - Pending Central Admin approval)",
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    // Refresh inventory record to get the ID (in case it was just created)
                    if (!$inventory) {
                        $inventory = $this->inventoryModel->where('branch_id', $branchId)
                            ->where('product_id', $productId)
                            ->first();
                    }
                    
                    // Add inventory items (for perishables with batch/expiry)
                    if ($inventory && (!empty($batchNumbers[$index]) || !empty($expiryDates[$index]))) {
                        log_message('debug', "Adding inventory item with batch/expiry for inventory ID: {$inventory['id']}");
                        $this->inventoryItemModel->insert([
                            'inventory_id' => $inventory['id'],
                            'batch_number' => $batchNumbers[$index] ?? null,
                            'expiry_date' => !empty($expiryDates[$index]) ? $expiryDates[$index] : null,
                            'quantity' => $quantity,
                            'received_date' => date('Y-m-d'),
                            'status' => 'available',
                        ]);
                    }

                    // Update PO item received quantity
                    $poItem = $this->purchaseOrderItemModel->where('purchase_order_id', $po['id'])
                        ->where('product_id', $productId)
                        ->first();

                    if ($poItem) {
                        $newReceived = $poItem['quantity_received'] + $quantity;
                        $this->purchaseOrderItemModel->update($poItem['id'], [
                            'quantity_received' => $newReceived
                        ]);
                        $itemUpdates[$productId] = $newReceived;
                    }
                }
            }
        }

        // Update delivery status
        $this->deliveryModel->update($id, [
            'status' => 'delivered',
            'delivery_date' => date('Y-m-d'),
            'received_by' => $session->get('user_id'),
            'received_at' => date('Y-m-d H:i:s'),
        ]);

        // Update PO status if all items received
        $allReceived = true;
        $poItems = $this->purchaseOrderItemModel->where('purchase_order_id', $po['id'])->findAll();
        foreach ($poItems as $item) {
            if ($item['quantity_received'] < $item['quantity']) {
                $allReceived = false;
                break;
            }
        }

        if ($allReceived) {
            $this->purchaseOrderModel->update($po['id'], ['status' => 'completed']);
            $poStatus = 'completed';
        } else {
            $this->purchaseOrderModel->update($po['id'], ['status' => 'partial']);
            $poStatus = 'partial';
        }

        // Payment transaction already exists and is completed (checked earlier)
        // No need to create it here

        $this->activityLogModel->logActivity($session->get('user_id'), 'receive', 'delivery', "Received delivery ID: $id");

        // Verify inventory was actually updated
        $inventoryUpdateCount = 0;
        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                $quantity = (int) $quantities[$index];
                if ($quantity > 0) {
                    $checkInventory = $this->inventoryModel->where('branch_id', $branchId)
                        ->where('product_id', $productId)
                        ->first();
                    if ($checkInventory) {
                        $inventoryUpdateCount++;
                        log_message('debug', "Verified inventory for Product ID {$productId}: Quantity = {$checkInventory['quantity']}");
                    } else {
                        log_message('error', "Inventory verification failed for Product ID {$productId}");
                    }
                }
            }
        }
        
        log_message('info', "Delivery {$id} received: {$inventoryUpdateCount} inventory records updated");

        // Send workflow notification to all stakeholders
        $branch = $this->branchModel->find($branchId);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $supplier = $this->supplierModel->find($po['supplier_id']);
        $supplierName = $supplier ? $supplier['name'] : 'Unknown Supplier';
        $this->notificationService->notifyDeliveryReceivedWorkflow(
            $id, 
            $delivery['delivery_number'], 
            $branchId, 
            $branchName, 
            $po['po_number'], 
            $po['supplier_id'], 
            $supplierName
        );

        // If AJAX request, return JSON so callers (like PO page) can update in-place
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Delivery received and inventory updated',
                'delivery_status' => 'delivered',
                'po_status' => $poStatus,
                'item_updates' => $itemUpdates,
                'delivery_id' => $id,
                'po_id' => $po['id'],
                'inventory_updates' => $inventoryUpdateCount
            ]);
        }

        // Complete database transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', "Transaction failed for delivery {$id}");
            return redirect()->back()->with('error', 'Failed to receive delivery. Database transaction error. Please check logs.');
        }

        $successMessage = "Delivery received successfully. {$inventoryUpdateCount} product(s) added to inventory.";
        return redirect()->to('/deliveries')->with('success', $successMessage);
    }

    public function processPayPalPayment($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->find($id);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        if (!$po || $po['payment_method'] !== 'paypal') {
            return redirect()->back()->with('error', 'Invalid payment method');
        }

        // Get PayPal transaction ID from POST
        $paypalTransactionId = $this->request->getPost('paypal_transaction_id');
        $paypalPayerId = $this->request->getPost('paypal_payer_id');

        if (!$paypalTransactionId) {
            return redirect()->back()->with('error', 'PayPal transaction ID is required');
        }

        // Check if payment transaction exists
        $paymentTransaction = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
        
        if ($paymentTransaction) {
            // Update existing payment transaction
            $this->paymentTransactionModel->update($paymentTransaction['id'], [
                'status' => 'completed',
                'paypal_transaction_id' => $paypalTransactionId,
                'paypal_payer_id' => $paypalPayerId,
                'payment_date' => date('Y-m-d H:i:s'),
                'processed_by' => $session->get('user_id'),
            ]);
        } else {
            // Create new payment transaction
            $transactionNumber = $this->paymentTransactionModel->generateTransactionNumber();
            $this->paymentTransactionModel->insert([
                'transaction_number' => $transactionNumber,
                'purchase_order_id' => $po['id'],
                'delivery_id' => $id,
                'branch_id' => $po['branch_id'],
                'supplier_id' => $po['supplier_id'],
                'payment_method' => 'paypal',
                'amount' => $po['total_amount'],
                'status' => 'completed',
                'paypal_transaction_id' => $paypalTransactionId,
                'paypal_payer_id' => $paypalPayerId,
                'payment_date' => date('Y-m-d H:i:s'),
                'processed_by' => $session->get('user_id'),
                'notes' => "PayPal payment for Purchase Order {$po['po_number']} via Delivery {$delivery['delivery_number']}",
            ]);
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'payment', 'payment_transaction', "PayPal payment processed for PO: {$po['po_number']}");

        // NEW FLOW: Notify branch that payment is complete and they can now receive the delivery
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        
        // Notify Branch Manager and Inventory Staff to receive the delivery
        $this->notificationService->notifyByBranchAndRole(
            $po['branch_id'],
            ['branch_manager', 'inventory_staff'],
            'success',
            '✅ Payment Complete - Ready to Receive',
            "Payment completed for Delivery {$delivery['delivery_number']}. You can now receive the delivery and update inventory.",
            base_url("deliveries/view/{$id}")
        );
        
        // Also notify Central Admin for confirmation
        $this->notificationService->sendToRole(
            'central_admin',
            'success',
            '✅ Payment Processed',
            "PayPal payment of ₱" . number_format($po['total_amount'], 2) . " completed for {$branchName}. Branch can now receive delivery.",
            base_url("deliveries/view/{$id}")
        );

        return redirect()->back()->with('success', 'PayPal payment processed successfully. Branch has been notified to receive the delivery.');
    }

    /**
     * Create PayPal payment for delivery - ONLY Central Admin can process payments
     */
    public function createPayPalPayment($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only Central Admin can process PayPal payments
        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->back()->with('error', 'Only Central Admin can process payments');
        }

        $delivery = $this->deliveryModel->find($id);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Delivery must be received before payment can be processed
        if ($delivery['status'] !== 'delivered') {
            return redirect()->back()->with('error', 'Delivery must be received before processing payment');
        }

        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        // Check if payment already exists and is completed
        $paymentTransaction = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
        if ($paymentTransaction && $paymentTransaction['status'] === 'completed') {
            return redirect()->back()->with('error', 'Payment already completed');
        }

        // Convert PHP to USD for PayPal (PayPal doesn't support PHP currency directly)
        $amountUSD = $this->paypalService->convertPHPToUSD($po['total_amount']);

        // Create PayPal payment (without items to avoid item_total mismatch)
        $result = $this->paypalService->createPayment(
            $amountUSD,
            'USD',
            "Payment for Purchase Order {$po['po_number']} - Delivery {$delivery['delivery_number']}",
            base_url('deliveries/paypal-success?delivery_id=' . $id),
            base_url('deliveries/paypal-cancel?delivery_id=' . $id)
        );

        if ($result['success']) {
            // Store order ID in session for later use
            $session->set('paypal_order_id', $result['order_id']);
            $session->set('paypal_delivery_id', $id);
            
            // Redirect to PayPal for approval
            return redirect()->to($result['approval_url']);
        } else {
            return redirect()->back()->with('error', 'Failed to create PayPal payment: ' . $result['error']);
        }
    }

    /**
     * Handle PayPal payment success
     */
    public function paypalSuccess()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // PayPal Checkout SDK returns 'token' (order ID) and 'PayerID'
        $orderId = $this->request->getGet('token');
        $payerId = $this->request->getGet('PayerID');
        $deliveryId = $this->request->getGet('delivery_id');

        if (!$orderId || !$deliveryId) {
            return redirect()->to('/deliveries')->with('error', 'Invalid PayPal response - missing token or delivery_id');
        }

        // Verify delivery exists
        $delivery = $this->deliveryModel->find($deliveryId);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Execute/Capture the payment (PayPal validates the order internally)
        $result = $this->paypalService->executePayment($orderId, $payerId);

        if ($result['success']) {
            // Get PO details (delivery already fetched above)
            $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);

            // Check if payment transaction exists
            $paymentTransaction = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
            
            if ($paymentTransaction) {
                // Update existing payment transaction
                $this->paymentTransactionModel->update($paymentTransaction['id'], [
                    'status' => 'completed',
                    'paypal_transaction_id' => $result['transaction_id'],
                    'paypal_payer_id' => $payerId,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'processed_by' => $session->get('user_id'),
                    'notes' => "PayPal payment executed successfully for PO {$po['po_number']}",
                ]);
            } else {
                // Create new payment transaction
                $transactionNumber = $this->paymentTransactionModel->generateTransactionNumber();
                $this->paymentTransactionModel->insert([
                    'transaction_number' => $transactionNumber,
                    'purchase_order_id' => $po['id'],
                    'delivery_id' => $deliveryId,
                    'branch_id' => $po['branch_id'],
                    'supplier_id' => $po['supplier_id'],
                    'payment_method' => 'paypal',
                    'amount' => $po['total_amount'],
                    'status' => 'completed',
                    'paypal_transaction_id' => $result['transaction_id'],
                    'paypal_payer_id' => $payerId,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'processed_by' => $session->get('user_id'),
                    'notes' => "PayPal payment executed successfully for PO {$po['po_number']}",
                ]);
            }

            // Log activity
            $this->activityLogModel->logActivity(
                $session->get('user_id'), 
                'payment', 
                'payment_transaction', 
                "PayPal payment completed for PO: {$po['po_number']} (Transaction: {$result['transaction_id']})"
            );

            // Clear session data
            $session->remove('paypal_order_id');
            $session->remove('paypal_delivery_id');

            return redirect()->to('/deliveries/view/' . $deliveryId)->with('success', 'PayPal payment completed successfully!');
        } else {
            return redirect()->to('/deliveries/view/' . $deliveryId)->with('error', 'PayPal payment execution failed: ' . $result['error']);
        }
    }

    /**
     * Handle PayPal payment cancellation
     */
    public function paypalCancel()
    {
        $session = session();
        $deliveryId = $this->request->getGet('delivery_id');

        // Clear session data
        $session->remove('paypal_order_id');
        $session->remove('paypal_delivery_id');

        if ($deliveryId) {
            return redirect()->to('/deliveries/view/' . $deliveryId)->with('warning', 'PayPal payment was cancelled');
        } else {
            return redirect()->to('/deliveries')->with('warning', 'PayPal payment was cancelled');
        }
    }

    /**
     * Delete a delivery - Only for scheduled deliveries
     * Only Central Admin and Logistics Coordinator can delete
     */
    public function diagnostics($deliveryId)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'central_admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        $delivery = $this->deliveryModel->find($deliveryId);
        if (!$delivery) {
            return $this->response->setJSON(['error' => 'Delivery not found'])->setStatusCode(404);
        }

        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        $poItems = $this->purchaseOrderItemModel->where('purchase_order_id', $po['id'])->findAll();
        
        $diagnostics = [
            'delivery' => $delivery,
            'purchase_order' => $po,
            'po_items' => $poItems,
            'inventory_status' => []
        ];

        // Check inventory for each product
        foreach ($poItems as $item) {
            $inventory = $this->inventoryModel->where('branch_id', $po['branch_id'])
                ->where('product_id', $item['product_id'])
                ->first();
            
            $diagnostics['inventory_status'][] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'] ?? 'Unknown',
                'ordered_quantity' => $item['quantity'],
                'received_quantity' => $item['quantity_received'],
                'inventory_exists' => !empty($inventory),
                'inventory_quantity' => $inventory['quantity'] ?? 0,
                'inventory_id' => $inventory['id'] ?? null
            ];
        }

        // Check inventory history
        $diagnostics['inventory_history'] = $this->inventoryHistoryModel
            ->where('delivery_id', $deliveryId)
            ->findAll();

        // Check payment transaction
        $diagnostics['payment_transaction'] = $this->paymentTransactionModel
            ->getByDelivery($deliveryId);

        return $this->response->setJSON($diagnostics);
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin and logistics_coordinator can delete deliveries
        $role = $session->get('role');
        if (!in_array($role, ['central_admin', 'logistics_coordinator'])) {
            return redirect()->back()->with('error', 'Unauthorized to delete deliveries');
        }

        $delivery = $this->deliveryModel->find($id);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Only scheduled deliveries can be deleted (not in_transit or delivered)
        if ($delivery['status'] !== 'scheduled') {
            return redirect()->back()->with('error', 'Only scheduled deliveries can be deleted. This delivery is already ' . $delivery['status'] . '.');
        }

        // Check if there's a payment transaction linked
        $paymentTransaction = $this->paymentTransactionModel->getByDelivery($id);
        if ($paymentTransaction) {
            return redirect()->back()->with('error', 'Cannot delete delivery with existing payment transaction');
        }

        // Delete the delivery
        $deliveryNumber = $delivery['delivery_number'];
        $this->deliveryModel->delete($id);

        $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'delivery', "Deleted delivery: $deliveryNumber");

        return redirect()->to('/deliveries')->with('success', "Delivery $deliveryNumber has been deleted successfully");
    }
}

