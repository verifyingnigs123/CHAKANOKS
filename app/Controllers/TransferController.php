<?php

namespace App\Controllers;

use App\Models\TransferModel;
use App\Models\TransferItemModel;
use App\Models\BranchModel;
use App\Models\ProductModel;
use App\Models\InventoryModel;
use App\Models\ActivityLogModel;
use App\Libraries\NotificationService;

class TransferController extends BaseController
{
    protected $transferModel;
    protected $transferItemModel;
    protected $branchModel;
    protected $productModel;
    protected $inventoryModel;
    protected $activityLogModel;
    protected $notificationService;

    public function __construct()
    {
        $this->transferModel = new TransferModel();
        $this->transferItemModel = new TransferItemModel();
        $this->branchModel = new BranchModel();
        $this->productModel = new ProductModel();
        $this->inventoryModel = new InventoryModel();
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

        $builder = $this->transferModel->select('transfers.*, 
            from_branch.name as from_branch_name, 
            to_branch.name as to_branch_name,
            requester.full_name as requested_by_name,
            approver.full_name as approved_by_name')
            ->join('branches as from_branch', 'from_branch.id = transfers.from_branch_id')
            ->join('branches as to_branch', 'to_branch.id = transfers.to_branch_id')
            ->join('users as requester', 'requester.id = transfers.requested_by')
            ->join('users as approver', 'approver.id = transfers.approved_by', 'left')
            ->orderBy('transfers.created_at', 'DESC');

        if ($branchId && $role !== 'central_admin' && $role !== 'system_admin') {
            $builder->where('transfers.from_branch_id', $branchId)
                ->orWhere('transfers.to_branch_id', $branchId);
        }

        $data['transfers'] = $builder->findAll();
        $data['role'] = $role;

        return view('transfers/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $session->get('branch_id');
        $role = $session->get('role');

        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['products'] = $this->productModel->where('status', 'active')->findAll();
        $data['from_branch_id'] = $branchId;

        return view('transfers/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $transferNumber = $this->transferModel->generateTransferNumber();
        $fromBranchId = $this->request->getPost('from_branch_id');
        $toBranchId = $this->request->getPost('to_branch_id');
        $requestedBy = $session->get('user_id');

        if ($fromBranchId == $toBranchId) {
            return redirect()->back()->withInput()->with('error', 'Cannot transfer to the same branch');
        }

        $transferData = [
            'transfer_number' => $transferNumber,
            'from_branch_id' => $fromBranchId,
            'to_branch_id' => $toBranchId,
            'requested_by' => $requestedBy,
            'status' => 'pending',
            'request_date' => date('Y-m-d'),
            'notes' => $this->request->getPost('notes'),
        ];

        $transferId = $this->transferModel->insert($transferData);

        // Add items
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');

        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                if (!empty($quantities[$index]) && $quantities[$index] > 0) {
                    // Check if source branch has enough inventory
                    $inventory = $this->inventoryModel->where('branch_id', $fromBranchId)
                        ->where('product_id', $productId)
                        ->first();

                    if (!$inventory || $inventory['quantity'] < $quantities[$index]) {
                        $this->transferModel->delete($transferId);
                        return redirect()->back()->withInput()->with('error', 'Insufficient inventory for one or more products');
                    }

                    $this->transferItemModel->insert([
                        'transfer_id' => $transferId,
                        'product_id' => $productId,
                        'quantity' => (int) $quantities[$index],
                        'quantity_received' => 0,
                    ]);
                }
            }
        }

        $this->activityLogModel->logActivity($requestedBy, 'create', 'transfer', "Created transfer: $transferNumber");

        // Send notification to admins and branch managers for approval
        $fromBranch = $this->branchModel->find($fromBranchId);
        $toBranch = $this->branchModel->find($toBranchId);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        $this->notificationService->sendTransferApprovalNotification($transferId, $transferNumber, $fromBranchId, $toBranchId, $fromBranchName, $toBranchName);

        return redirect()->to('/transfers')->with('success', 'Transfer request created successfully');
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $transfer = $this->transferModel->select('transfers.*, 
            from_branch.name as from_branch_name, 
            to_branch.name as to_branch_name,
            requester.full_name as requested_by_name,
            approver.full_name as approved_by_name')
            ->join('branches as from_branch', 'from_branch.id = transfers.from_branch_id')
            ->join('branches as to_branch', 'to_branch.id = transfers.to_branch_id')
            ->join('users as requester', 'requester.id = transfers.requested_by')
            ->join('users as approver', 'approver.id = transfers.approved_by', 'left')
            ->find($id);

        if (!$transfer) {
            return redirect()->to('/transfers')->with('error', 'Transfer not found');
        }

        $items = $this->transferItemModel->select('transfer_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = transfer_items.product_id')
            ->where('transfer_items.transfer_id', $id)
            ->findAll();

        $data['transfer'] = $transfer;
        $data['items'] = $items;
        $data['role'] = $session->get('role');

        return view('transfers/view', $data);
    }

    public function approve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'branch_manager' && $role !== 'central_admin' && $role !== 'system_admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $transfer = $this->transferModel->find($id);
        if (!$transfer || $transfer['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid transfer request');
        }

        $this->transferModel->update($id, [
            'status' => 'approved',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'approve', 'transfer', "Approved transfer ID: $id");

        return redirect()->back()->with('success', 'Transfer approved');
    }

    public function reject($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'branch_manager' && $role !== 'central_admin' && $role !== 'system_admin') {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $this->transferModel->update($id, [
            'status' => 'rejected',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'reject', 'transfer', "Rejected transfer ID: $id");

        return redirect()->back()->with('success', 'Transfer rejected');
    }

    public function complete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $transfer = $this->transferModel->find($id);
        if (!$transfer || $transfer['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Transfer must be approved first');
        }

        $items = $this->transferItemModel->where('transfer_id', $id)->findAll();

        foreach ($items as $item) {
            // Subtract from source branch
            $fromInventory = $this->inventoryModel->where('branch_id', $transfer['from_branch_id'])
                ->where('product_id', $item['product_id'])
                ->first();

            if ($fromInventory) {
                $newQuantity = $fromInventory['quantity'] - $item['quantity'];
                $this->inventoryModel->updateQuantity($transfer['from_branch_id'], $item['product_id'], $newQuantity, $session->get('user_id'));
            }

            // Add to destination branch
            $toInventory = $this->inventoryModel->where('branch_id', $transfer['to_branch_id'])
                ->where('product_id', $item['product_id'])
                ->first();

            if ($toInventory) {
                $newQuantity = $toInventory['quantity'] + $item['quantity'];
                $this->inventoryModel->updateQuantity($transfer['to_branch_id'], $item['product_id'], $newQuantity, $session->get('user_id'));
            } else {
                $this->inventoryModel->updateQuantity($transfer['to_branch_id'], $item['product_id'], $item['quantity'], $session->get('user_id'));
            }

            // Update received quantity
            $this->transferItemModel->update($item['id'], [
                'quantity_received' => $item['quantity']
            ]);
        }

        $this->transferModel->update($id, [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'complete', 'transfer', "Completed transfer ID: $id");

        return redirect()->to('/transfers')->with('success', 'Transfer completed and inventory updated');
    }
}

