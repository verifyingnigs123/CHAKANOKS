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
        $users = $this->userModel->whereIn('role', ['system_admin', 'central_admin'])
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
}

