<?php

namespace App\Libraries;

use App\Models\NotificationModel;
use App\Models\UserModel;

class NotificationService
{
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
    }

    /**
     * Send notification to a specific user
     */
    public function sendToUser($userId, $type, $title, $message, $link = null)
    {
        return $this->notificationModel->insert([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Send notification to users by role
     */
    public function sendToRole($role, $type, $title, $message, $link = null)
    {
        $users = $this->userModel->where('role', $role)
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], $type, $title, $message, $link);
        }

        return count($users);
    }

    /**
     * Send notification to all admins
     */
    public function sendToAdmins($type, $title, $message, $link = null)
    {
        $users = $this->userModel->whereIn('role', ['central_admin', 'central_admin'])
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], $type, $title, $message, $link);
        }

        return count($users);
    }

    /**
     * Send low stock alert
     */
    public function sendLowStockAlert($productId, $productName, $branchId, $branchName, $currentStock, $minStock)
    {
        $message = "Low stock alert: {$productName} at {$branchName}. Current: {$currentStock}, Minimum: {$minStock}";
        return $this->sendToAdmins('warning', 'Low Stock Alert', $message, base_url("inventory?branch_id={$branchId}"));
    }

    /**
     * Send purchase request notification
     */
    public function sendPurchaseRequestNotification($requestId, $requestNumber, $branchName)
    {
        $title = 'New Purchase Request';
        $message = "New purchase request {$requestNumber} from {$branchName} requires approval";
        $link = base_url("purchase-requests/view/{$requestId}");
        return $this->sendToAdmins('info', $title, $message, $link);
    }

    /**
     * Send approved purchase request notification (needs purchase order)
     */
    public function sendApprovedPurchaseRequestNotification($requestId, $requestNumber, $branchName)
    {
        $title = 'Purchase Request Approved';
        $message = "Purchase request {$requestNumber} from {$branchName} has been approved and needs to be converted to a Purchase Order";
        $link = base_url("purchase-orders/create-from-request/{$requestId}");
        return $this->sendToAdmins('warning', $title, $message, $link);
    }

    /**
     * Send purchase order created notification (needs to be sent)
     */
    public function sendPurchaseOrderCreatedNotification($poId, $poNumber, $branchName, $supplierName)
    {
        $title = 'Purchase Order Created - Send to Supplier';
        $message = "Purchase Order {$poNumber} for {$branchName} from {$supplierName} has been created. Please send it to the supplier to proceed with delivery.";
        $link = base_url("purchase-orders/view/{$poId}");
        return $this->sendToAdmins('warning', $title, $message, $link);
    }

    /**
     * Send purchase order sent notification (needs delivery scheduling)
     */
    public function sendPurchaseOrderSentNotification($poId, $poNumber, $branchName, $supplierName)
    {
        $title = 'Purchase Order Sent - Schedule Delivery';
        $message = "Purchase Order {$poNumber} for {$branchName} from {$supplierName} has been sent. Please schedule the delivery.";
        $link = base_url("deliveries/create?po_id={$poId}");
        return $this->sendToAdmins('warning', $title, $message, $link);
    }

    /**
     * Send delivery notification
     */
    public function sendDeliveryNotification($deliveryId, $deliveryNumber, $branchId)
    {
        $title = 'Delivery Scheduled';
        $message = "Delivery {$deliveryNumber} has been scheduled";
        $link = base_url("deliveries/view/{$deliveryId}");
        
        // Notify branch manager
        $users = $this->userModel->where('branch_id', $branchId)
            ->where('role', 'branch_manager')
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], 'info', $title, $message, $link);
        }

        return count($users);
    }

    /**
     * Send delivery scheduled notification (needs to be received)
     */
    public function sendDeliveryScheduledNotification($deliveryId, $deliveryNumber, $branchId, $branchName, $poNumber)
    {
        $title = 'Delivery Scheduled - Ready to Receive';
        $message = "Delivery {$deliveryNumber} for Purchase Order {$poNumber} has been scheduled for {$branchName}. Please prepare to receive the delivery.";
        $link = base_url("deliveries/view/{$deliveryId}");
        
        $count = 0;
        
        // Notify branch manager and inventory staff at the destination branch
        $users = $this->userModel->where('branch_id', $branchId)
            ->whereIn('role', ['branch_manager', 'inventory_staff'])
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], 'warning', $title, $message, $link);
            $count++;
        }

        // Also notify admins
        $adminCount = $this->sendToAdmins('warning', $title, $message, $link);
        $count += $adminCount;

        return $count;
    }

    /**
     * Send delivery receiving notification (when delivery is in transit or delivered)
     */
    public function sendDeliveryReceivingNotification($deliveryId, $deliveryNumber, $branchId, $branchName, $status = 'in_transit')
    {
        if ($status === 'in_transit') {
            $title = 'Delivery In Transit';
            $message = "Delivery {$deliveryNumber} is in transit to {$branchName}. Please prepare to receive.";
        } else {
            $title = 'Delivery Ready to Receive';
            $message = "Delivery {$deliveryNumber} has arrived at {$branchName} and needs to be received.";
        }
        
        $link = base_url("deliveries/view/{$deliveryId}");
        
        $count = 0;
        
        // Notify branch manager and inventory staff at the destination branch
        $users = $this->userModel->where('branch_id', $branchId)
            ->whereIn('role', ['branch_manager', 'inventory_staff'])
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], 'warning', $title, $message, $link);
            $count++;
        }

        return $count;
    }

    /**
     * Send delivery received notification (inventory updated)
     */
    public function sendDeliveryReceivedNotification($deliveryId, $deliveryNumber, $branchId, $branchName, $poNumber, $supplierId = null)
    {
        $title = 'Purchase Order Received - Inventory Updated';
        $message = "Delivery {$deliveryNumber} for Purchase Order {$poNumber} has been received at {$branchName}. Inventory has been updated successfully. Click to view updated inventory.";
        $link = base_url("inventory?branch_id={$branchId}");
        
        $count = 0;
        
        // Notify branch manager and inventory staff
        $users = $this->userModel->where('branch_id', $branchId)
            ->whereIn('role', ['branch_manager', 'inventory_staff'])
            ->where('status', 'active')
            ->findAll();

        foreach ($users as $user) {
            $this->sendToUser($user['id'], 'success', $title, $message, $link);
            $count++;
        }

        // Also notify admins
        $adminCount = $this->sendToAdmins('success', $title, $message, $link);
        $count += $adminCount;

        // Notify supplier users if supplierId provided
        if ($supplierId) {
            $supplierUsers = $this->userModel->where('role', 'supplier')
                ->where('supplier_id', $supplierId)
                ->where('status', 'active')
                ->findAll();

            foreach ($supplierUsers as $suser) {
                $this->sendToUser($suser['id'], 'info', 'Order Received by Branch', "Purchase Order {$poNumber} has been received at {$branchName}.", base_url("purchase-orders/view/{$deliveryId}"));
                $count++;
            }
        }

        return $count;
    }

    /**
     * Send transfer approval notification
     */
    public function sendTransferApprovalNotification($transferId, $transferNumber, $fromBranchId, $toBranchId, $fromBranchName, $toBranchName)
    {
        $title = 'Transfer Request Pending Approval';
        $message = "Transfer request {$transferNumber} from {$fromBranchName} to {$toBranchName} requires approval";
        $link = base_url("transfers/view/{$transferId}");
        
        $count = 0;
        
        // Notify admins
        $adminUsers = $this->userModel->whereIn('role', ['central_admin', 'central_admin'])
            ->where('status', 'active')
            ->findAll();
        
        foreach ($adminUsers as $user) {
            $this->sendToUser($user['id'], 'info', $title, $message, $link);
            $count++;
        }

        // Notify branch managers of both branches
        $branchManagers = $this->userModel->whereIn('branch_id', [$fromBranchId, $toBranchId])
            ->where('role', 'branch_manager')
            ->where('status', 'active')
            ->findAll();
        
        foreach ($branchManagers as $user) {
            $this->sendToUser($user['id'], 'info', $title, $message, $link);
            $count++;
        }

        return $count;
    }
}

