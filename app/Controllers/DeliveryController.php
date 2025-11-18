<?php

namespace App\Controllers;

use App\Models\DeliveryModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\InventoryModel;
use App\Models\InventoryItemModel;
use App\Models\StockAlertModel;
use App\Models\ActivityLogModel;
use App\Models\DriverModel;
use App\Models\BranchModel;
use App\Models\InventoryHistoryModel;
use App\Libraries\NotificationService;

class DeliveryController extends BaseController
{
    protected $deliveryModel;
    protected $purchaseOrderModel;
    protected $purchaseOrderItemModel;
    protected $inventoryModel;
    protected $inventoryItemModel;
    protected $stockAlertModel;
    protected $activityLogModel;
    protected $driverModel;
    protected $branchModel;
    protected $inventoryHistoryModel;
    protected $notificationService;

    public function __construct()
    {
        $this->deliveryModel = new DeliveryModel();
        $this->purchaseOrderModel = new PurchaseOrderModel();
        $this->purchaseOrderItemModel = new PurchaseOrderItemModel();
        $this->inventoryModel = new InventoryModel();
        $this->inventoryItemModel = new InventoryItemModel();
        $this->stockAlertModel = new StockAlertModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->driverModel = new DriverModel();
        $this->branchModel = new BranchModel();
        $this->inventoryHistoryModel = new InventoryHistoryModel();
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

        $builder = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->orderBy('deliveries.created_at', 'DESC');

        if ($branchId && $role !== 'central_admin' && $role !== 'system_admin') {
            $builder->where('deliveries.branch_id', $branchId);
        }

        $data['deliveries'] = $builder->findAll();
        $data['role'] = $role;

        return view('deliveries/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get purchase orders that are confirmed or sent
        $data['purchase_orders'] = $this->purchaseOrderModel->select('purchase_orders.*, suppliers.name as supplier_name, branches.name as branch_name')
            ->join('suppliers', 'suppliers.id = purchase_orders.supplier_id')
            ->join('branches', 'branches.id = purchase_orders.branch_id')
            ->whereIn('purchase_orders.status', ['sent', 'confirmed'])
            ->findAll();

        // Get active drivers with their vehicles
        $data['drivers'] = $this->driverModel->getActiveDrivers();

        return view('deliveries/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $deliveryNumber = $this->deliveryModel->generateDeliveryNumber();
        $purchaseOrderId = $this->request->getPost('purchase_order_id');

        $po = $this->purchaseOrderModel->find($purchaseOrderId);
        if (!$po) {
            return redirect()->back()->with('error', 'Purchase order not found');
        }

        $deliveryData = [
            'delivery_number' => $deliveryNumber,
            'purchase_order_id' => $purchaseOrderId,
            'supplier_id' => $po['supplier_id'],
            'branch_id' => $po['branch_id'],
            'status' => 'scheduled',
            'scheduled_date' => $this->request->getPost('scheduled_date'),
            'driver_name' => $this->request->getPost('driver_name'),
            'vehicle_number' => $this->request->getPost('vehicle_number'),
            'notes' => $this->request->getPost('notes'),
        ];

        $deliveryId = $this->deliveryModel->insert($deliveryData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'delivery', "Created delivery: $deliveryNumber");

        // Send notification that delivery is scheduled and needs to be received
        $branch = $this->branchModel->find($po['branch_id']);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendDeliveryScheduledNotification($deliveryId, $deliveryNumber, $po['branch_id'], $branchName, $po['po_number']);

        return redirect()->to('/deliveries')->with('success', 'Delivery scheduled successfully');
    }

    public function view($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as received_by_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->join('users', 'users.id = deliveries.received_by', 'left')
            ->find($id);

        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        // Get PO items
        $poItems = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $delivery['purchase_order_id'])
            ->findAll();

        $data['delivery'] = $delivery;
        $data['po_items'] = $poItems;
        $data['role'] = $session->get('role');

        return view('deliveries/view', $data);
    }

    public function print($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->select('deliveries.*, purchase_orders.po_number, suppliers.name as supplier_name, branches.name as branch_name, users.full_name as received_by_name')
            ->join('purchase_orders', 'purchase_orders.id = deliveries.purchase_order_id')
            ->join('suppliers', 'suppliers.id = deliveries.supplier_id')
            ->join('branches', 'branches.id = deliveries.branch_id')
            ->join('users', 'users.id = deliveries.received_by', 'left')
            ->find($id);

        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        $poItems = $this->purchaseOrderItemModel->select('purchase_order_items.*, products.name as product_name, products.sku, products.unit')
            ->join('products', 'products.id = purchase_order_items.product_id')
            ->where('purchase_order_items.purchase_order_id', $delivery['purchase_order_id'])
            ->findAll();

        $data['delivery'] = $delivery;
        $data['po_items'] = $poItems;

        return view('deliveries/print', $data);
    }

    public function updateStatus($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $status = $this->request->getPost('status');
        $updateData = ['status' => $status];

        if ($status == 'in_transit') {
            $updateData['delivery_date'] = date('Y-m-d');
        } elseif ($status == 'delivered') {
            $updateData['delivery_date'] = date('Y-m-d');
            $updateData['received_by'] = $session->get('user_id');
            $updateData['received_at'] = date('Y-m-d H:i:s');
        }

        $this->deliveryModel->update($id, $updateData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'delivery', "Updated delivery ID: $id status to $status");

        // Send notification when delivery is in transit or delivered (needs receiving)
        if ($status == 'in_transit' || $status == 'delivered') {
            $delivery = $this->deliveryModel->find($id);
            $branch = $this->branchModel->find($delivery['branch_id']);
            $branchName = $branch ? $branch['name'] : 'Unknown Branch';
            $this->notificationService->sendDeliveryReceivingNotification($id, $delivery['delivery_number'], $delivery['branch_id'], $branchName, $status);
        }

        return redirect()->back()->with('success', 'Delivery status updated');
    }

    public function receive($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $delivery = $this->deliveryModel->find($id);
        if (!$delivery) {
            return redirect()->to('/deliveries')->with('error', 'Delivery not found');
        }

        $po = $this->purchaseOrderModel->find($delivery['purchase_order_id']);
        $branchId = $po['branch_id'];

        // Get received quantities
        $products = $this->request->getPost('products');
        $quantities = $this->request->getPost('quantities');
        $batchNumbers = $this->request->getPost('batch_numbers');
        $expiryDates = $this->request->getPost('expiry_dates');

        if ($products && $quantities) {
            foreach ($products as $index => $productId) {
                $quantity = (int) $quantities[$index];
                if ($quantity > 0) {
                    // Get current inventory before update
                    $inventory = $this->inventoryModel->where('branch_id', $branchId)
                        ->where('product_id', $productId)
                        ->first();

                    $previousQuantity = $inventory ? $inventory['quantity'] : 0;

                    // Update inventory
                    if ($inventory) {
                        $newQuantity = $inventory['quantity'] + $quantity;
                        $this->inventoryModel->updateQuantity($branchId, $productId, $newQuantity, $session->get('user_id'));
                    } else {
                        $this->inventoryModel->updateQuantity($branchId, $productId, $quantity, $session->get('user_id'));
                        $inventory = $this->inventoryModel->where('branch_id', $branchId)
                            ->where('product_id', $productId)
                            ->first();
                        $newQuantity = $quantity;
                    }

                    // Record inventory history
                    $this->inventoryHistoryModel->insert([
                        'branch_id' => $branchId,
                        'product_id' => $productId,
                        'purchase_order_id' => $po['id'],
                        'delivery_id' => $id,
                        'po_number' => $po['po_number'],
                        'delivery_number' => $delivery['delivery_number'],
                        'quantity_added' => $quantity,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $newQuantity,
                        'transaction_type' => 'delivery_received',
                        'received_by' => $session->get('user_id'),
                        'notes' => "Received from Purchase Order {$po['po_number']} via Delivery {$delivery['delivery_number']}",
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    // Add inventory items (for perishables with batch/expiry)
                    if (!empty($batchNumbers[$index]) || !empty($expiryDates[$index])) {
                        $this->inventoryItemModel->insert([
                            'inventory_id' => $inventory['id'],
                            'batch_number' => $batchNumbers[$index] ?? null,
                            'expiry_date' => !empty($expiryDates[$index]) ? $expiryDates[$index] : null,
                            'quantity' => $quantity,
                            'received_date' => date('Y-m-d'),
                            'status' => 'available',
                        ]);
                    }

                    // Update PO item received quantity
                    $poItem = $this->purchaseOrderItemModel->where('purchase_order_id', $po['id'])
                        ->where('product_id', $productId)
                        ->first();

                    if ($poItem) {
                        $newReceived = $poItem['quantity_received'] + $quantity;
                        $this->purchaseOrderItemModel->update($poItem['id'], [
                            'quantity_received' => $newReceived
                        ]);
                    }
                }
            }
        }

        // Update delivery status
        $this->deliveryModel->update($id, [
            'status' => 'delivered',
            'delivery_date' => date('Y-m-d'),
            'received_by' => $session->get('user_id'),
            'received_at' => date('Y-m-d H:i:s'),
        ]);

        // Update PO status if all items received
        $allReceived = true;
        $poItems = $this->purchaseOrderItemModel->where('purchase_order_id', $po['id'])->findAll();
        foreach ($poItems as $item) {
            if ($item['quantity_received'] < $item['quantity']) {
                $allReceived = false;
                break;
            }
        }

        if ($allReceived) {
            $this->purchaseOrderModel->update($po['id'], ['status' => 'completed']);
        } else {
            $this->purchaseOrderModel->update($po['id'], ['status' => 'partial']);
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'receive', 'delivery', "Received delivery ID: $id");

        // Send notification that delivery is received and inventory is updated
        $branch = $this->branchModel->find($branchId);
        $branchName = $branch ? $branch['name'] : 'Unknown Branch';
        $this->notificationService->sendDeliveryReceivedNotification($id, $delivery['delivery_number'], $branchId, $branchName, $po['po_number']);

        return redirect()->to('/deliveries')->with('success', 'Delivery received and inventory updated');
    }
}

