<?php

namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\SupplierModel;
use App\Models\BranchModel;
use App\Models\ProductModel;
use App\Models\DeliveryModel;
use App\Models\ActivityLogModel;
use App\Models\PaymentTransactionModel;
use App\Libraries\NotificationService;

class PurchaseOrderController extends BaseController
{
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $purchaseRequestModel;
    protected $purchaseRequestItemModel;
    protected $supplierModel;
    protected $branchModel;
    protected $productModel;
    protected $deliveryModel;
    protected $activityLogModel;
    protected $notificationService;
    protected $paymentTransactionModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseRequestItemModel = new PurchaseRequestItemModel();
        $this->supplierModel = new SupplierModel();
        $this->branchModel = new BranchModel();
        $this->productModel = new ProductModel();
        $this->deliveryModel = new DeliveryModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->notificationService = new NotificationService();
        $this->paymentTransactionModel = new PaymentTransactionModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');
        $supplierId = $session->get('supplier_id');

        $builder = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as created_by_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->join('users', 'users.id = purchase_orders.created_by')
            ->orderBy('purchase_orders.created_at', 'DESC');

        // Filter by branch for branch-based roles
        if ($branchId && !in_array($role, ['central_admin', 'supplier', 'logistics_coordinator'])) {
            $builder->where('purchase_orders.branch_id', $branchId);
        }
        
        // Filter by supplier for supplier role - they only see their own orders
        if ($role === 'supplier' && $supplierId) {
            $builder->where('purchase_orders.supplier_id', $supplierId);
        }

        $data['purchase_orders'] = $builder->findAll();
        $data['role'] = $role;

        // Data for Create PO Modal
        $data['approved_requests'] = $this->purchaseRequestModel->select('purchase_requests.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id')
            ->where('purchase_requests.status', 'approved')
            ->where('purchase_requests.id NOT IN (SELECT purchase_request_id FROM purchase_orders WHERE purchase_request_id IS NOT NULL)', null, false)
            ->findAll();
        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['allProducts'] = $this->productModel->where('status', 'active')->findAll();

        return view('purchase_orders/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin can create PO
        if ($session->get('role') !== 'central_admin') {
            return redirect()->to('/purchase-orders')->with('error', 'Only Central Admin can create Purchase Orders');
        }

        // Get approved purchase requests
        $data['approved_requests'] = $this->purchaseRequestModel->select('purchase_requests.*, branches.name as branch_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id')
            ->where('purchase_requests.status', 'approved')
            ->where('purchase_requests.id NOT IN (SELECT purchase_request_id FROM purchase_orders WHERE purchase_request_id IS NOT NULL)', null, false)
            ->findAll();

        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['allProducts'] = $this->productModel->where('status', 'active')->findAll();

        return view('purchase_orders/create', $data);
    }

    public function getRequestItems($requestId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $request = $this->purchaseRequestModel->find($requestId);
        if (!$request || $request['status'] !== 'approved') {
            return $this->response->setJSON(['error' => 'Invalid or unapproved request'])->setStatusCode(400);
        }

        $items = $this->purchaseRequestItemModel->select('purchase_request_items.*, products.name as product_name, products.sku, products.unit, products.cost_price')
            ->join('products', 'products.id = purchase_request_items.product_id')
            ->where('purchase_request_items.purchase_request_id', $requestId)
            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'branch_id' => $request['branch_id'],
            'items' => $items
        ]);
    }

    public function createFromRequest($requestId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $request = $this->purchaseRequestModel->find($requestId);
        if (!$request || $request['status'] !== 'approved') {
            return redirect()->to('/purchase-requests')->with('error', 'Invalid or unapproved request');
        }

        // If a PO already exists for this request, redirect to it
        $existingPO = $this->purchaseOrderModel->where('purchase_request_id', $requestId)->first();
        if ($existingPO) {
            return redirect()->to('/purchase-orders/view/' . $existingPO['id'])->with('info', 'Purchase Order already exists for this request');
        }

        // Load request items and determine supplier mapping
        $items = $this->purchaseRequestItemModel->select('purchase_request_items.*, products.name as product_name, products.sku, products.supplier_id, products.cost_price')
            ->join('products', 'products.id = purchase_request_items.product_id')
            ->where('purchase_request_items.purchase_request_id', $requestId)
            ->findAll();

        // Determine supplier: prefer request.supplier_id, otherwise infer from products
        $supplierId = $request['supplier_id'] ?? null;
        if (empty($supplierId)) {
            $supplierIds = array_unique(array_map(fn($it) => $it['supplier_id'] ?? null, $items));
            $supplierIds = array_values(array_filter($supplierIds));
            if (count($supplierIds) === 1) {
                $supplierId = $supplierIds[0];
            }
        }

        // If we have a single supplier, auto-create and send PO
        if (!empty($supplierId)) {
            try {
                $poNumber = $this->purchaseOrderModel->generatePONumber();
                $poData = [
                    'po_number' => $poNumber,
                    'purchase_request_id' => $requestId,
                    'supplier_id' => $supplierId,
                    'branch_id' => $request['branch_id'],
                    'created_by' => $session->get('user_id'),
                    'status' => 'sent',
                    'order_date' => date('Y-m-d'),
                    'sent_at' => date('Y-m-d H:i:s'),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total_amount' => 0,
                    'notes' => null,
                    'payment_method' => 'pending',
                ];

                $poId = $this->purchaseOrderModel->insert($poData);
                if ($poId) {
                    $subtotal = 0;
                    foreach ($items as $it) {
                        $unitPrice = $it['unit_price'] ?? $it['cost_price'] ?? 0;
                        $quantity = (int) $it['quantity'];
                        $totalPrice = $unitPrice * $quantity;
                        $this->purchaseOrderItemModel->insert([
                            'purchase_order_id' => $poId,
                            'product_id' => $it['product_id'],
                            'quantity' => $quantity,
                            'quantity_received' => 0,
                            'unit_price' => $unitPrice,
                            'total_price' => $totalPrice,
                        ]);
                        $subtotal += $totalPrice;
                    }

                    $tax = $subtotal * 0.12;
                    $totalAmount = $subtotal + $tax;
                    $this->purchaseOrderModel->update($poId, ['subtotal' => $subtotal, 'tax' => $tax, 'total_amount' => $totalAmount]);

                    // Mark request converted
                    $this->purchaseRequestModel->update($requestId, ['status' => 'converted']);

                    // Notify supplier users
                    $userModel = new \App\Models\UserModel();
                    $supplierUsers = $userModel->where('role', 'supplier')->where('supplier_id', $supplierId)->where('status', 'active')->findAll();
                    foreach ($supplierUsers as $suser) {
                        $this->notificationService->sendToUser($suser['id'], 'info', 'New Purchase Order', "Purchase Order {$poNumber} has been created and sent.", base_url("purchase-orders/view/{$poId}"));
                    }

                    $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'purchase_order', "Auto-created and sent PO {$poNumber} from request {$request['request_number']}");

                    return redirect()->to('/purchase-orders/view/' . $poId)->with('success', 'Purchase Order created and sent to supplier');
                }
            } catch (\Exception $e) {
                // on failure, fall back to manual create view
                $this->activityLogModel->logActivity($session->get('user_id'), 'error', 'purchase_order', "Auto-create PO failed for request {$requestId}: {$e->getMessage()}");
            }
        }

        // Fallback: show manual create-from-request view so admin can pick supplier or edit
        $data['request'] = $request;
        $data['request_items'] = $items;
        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();

        return view('purchase_orders/create_from_request', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin can create PO
        if ($session->get('role') !== 'central_admin') {
            return redirect()->to('/purchase-orders')->with('error', 'Only Central Admin can create Purchase Orders');
        }

        // Validate required fields
        $supplierId = $this->request->getPost('supplier_id');
        $branchId = $this->request->getPost('branch_id');
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $unitPrices = $this->request->getPost('unit_prices');

        if (!$supplierId || !$branchId) {
            return redirect()->back()->withInput()->with('error', 'Supplier and Branch are required');
        }

        if (!$products || !$quantities || !$unitPrices || empty($products)) {
            return redirect()->back()->withInput()->with('error', 'Please add at least one product to the purchase order');
        }

        $poNumber = $this->purchaseOrderModel->generatePONumber();
        $purchaseRequestId = $this->request->getPost('purchase_request_id');

        $paymentMethod = $this->request->getPost('payment_method') ?: 'pending';
        if (!in_array($paymentMethod, ['cod', 'paypal', 'pending'])) {
            $paymentMethod = 'pending';
        }

        $poData = [
            'po_number' => $poNumber,
            'purchase_request_id' => $purchaseRequestId ?: null,
            'supplier_id' => $supplierId,
            'branch_id' => $branchId,
            'created_by' => $session->get('user_id'),
            'status' => 'draft',
            'order_date' => date('Y-m-d'),
            'expected_delivery_date' => $this->request->getPost('expected_delivery_date') ?: null,
            'subtotal' => 0,
            'tax' => 0,
            'total_amount' => 0,
            'notes' => $this->request->getPost('notes') ?: null,
            'payment_method' => $paymentMethod,
        ];

        $poId = $this->purchaseOrderModel->insert($poData);

        if (!$poId) {
            return redirect()->back()->withInput()->with('error', 'Failed to create purchase order. Please try again.');
        }

        // Add items
        $subtotal = 0;
        $itemsAdded = 0;
        
        foreach ($products as $index => $productId) {
            if (!empty($productId) && !empty($quantities[$index]) && $quantities[$index] > 0 && !empty($unitPrices[$index])) {
                $quantity = (int) $quantities[$index];
                $unitPrice = (float) $unitPrices[$index];
                
                if ($quantity > 0 && $unitPrice > 0) {
                    $totalPrice = $quantity * $unitPrice;
                    $subtotal += $totalPrice;

                    $this->purchaseOrderItemModel->insert([
                        'purchase_order_id' => $poId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'quantity_received' => 0,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);
                    $itemsAdded++;
                }
            }
        }

        if ($itemsAdded == 0) {
            // Delete the PO if no items were added
            $this->purchaseOrderModel->delete($poId);
            return redirect()->back()->withInput()->with('error', 'Please add at least one valid product with quantity and price');
        }

        // Update totals
        $tax = $subtotal * 0.12; // 12% tax (adjustable)
        $totalAmount = $subtotal + $tax;

        $this->purchaseOrderModel->update($poId, [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total_amount' => $totalAmount,
        ]);

        // Update purchase request status if linked
        if ($purchaseRequestId) {
            $this->purchaseRequestModel->update($purchaseRequestId, [
                'status' => 'converted'
            ]);
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'purchase_order', "Created purchase order: $poNumber");

        // Send notification that PO is created and needs to be sent to supplier
        $branch = $this->branchModel->find($branchId);
        $supplier = $this->supplierModel->find($supplierId);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $supplierName = $supplier ? $supplier['name'] : 'Unknown Supplier';
        $this->notificationService->sendPurchaseOrderCreatedNotification($poId, $poNumber, $branchName, $supplierName);

        return redirect()->to('/purchase-orders')->with('success', 'Purchase order created successfully');
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $supplierId = $session->get('supplier_id');
        $branchId = $session->get('branch_id');

        $po = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, suppliers.email as supplier_email, suppliers.phone as supplier_phone, branches.name as branch_name, users.full_name as created_by_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id', 'left')
            ->join('branches', 'branches.id = purchase_orders.branch_id', 'left')
            ->join('users', 'users.id = purchase_orders.created_by', 'left')
            ->find($id);

        if (!$po) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
        }
        
        // Supplier can only view their own orders
        if ($role === 'supplier' && $supplierId && $po['supplier_id'] != $supplierId) {
            return redirect()->to('/purchase-orders')->with('error', 'Unauthorized access');
        }
        
        // Branch users can only view their branch orders
        if ($branchId && !in_array($role, ['central_admin', 'supplier', 'logistics_coordinator']) && $po['branch_id'] != $branchId) {
            return redirect()->to('/purchase-orders')->with('error', 'Unauthorized access');
        }

        $items = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $id)
            ->findAll();

        // Find an active delivery for this PO (scheduled or in_transit)
        $delivery = $this->deliveryModel->where('purchase_order_id', $id)
            ->whereIn('status', ['scheduled', 'in_transit'])
            ->orderBy('created_at', 'DESC')
            ->first();

        // Get payment transaction if exists
        $paymentTransaction = $this->paymentTransactionModel->getByPurchaseOrder($id);

        $data['po'] = $po;
        $data['items'] = $items;
        $data['delivery'] = $delivery;
        $data['payment_transaction'] = $paymentTransaction;
        $data['role'] = $session->get('role');

        return view('purchase_orders/view', $data);
    }

    public function send($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        $this->purchaseOrderModel->update($id, [
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'send', 'purchase_order', "Sent purchase order ID: $id");

        // Send notification that PO is sent and needs delivery scheduling
        $branch = $this->branchModel->find($po['branch_id']);
        $supplier = $this->supplierModel->find($po['supplier_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $supplierName = $supplier ? $supplier['name'] : 'Unknown Supplier';
        $this->notificationService->sendPurchaseOrderSentNotification($id, $po['po_number'], $branchName, $supplierName);

        return redirect()->back()->with('success', 'Purchase order sent to supplier');
    }

    public function confirm($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $this->purchaseOrderModel->update($id, [
            'status' => 'confirmed',
            'confirmed_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'confirm', 'purchase_order', "Confirmed purchase order ID: $id");

        return redirect()->back()->with('success', 'Purchase order confirmed');
    }

    /**
     * Supplier marks PO as prepared
     */
    public function markPrepared($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'supplier') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        $this->purchaseOrderModel->update($id, [
            'status' => 'prepared',
            'prepared_by' => $session->get('user_id'),
            'prepared_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'prepare', 'purchase_order', "Marked purchase order ID: $id as prepared");

        // Notify logistics coordinator to schedule shipment
        $supplier = $this->supplierModel->find($po['supplier_id']);
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendToRole('logistics_coordinator', 'info', 'PO Prepared - Schedule Shipment', "Purchase Order {$po['po_number']} for {$branchName} has been prepared by supplier.", base_url("purchase-orders/view/{$id}"));

        // Notify logistics coordinator to schedule shipment
        $this->notificationService->sendToRole('logistics_coordinator', 'info', 'PO Prepared - Schedule Shipment', "Purchase Order {$po['po_number']} for {$branchName} has been prepared by supplier.", base_url("purchase-orders/view/{$id}"));

        return redirect()->back()->with('success', 'Purchase order marked as prepared');
    }

    public function updatePaymentMethod($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        // Only branch users or central admin can update payment method
        $role = $session->get('role');
        $branchId = $session->get('branch_id');
        if ($role !== 'central_admin' && ($role !== 'branch_manager' || $po['branch_id'] != $branchId)) {
            return redirect()->back()->with('error', 'Unauthorized to update payment method');
        }

        $paymentMethod = $this->request->getPost('payment_method');
        if (!in_array($paymentMethod, ['cod', 'paypal'])) {
            return redirect()->back()->with('error', 'Invalid payment method');
        }

        $this->purchaseOrderModel->update($id, [
            'payment_method' => $paymentMethod
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'purchase_order', "Updated payment method to {$paymentMethod} for PO: {$po['po_number']}");

        return redirect()->back()->with('success', 'Payment method updated successfully');
    }

    public function print($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $po = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, suppliers.email as supplier_email, suppliers.phone as supplier_phone, branches.name as branch_name, users.full_name as created_by_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->join('users', 'users.id = purchase_orders.created_by')
            ->find($id);

        if (!$po) {
            return redirect()->to('/purchase-orders')->with('error', 'Purchase order not found');
        }

        $items = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $id)
            ->findAll();

        $data['po'] = $po;
        $data['items'] = $items;

        return view('purchase_orders/print', $data);
    }

    /**
     * Supplier updates delivery status
     */
    public function updateDeliveryStatus($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'supplier') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        // Verify supplier owns this PO
        $supplierId = $session->get('supplier_id');
        if ($po['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $deliveryStatus = $this->request->getPost('delivery_status');
        $trackingNumber = $this->request->getPost('tracking_number');
        $deliveryNotes = $this->request->getPost('delivery_notes');

        $updateData = [
            'delivery_status' => $deliveryStatus,
            'tracking_number' => $trackingNumber,
            'delivery_notes' => $deliveryNotes,
            'delivery_status_updated_at' => date('Y-m-d H:i:s'),
            'delivery_status_updated_by' => $session->get('user_id'),
        ];

        // If delivered, mark PO as completed
        if ($deliveryStatus === 'delivered') {
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        }

        $this->purchaseOrderModel->update($id, $updateData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'update_delivery', 'purchase_order', "Updated delivery status to {$deliveryStatus} for PO: {$po['po_number']}");

        // Notify branch about delivery status
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        
        $statusMessages = [
            'preparing' => 'is being prepared for shipment',
            'shipped' => 'has been shipped',
            'out_for_delivery' => 'is out for delivery',
            'delivered' => 'has been delivered',
        ];
        $statusMsg = $statusMessages[$deliveryStatus] ?? 'status updated';
        
        $this->notificationService->sendToRole('branch_manager', 'info', 'Delivery Update', "Purchase Order {$po['po_number']} {$statusMsg}.", base_url("purchase-orders/view/{$id}"));

        return redirect()->back()->with('success', 'Delivery status updated successfully');
    }

    /**
     * Supplier submits invoice
     */
    public function submitInvoice($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'supplier') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $po = $this->purchaseOrderModel->find($id);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        // Verify supplier owns this PO
        $supplierId = $session->get('supplier_id');
        if ($po['supplier_id'] != $supplierId) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $invoiceNumber = $this->request->getPost('invoice_number');
        $invoiceDate = $this->request->getPost('invoice_date');
        $invoiceAmount = $this->request->getPost('invoice_amount');
        $invoiceNotes = $this->request->getPost('invoice_notes');

        if (empty($invoiceNumber) || empty($invoiceDate) || empty($invoiceAmount)) {
            return redirect()->back()->with('error', 'Please fill in all required fields');
        }

        $this->purchaseOrderModel->update($id, [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $invoiceDate,
            'invoice_amount' => $invoiceAmount,
            'invoice_notes' => $invoiceNotes,
            'invoice_submitted_at' => date('Y-m-d H:i:s'),
            'invoice_submitted_by' => $session->get('user_id'),
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'submit_invoice', 'purchase_order', "Submitted invoice {$invoiceNumber} for PO: {$po['po_number']}");

        // Notify central admin about invoice submission
        $this->notificationService->sendToRole('central_admin', 'info', 'Invoice Submitted', "Invoice {$invoiceNumber} submitted for Purchase Order {$po['po_number']}.", base_url("purchase-orders/view/{$id}"));

        return redirect()->back()->with('success', 'Invoice submitted successfully');
    }
}

