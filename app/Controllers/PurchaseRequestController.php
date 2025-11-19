<?php

namespace App\Controllers;

use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestItemModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\ActivityLogModel;
use App\Libraries\NotificationService;

class PurchaseRequestController extends BaseController
{
    protected $purchaseRequestModel;
    protected $purchaseRequestItemModel;
    protected $productModel;
    protected $branchModel;
    protected $activityLogModel;
    protected $notificationService;

    public function __construct()
    {
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseRequestItemModel = new PurchaseRequestItemModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->notificationService = new NotificationService();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');

        $builder = $this->purchaseRequestModel->select('purchase_requests.*, branches.name as branch_name, users.full_name as requested_by_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id')
            ->join('users', 'users.id = purchase_requests.requested_by');

        if ($branchId && $role !== 'central_admin' && $role !== 'central_admin') {
            $builder->where('purchase_requests.branch_id', $branchId);
        }

        // Filter by branch (from dropdown)
        $filterBranchId = $this->request->getGet('branch_id');
        if ($filterBranchId && ($role === 'central_admin' || $role === 'central_admin')) {
            $builder->where('purchase_requests.branch_id', $filterBranchId);
        }

        // Search functionality (for request number and requester name)
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('purchase_requests.request_number', $search)
                ->orLike('users.full_name', $search)
                ->groupEnd();
        }

        // Filter by status
        $status = $this->request->getGet('status');
        if ($status) {
            $builder->where('purchase_requests.status', $status);
        }

        // Filter by priority
        $priority = $this->request->getGet('priority');
        if ($priority) {
            $builder->where('purchase_requests.priority', $priority);
        }

        $data['requests'] = $builder->orderBy('purchase_requests.created_at', 'DESC')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['search'] = $search;
        $data['status'] = $status;
        $data['priority'] = $priority;
        $data['filter_branch_id'] = $filterBranchId;
        $data['role'] = $role;

        return view('purchase_requests/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['products'] = $this->productModel->where('status', 'active')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['branch_id'] = $session->get('branch_id');

        return view('purchase_requests/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $requestNumber = $this->purchaseRequestModel->generateRequestNumber();
        $branchId = $this->request->getPost('branch_id') ?? $session->get('branch_id');
        $requestedBy = $session->get('user_id');

        $requestData = [
            'request_number' => $requestNumber,
            'branch_id' => $branchId,
            'requested_by' => $requestedBy,
            'status' => 'pending',
            'priority' => $this->request->getPost('priority') ?: 'normal',
            'notes' => $this->request->getPost('notes'),
        ];

        $requestId = $this->purchaseRequestModel->insert($requestData);

        // Add items
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');

        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                if (!empty($quantities[$index]) && $quantities[$index] > 0) {
                    $product = $this->productModel->find($productId);
                    $quantity = (int) $quantities[$index];
                    $unitPrice = $product['cost_price'] ?? 0;
                    $totalPrice = $quantity * $unitPrice;

                    $this->purchaseRequestItemModel->insert([
                        'purchase_request_id' => $requestId,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);
                }
            }
        }

        $this->activityLogModel->logActivity($requestedBy, 'create', 'purchase_request', "Created purchase request: $requestNumber");

        // Send notification to admins for approval
        $branch = $this->branchModel->find($branchId);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendPurchaseRequestNotification($requestId, $requestNumber, $branchName);

        return redirect()->to('/purchase-requests')->with('success', 'Purchase request created successfully');
    }

    public function approve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin' && $role !== 'central_admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $request = $this->purchaseRequestModel->find($id);
        if (!$request) {
            return redirect()->back()->with('error', 'Purchase request not found');
        }

        $this->purchaseRequestModel->update($id, [
            'status' => 'approved',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'approve', 'purchase_request', "Approved purchase request ID: $id");

        // Send notification that approved request needs to be converted to Purchase Order
        $branch = $this->branchModel->find($request['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendApprovedPurchaseRequestNotification($id, $request['request_number'], $branchName);

        return redirect()->back()->with('success', 'Purchase request approved');
    }

    public function reject($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin' && $role !== 'central_admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $this->purchaseRequestModel->update($id, [
            'status' => 'rejected',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $this->request->getPost('rejection_reason')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'reject', 'purchase_request', "Rejected purchase request ID: $id");

        return redirect()->back()->with('success', 'Purchase request rejected');
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $request = $this->purchaseRequestModel->select('purchase_requests.*, branches.name as branch_name, users.full_name as requested_by_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id')
            ->join('users', 'users.id = purchase_requests.requested_by')
            ->find($id);

        if (!$request) {
            return redirect()->to('/purchase-requests')->with('error', 'Request not found');
        }

        $items = $this->purchaseRequestItemModel->select('purchase_request_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_request_items.product_id')
            ->where('purchase_request_items.purchase_request_id', $id)
            ->findAll();

        $data['request'] = $request;
        $data['items'] = $items;
        $data['role'] = $session->get('role');

        return view('purchase_requests/view', $data);
    }

    public function print($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $request = $this->purchaseRequestModel->select('purchase_requests.*, branches.name as branch_name, users.full_name as requested_by_name')
            ->join('branches', 'branches.id = purchase_requests.branch_id')
            ->join('users', 'users.id = purchase_requests.requested_by')
            ->find($id);

        if (!$request) {
            return redirect()->to('/purchase-requests')->with('error', 'Request not found');
        }

        $items = $this->purchaseRequestItemModel->select('purchase_request_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_request_items.product_id')
            ->where('purchase_request_items.purchase_request_id', $id)
            ->findAll();

        $data['request'] = $request;
        $data['items'] = $items;

        return view('purchase_requests/print', $data);
    }
}

