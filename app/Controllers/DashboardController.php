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
use App\Models\NotificationModel;

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
                $data['total_branches'] = (new BranchModel())->where('status', 'active')->countAllResults();
                // Count products from supplier_products table (managed by suppliers)
                $supplierProductModel = new \App\Models\SupplierProductModel();
                $data['total_products'] = $supplierProductModel->where('status', 'active')->countAllResults();
                $data['total_suppliers'] = (new SupplierModel())->where('status', 'active')->countAllResults();
                $data['pending_requests'] = (new PurchaseRequestModel())->where('status', 'pending')->countAllResults();
                $data['active_alerts'] = (new StockAlertModel())->where('status', 'active')->countAllResults();
                $data['pending_orders'] = (new PurchaseOrderModel())->whereIn('status', ['draft', 'sent'])->countAllResults();
                $data['in_transit_deliveries'] = (new DeliveryModel())->where('status', 'in_transit')->countAllResults();
                $data['completed_orders'] = (new PurchaseOrderModel())->where('status', 'completed')->countAllResults();
                
                // Branch inventory summary
                $data['branch_inventory_summary'] = $this->getBranchInventorySummary();
                
                // Low stock items across all branches
                $data['low_stock_items'] = $this->getAllLowStockItems();
                
                // Supplier performance
                $data['supplier_performance'] = $this->getSupplierPerformance();
                
                // Recent activities
                $data['recent_orders'] = (new PurchaseOrderModel())->orderBy('created_at', 'DESC')->limit(5)->findAll();
                $data['recent_deliveries'] = (new DeliveryModel())->orderBy('created_at', 'DESC')->limit(5)->findAll();
                
                // Chart data
                $data['purchase_orders_chart'] = $this->getPurchaseOrdersChartData();
                $data['inventory_value_chart'] = $this->getInventoryValueChartData();
                $data['deliveries_chart'] = $this->getDeliveriesChartData();
                break;

            case 'branch_manager':
                // Get branch info
                $branch = (new BranchModel())->find($branchId);
                $data['branch_name'] = $branch['name'] ?? 'Your Branch';
                $data['branch_info'] = $branch;
                
                $data['branch_inventory'] = (new InventoryModel())->where('branch_id', $branchId)->countAllResults();
                $lowStockItems = $this->getLowStockItems($branchId);
                $data['low_stock_items'] = $lowStockItems;
                $data['low_stock_count'] = count($lowStockItems);
                $data['pending_requests'] = (new PurchaseRequestModel())->where('branch_id', $branchId)->where('status', 'pending')->countAllResults();
                $data['active_alerts'] = (new StockAlertModel())->where('branch_id', $branchId)->where('status', 'active')->countAllResults();
                
                // Additional metrics for branch manager
                $data['pending_transfers'] = (new TransferModel())->where('to_branch_id', $branchId)->where('status', 'pending')->countAllResults();
                $data['pending_approvals'] = (new TransferModel())->where('from_branch_id', $branchId)->where('status', 'pending')->countAllResults();
                $data['in_transit_deliveries'] = (new DeliveryModel())->where('branch_id', $branchId)->where('status', 'in_transit')->countAllResults();
                $data['scheduled_deliveries'] = (new DeliveryModel())->where('branch_id', $branchId)->where('status', 'scheduled')->countAllResults();
                
                // Inventory value
                $data['inventory_value'] = $this->getBranchInventoryValue($branchId);
                
                // Purchase orders for this branch
                $data['pending_orders'] = (new PurchaseOrderModel())->where('branch_id', $branchId)->whereIn('status', ['draft', 'sent'])->countAllResults();
                $data['completed_orders'] = (new PurchaseOrderModel())->where('branch_id', $branchId)->where('status', 'completed')->countAllResults();
                
                // Send low stock notifications to branch staff
                $this->sendLowStockNotifications($branchId, $lowStockItems);
                
                // Recent activities
                $data['recent_requests'] = (new PurchaseRequestModel())->where('branch_id', $branchId)->orderBy('created_at', 'DESC')->limit(5)->findAll();
                $transferModel = new TransferModel();
                $data['recent_transfers'] = $transferModel->groupStart()
                    ->where('from_branch_id', $branchId)
                    ->orWhere('to_branch_id', $branchId)
                    ->groupEnd()
                    ->orderBy('created_at', 'DESC')
                    ->limit(5)
                    ->findAll();
                $data['recent_deliveries'] = (new DeliveryModel())->where('branch_id', $branchId)->orderBy('created_at', 'DESC')->limit(5)->findAll();
                
                // Chart data
                $data['purchase_requests_chart'] = $this->getBranchPurchaseRequestsChartData($branchId);
                $data['inventory_trends_chart'] = $this->getBranchInventoryTrendsChartData($branchId);
                $data['transfers_chart'] = $this->getBranchTransfersChartData($branchId);
                break;

            case 'inventory_staff':
                $data['branch_inventory'] = (new InventoryModel())->where('branch_id', $branchId)->countAllResults();
                $lowStockItems = $this->getLowStockItems($branchId);
                $data['low_stock_items'] = $lowStockItems;
                $data['low_stock_count'] = count($lowStockItems);
                $data['active_alerts'] = (new StockAlertModel())->where('branch_id', $branchId)->where('status', 'active')->countAllResults();
                $data['pending_transfers'] = (new TransferModel())->where('to_branch_id', $branchId)->where('status', 'pending')->countAllResults();
                
                // Send low stock notifications to branch staff
                $this->sendLowStockNotifications($branchId, $lowStockItems);
                break;

            case 'supplier':
                // Supplier dashboard: show orders assigned to this supplier
                $supplierId = $session->get('supplier_id');
                
                // Fallback: get supplier_id from user record if not in session
                if (!$supplierId) {
                    $userModel = new \App\Models\UserModel();
                    $user = $userModel->find($userId);
                    if ($user && !empty($user['supplier_id'])) {
                        $supplierId = $user['supplier_id'];
                        $session->set('supplier_id', $supplierId);
                    }
                }
                
                // Get supplier info
                $supplierModel = new SupplierModel();
                $supplier = $supplierModel->find($supplierId);
                $data['supplier_name'] = $supplier['name'] ?? $session->get('full_name') . ' Supplies';
                $data['supplier_info'] = $supplier;
                
                // Approved orders waiting for preparation (sent/confirmed and not prepared)
                $poModel = new PurchaseOrderModel();
                $data['waiting_preparation'] = $poModel->where('supplier_id', $supplierId)
                    ->whereIn('status', ['sent', 'confirmed'])
                    ->orderBy('created_at', 'DESC')
                    ->findAll();

                // Orders being prepared (prepared)
                $data['being_prepared'] = $poModel->where('supplier_id', $supplierId)
                    ->where('status', 'prepared')
                    ->orderBy('prepared_at', 'DESC')
                    ->findAll();

                // Completed and shipped
                $data['completed_orders'] = $poModel->where('supplier_id', $supplierId)
                    ->where('status', 'completed')
                    ->orderBy('updated_at', 'DESC')
                    ->findAll();

                // Notifications for supplier user
                $notificationModel = new NotificationModel();
                $data['notifications'] = $notificationModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->limit(10)->findAll();

                // Approved purchase requests assigned to this supplier (for creating PO / preparing)
                $purchaseRequestModel = new PurchaseRequestModel();

                // Fetch requests where supplier_id = this supplier OR any of the request items' products belong to this supplier
                $builder = $purchaseRequestModel->select('purchase_requests.*')
                    ->join('purchase_request_items', 'purchase_request_items.purchase_request_id = purchase_requests.id')
                    ->join('products', 'products.id = purchase_request_items.product_id')
                    ->where('purchase_requests.status', 'approved')
                    ->groupStart()
                        ->where('purchase_requests.supplier_id', $supplierId)
                        ->orWhere('products.supplier_id', $supplierId)
                    ->groupEnd()
                    ->orderBy('purchase_requests.approved_at', 'DESC')
                    ->groupBy('purchase_requests.id');

                $data['approved_requests_for_supplier'] = $builder->findAll();
                break;

            case 'logistics_coordinator':
                // Logistics dashboard: shipments and schedules
                $deliveryModel = new DeliveryModel();
                $poModel = new PurchaseOrderModel();

                // Orders ready for shipment (prepared)
                $data['ready_for_shipment'] = $poModel->where('status', 'prepared')->orderBy('prepared_at', 'DESC')->findAll();

                // Shipment schedules (scheduled and in_transit)
                $data['shipment_schedules'] = $deliveryModel->whereIn('status', ['scheduled', 'in_transit'])->orderBy('scheduled_date', 'ASC')->findAll();

                // Active deliveries (in_transit)
                $data['active_deliveries'] = $deliveryModel->where('status', 'in_transit')->orderBy('updated_at', 'DESC')->findAll();

                // Delivery completion history
                $data['delivery_history'] = $deliveryModel->where('status', 'delivered')->orderBy('delivery_date', 'DESC')->limit(20)->findAll();
                break;

            default:
                break;
        }

        return view('dashboard/index', $data);
    }

    private function getLowStockItems($branchId)
    {
        $inventoryModel = new InventoryModel();

        $lowStock = $inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.min_stock_level')
            ->join('products', 'products.id = inventory.product_id')
            ->where('inventory.branch_id', $branchId)
            ->where('inventory.quantity <= products.min_stock_level', null, false)
            ->where('inventory.quantity >', 0) // Exclude out of stock
            ->orderBy('inventory.quantity', 'ASC')
            ->findAll();

        return $lowStock;
    }
    
    private function getAllLowStockItems()
    {
        $inventoryModel = new InventoryModel();

        $lowStock = $inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.min_stock_level, branches.name as branch_name')
            ->join('products', 'products.id = inventory.product_id')
            ->join('branches', 'branches.id = inventory.branch_id')
            ->where('inventory.quantity <= products.min_stock_level', null, false)
            ->where('inventory.quantity >', 0) // Exclude out of stock
            ->orderBy('inventory.quantity', 'ASC')
            ->limit(10)
            ->findAll();

        return $lowStock;
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

    private function getPurchaseOrdersChartData()
    {
        $purchaseOrderModel = new PurchaseOrderModel();
        
        // Get last 7 days of purchase orders
        $data = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M d', strtotime($date));
            $count = $purchaseOrderModel->where('created_at >=', $date . ' 00:00:00')
                ->where('created_at <=', $date . ' 23:59:59')
                ->countAllResults();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getInventoryValueChartData()
    {
        $summary = $this->getBranchInventorySummary();
        $labels = [];
        $values = [];

        foreach ($summary as $item) {
            $labels[] = $item['branch_name'];
            $values[] = $item['total_value'];
        }

        return [
            'labels' => $labels,
            'data' => $values
        ];
    }

    private function getDeliveriesChartData()
    {
        $deliveryModel = new DeliveryModel();
        
        $statuses = ['scheduled', 'in_transit', 'delivered'];
        $data = [];
        
        foreach ($statuses as $status) {
            $data[] = $deliveryModel->where('status', $status)->countAllResults();
        }

        return [
            'labels' => ['Scheduled', 'In Transit', 'Delivered'],
            'data' => $data
        ];
    }

    private function getBranchInventoryValue($branchId)
    {
        $inventoryModel = new InventoryModel();
        $productModel = new ProductModel();

        $inventoryItems = $inventoryModel->select('inventory.quantity, products.cost_price')
            ->join('products', 'products.id = inventory.product_id')
            ->where('inventory.branch_id', $branchId)
            ->findAll();

        $totalValue = 0;
        foreach ($inventoryItems as $item) {
            $totalValue += ($item['quantity'] * $item['cost_price']);
        }

        return $totalValue;
    }

    private function getBranchPurchaseRequestsChartData($branchId)
    {
        $purchaseRequestModel = new PurchaseRequestModel();
        
        // Get last 7 days of purchase requests
        $data = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('M d', strtotime($date));
            $count = $purchaseRequestModel->where('branch_id', $branchId)
                ->where('created_at >=', $date . ' 00:00:00')
                ->where('created_at <=', $date . ' 23:59:59')
                ->countAllResults();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getBranchInventoryTrendsChartData($branchId)
    {
        $inventoryModel = new InventoryModel();
        
        // Get current inventory count for last 7 days (simplified - showing current inventory)
        // For a more accurate trend, we'd need to track daily snapshots
        $data = [];
        $labels = [];
        $currentCount = $inventoryModel->where('branch_id', $branchId)->countAllResults();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('M d', strtotime("-$i days"));
            $labels[] = $date;
            // For now, show current count (in a real scenario, you'd track daily snapshots)
            $data[] = $currentCount;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getBranchTransfersChartData($branchId)
    {
        $transferModel = new TransferModel();
        
        $statuses = ['pending', 'approved', 'completed', 'rejected'];
        $data = [];
        $labels = [];
        
        foreach ($statuses as $status) {
            $count = $transferModel->groupStart()
                ->where('from_branch_id', $branchId)
                ->orWhere('to_branch_id', $branchId)
                ->groupEnd()
                ->where('status', $status)
                ->countAllResults();
            $data[] = $count;
            $labels[] = ucfirst($status);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Send low stock notifications to branch staff
     * This method checks for low stock items and sends notifications to branch manager and inventory staff
     */
    private function sendLowStockNotifications($branchId, $lowStockItems)
    {
        if (empty($lowStockItems)) {
            return;
        }

        // Load notification service
        $notificationService = new \App\Libraries\NotificationService();
        
        // Get branch name
        $branchModel = new BranchModel();
        $branch = $branchModel->find($branchId);
        $branchName = $branch['name'] ?? 'Branch';

        // Send notification for each low stock item
        foreach ($lowStockItems as $item) {
            $productName = $item['product_name'];
            $currentQty = $item['quantity'];
            $minLevel = $item['min_stock_level'];
            
            // Send notification to branch manager and inventory staff
            $notificationService->notifyLowStock($branchId, $productName, $currentQty, $minLevel);
        }
    }
}

