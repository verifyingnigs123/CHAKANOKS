<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\ProductModel;
use App\Models\BranchModel;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseOrderModel;
use App\Models\DeliveryModel;
use App\Models\SupplierModel;
use App\Models\TransferModel;

class ReportController extends BaseController
{
    protected $inventoryModel;
    protected $productModel;
    protected $branchModel;
    protected $purchaseRequestModel;
    protected $purchaseOrderModel;
    protected $deliveryModel;
    protected $supplierModel;
    protected $transferModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->productModel = new ProductModel();
        $this->branchModel = new BranchModel();
        $this->purchaseRequestModel = new PurchaseRequestModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->deliveryModel = new DeliveryModel();
        $this->supplierModel = new SupplierModel();
        $this->transferModel = new TransferModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('reports/index');
    }

    public function inventory()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $this->request->getGet('branch_id');
        $category = $this->request->getGet('category');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.category, products.cost_price, products.selling_price, branches.name as branch_name')
            ->join('products', 'products.id = inventory.product_id')
            ->join('branches', 'branches.id = inventory.branch_id', 'left');

        if ($branchId) {
            $builder->where('inventory.branch_id', $branchId);
        }

        if ($category) {
            $builder->where('products.category', $category);
        }

        $data['inventory'] = $builder->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['categories'] = $this->productModel->select('category')->distinct()->findAll();
        $data['branchId'] = $branchId;
        $data['category'] = $category;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        // Calculate totals
        $totalValue = 0;
        $totalItems = 0;
        foreach ($data['inventory'] as $item) {
            $totalValue += ($item['quantity'] * $item['cost_price']);
            $totalItems += $item['quantity'];
        }
        $data['totalValue'] = $totalValue;
        $data['totalItems'] = $totalItems;

        return view('reports/inventory', $data);
    }

    public function purchaseOrders()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $status = $this->request->getGet('status');
        $supplierId = $this->request->getGet('supplier_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id');

        if ($status) {
            $builder->where('purchase_orders.status', $status);
        }

        if ($supplierId) {
            $builder->where('purchase_orders.supplier_id', $supplierId);
        }

        if ($dateFrom) {
            $builder->where('DATE(purchase_orders.order_date) >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('DATE(purchase_orders.order_date) <=', $dateTo);
        }

        $data['orders'] = $builder->orderBy('purchase_orders.created_at', 'DESC')->findAll();
        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();
        $data['status'] = $status;
        $data['supplierId'] = $supplierId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        // Calculate totals
        $totalAmount = 0;
        foreach ($data['orders'] as $order) {
            $totalAmount += $order['total_amount'];
        }
        $data['totalAmount'] = $totalAmount;

        return view('reports/purchase_orders', $data);
    }

    public function deliveries()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $status = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id');

        if ($status) {
            $builder->where('deliveries.status', $status);
        }

        if ($dateFrom) {
            $builder->where('DATE(deliveries.scheduled_date) >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('DATE(deliveries.scheduled_date) <=', $dateTo);
        }

        $data['deliveries'] = $builder->orderBy('deliveries.created_at', 'DESC')->findAll();
        $data['status'] = $status;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        return view('reports/deliveries', $data);
    }

    public function supplierPerformance()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $supplierId = $this->request->getGet('supplier_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $builder = $this->purchaseOrderModel->select('suppliers.*, 
            COUNT(purchase_orders.id) as total_orders,
            SUM(CASE WHEN purchase_orders.status = "completed" THEN 1 ELSE 0 END) as completed_orders,
            SUM(CASE WHEN purchase_orders.status = "completed" THEN purchase_orders.total_amount ELSE 0 END) as total_value,
            AVG(CASE WHEN purchase_orders.status = "completed" THEN DATEDIFF(purchase_orders.updated_at, purchase_orders.order_date) ELSE NULL END) as avg_delivery_days')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->groupBy('suppliers.id');

        if ($supplierId) {
            $builder->where('suppliers.id', $supplierId);
        }

        if ($dateFrom) {
            $builder->where('DATE(purchase_orders.order_date) >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('DATE(purchase_orders.order_date) <=', $dateTo);
        }

        $data['performance'] = $builder->findAll();
        $data['suppliers'] = $this->supplierModel->where('status', 'active')->findAll();
        $data['supplierId'] = $supplierId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        return view('reports/supplier_performance', $data);
    }

    public function wastage()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $this->request->getGet('branch_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Get expired items
        $builder = $this->inventoryModel->select('inventory_items.*, products.name as product_name, products.sku, products.cost_price, branches.name as branch_name')
            ->join('inventory_items', 'inventory_items.inventory_id = inventory.id')
            ->join('products', 'products.id = inventory.product_id')
            ->join('branches', 'branches.id = inventory.branch_id', 'left')
            ->where('inventory_items.expiry_date <', date('Y-m-d'))
            ->where('inventory_items.status', 'available');

        if ($branchId) {
            $builder->where('inventory.branch_id', $branchId);
        }

        if ($dateFrom) {
            $builder->where('inventory_items.expiry_date >=', $dateFrom);
        }

        if ($dateTo) {
            $builder->where('inventory_items.expiry_date <=', $dateTo);
        }

        $data['wastage'] = $builder->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['branchId'] = $branchId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        // Calculate total wastage value
        $totalWastage = 0;
        foreach ($data['wastage'] as $item) {
            $totalWastage += ($item['quantity'] * $item['cost_price']);
        }
        $data['totalWastage'] = $totalWastage;

        return view('reports/wastage', $data);
    }

    public function exportInventory()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branchId = $this->request->getGet('branch_id');
        $category = $this->request->getGet('category');

        $builder = $this->inventoryModel->select('inventory.*, products.name as product_name, products.sku, products.category, products.cost_price, branches.name as branch_name')
            ->join('products', 'products.id = inventory.product_id')
            ->join('branches', 'branches.id = inventory.branch_id', 'left');

        if ($branchId) {
            $builder->where('inventory.branch_id', $branchId);
        }

        if ($category) {
            $builder->where('products.category', $category);
        }

        $inventory = $builder->findAll();

        $filename = 'inventory_report_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Branch', 'Product', 'SKU', 'Category', 'Quantity', 'Unit Cost', 'Total Value']);

        foreach ($inventory as $item) {
            fputcsv($output, [
                $item['branch_name'] ?? 'N/A',
                $item['product_name'],
                $item['sku'],
                $item['category'],
                $item['quantity'],
                $item['cost_price'],
                $item['quantity'] * $item['cost_price']
            ]);
        }

        fclose($output);
        exit;
    }
}

