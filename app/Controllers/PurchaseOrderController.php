<?php

namespace App\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\SupplierModel;
use App\Models\BranchModel;
use App\Models\ProductModel;
use App\Models\ActivityLogModel;

class PurchaseOrderController extends BaseController
{
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $purchaseRequestModel;
    protected $purchaseRequestItemModel;
    protected $supplierModel;
    protected $branchModel;
    protected $productModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseRequestItemModel = new PurchaseRequestItemModel();
        $this->supplierModel = new SupplierModel();
        $this->branchModel = new BranchModel();
        $this->productModel = new ProductModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');

        $builder = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as created_by_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->join('users', 'users.id = purchase_orders.created_by')
            ->orderBy('purchase_orders.created_at', 'DESC');

        if ($branchId && $role !== 'central_admin' && $role !== 'system_admin') {
            $builder->where('purchase_orders.branch_id', $branchId);
        }

        $data['purchase_orders'] = $builder->findAll();
        $data['role'] = $role;

        return view('purchase_orders/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
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

        $data['request'] = $request;
        $data['request_items'] = $this->purchaseRequestItemModel->select('purchase_request_items.*, products.name as product_name, products.sku')
            ->join('products', 'products.id = purchase_request_items.product_id')
            ->where('purchase_request_items.purchase_request_id', $requestId)
            ->findAll();

        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();

        return view('purchase_orders/create_from_request', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
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

        return redirect()->to('/purchase-orders')->with('success', 'Purchase order created successfully');
    }

    public function view($id)
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
        $data['role'] = $session->get('role');

        return view('purchase_orders/view', $data);
    }

    public function send($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $this->purchaseOrderModel->update($id, [
            'status' => 'sent'
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'send', 'purchase_order', "Sent purchase order ID: $id");

        return redirect()->back()->with('success', 'Purchase order sent to supplier');
    }

    public function confirm($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $this->purchaseOrderModel->update($id, [
            'status' => 'confirmed'
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'confirm', 'purchase_order', "Confirmed purchase order ID: $id");

        return redirect()->back()->with('success', 'Purchase order confirmed');
    }
}

