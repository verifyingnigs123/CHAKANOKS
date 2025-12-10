<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\InventoryModel;
use App\Models\ActivityLogModel;
use App\Models\BranchModel;

class BarcodeController extends BaseController
{
    protected $productModel;
    protected $inventoryModel;
    protected $activityLogModel;
    protected $branchModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->inventoryModel = new InventoryModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->branchModel = new BranchModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['role'] = $session->get('role');
        $data['user_branch_id'] = $session->get('branch_id');

        return view('barcode/scan', $data);
    }

    public function scan()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $barcode = $this->request->getPost('barcode');
        $branchId = $this->request->getPost('branch_id');
        
        if (!$barcode) {
            return $this->response->setJSON(['error' => 'Barcode is required'])->setStatusCode(400);
        }

        $product = $this->productModel->where('barcode', $barcode)->first();
        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product not found with barcode: ' . $barcode
            ]);
        }

        // Get branch_id from request or session
        $role = $session->get('role');
        if (!$branchId) {
            $branchId = $session->get('branch_id');
        }
        
        // For central_admin without branch, get first active branch or show product without inventory
        if (!$branchId && $role === 'central_admin') {
            // Return product info without specific branch inventory
            return $this->response->setJSON([
                'success' => true,
                'product' => $product,
                'inventory' => null,
                'message' => 'Select a branch to view inventory'
            ]);
        }
        
        if (!$branchId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No branch assigned to user. Please contact administrator.'
            ]);
        }

        $inventory = $this->inventoryModel->where('branch_id', $branchId)
            ->where('product_id', $product['id'])
            ->first();

        return $this->response->setJSON([
            'success' => true,
            'product' => $product,
            'branch_id' => $branchId,
            'inventory' => $inventory ? [
                'quantity' => $inventory['quantity'],
                'min_stock_level' => $product['min_stock_level']
            ] : null
        ]);
    }

    public function updateInventory()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $barcode = $this->request->getPost('barcode');
        $quantity = $this->request->getPost('quantity');
        $action = $this->request->getPost('action'); // 'add' or 'subtract'
        $branchId = $this->request->getPost('branch_id');

        if (!$barcode || !$quantity || !$action) {
            return $this->response->setJSON(['error' => 'Missing parameters'])->setStatusCode(400);
        }

        $product = $this->productModel->where('barcode', $barcode)->first();
        if (!$product) {
            return $this->response->setJSON(['error' => 'Product not found'])->setStatusCode(404);
        }

        // Get branch_id from request or session
        if (!$branchId) {
            $branchId = $session->get('branch_id');
        }
        
        if (!$branchId) {
            return $this->response->setJSON(['error' => 'Please select a branch first'])->setStatusCode(400);
        }

        $inventory = $this->inventoryModel->where('branch_id', $branchId)
            ->where('product_id', $product['id'])
            ->first();

        if (!$inventory) {
            // Create new inventory record
            $inventory = [
                'branch_id' => $branchId,
                'product_id' => $product['id'],
                'quantity' => $action === 'add' ? $quantity : 0,
                'available_quantity' => $action === 'add' ? $quantity : 0
            ];
            $this->inventoryModel->insert($inventory);
            $newQuantity = $inventory['quantity'];
        } else {
            // Update existing inventory
            $currentQuantity = $inventory['quantity'];
            if ($action === 'add') {
                $newQuantity = $currentQuantity + $quantity;
            } else {
                $newQuantity = max(0, $currentQuantity - $quantity);
            }
            $this->inventoryModel->update($inventory['id'], [
                'quantity' => $newQuantity,
                'available_quantity' => $newQuantity
            ]);
        }

        $this->activityLogModel->logActivity(
            $session->get('user_id'),
            'scan',
            'inventory',
            "Scanned barcode: $barcode - " . ($action === 'add' ? 'Added' : 'Subtracted') . " $quantity units (Branch ID: $branchId)"
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Inventory updated successfully',
            'quantity' => $newQuantity
        ]);
    }
}

