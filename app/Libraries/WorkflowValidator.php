<?php

namespace App\Libraries;

/**
 * Workflow Validator Service
 * 
 * This service validates status transitions and workflow rules
 * to ensure proper system flow and data integrity.
 */
class WorkflowValidator
{
    /**
     * Valid status transitions for Purchase Requests
     */
    private $purchaseRequestTransitions = [
        'pending' => ['approved', 'rejected'],
        'approved' => [], // Terminal state
        'rejected' => [], // Terminal state
    ];

    /**
     * Valid status transitions for Purchase Orders
     */
    private $purchaseOrderTransitions = [
        'draft' => ['sent'],
        'sent' => ['confirmed'],
        'confirmed' => ['prepared'],
        'prepared' => ['completed', 'partial'],
        'partial' => ['completed'],
        'completed' => [], // Terminal state
    ];

    /**
     * Valid status transitions for Deliveries
     */
    private $deliveryTransitions = [
        'scheduled' => ['in_transit', 'delivered'],
        'in_transit' => ['delivered'],
        'delivered' => [], // Terminal state
    ];

    /**
     * Valid status transitions for Payment Transactions
     */
    private $paymentTransitions = [
        'pending' => ['completed'],
        'completed' => [], // Terminal state
    ];

    /**
     * Validate if a status transition is allowed
     * 
     * @param string $entityType Type of entity (purchase_request, purchase_order, delivery, payment)
     * @param string $currentStatus Current status
     * @param string $newStatus New status to transition to
     * @return array Validation result
     */
    public function validateStatusTransition($entityType, $currentStatus, $newStatus)
    {
        $transitions = $this->getTransitionsForEntity($entityType);
        
        if (!isset($transitions[$currentStatus])) {
            return [
                'valid' => false,
                'error' => "Invalid current status: {$currentStatus} for {$entityType}"
            ];
        }

        if (!in_array($newStatus, $transitions[$currentStatus])) {
            return [
                'valid' => false,
                'error' => "Cannot transition from {$currentStatus} to {$newStatus} for {$entityType}"
            ];
        }

        return [
            'valid' => true,
            'message' => "Status transition from {$currentStatus} to {$newStatus} is valid"
        ];
    }

    /**
     * Get valid next statuses for an entity
     * 
     * @param string $entityType Type of entity
     * @param string $currentStatus Current status
     * @return array List of valid next statuses
     */
    public function getValidNextStatuses($entityType, $currentStatus)
    {
        $transitions = $this->getTransitionsForEntity($entityType);
        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Validate Purchase Request workflow rules
     * 
     * @param array $request Purchase request data
     * @param string $action Action being performed
     * @param array $user User performing the action
     * @return array Validation result
     */
    public function validatePurchaseRequestWorkflow($request, $action, $user)
    {
        switch ($action) {
            case 'create':
                if (!in_array($user['role'], ['branch_manager', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Branch Managers and Central Admins can create purchase requests'
                    ];
                }
                break;

            case 'approve':
                if (!in_array($user['role'], ['central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Central Admins can approve purchase requests'
                    ];
                }
                if ($request['status'] !== 'pending') {
                    return [
                        'valid' => false,
                        'error' => 'Only pending requests can be approved'
                    ];
                }
                break;

            case 'reject':
                if (!in_array($user['role'], ['central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Central Admins can reject purchase requests'
                    ];
                }
                if ($request['status'] !== 'pending') {
                    return [
                        'valid' => false,
                        'error' => 'Only pending requests can be rejected'
                    ];
                }
                break;
        }

        return ['valid' => true];
    }

    /**
     * Validate Purchase Order workflow rules
     * 
     * @param array $order Purchase order data
     * @param string $action Action being performed
     * @param array $user User performing the action
     * @return array Validation result
     */
    public function validatePurchaseOrderWorkflow($order, $action, $user)
    {
        switch ($action) {
            case 'create':
                if (!in_array($user['role'], ['central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Central Admins can create purchase orders'
                    ];
                }
                break;

            case 'send':
                if (!in_array($user['role'], ['central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Central Admins can send purchase orders'
                    ];
                }
                if ($order['status'] !== 'draft') {
                    return [
                        'valid' => false,
                        'error' => 'Only draft orders can be sent'
                    ];
                }
                break;

            case 'confirm':
                if (!in_array($user['role'], ['supplier', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Suppliers can confirm purchase orders'
                    ];
                }
                if ($order['status'] !== 'sent') {
                    return [
                        'valid' => false,
                        'error' => 'Only sent orders can be confirmed'
                    ];
                }
                break;

            case 'prepare':
                if (!in_array($user['role'], ['supplier', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Suppliers can mark orders as prepared'
                    ];
                }
                if ($order['status'] !== 'confirmed') {
                    return [
                        'valid' => false,
                        'error' => 'Only confirmed orders can be marked as prepared'
                    ];
                }
                break;
        }

        return ['valid' => true];
    }

    /**
     * Validate Delivery workflow rules
     * 
     * @param array $delivery Delivery data
     * @param string $action Action being performed
     * @param array $user User performing the action
     * @return array Validation result
     */
    public function validateDeliveryWorkflow($delivery, $action, $user)
    {
        switch ($action) {
            case 'create':
                if (!in_array($user['role'], ['logistics_coordinator', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Logistics Coordinators can create deliveries'
                    ];
                }
                break;

            case 'update_status':
                if (!in_array($user['role'], ['logistics_coordinator', 'central_admin', 'supplier'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Logistics Coordinators and Suppliers can update delivery status'
                    ];
                }
                break;

            case 'receive':
                if (!in_array($user['role'], ['branch_manager', 'inventory_staff', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Branch Managers and Inventory Staff can receive deliveries'
                    ];
                }
                if ($delivery['status'] !== 'delivered') {
                    return [
                        'valid' => false,
                        'error' => 'Only delivered items can be received'
                    ];
                }
                break;
        }

        return ['valid' => true];
    }

    /**
     * Validate Payment workflow rules
     * 
     * @param array $payment Payment data
     * @param string $action Action being performed
     * @param array $user User performing the action
     * @return array Validation result
     */
    public function validatePaymentWorkflow($payment, $action, $user)
    {
        switch ($action) {
            case 'process':
                if (!in_array($user['role'], ['branch_manager', 'inventory_staff', 'central_admin'])) {
                    return [
                        'valid' => false,
                        'error' => 'Only Branch Managers and Inventory Staff can process payments'
                    ];
                }
                break;
        }

        return ['valid' => true];
    }

    /**
     * Get transitions for a specific entity type
     * 
     * @param string $entityType Entity type
     * @return array Transitions array
     */
    private function getTransitionsForEntity($entityType)
    {
        switch ($entityType) {
            case 'purchase_request':
                return $this->purchaseRequestTransitions;
            case 'purchase_order':
                return $this->purchaseOrderTransitions;
            case 'delivery':
                return $this->deliveryTransitions;
            case 'payment':
                return $this->paymentTransitions;
            default:
                return [];
        }
    }

    /**
     * Check if user has permission for specific action
     * 
     * @param string $role User role
     * @param string $action Action to perform
     * @param string $entityType Entity type
     * @return bool True if user has permission
     */
    public function hasPermission($role, $action, $entityType)
    {
        $permissions = [
            'central_admin' => ['*'], // Full access
            'branch_manager' => [
                'purchase_request:create',
                'delivery:receive',
                'payment:process',
                'transfer:create',
                'transfer:approve'
            ],
            'inventory_staff' => [
                'delivery:receive',
                'payment:process',
                'inventory:update',
                'barcode:scan'
            ],
            'supplier' => [
                'purchase_order:confirm',
                'purchase_order:prepare',
                'delivery:update_status'
            ],
            'logistics_coordinator' => [
                'delivery:create',
                'delivery:update_status',
                'delivery:schedule'
            ],
            'franchise_manager' => [
                'franchise:approve',
                'franchise:reject'
            ]
        ];

        $userPermissions = $permissions[$role] ?? [];
        
        // Central admin has full access
        if (in_array('*', $userPermissions)) {
            return true;
        }

        // Check specific permission
        $permission = $entityType . ':' . $action;
        return in_array($permission, $userPermissions);
    }
}