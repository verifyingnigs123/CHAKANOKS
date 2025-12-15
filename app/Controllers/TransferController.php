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

        if ($branchId && $role !== 'central_admin' && $role !== 'central_admin') {
            $builder->where('transfers.from_branch_id', $branchId)
                ->orWhere('transfers.to_branch_id', $branchId);
        }

        $data['transfers'] = $builder->findAll();
        $data['role'] = $role;

        // Data for Create Transfer Modal
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['products'] = $this->productModel->where('status', 'active')->findAll();
        $data['from_branch_id'] = $branchId;

        return view('transfers/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin, branch_manager, and franchise_manager can create transfers
        $role = $session->get('role');
        if (!in_array($role, ['central_admin', 'branch_manager', 'franchise_manager'])) {
            return redirect()->to('/transfers')->with('error', 'Only Central Admin, Branch Managers, and Franchise Managers can create transfers');
        }

        $branchId = $session->get('branch_id');

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

        // Only central_admin, branch_manager, and franchise_manager can create transfers
        $role = $session->get('role');
        if (!in_array($role, ['central_admin', 'branch_manager', 'franchise_manager'])) {
            return redirect()->to('/transfers')->with('error', 'Only Central Admin, Branch Managers, and Franchise Managers can create transfers');
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

        // Send workflow notification to destination branch for approval
        $fromBranch = $this->branchModel->find($fromBranchId);
        $toBranch = $this->branchModel->find($toBranchId);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        
        // Log for debugging
        log_message('info', "Sending transfer notifications for Transfer ID: $transferId, Number: $transferNumber");
        log_message('info', "From: $fromBranchName (ID: $fromBranchId), To: $toBranchName (ID: $toBranchId)");
        
        $notificationCount = $this->notificationService->notifyTransferCreatedWorkflow(
            $transferId, 
            $transferNumber, 
            $fromBranchId, 
            $fromBranchName, 
            $toBranchId, 
            $toBranchName
        );
        
        log_message('info', "Transfer notifications sent: $notificationCount notifications created");

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

    public function getDetails($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
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
            return $this->response->setJSON(['error' => 'Transfer not found'])->setStatusCode(404);
        }

        $items = $this->transferItemModel->select('transfer_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = transfer_items.product_id')
            ->where('transfer_items.transfer_id', $id)
            ->findAll();

        return $this->response->setJSON([
            'transfer' => $transfer,
            'items' => $items
        ]);
    }

    public function approve($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        // Only Central Admin can approve transfers
        if ($role !== 'central_admin') {
            return redirect()->back()->with('error', 'Only Central Admin can approve transfers');
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

        // Send workflow notification to source branch to complete transfer
        $fromBranch = $this->branchModel->find($transfer['from_branch_id']);
        $toBranch = $this->branchModel->find($transfer['to_branch_id']);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        
        log_message('info', "Transfer {$transfer['transfer_number']} approved by Central Admin");
        $notificationCount = $this->notificationService->notifyTransferApprovedWorkflow(
            $id, 
            $transfer['transfer_number'], 
            $transfer['from_branch_id'], 
            $fromBranchName, 
            $transfer['to_branch_id'], 
            $toBranchName
        );
        log_message('info', "Transfer approval notifications sent: $notificationCount");

        return redirect()->back()->with('success', 'Transfer approved successfully');
    }

    public function reject($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        // Only Central Admin can reject transfers
        if ($role !== 'central_admin') {
            return redirect()->back()->with('error', 'Only Central Admin can reject transfers');
        }

        $transfer = $this->transferModel->find($id);
        if (!$transfer || $transfer['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Invalid transfer request');
        }

        $this->transferModel->update($id, [
            'status' => 'rejected',
            'approved_by' => $session->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'reject', 'transfer', "Rejected transfer ID: $id");

        // Send workflow notification to source branch
        $fromBranch = $this->branchModel->find($transfer['from_branch_id']);
        $toBranch = $this->branchModel->find($transfer['to_branch_id']);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        $rejectionReason = $this->request->getPost('rejection_reason') ?: 'No reason provided';
        
        log_message('info', "Transfer {$transfer['transfer_number']} rejected by Central Admin");
        $notificationCount = $this->notificationService->notifyTransferRejectedWorkflow(
            $id, 
            $transfer['transfer_number'], 
            $transfer['from_branch_id'], 
            $fromBranchName, 
            $transfer['to_branch_id'], 
            $toBranchName, 
            $rejectionReason
        );
        log_message('info', "Transfer rejection notifications sent: $notificationCount");

        return redirect()->back()->with('success', 'Transfer rejected successfully');
    }

    public function schedule($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        // Only Central Admin or Logistics Coordinator can schedule
        if ($role !== 'central_admin' && $role !== 'logistics_coordinator') {
            return redirect()->back()->with('error', 'Only Central Admin or Logistics Coordinator can schedule transfers');
        }

        $transfer = $this->transferModel->find($id);
        if (!$transfer || $transfer['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Transfer must be approved first');
        }

        $scheduledDate = $this->request->getPost('scheduled_date');
        if (!$scheduledDate) {
            return redirect()->back()->with('error', 'Please provide a scheduled date');
        }

        $this->transferModel->update($id, [
            'status' => 'scheduled',
            'scheduled_date' => $scheduledDate,
            'scheduled_by' => $session->get('user_id'),
            'scheduled_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'schedule', 'transfer', "Scheduled transfer ID: $id for $scheduledDate");

        // Send notifications
        $fromBranch = $this->branchModel->find($transfer['from_branch_id']);
        $toBranch = $this->branchModel->find($transfer['to_branch_id']);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        
        // Notify both branches
        $this->notificationService->createForBranch(
            $transfer['from_branch_id'],
            'branch_manager',
            'info',
            '📅 Transfer Scheduled',
            "Transfer {$transfer['transfer_number']} to {$toBranchName} scheduled for " . date('M d, Y', strtotime($scheduledDate)),
            base_url("transfers/view/{$id}")
        );
        
        $this->notificationService->createForBranch(
            $transfer['to_branch_id'],
            'branch_manager',
            'info',
            '📅 Incoming Transfer Scheduled',
            "Transfer {$transfer['transfer_number']} from {$fromBranchName} scheduled for " . date('M d, Y', strtotime($scheduledDate)),
            base_url("transfers/view/{$id}")
        );

        return redirect()->back()->with('success', 'Transfer scheduled successfully');
    }

    public function dispatch($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        // Only Central Admin or Logistics Coordinator can dispatch
        if ($role !== 'central_admin' && $role !== 'logistics_coordinator') {
            return redirect()->back()->with('error', 'Only Central Admin or Logistics Coordinator can dispatch transfers');
        }

        $transfer = $this->transferModel->find($id);
        if (!$transfer || ($transfer['status'] !== 'approved' && $transfer['status'] !== 'scheduled')) {
            return redirect()->back()->with('error', 'Transfer must be approved or scheduled first');
        }

        // Deduct inventory from source branch when dispatching
        $items = $this->transferItemModel->where('transfer_id', $id)->findAll();
        foreach ($items as $item) {
            $fromInventory = $this->inventoryModel->where('branch_id', $transfer['from_branch_id'])
                ->where('product_id', $item['product_id'])
                ->first();

            if ($fromInventory) {
                $newQuantity = $fromInventory['quantity'] - $item['quantity'];
                $this->inventoryModel->updateQuantity($transfer['from_branch_id'], $item['product_id'], $newQuantity, $session->get('user_id'));
            }
        }

        $this->transferModel->update($id, [
            'status' => 'in_transit',
            'dispatched_by' => $session->get('user_id'),
            'dispatched_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'dispatch', 'transfer', "Dispatched transfer ID: $id");

        // Send notifications
        $fromBranch = $this->branchModel->find($transfer['from_branch_id']);
        $toBranch = $this->branchModel->find($transfer['to_branch_id']);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        
        // Notify destination branch that transfer is on the way
        $this->notificationService->createForBranch(
            $transfer['to_branch_id'],
            'branch_manager',
            'warning',
            '🚚 Transfer In Transit - Prepare to Receive',
            "Transfer {$transfer['transfer_number']} from {$fromBranchName} is on the way. Click to receive when it arrives.",
            base_url("transfers/view/{$id}")
        );
        
        // Notify source branch
        $this->notificationService->createForBranch(
            $transfer['from_branch_id'],
            'branch_manager',
            'info',
            '🚚 Transfer Dispatched',
            "Transfer {$transfer['transfer_number']} to {$toBranchName} has been dispatched. Inventory deducted.",
            base_url("transfers/view/{$id}")
        );
        
        // Notify Central Admin
        $this->notificationService->createForRole(
            'central_admin',
            'info',
            '🚚 Transfer Dispatched',
            "Transfer {$transfer['transfer_number']} from {$fromBranchName} to {$toBranchName} is in transit.",
            base_url("transfers/view/{$id}")
        );

        return redirect()->back()->with('success', 'Transfer dispatched successfully. Inventory deducted from source branch.');
    }

    public function receive($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $userBranchId = $session->get('branch_id');

        $transfer = $this->transferModel->find($id);
        if (!$transfer || $transfer['status'] !== 'in_transit') {
            return redirect()->back()->with('error', 'Transfer must be in transit to receive');
        }

        // Only destination branch manager or central admin can receive
        if ($role !== 'central_admin' && ($role !== 'branch_manager' || $userBranchId != $transfer['to_branch_id'])) {
            return redirect()->back()->with('error', 'Only the destination branch manager can receive this transfer');
        }

        $items = $this->transferItemModel->where('transfer_id', $id)->findAll();

        // Add inventory to destination branch
        foreach ($items as $item) {
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
            'received_by' => $session->get('user_id'),
            'received_at' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity($session->get('user_id'), 'receive', 'transfer', "Received transfer ID: $id");

        // Send completion notifications to all parties
        $fromBranch = $this->branchModel->find($transfer['from_branch_id']);
        $toBranch = $this->branchModel->find($transfer['to_branch_id']);
        $fromBranchName = $fromBranch ? $fromBranch['name'] : 'Unknown Branch';
        $toBranchName = $toBranch ? $toBranch['name'] : 'Unknown Branch';
        
        log_message('info', "Transfer {$transfer['transfer_number']} completed");
        $notificationCount = $this->notificationService->notifyTransferCompletedWorkflow(
            $id, 
            $transfer['transfer_number'], 
            $transfer['from_branch_id'], 
            $fromBranchName, 
            $transfer['to_branch_id'], 
            $toBranchName
        );
        log_message('info', "Transfer completion notifications sent: $notificationCount");

        return redirect()->to('/transfers')->with('success', 'Transfer received successfully. Inventory updated.');
    }

}

