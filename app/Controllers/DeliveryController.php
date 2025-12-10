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

        // Send notification that delivery is scheduled and needs to be received
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendDeliveryScheduledNotification($deliveryId, $deliveryNumber, $po['branch_id'], $branchName, $po['po_number']);

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

        // Get PO items
        $poItems = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $delivery['purchase_order_id'])
            ->findAll();

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
            $updateData['received_by'] = $session->get('user_id');
            $updateData['received_at'] = date('Y-m-d H:i:s');
        }

        $this->deliveryModel->update($id, $updateData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'delivery', "Updated delivery ID: $id status to $status");

        // Send notification when delivery is in transit or delivered (needs receiving)
        if ($status == 'in_transit' || $status == 'delivered') {
            $delivery = $this->deliveryModel->find($id);
            $branch = $this->branchModel->find($delivery['branch_id']);
            $branchName = $branch ? $branch['name'] : 'Unknown Branch';
            $this->notificationService->sendDeliveryReceivingNotification($id, $delivery['delivery_number'], $delivery['branch_id'], $branchName, $status);
        }

        return redirect()->back()->with('success', 'Delivery status updated');
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

        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        $branchId = $po['branch_id'];

        // Get received quantities
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $batchNumbers = $this->request->getPost('batch_numbers');
        $expiryDates = $this->request->getPost('expiry_dates');

        $itemUpdates = [];
        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                $quantity = (int) $quantities[$index];
                if ($quantity > 0) {
                    // Get current inventory before update
                    $inventory = $this->inventoryModel->where('branch_id', $branchId)
                        ->where('product_id', $productId)
                        ->first();

                    $previousQuantity = $inventory ? $inventory['quantity'] : 0;

                    // Update inventory
                    if ($inventory) {
                        $newQuantity = $inventory['quantity'] + $quantity;
                        $this->inventoryModel->updateQuantity($branchId, $productId, $newQuantity, $session->get('user_id'));
                    } else {
                        $this->inventoryModel->updateQuantity($branchId, $productId, $quantity, $session->get('user_id'));
                        $inventory = $this->inventoryModel->where('branch_id', $branchId)
                            ->where('product_id', $productId)
                            ->first();
                        $newQuantity = $quantity;
                    }

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

                    // Add inventory items (for perishables with batch/expiry)
                    if (!empty($batchNumbers[$index]) || !empty($expiryDates[$index])) {
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

        // Create pending payment transaction for PayPal (Central Admin will process later)
        $existingPayment = $this->paymentTransactionModel->getByPurchaseOrder($po['id']);
        
        if (!$existingPayment) {
            // Create pending payment transaction - Central Admin will process via PayPal
            $transactionNumber = $this->paymentTransactionModel->generateTransactionNumber();
            
            $paymentData = [
                'transaction_number' => $transactionNumber,
                'purchase_order_id' => $po['id'],
                'delivery_id' => $id,
                'branch_id' => $branchId,
                'supplier_id' => $po['supplier_id'],
                'payment_method' => 'paypal',
                'amount' => $po['total_amount'],
                'status' => 'pending', // Pending until Central Admin processes PayPal payment
                'payment_date' => null,
                'processed_by' => null,
                'notes' => "Payment pending for Purchase Order {$po['po_number']} - Awaiting Central Admin PayPal payment",
            ];
            
            $this->paymentTransactionModel->insert($paymentData);
            
            // Notify Central Admin that payment is needed
            $this->notificationService->sendToRole('central_admin', 'warning', 'Payment Required', "Delivery {$delivery['delivery_number']} received. Please process PayPal payment of ₱" . number_format($po['total_amount'], 2) . " to supplier.", base_url("deliveries/view/{$id}"));
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'receive', 'delivery', "Received delivery ID: $id");

        // Send notification that delivery is received and inventory is updated
        $branch = $this->branchModel->find($branchId);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendDeliveryReceivedNotification($id, $delivery['delivery_number'], $branchId, $branchName, $po['po_number'], $po['supplier_id']);

        // If AJAX request, return JSON so callers (like PO page) can update in-place
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Delivery received and inventory updated',
                'delivery_status' => 'delivered',
                'po_status' => $poStatus,
                'item_updates' => $itemUpdates,
                'delivery_id' => $id,
                'po_id' => $po['id']
            ]);
        }

        return redirect()->to('/deliveries')->with('success', 'Delivery received and inventory updated');
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

        return redirect()->back()->with('success', 'PayPal payment processed successfully');
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

