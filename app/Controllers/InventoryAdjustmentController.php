<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\InventoryAdjustmentModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\ActivityLogModel;

class InventoryAdjustmentController extends BaseController
{
    protected $inventoryModel;
    protected $inventoryAdjustmentModel;
    protected $productModel;
    protected $branchModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->inventoryAdjustmentModel = new InventoryAdjustmentModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $builder = $this->inventoryAdjustmentModel->select('inventory_adjustments.*, products.name as product_name, products.sku, branches.name as branch_name, users.full_name as adjusted_by_name')
            ->join('products', 'products.id = inventory_adjustments.product_id')
            ->join('branches', 'branches.id = inventory_adjustments.branch_id', 'left')
            ->join('users', 'users.id = inventory_adjustments.adjusted_by', 'left');

        $role = $session->get('role');
        $branchId = $session->get('branch_id');

        if ($branchId && $role !== 'central_admin' && $role !== 'central_admin') {
            $builder->where('inventory_adjustments.branch_id', $branchId);
        }

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('products.name', $search)
                ->orLike('products.sku', $search)
                ->orLike('inventory_adjustments.reason', $search)
                ->groupEnd();
        }

        // Filter by adjustment type
        $type = $this->request->getGet('type');
        if ($type) {
            $builder->where('inventory_adjustments.type', $type);
        }

        // Filter by branch
        $filterBranch = $this->request->getGet('branch_id');
        if ($filterBranch) {
            $builder->where('inventory_adjustments.branch_id', $filterBranch);
        }

        // Date range filter
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        if ($dateFrom) {
            $builder->where('DATE(inventory_adjustments.created_at) >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('DATE(inventory_adjustments.created_at) <=', $dateTo);
        }

        $data['adjustments'] = $builder->orderBy('inventory_adjustments.created_at', 'DESC')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['search'] = $search;
        $data['type'] = $type;
        $data['filterBranch'] = $filterBranch;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['role'] = $role;

        return view('inventory_adjustments/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['products'] = $this->productModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['role'] = $session->get('role');
        $data['branchId'] = $session->get('branch_id');

        return view('inventory_adjustments/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $rules = [
            'product_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'type' => 'required|in_list[increase,decrease,set]',
            'quantity' => 'required|integer|greater_than[0]',
            'reason' => 'required|min_length[3]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = $this->request->getPost('product_id');
        $branchId = $this->request->getPost('branch_id');
        $type = $this->request->getPost('type');
        $quantity = (int)$this->request->getPost('quantity');
        $reason = $this->request->getPost('reason');

        // Get current inventory
        $inventory = $this->inventoryModel->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        $oldQuantity = $inventory ? $inventory['quantity'] : 0;
        $newQuantity = 0;

        // Calculate new quantity based on type
        if ($type === 'increase') {
            $newQuantity = $oldQuantity + $quantity;
        } elseif ($type === 'decrease') {
            $newQuantity = max(0, $oldQuantity - $quantity);
        } else { // set
            $newQuantity = $quantity;
        }

        // Update or create inventory
        if ($inventory) {
            $this->inventoryModel->update($inventory['id'], ['quantity' => $newQuantity]);
        } else {
            $this->inventoryModel->insert([
                'branch_id' => $branchId,
                'product_id' => $productId,
                'quantity' => $newQuantity
            ]);
        }

        // Record adjustment
        $this->inventoryAdjustmentModel->insert([
            'product_id' => $productId,
            'branch_id' => $branchId,
            'type' => $type,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'quantity_change' => $newQuantity - $oldQuantity,
            'reason' => $reason,
            'adjusted_by' => $session->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->activityLogModel->logActivity(
            $session->get('user_id'),
            'update',
            'inventory',
            "Inventory adjustment: Product ID $productId, Branch ID $branchId - $type from $oldQuantity to $newQuantity"
        );

        return redirect()->to('/inventory-adjustments')->with('success', 'Inventory adjustment recorded successfully');
    }
}

