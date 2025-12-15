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
     * Check if a similar notification already exists (within last 5 minutes)
     */
    protected function isDuplicate(int $userId, string $title, string $message): bool
    {
        $fiveMinutesAgo = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        
        $existing = $this->notificationModel
            ->where('user_id', $userId)
            ->where('title', $title)
            ->where('message', $message)
            ->where('created_at >=', $fiveMinutesAgo)
            ->first();
        
        return $existing !== null;
    }

    /**
     * Create notification for a specific user (with duplicate prevention)
     */
    public function createForUser(int $userId, string $type, string $title, string $message, ?string $link = null): bool
    {
        // Check for duplicate notification within last 5 minutes
        if ($this->isDuplicate($userId, $title, $message)) {
            return false; // Skip duplicate
        }
        
        return $this->notificationModel->insert([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]) !== false;
    }

    /**
     * Create notification for all users with a specific role
     */
    public function createForRole(string $role, string $type, string $title, string $message, ?string $link = null): int
    {
        $users = $this->userModel->where('role', $role)->where('status', 'active')->findAll();
        $count = 0;
        
        foreach ($users as $user) {
            if ($this->createForUser($user['id'], $type, $title, $message, $link)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Create notification for users at a specific branch with a specific role
     */
    public function createForBranch(int $branchId, ?string $role, string $type, string $title, string $message, ?string $link = null): int
    {
        $query = $this->userModel->where('branch_id', $branchId)->where('status', 'active');
        
        if ($role) {
            $query->where('role', $role);
        }
        
        $users = $query->findAll();
        $count = 0;
        
        foreach ($users as $user) {
            if ($this->createForUser($user['id'], $type, $title, $message, $link)) {
                $count++;
            }
        }
        
        return $count;
    }


    /**
     * Create notification for supplier user
     */
    public function createForSupplier(int $supplierId, string $type, string $title, string $message, ?string $link = null): int
    {
        $users = $this->userModel->where('supplier_id', $supplierId)->where('status', 'active')->findAll();
        $count = 0;
        
        foreach ($users as $user) {
            if ($this->createForUser($user['id'], $type, $title, $message, $link)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Notify central admins about a new purchase request
     */
    public function notifyPurchaseRequestCreated(int $requestId, string $branchName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            'New Purchase Request',
            "New purchase request from {$branchName}",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * Notify branch about purchase request approval/rejection
     */
    public function notifyPurchaseRequestStatus(int $requestId, int $branchId, string $status): int
    {
        $type = $status === 'approved' ? 'success' : 'danger';
        $title = $status === 'approved' ? 'Purchase Request Approved' : 'Purchase Request Rejected';
        
        return $this->createForBranch(
            $branchId,
            null,
            $type,
            $title,
            "Your purchase request has been {$status}",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * Notify about purchase order sent to supplier
     */
    public function notifyPurchaseOrderSent(int $orderId, int $supplierId, string $poNumber): int
    {
        // Notify supplier
        $count = $this->createForSupplier(
            $supplierId,
            'info',
            'New Purchase Order',
            "You have received a new purchase order: {$poNumber}",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'info',
            'Purchase Order Sent',
            "Purchase order {$poNumber} has been sent to supplier",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        return $count;
    }

    /**
     * Notify about delivery arrival
     */
    public function notifyDeliveryArrived(int $deliveryId, int $branchId, string $deliveryNumber): int
    {
        // Notify branch staff
        $count = $this->createForBranch(
            $branchId,
            null,
            'info',
            'Delivery Arrived',
            "Delivery {$deliveryNumber} has arrived at your branch",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        return $count;
    }

    /**
     * Notify about delivery completion
     */
    public function notifyDeliveryCompleted(int $deliveryId, string $deliveryNumber): int
    {
        return $this->createForRole(
            'central_admin',
            'success',
            'Delivery Completed',
            "Delivery {$deliveryNumber} has been completed",
            base_url("deliveries/view/{$deliveryId}")
        );
    }

    /**
     * Notify about low stock alert
     */
    public function notifyLowStock(int $branchId, string $productName, int $currentQty, int $minLevel): int
    {
        $count = 0;
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'warning',
            'Low Stock Alert',
            "{$productName} is running low ({$currentQty} remaining, min: {$minLevel})",
            base_url('inventory/alerts')
        );
        
        // Notify branch manager
        $count += $this->createForBranch(
            $branchId,
            'branch_manager',
            'warning',
            'Low Stock Alert',
            "{$productName} is running low ({$currentQty} remaining)",
            base_url('inventory/alerts')
        );
        
        // Notify inventory staff
        $count += $this->createForRole(
            'inventory_staff',
            'warning',
            'Low Stock Alert',
            "{$productName} needs restocking ({$currentQty} remaining)",
            base_url('inventory/alerts')
        );
        
        return $count;
    }

    /**
     * Notify about transfer
     */
    public function notifyTransferCreated(int $transferId, int $fromBranchId, int $toBranchId, string $transferNumber): int
    {
        $count = 0;
        
        // Notify source branch
        $count += $this->createForBranch(
            $fromBranchId,
            null,
            'info',
            'Transfer Created',
            "Outgoing transfer {$transferNumber} has been created",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify destination branch
        $count += $this->createForBranch(
            $toBranchId,
            null,
            'info',
            'Incoming Transfer',
            "Incoming transfer {$transferNumber} is on the way",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * Notify about franchise application
     */
    public function notifyFranchiseApplication(int $applicationId, string $applicantName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            'New Franchise Application',
            "New franchise application from {$applicantName}",
            base_url("franchise/applications/view/{$applicationId}")
        );
    }

    /**
     * Notify supplier about payment received
     */
    public function notifyPaymentReceived(int $orderId, int $supplierId, string $poNumber, float $amount): int
    {
        return $this->createForSupplier(
            $supplierId,
            'success',
            'Payment Received',
            "Payment of ₱" . number_format($amount, 2) . " received for {$poNumber}",
            base_url("purchase-orders/view/{$orderId}")
        );
    }

    // ============================================
    // Alias methods for backward compatibility
    // ============================================

    /**
     * Send notification to a specific user (alias for createForUser)
     */
    public function sendToUser(int $userId, string $type, string $title, string $message, ?string $link = null): bool
    {
        return $this->createForUser($userId, $type, $title, $message, $link);
    }

    /**
     * Send purchase request notification to central admins
     */
    public function sendPurchaseRequestNotification(int $requestId, string $requestNumber, string $branchName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            'New Purchase Request',
            "New purchase request {$requestNumber} from {$branchName}",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * Send purchase request approval notification to branch
     */
    public function sendPurchaseRequestApprovalToBranch(int $requestId, string $requestNumber, int $branchId, string $branchName): int
    {
        return $this->createForBranch(
            $branchId,
            null,
            'success',
            'Purchase Request Approved',
            "Your purchase request {$requestNumber} has been approved",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * Send approved purchase request notification (for PO conversion)
     */
    public function sendApprovedPurchaseRequestNotification(int $requestId, string $requestNumber, string $branchName): int
    {
        return $this->createForRole(
            'central_admin',
            'success',
            'Purchase Request Ready for PO',
            "Purchase request {$requestNumber} from {$branchName} is approved and ready for PO conversion",
            base_url("purchase-orders/create-from-request/{$requestId}")
        );
    }

    /**
     * Send purchase order notification to supplier
     */
    public function sendPurchaseOrderToSupplier(int $orderId, string $poNumber, int $supplierId): int
    {
        return $this->createForSupplier(
            $supplierId,
            'info',
            'New Purchase Order',
            "You have received a new purchase order: {$poNumber}",
            base_url("purchase-orders/view/{$orderId}")
        );
    }

    /**
     * Send delivery notification to branch
     */
    public function sendDeliveryNotification(int $deliveryId, string $deliveryNumber, int $branchId, string $status): int
    {
        $type = $status === 'delivered' ? 'success' : 'info';
        $title = $status === 'delivered' ? 'Delivery Completed' : 'Delivery Update';
        $message = $status === 'delivered' 
            ? "Delivery {$deliveryNumber} has been completed" 
            : "Delivery {$deliveryNumber} status: {$status}";
        
        return $this->createForBranch(
            $branchId,
            null,
            $type,
            $title,
            $message,
            base_url("deliveries/view/{$deliveryId}")
        );
    }

    /**
     * Send notification to all users with a specific role (alias for createForRole)
     */
    public function sendToRole(string $role, string $type, string $title, string $message, ?string $link = null): int
    {
        return $this->createForRole($role, $type, $title, $message, $link);
    }

    /**
     * Send delivery scheduled notification
     */
    public function sendDeliveryScheduledNotification(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName, string $poNumber): int
    {
        return $this->createForBranch(
            $branchId,
            null,
            'info',
            'Delivery Scheduled',
            "Delivery {$deliveryNumber} for PO {$poNumber} has been scheduled",
            base_url("deliveries/view/{$deliveryId}")
        );
    }

    /**
     * Send delivery receiving notification (when in transit or delivered)
     */
    public function sendDeliveryReceivingNotification(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName, string $status): int
    {
        $title = $status === 'in_transit' ? 'Delivery In Transit' : 'Delivery Arrived';
        $message = $status === 'in_transit' 
            ? "Delivery {$deliveryNumber} is on the way to {$branchName}"
            : "Delivery {$deliveryNumber} has arrived at {$branchName}";
        
        return $this->createForBranch(
            $branchId,
            null,
            'info',
            $title,
            $message,
            base_url("deliveries/view/{$deliveryId}")
        );
    }

    /**
     * Send delivery received notification
     */
    public function sendDeliveryReceivedNotification(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName, string $poNumber, int $supplierId): int
    {
        $count = 0;
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'success',
            'Delivery Received',
            "Delivery {$deliveryNumber} for PO {$poNumber} has been received at {$branchName}",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify supplier
        $count += $this->createForSupplier(
            $supplierId,
            'success',
            'Delivery Confirmed',
            "Delivery {$deliveryNumber} has been received by {$branchName}",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        return $count;
    }

    /**
     * Send transfer approval notification
     */
    public function sendTransferApprovalNotification(int $transferId, string $transferNumber, int $fromBranchId, int $toBranchId, string $fromBranchName, string $toBranchName): int
    {
        $count = 0;
        
        // Notify central admin for approval
        $count += $this->createForRole(
            'central_admin',
            'info',
            'Transfer Request',
            "New transfer {$transferNumber} from {$fromBranchName} to {$toBranchName} needs approval",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * Send transfer approval to branch notification
     */
    public function sendTransferApprovalToBranch(int $transferId, string $transferNumber, int $fromBranchId, int $toBranchId, string $fromBranchName, string $toBranchName): int
    {
        $count = 0;
        
        // Notify source branch
        $count += $this->createForBranch(
            $fromBranchId,
            null,
            'success',
            'Transfer Approved',
            "Transfer {$transferNumber} to {$toBranchName} has been approved",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify destination branch
        $count += $this->createForBranch(
            $toBranchId,
            null,
            'info',
            'Incoming Transfer',
            "Transfer {$transferNumber} from {$fromBranchName} is on the way",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * Send purchase order created notification
     */
    public function sendPurchaseOrderCreatedNotification(int $orderId, string $poNumber, string $branchName, string $supplierName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            'Purchase Order Created',
            "PO {$poNumber} created for {$branchName} from {$supplierName}",
            base_url("purchase-orders/view/{$orderId}")
        );
    }

    /**
     * Send purchase order sent notification
     */
    public function sendPurchaseOrderSentNotification(int $orderId, string $poNumber, string $branchName, string $supplierName): int
    {
        $count = 0;
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'info',
            'Purchase Order Sent',
            "PO {$poNumber} has been sent to {$supplierName}",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        return $count;
    }

    // ============================================
    // ENHANCED WORKFLOW NOTIFICATIONS
    // ============================================

    /**
     * PURCHASE REQUEST WORKFLOW - Step 1: Branch Manager creates request
     */
    public function notifyPurchaseRequestCreatedWorkflow(int $requestId, string $requestNumber, string $branchName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            '🔔 Action Required: Approve Purchase Request',
            "Purchase Request {$requestNumber} from {$branchName} needs your approval. Click to review and approve.",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * PURCHASE REQUEST WORKFLOW - Step 2: Central Admin approves
     */
    public function notifyPurchaseRequestApprovedWorkflow(int $requestId, string $requestNumber, int $branchId, string $branchName): int
    {
        $count = 0;
        
        // Notify branch manager
        $count += $this->createForBranch(
            $branchId,
            'branch_manager',
            'success',
            '✅ Purchase Request Approved',
            "Your request {$requestNumber} has been approved. A Purchase Order will be created soon.",
            base_url("purchase-requests/view/{$requestId}")
        );
        
        // Notify central admin to create PO
        $count += $this->createForRole(
            'central_admin',
            'warning',
            '🔔 Action Required: Create Purchase Order',
            "Approved request {$requestNumber} from {$branchName} is ready. Click to create Purchase Order.",
            base_url("purchase-orders/create-from-request/{$requestId}")
        );
        
        return $count;
    }

    /**
     * PURCHASE REQUEST WORKFLOW - Step 2b: Central Admin rejects
     */
    public function notifyPurchaseRequestRejectedWorkflow(int $requestId, string $requestNumber, int $branchId, string $reason): int
    {
        return $this->createForBranch(
            $branchId,
            'branch_manager',
            'danger',
            '❌ Purchase Request Rejected',
            "Request {$requestNumber} was rejected. Reason: {$reason}. Click to view details.",
            base_url("purchase-requests/view/{$requestId}")
        );
    }

    /**
     * PURCHASE ORDER WORKFLOW - Step 3: Central Admin creates and sends PO
     */
    public function notifyPurchaseOrderSentWorkflow(int $orderId, string $poNumber, int $supplierId, string $supplierName, int $branchId, string $branchName): int
    {
        $count = 0;
        
        // Notify supplier - ACTION REQUIRED
        $count += $this->createForSupplier(
            $supplierId,
            'info',
            '🔔 Action Required: Confirm Purchase Order',
            "New PO {$poNumber} from {$branchName}. Click to view and confirm order.",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        // Notify branch manager - INFO
        $count += $this->createForBranch(
            $branchId,
            'branch_manager',
            'info',
            '📦 Purchase Order Sent to Supplier',
            "PO {$poNumber} has been sent to {$supplierName}. Awaiting confirmation.",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        return $count;
    }

    /**
     * PURCHASE ORDER WORKFLOW - Step 4: Supplier confirms
     */
    public function notifyPurchaseOrderConfirmedWorkflow(int $orderId, string $poNumber, int $supplierId, string $supplierName): int
    {
        $count = 0;
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'success',
            '✅ Purchase Order Confirmed',
            "Supplier {$supplierName} confirmed PO {$poNumber}. Awaiting preparation.",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        // Notify supplier - ACTION REQUIRED
        $count += $this->createForSupplier(
            $supplierId,
            'warning',
            '🔔 Action Required: Prepare Order',
            "PO {$poNumber} is confirmed. Click to mark as prepared when ready.",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        return $count;
    }

    /**
     * PURCHASE ORDER WORKFLOW - Step 5: Supplier marks as prepared
     */
    public function notifyPurchaseOrderPreparedWorkflow(int $orderId, string $poNumber, string $supplierName): int
    {
        $count = 0;
        
        // Notify logistics coordinator - ACTION REQUIRED
        $count += $this->createForRole(
            'logistics_coordinator',
            'warning',
            '🔔 Action Required: Schedule Delivery',
            "PO {$poNumber} from {$supplierName} is prepared. Click to schedule delivery.",
            base_url("deliveries/create?po_id={$orderId}")
        );
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'info',
            '📦 Order Ready for Delivery',
            "PO {$poNumber} is prepared by {$supplierName}. Awaiting delivery scheduling.",
            base_url("purchase-orders/view/{$orderId}")
        );
        
        return $count;
    }

    /**
     * DELIVERY WORKFLOW - Step 6: Logistics schedules delivery
     */
    public function notifyDeliveryScheduledWorkflow(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName, string $poNumber, string $scheduledDate): int
    {
        $count = 0;
        
        // Notify branch manager - INFO
        $count += $this->createForBranch(
            $branchId,
            'branch_manager',
            'info',
            '🚚 Delivery Scheduled',
            "Delivery {$deliveryNumber} for PO {$poNumber} scheduled for {$scheduledDate}. Prepare to receive.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify inventory staff - INFO
        $count += $this->createForBranch(
            $branchId,
            'inventory_staff',
            'info',
            '🚚 Incoming Delivery',
            "Delivery {$deliveryNumber} scheduled for {$scheduledDate}. Be ready to receive.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify logistics coordinator
        $count += $this->createForRole(
            'logistics_coordinator',
            'success',
            '✅ Delivery Scheduled',
            "Delivery {$deliveryNumber} to {$branchName} scheduled for {$scheduledDate}.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        return $count;
    }

    /**
     * DELIVERY WORKFLOW - Step 7: Logistics dispatches (in transit)
     */
    public function notifyDeliveryInTransitWorkflow(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName): int
    {
        $count = 0;
        
        // Notify branch manager - ACTION REQUIRED
        $count += $this->createForBranch(
            $branchId,
            'branch_manager',
            'warning',
            '🔔 Delivery In Transit - Prepare to Receive',
            "Delivery {$deliveryNumber} is on the way. Click to receive when it arrives.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify inventory staff - ACTION REQUIRED
        $count += $this->createForBranch(
            $branchId,
            'inventory_staff',
            'warning',
            '🔔 Delivery Arriving Soon',
            "Delivery {$deliveryNumber} is in transit. Click to receive and update inventory.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        return $count;
    }

    /**
     * DELIVERY WORKFLOW - Step 8: Branch receives delivery
     */
    public function notifyDeliveryReceivedWorkflow(int $deliveryId, string $deliveryNumber, int $branchId, string $branchName, string $poNumber, int $supplierId, string $supplierName): int
    {
        $count = 0;
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'success',
            '✅ Delivery Completed',
            "Delivery {$deliveryNumber} received at {$branchName}. PO {$poNumber} completed.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify supplier
        $count += $this->createForSupplier(
            $supplierId,
            'success',
            '✅ Delivery Confirmed by Customer',
            "Delivery {$deliveryNumber} confirmed by {$branchName}. Order completed successfully.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        // Notify logistics coordinator
        $count += $this->createForRole(
            'logistics_coordinator',
            'success',
            '✅ Delivery Completed',
            "Delivery {$deliveryNumber} to {$branchName} completed successfully.",
            base_url("deliveries/view/{$deliveryId}")
        );
        
        return $count;
    }

    /**
     * TRANSFER WORKFLOW - Step 1: Branch Manager creates transfer
     */
    public function notifyTransferCreatedWorkflow(int $transferId, string $transferNumber, int $fromBranchId, string $fromBranchName, int $toBranchId, string $toBranchName): int
    {
        $count = 0;
        
        // Notify destination branch manager - ACTION REQUIRED
        $count += $this->createForBranch(
            $toBranchId,
            'branch_manager',
            'warning',
            '🔔 Action Required: Approve Transfer',
            "Transfer {$transferNumber} from {$fromBranchName} needs your approval. Click to review.",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify source branch
        $count += $this->createForBranch(
            $fromBranchId,
            'branch_manager',
            'info',
            '📤 Transfer Request Sent',
            "Transfer {$transferNumber} to {$toBranchName} is pending approval.",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * TRANSFER WORKFLOW - Step 2: Destination branch approves
     */
    public function notifyTransferApprovedWorkflow(int $transferId, string $transferNumber, int $fromBranchId, string $fromBranchName, int $toBranchId, string $toBranchName): int
    {
        $count = 0;
        
        // Notify source branch - ACTION REQUIRED
        $count += $this->createForBranch(
            $fromBranchId,
            'branch_manager',
            'success',
            '✅ Transfer Approved - Ready to Ship',
            "Transfer {$transferNumber} to {$toBranchName} approved. Click to complete transfer.",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify destination branch
        $count += $this->createForBranch(
            $toBranchId,
            'branch_manager',
            'info',
            '📥 Transfer Approved - Awaiting Shipment',
            "Transfer {$transferNumber} from {$fromBranchName} approved. Awaiting shipment.",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify logistics coordinator
        $count += $this->createForRole(
            'logistics_coordinator',
            'info',
            '🚚 Transfer Approved',
            "Transfer {$transferNumber} from {$fromBranchName} to {$toBranchName} approved.",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * TRANSFER WORKFLOW - Step 3: Source branch completes transfer
     */
    public function notifyTransferCompletedWorkflow(int $transferId, string $transferNumber, int $fromBranchId, string $fromBranchName, int $toBranchId, string $toBranchName): int
    {
        $count = 0;
        
        // Notify destination branch
        $count += $this->createForBranch(
            $toBranchId,
            'branch_manager',
            'success',
            '✅ Transfer Completed',
            "Transfer {$transferNumber} from {$fromBranchName} completed. Inventory updated.",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify destination inventory staff
        $count += $this->createForBranch(
            $toBranchId,
            'inventory_staff',
            'success',
            '✅ Inventory Updated',
            "Transfer {$transferNumber} received. Inventory has been updated.",
            base_url("transfers/view/{$transferId}")
        );
        
        // Notify source branch
        $count += $this->createForBranch(
            $fromBranchId,
            'branch_manager',
            'success',
            '✅ Transfer Completed',
            "Transfer {$transferNumber} to {$toBranchName} completed successfully.",
            base_url("transfers/view/{$transferId}")
        );
        
        return $count;
    }

    /**
     * TRANSFER WORKFLOW - Step 2b: Destination branch rejects
     */
    public function notifyTransferRejectedWorkflow(int $transferId, string $transferNumber, int $fromBranchId, string $fromBranchName, int $toBranchId, string $toBranchName, string $reason): int
    {
        return $this->createForBranch(
            $fromBranchId,
            'branch_manager',
            'danger',
            '❌ Transfer Rejected',
            "Transfer {$transferNumber} to {$toBranchName} was rejected. Reason: {$reason}",
            base_url("transfers/view/{$transferId}")
        );
    }

    /**
     * FRANCHISE WORKFLOW - Step 1: Public submits application
     */
    public function notifyFranchiseApplicationSubmittedWorkflow(int $applicationId, string $applicantName, string $email, string $phone): int
    {
        $count = 0;
        
        // Notify central admin - ACTION REQUIRED
        $count += $this->createForRole(
            'central_admin',
            'warning',
            '🔔 Action Required: Review Franchise Application',
            "New application from {$applicantName} ({$email}). Click to review and start evaluation.",
            base_url("franchise/applications/view/{$applicationId}")
        );
        
        // Notify franchise manager - ACTION REQUIRED
        $count += $this->createForRole(
            'franchise_manager',
            'warning',
            '🔔 Action Required: Review Franchise Application',
            "New application from {$applicantName}. Click to review and provide recommendation.",
            base_url("franchise/applications/view/{$applicationId}")
        );
        
        return $count;
    }

    /**
     * FRANCHISE WORKFLOW - Step 2: Franchise Manager starts review
     */
    public function notifyFranchiseApplicationUnderReviewWorkflow(int $applicationId, string $applicantName): int
    {
        return $this->createForRole(
            'central_admin',
            'info',
            '📋 Franchise Application Under Review',
            "Application from {$applicantName} is now under review by Franchise Manager.",
            base_url("franchise/applications/view/{$applicationId}")
        );
    }

    /**
     * FRANCHISE WORKFLOW - Step 3: Central Admin approves
     */
    public function notifyFranchiseApplicationApprovedWorkflow(int $applicationId, string $applicantName, string $email): int
    {
        $count = 0;
        
        // Notify franchise manager
        $count += $this->createForRole(
            'franchise_manager',
            'success',
            '✅ Franchise Application Approved',
            "Application from {$applicantName} has been approved. Ready for conversion to branch.",
            base_url("franchise/applications/view/{$applicationId}")
        );
        
        // Notify central admin - ACTION REQUIRED
        $count += $this->createForRole(
            'central_admin',
            'warning',
            '🔔 Action Required: Convert to Branch',
            "Approved application from {$applicantName}. Click to convert to franchise branch.",
            base_url("franchise/applications/view/{$applicationId}")
        );
        
        return $count;
    }

    /**
     * FRANCHISE WORKFLOW - Step 3b: Central Admin rejects
     */
    public function notifyFranchiseApplicationRejectedWorkflow(int $applicationId, string $applicantName, string $reason): int
    {
        return $this->createForRole(
            'franchise_manager',
            'danger',
            '❌ Franchise Application Rejected',
            "Application from {$applicantName} was rejected. Reason: {$reason}",
            base_url("franchise/applications/view/{$applicationId}")
        );
    }

    /**
     * FRANCHISE WORKFLOW - Step 4: Central Admin converts to branch
     */
    public function notifyFranchiseConvertedToBranchWorkflow(int $applicationId, string $applicantName, int $branchId, string $branchName): int
    {
        $count = 0;
        
        // Notify franchise manager
        $count += $this->createForRole(
            'franchise_manager',
            'success',
            '✅ Franchise Branch Created',
            "Application from {$applicantName} converted to branch: {$branchName}",
            base_url("branches/view/{$branchId}")
        );
        
        // Notify central admin
        $count += $this->createForRole(
            'central_admin',
            'success',
            '✅ New Franchise Branch',
            "Franchise branch {$branchName} created successfully from {$applicantName}'s application.",
            base_url("branches/view/{$branchId}")
        );
        
        return $count;
    }
}
