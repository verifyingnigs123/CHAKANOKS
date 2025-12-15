# Notification Implementation Guide

## Overview
This guide shows where to add workflow notifications in each controller to create actionable, role-based notifications that redirect users to the exact page they need.

## Notification Features
- ✅ **Clickable notifications** - Click to go directly to the action page
- ✅ **Auto mark as read** - Automatically marked when clicked
- ✅ **Role-based** - Only relevant users receive notifications
- ✅ **Action-oriented** - Clear call-to-action in each notification
- ✅ **Emoji indicators** - 🔔 for action required, ✅ for completed, ❌ for rejected

---

## 1. Purchase Request Workflow

### PurchaseRequestController.php

#### Step 1: Branch Manager creates request
**Location:** `store()` method - After successful creation

```php
// Add after: $this->purchaseRequestModel->insert($data);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseRequestCreatedWorkflow(
    $requestId,
    $requestNumber,
    $branchName
);
```

#### Step 2a: Central Admin approves
**Location:** `approve()` method - After status update

```php
// Add after: $this->purchaseRequestModel->update($id, ['status' => 'approved']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseRequestApprovedWorkflow(
    $id,
    $request['request_number'],
    $request['branch_id'],
    $branchName
);
```

#### Step 2b: Central Admin rejects
**Location:** `reject()` method - After status update

```php
// Add after: $this->purchaseRequestModel->update($id, ['status' => 'rejected']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseRequestRejectedWorkflow(
    $id,
    $request['request_number'],
    $request['branch_id'],
    $rejectionReason
);
```

---

## 2. Purchase Order Workflow

### PurchaseOrderController.php

#### Step 3: Central Admin sends PO to supplier
**Location:** `send()` method - After status update

```php
// Add after: $this->purchaseOrderModel->update($id, ['status' => 'sent']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseOrderSentWorkflow(
    $id,
    $po['po_number'],
    $po['supplier_id'],
    $supplierName,
    $po['branch_id'],
    $branchName
);
```

#### Step 4: Supplier confirms order
**Location:** `confirm()` method - After status update

```php
// Add after: $this->purchaseOrderModel->update($id, ['status' => 'confirmed']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseOrderConfirmedWorkflow(
    $id,
    $po['po_number'],
    $po['supplier_id'],
    $supplierName
);
```

#### Step 5: Supplier marks as prepared
**Location:** `markPrepared()` method - After status update

```php
// Add after: $this->purchaseOrderModel->update($id, ['status' => 'prepared']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyPurchaseOrderPreparedWorkflow(
    $id,
    $po['po_number'],
    $supplierName
);
```

---

## 3. Delivery Workflow

### DeliveryController.php

#### Step 6: Logistics schedules delivery
**Location:** `store()` method - After successful creation

```php
// Add after: $this->deliveryModel->insert($data);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyDeliveryScheduledWorkflow(
    $deliveryId,
    $deliveryNumber,
    $branchId,
    $branchName,
    $poNumber,
    $scheduledDate
);
```

#### Step 7: Logistics dispatches (in transit)
**Location:** `updateStatus()` method - When status = 'in_transit'

```php
// Add after: $this->deliveryModel->update($id, ['status' => 'in_transit']);
if ($status === 'in_transit') {
    $notificationService = new \App\Libraries\NotificationService();
    $notificationService->notifyDeliveryInTransitWorkflow(
        $id,
        $delivery['delivery_number'],
        $delivery['branch_id'],
        $branchName
    );
}
```

#### Step 8: Branch receives delivery
**Location:** `receive()` method - After status update

```php
// Add after: $this->deliveryModel->update($id, ['status' => 'delivered']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyDeliveryReceivedWorkflow(
    $id,
    $delivery['delivery_number'],
    $delivery['branch_id'],
    $branchName,
    $poNumber,
    $supplierId,
    $supplierName
);
```

---

## 4. Transfer Workflow

### TransferController.php

#### Step 1: Branch Manager creates transfer
**Location:** `store()` method - After successful creation

```php
// Add after: $this->transferModel->insert($data);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyTransferCreatedWorkflow(
    $transferId,
    $transferNumber,
    $fromBranchId,
    $fromBranchName,
    $toBranchId,
    $toBranchName
);
```

#### Step 2a: Destination branch approves
**Location:** `approve()` method - After status update

```php
// Add after: $this->transferModel->update($id, ['status' => 'approved']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyTransferApprovedWorkflow(
    $id,
    $transfer['transfer_number'],
    $transfer['from_branch_id'],
    $fromBranchName,
    $transfer['to_branch_id'],
    $toBranchName
);
```

#### Step 2b: Destination branch rejects
**Location:** `reject()` method - After status update

```php
// Add after: $this->transferModel->update($id, ['status' => 'rejected']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyTransferRejectedWorkflow(
    $id,
    $transfer['transfer_number'],
    $transfer['from_branch_id'],
    $fromBranchName,
    $transfer['to_branch_id'],
    $toBranchName,
    $rejectionReason
);
```

#### Step 3: Source branch completes transfer
**Location:** `complete()` method - After status update

```php
// Add after: $this->transferModel->update($id, ['status' => 'completed']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyTransferCompletedWorkflow(
    $id,
    $transfer['transfer_number'],
    $transfer['from_branch_id'],
    $fromBranchName,
    $transfer['to_branch_id'],
    $toBranchName
);
```

---

## 5. Franchise Application Workflow

### FranchiseController.php (or Home.php for public submission)

#### Step 1: Public submits application
**Location:** `submitFranchiseApplication()` method in Home.php - After successful creation

```php
// Add after: $franchiseModel->insert($data);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyFranchiseApplicationSubmittedWorkflow(
    $applicationId,
    $applicantName,
    $email,
    $phone
);
```

#### Step 2: Franchise Manager starts review
**Location:** `startReview()` method - After status update

```php
// Add after: $this->franchiseModel->update($id, ['status' => 'under_review']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyFranchiseApplicationUnderReviewWorkflow(
    $id,
    $applicantName
);
```

#### Step 3a: Central Admin approves
**Location:** `approve()` method - After status update

```php
// Add after: $this->franchiseModel->update($id, ['status' => 'approved']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyFranchiseApplicationApprovedWorkflow(
    $id,
    $applicantName,
    $email
);
```

#### Step 3b: Central Admin rejects
**Location:** `reject()` method - After status update

```php
// Add after: $this->franchiseModel->update($id, ['status' => 'rejected']);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyFranchiseApplicationRejectedWorkflow(
    $id,
    $applicantName,
    $rejectionReason
);
```

#### Step 4: Central Admin converts to branch
**Location:** `convertToBranch()` method - After branch creation

```php
// Add after: $branchModel->insert($branchData);
$notificationService = new \App\Libraries\NotificationService();
$notificationService->notifyFranchiseConvertedToBranchWorkflow(
    $applicationId,
    $applicantName,
    $branchId,
    $branchName
);
```

---

## Testing Checklist

### Purchase Request → Order → Delivery Flow
- [ ] Branch Manager creates request → Central Admin gets notification
- [ ] Central Admin approves → Branch Manager gets notification + Central Admin gets "Create PO" notification
- [ ] Central Admin creates & sends PO → Supplier gets notification
- [ ] Supplier confirms → Central Admin gets notification + Supplier gets "Prepare" notification
- [ ] Supplier marks prepared → Logistics gets notification
- [ ] Logistics schedules delivery → Branch gets notification
- [ ] Logistics dispatches → Branch gets "In Transit" notification
- [ ] Branch receives → Central Admin, Supplier, Logistics get completion notification

### Transfer Flow
- [ ] Branch A creates transfer → Branch B gets approval notification
- [ ] Branch B approves → Branch A gets "Complete Transfer" notification
- [ ] Branch A completes → Branch B gets completion notification

### Franchise Flow
- [ ] Public submits → Central Admin & Franchise Manager get notification
- [ ] Franchise Manager reviews → Central Admin gets update
- [ ] Central Admin approves → Franchise Manager gets notification + Central Admin gets "Convert" notification
- [ ] Central Admin converts → Both get success notification

---

## Notification Link Patterns

All notifications include direct links to action pages:

- **Purchase Requests:** `/purchase-requests/view/{id}`
- **Purchase Orders:** `/purchase-orders/view/{id}`
- **Create PO from Request:** `/purchase-orders/create-from-request/{id}`
- **Deliveries:** `/deliveries/view/{id}`
- **Create Delivery:** `/deliveries/create?po_id={id}`
- **Transfers:** `/transfers/view/{id}`
- **Franchise Applications:** `/franchise/applications/view/{id}`
- **Branches:** `/branches/view/{id}`

---

## Benefits

1. **Reduced Navigation Time** - Users click notification and go directly to action page
2. **Clear Responsibilities** - Each role knows exactly what they need to do
3. **Workflow Visibility** - Everyone sees the status of their requests/orders
4. **Faster Processing** - No need to manually check for new tasks
5. **Better Accountability** - Notifications create audit trail of workflow steps
