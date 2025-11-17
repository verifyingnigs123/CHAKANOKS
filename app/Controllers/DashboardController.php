<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\BranchModel;
use App\Models\StockAlertModel;
use App\Models\ProductModel;
use App\Models\DeliveryModel;
use App\Models\TransferModel;
use App\Models\SupplierModel;
use App\Models\PurchaseOrderItemModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $branchId = $session->get('branch_id');
        $userId = $session->get('user_id');

        $data = [];
        $data['role'] = $role;
        $data['username'] = $session->get('username');

        // Role-based dashboard data
        switch ($role) {
            case 'central_admin':
            case 'system_admin':
                $data['total_branches'] = (new BranchModel())->where('status', 'active')->countAllResults();
                $data['total_products'] = (new ProductModel())->countAllResults();
                $data['total_suppliers'] = (new SupplierModel())->where('status', 'active')->countAllResults();
                $data['pending_requests'] = (new PurchaseRequestModel())->where('status', 'pending')->countAllResults();
                $data['active_alerts'] = (new StockAlertModel())->where('status', 'active')->countAllResults();
                $data['pending_orders'] = (new PurchaseOrderModel())->whereIn('status', ['draft', 'sent'])->countAllResults();
                $data['in_transit_deliveries'] = (new DeliveryModel())->where('status', 'in_transit')->countAllResults();
                $data['completed_orders'] = (new PurchaseOrderModel())->where('status', 'completed')->countAllResults();
                
                // Branch inventory summary
                $data['branch_inventory_summary'] = $this->getBranchInventorySummary();
                
                // Supplier performance
                $data['supplier_performance'] = $this->getSupplierPerformance();
                
                // Recent activities
                $data['recent_orders'] = (new PurchaseOrderModel())->orderBy('created_at', 'DESC')->limit(5)->findAll();
                $data['recent_deliveries'] = (new DeliveryModel())->orderBy('created_at', 'DESC')->limit(5)->findAll();
                break;

            case 'branch_manager':
                $data['branch_inventory'] = (new InventoryModel())->where('branch_id', $branchId)->countAllResults();
                $data['low_stock_items'] = $this->getLowStockItems($branchId);
                $data['pending_requests'] = (new PurchaseRequestModel())->where('branch_id', $branchId)->where('status', 'pending')->countAllResults();
                $data['active_alerts'] = (new StockAlertModel())->where('branch_id', $branchId)->where('status', 'active')->countAllResults();
                break;

            case 'inventory_staff':
                $data['branch_inventory'] = (new InventoryModel())->where('branch_id', $branchId)->countAllResults();
                $data['active_alerts'] = (new StockAlertModel())->where('branch_id', $branchId)->where('status', 'active')->countAllResults();
                $data['pending_transfers'] = (new TransferModel())->where('to_branch_id', $branchId)->where('status', 'pending')->countAllResults();
                break;

            default:
                break;
        }

        return view('dashboard/index', $data);
    }

    private function getLowStockItems($branchId)
    {
        $inventoryModel = new InventoryModel();
        $productModel = new ProductModel();

        $lowStock = $inventoryModel->select('inventory.*, products.name, products.min_stock_level')
            ->join('products', 'products.id = inventory.product_id')
            ->where('inventory.branch_id', $branchId)
            ->where('inventory.quantity <= products.min_stock_level', null, false)
            ->findAll();

        return count($lowStock);
    }

    private function getBranchInventorySummary()
    {
        $inventoryModel = new InventoryModel();
        $branchModel = new BranchModel();

        $branches = $branchModel->where('status', 'active')->findAll();
        $summary = [];

        foreach ($branches as $branch) {
            $totalItems = $inventoryModel->where('branch_id', $branch['id'])->countAllResults();
            
            // Calculate total value
            $inventoryItems = $inventoryModel->select('inventory.quantity, products.cost_price')
                ->join('products', 'products.id = inventory.product_id')
                ->where('inventory.branch_id', $branch['id'])
                ->findAll();
            
            $totalValue = 0;
            foreach ($inventoryItems as $item) {
                $totalValue += ($item['quantity'] * $item['cost_price']);
            }

            $summary[] = [
                'branch_name' => $branch['name'],
                'total_items' => $totalItems,
                'total_value' => $totalValue,
            ];
        }

        return $summary;
    }

    private function getSupplierPerformance()
    {
        $supplierModel = new SupplierModel();
        $purchaseOrderModel = new PurchaseOrderModel();
        $purchaseOrderItemModel = new PurchaseOrderItemModel();

        $suppliers = $supplierModel->where('status', 'active')->findAll();
        $performance = [];

        foreach ($suppliers as $supplier) {
            $totalOrders = $purchaseOrderModel->where('supplier_id', $supplier['id'])->countAllResults();
            $completedOrders = $purchaseOrderModel->where('supplier_id', $supplier['id'])
                ->where('status', 'completed')
                ->countAllResults();

            $totalValue = $purchaseOrderModel->selectSum('total_amount', 'total')
                ->where('supplier_id', $supplier['id'])
                ->where('status', 'completed')
                ->first();

            $performance[] = [
                'supplier_name' => $supplier['name'],
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'completion_rate' => $totalOrders > 0 ? ($completedOrders / $totalOrders * 100) : 0,
                'total_value' => $totalValue['total'] ?? 0,
            ];
        }

        return $performance;
    }
}

