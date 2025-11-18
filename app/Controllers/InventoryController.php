<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\StockAlertModel;
use App\Models\InventoryItemModel;
use App\Models\ActivityLogModel;
use App\Models\InventoryHistoryModel;

class InventoryController extends BaseController
{
    protected $inventoryModel;
    protected $productModel;
    protected $branchModel;
    protected $stockAlertModel;
    protected $inventoryItemModel;
    protected $activityLogModel;
    protected $inventoryHistoryModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->stockAlertModel = new StockAlertModel();
        $this->inventoryItemModel = new InventoryItemModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->inventoryHistoryModel = new InventoryHistoryModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $this->request->getGet('branch_id');
        // If branch_id is empty string from query, treat as null (show all)
        if ($branchId === '') {
            $branchId = null;
        }
        // If no branch_id in query and user is not admin, use their branch
        if ($branchId === null && $session->get('role') !== 'central_admin' && $session->get('role') !== 'system_admin') {
            $branchId = $session->get('branch_id');
        }
        
        $role = $session->get('role');

        // Get inventory with product details, include branch name when showing all branches
        if ($branchId === null) {
            $builder = $this->inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.barcode, products.unit, products.min_stock_level, products.is_perishable, branches.name as branch_name')
                ->join('products', 'products.id = inventory.product_id')
                ->join('branches', 'branches.id = inventory.branch_id', 'left');
        } else {
            $builder = $this->inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.barcode, products.unit, products.min_stock_level, products.is_perishable')
                ->join('products', 'products.id = inventory.product_id');
            $builder->where('inventory.branch_id', $branchId);
        }

        $data['inventory'] = $builder->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['current_branch_id'] = $branchId;
        $data['role'] = $role;

        return view('inventory/index', $data);
    }

    public function update()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('user_id');
        $branchId = $this->request->getPost('branch_id');
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');
        $action = $this->request->getPost('action'); // 'add', 'subtract', 'set'

        $inventory = $this->inventoryModel->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        $currentQuantity = $inventory ? $inventory['quantity'] : 0;

        switch ($action) {
            case 'add':
                $newQuantity = $currentQuantity + $quantity;
                break;
            case 'subtract':
                $newQuantity = max(0, $currentQuantity - $quantity);
                break;
            case 'set':
            default:
                $newQuantity = $quantity;
                break;
        }

        $this->inventoryModel->updateQuantity($branchId, $productId, $newQuantity, $userId);

        // Check for stock alerts
        $product = $this->productModel->find($productId);
        if ($product && $newQuantity <= $product['min_stock_level']) {
            $alertType = $newQuantity == 0 ? 'out_of_stock' : 'low_stock';
            $this->stockAlertModel->insert([
                'branch_id' => $branchId,
                'product_id' => $productId,
                'alert_type' => $alertType,
                'current_quantity' => $newQuantity,
                'threshold' => $product['min_stock_level'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->activityLogModel->logActivity($userId, 'update', 'inventory', "Updated inventory for product ID: $productId, branch ID: $branchId");

        return redirect()->back()->with('success', 'Inventory updated successfully');
    }

    public function scan()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $barcode = $this->request->getPost('barcode');
        $branchId = $session->get('branch_id') ?? $this->request->getPost('branch_id');
        $quantity = (int) ($this->request->getPost('quantity') ?: 1);

        $product = $this->productModel->where('barcode', $barcode)->first();

        if ($product) {
            // Get current inventory
            $inventory = $this->inventoryModel->where('branch_id', $branchId)
                ->where('product_id', $product['id'])
                ->first();
            
            // Add the scanned quantity to existing inventory
            $currentQuantity = $inventory ? $inventory['quantity'] : 0;
            $newQuantity = $currentQuantity + $quantity;
            
            $this->inventoryModel->updateQuantity($branchId, $product['id'], $newQuantity, $session->get('user_id'));
            
            // Check for stock alerts
            if ($newQuantity <= $product['min_stock_level']) {
                $alertType = $newQuantity == 0 ? 'out_of_stock' : 'low_stock';
                $this->stockAlertModel->insert([
                    'branch_id' => $branchId,
                    'product_id' => $product['id'],
                    'alert_type' => $alertType,
                    'current_quantity' => $newQuantity,
                    'threshold' => $product['min_stock_level'],
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            $this->activityLogModel->logActivity($session->get('user_id'), 'scan', 'inventory', "Scanned barcode: $barcode - Added $quantity units");
            
            // Include updated quantity in response
            $product['scanned_quantity'] = $quantity;
            $product['new_total_quantity'] = $newQuantity;
            
            return $this->response->setJSON(['success' => true, 'product' => $product]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Product not found']);
    }

    public function alerts()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $session->get('branch_id');
        $role = $session->get('role');

        $builder = $this->stockAlertModel->select('stock_alerts.*, products.name as product_name, products.sku, branches.name as branch_name')
            ->join('products', 'products.id = stock_alerts.product_id')
            ->join('branches', 'branches.id = stock_alerts.branch_id')
            ->where('stock_alerts.status', 'active');

        if ($branchId && $role !== 'central_admin' && $role !== 'system_admin') {
            $builder->where('stock_alerts.branch_id', $branchId);
        }

        $data['alerts'] = $builder->orderBy('stock_alerts.created_at', 'DESC')->findAll();
        $data['role'] = $role;

        return view('inventory/alerts', $data);
    }

    public function acknowledgeAlert($alertId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $this->stockAlertModel->update($alertId, [
            'status' => 'acknowledged',
            'acknowledged_by' => $session->get('user_id'),
            'acknowledged_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Alert acknowledged');
    }

    public function getQuantity()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $branchId = $this->request->getGet('branch_id');
        $productId = $this->request->getGet('product_id');

        if (!$branchId || !$productId) {
            return $this->response->setJSON(['error' => 'Missing parameters'])->setStatusCode(400);
        }

        $inventory = $this->inventoryModel->where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->first();

        return $this->response->setJSON([
            'quantity' => $inventory ? $inventory['quantity'] : 0
        ]);
    }

    public function history()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $this->request->getGet('branch_id');
        $productId = $this->request->getGet('product_id');

        // If no branch_id in query and user is not admin, use their branch
        if ($branchId === null && $role !== 'central_admin' && $role !== 'system_admin') {
            $branchId = $session->get('branch_id');
        }

        // Get history based on filters
        if ($productId) {
            $history = $this->inventoryHistoryModel->getHistoryByProduct($productId, $branchId, 100);
        } elseif ($branchId) {
            $history = $this->inventoryHistoryModel->getHistoryByBranch($branchId, 100);
        } else {
            $history = $this->inventoryHistoryModel->getAllHistory(null, 100);
        }

        $data['history'] = $history;
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['products'] = $this->productModel->where('status', 'active')->findAll();
        $data['current_branch_id'] = $branchId;
        $data['current_product_id'] = $productId;
        $data['role'] = $role;

        return view('inventory/history', $data);
    }
}

