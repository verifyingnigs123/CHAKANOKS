# Complete Transfer Workflow - Implementation Summary

## Overview
The Branch Transfer system now supports both **Push** (Create Transfer) and **Pull** (Request Transfer) mechanisms with a complete tracking workflow similar to Purchase Request → Delivery flow.

---

## Transfer Types

### 1. Create Transfer (Push)
- **Initiated by:** Branch A (source branch)
- **Direction:** Branch A sends products TO Branch B
- **Use case:** Branch A has excess inventory and wants to send to Branch B

### 2. Request Transfer (Pull)
- **Initiated by:** Branch B (destination branch)
- **Direction:** Branch B requests products FROM Branch A
- **Use case:** Branch B needs products and requests from Branch A

**Note:** Both types follow the SAME workflow after creation.

---

## Complete Workflow (5 Steps)

### Step 1: Create/Request Transfer
**Who:** Branch Manager or Franchise Manager

**Actions:**
- Create Transfer: Select FROM branch, TO branch, products, quantities
- Request Transfer: Select FROM branch (auto-fills TO branch as user's branch), products, quantities

**System Actions:**
- Validates inventory availability in source branch
- Creates transfer record with status: `pending`
- Sends notifications to:
  - ✅ **Central Admin** (ACTION REQUIRED - for approval)
  - ℹ️ **Logistics Coordinator** (INFO - for awareness)
  - ℹ️ **Source Branch Manager** (INFO - request sent)
  - ℹ️ **Destination Branch Manager** (INFO - incoming request)

---

### Step 2: Central Admin Approval
**Who:** Central Admin ONLY

**Actions:**
- Review transfer request
- **Approve** or **Reject** with reason

**If Approved:**
- Status changes to: `approved`
- Records: `approved_by`, `approved_at`
- Sends notifications to:
  - ✅ **Logistics Coordinator** (ACTION REQUIRED - to schedule)
  - ℹ️ **Source Branch Manager** (INFO - approved)
  - ℹ️ **Destination Branch Manager** (INFO - approved)

**If Rejected:**
- Status changes to: `rejected`
- Records: `approved_by`, `approved_at`
- Sends notifications to:
  - ❌ **Source Branch Manager** (INFO - rejected with reason)
  - ❌ **Destination Branch Manager** (INFO - rejected with reason)
  - ❌ **Logistics Coordinator** (INFO - rejected with reason)

---

### Step 3: Schedule Transfer
**Who:** Central Admin or Logistics Coordinator

**Actions:**
- Click "Schedule" button
- Select scheduled delivery date
- Submit

**System Actions:**
- Status changes to: `scheduled`
- Records: `scheduled_date`, `scheduled_by`, `scheduled_at`
- Sends notifications to:
  - ℹ️ **Source Branch Manager** (INFO - scheduled for date)
  - ℹ️ **Destination Branch Manager** (INFO - scheduled for date)

---

### Step 4: Dispatch Transfer
**Who:** Central Admin or Logistics Coordinator

**Actions:**
- Click "Dispatch" button
- Confirm dispatch

**System Actions:**
- Status changes to: `in_transit`
- Records: `dispatched_by`, `dispatched_at`
- **DEDUCTS inventory from source branch** (critical!)
- Sends notifications to:
  - ✅ **Destination Branch Manager** (ACTION REQUIRED - prepare to receive)
  - ℹ️ **Source Branch Manager** (INFO - dispatched, inventory deducted)
  - ℹ️ **Central Admin** (INFO - in transit)

---

### Step 5: Receive Transfer
**Who:** Destination Branch Manager ONLY (or Central Admin)

**Actions:**
- Click "Receive" button
- Confirm receipt

**System Actions:**
- Status changes to: `completed`
- Records: `received_by`, `received_at`, `completed_at`
- **ADDS inventory to destination branch** (critical!)
- Updates `quantity_received` for all items
- Sends notifications to:
  - ✅ **Central Admin** (INFO - completed)
  - ✅ **Logistics Coordinator** (INFO - delivered)
  - ✅ **Source Branch Manager** (INFO - completed)
  - ✅ **Source Inventory Staff** (INFO - inventory deducted)
  - ✅ **Destination Branch Manager** (INFO - received)
  - ✅ **Destination Inventory Staff** (INFO - inventory added)

---

## Status Flow

```
pending → approved → scheduled → in_transit → completed
   ↓
rejected
```

---

## Inventory Management

### Two-Stage Inventory Update

1. **Dispatch (Step 4):**
   - Inventory DEDUCTED from source branch
   - Ensures accurate tracking during transit
   - Prevents double-counting

2. **Receive (Step 5):**
   - Inventory ADDED to destination branch
   - Completes the transfer cycle
   - Updates both branches' inventory

---

## Permissions & Access Control

### Central Admin
- ✅ Approve/Reject transfers
- ✅ Schedule transfers
- ✅ Dispatch transfers
- ✅ Receive transfers (override)
- ✅ View all transfers

### Logistics Coordinator
- ✅ Schedule transfers
- ✅ Dispatch transfers
- ✅ View all transfers
- ❌ Approve/Reject transfers
- ❌ Receive transfers

### Branch Manager
- ✅ Create transfers (from their branch)
- ✅ Request transfers (to their branch)
- ✅ Receive transfers (to their branch only)
- ✅ View transfers involving their branch
- ❌ Approve/Reject transfers
- ❌ Schedule/Dispatch transfers

### Franchise Manager
- ✅ Create transfers (from their branch)
- ✅ Request transfers (to their branch)
- ✅ View transfers involving their branch
- ❌ Approve/Reject transfers
- ❌ Schedule/Dispatch/Receive transfers

---

## Notification Strategy

### Notification Types
- **ACTION REQUIRED** (⚠️ warning type): User must take action
- **INFO** (ℹ️ info type): Informational, no action needed
- **SUCCESS** (✅ success type): Positive outcome
- **DANGER** (❌ danger type): Negative outcome or rejection

### Notification Recipients by Stage

| Stage | Central Admin | Logistics | Source Branch | Dest Branch | Inventory Staff |
|-------|--------------|-----------|---------------|-------------|-----------------|
| Created | ACTION | INFO | INFO | INFO | - |
| Approved | - | ACTION | INFO | INFO | - |
| Rejected | - | INFO | DANGER | INFO | - |
| Scheduled | - | - | INFO | INFO | - |
| Dispatched | INFO | - | INFO | ACTION | - |
| Completed | SUCCESS | SUCCESS | SUCCESS | SUCCESS | SUCCESS (both) |

---

## Database Schema

### transfers table
```sql
- id
- transfer_number (auto-generated: TRF-YYYYMMDD-XXXX)
- from_branch_id
- to_branch_id
- requested_by (user_id)
- approved_by (user_id, nullable)
- scheduled_by (user_id, nullable)
- dispatched_by (user_id, nullable)
- received_by (user_id, nullable)
- status (enum: pending, approved, rejected, scheduled, in_transit, completed)
- request_date
- approved_at (nullable)
- scheduled_date (nullable)
- scheduled_at (nullable)
- dispatched_at (nullable)
- received_at (nullable)
- completed_at (nullable)
- notes (text, nullable)
- created_at
- updated_at
```

### transfer_items table
```sql
- id
- transfer_id
- product_id
- quantity
- quantity_received
- created_at
- updated_at
```

---

## UI Components

### Main Transfer Page
- **Search & Filter:** Search by transfer #, branch names; filter by status
- **Create Transfer Button:** Green button for push transfers
- **Request Transfer Button:** Blue button for pull transfers
- **Transfer Table:** Shows all transfers with status badges and action buttons

### Create Transfer Modal
- Green gradient header
- Select FROM branch, TO branch
- Dynamic product loading based on source branch
- Shows available quantity for each product
- Real-time inventory validation

### Request Transfer Modal
- Blue gradient header
- Select FROM branch (TO branch auto-filled)
- Dynamic product loading from selected source branch
- Shows available quantity
- Clear indication this is a "pull" request

### View Transfer Details Modal
- Blue gradient header
- Transfer information card with icons
- Transfer items table
- Dynamic action buttons based on status and user role
- Approval/rejection info when applicable
- Completion info when applicable

### Schedule Transfer Modal
- Blue gradient header
- Date picker for scheduled delivery date
- Minimum date: today

---

## Routes

```php
GET  /transfers                      - List all transfers
GET  /transfers/create               - Create transfer form (deprecated, using modal)
POST /transfers/store                - Store new transfer (push)
POST /transfers/request-store        - Store transfer request (pull)
GET  /transfers/view/{id}            - View transfer details (full page)
GET  /transfers/get-details/{id}     - Get transfer details (JSON for modal)
POST /transfers/{id}/approve         - Approve transfer (Central Admin only)
POST /transfers/{id}/reject          - Reject transfer (Central Admin only)
POST /transfers/{id}/schedule        - Schedule transfer (Central Admin/Logistics)
POST /transfers/{id}/dispatch        - Dispatch transfer (Central Admin/Logistics)
POST /transfers/{id}/receive         - Receive transfer (Destination Branch Manager)
```

---

## Key Features

### ✅ Implemented
1. Push and Pull transfer mechanisms
2. Complete 5-step workflow with tracking
3. Two-stage inventory updates (dispatch & receive)
4. Role-based permissions and access control
5. Comprehensive notification system for all roles
6. Real-time inventory validation
7. Modal-based UI with Tailwind design
8. Status badges with icons
9. Activity logging for all actions
10. Duplicate notification prevention

### 🎯 Business Logic
- Only Central Admin can approve/reject transfers
- Inventory deducted on dispatch, added on receive
- Source branch inventory validated before creation
- Destination branch can only receive their own transfers
- All involved parties notified at each stage
- Transfer number auto-generated with date prefix

---

## Testing Checklist

### Create Transfer (Push)
- [ ] Branch Manager can create transfer from their branch
- [ ] Cannot transfer to same branch
- [ ] Validates sufficient inventory in source branch
- [ ] Central Admin receives approval notification
- [ ] All parties receive info notifications

### Request Transfer (Pull)
- [ ] Branch Manager can request transfer to their branch
- [ ] Cannot request from same branch
- [ ] Validates sufficient inventory in source branch
- [ ] Central Admin receives approval notification
- [ ] All parties receive info notifications

### Approval Workflow
- [ ] Only Central Admin can approve
- [ ] Only Central Admin can reject
- [ ] Branch Managers cannot approve/reject
- [ ] Logistics receives schedule notification after approval
- [ ] All parties notified of approval/rejection

### Scheduling
- [ ] Central Admin can schedule
- [ ] Logistics Coordinator can schedule
- [ ] Date picker enforces minimum date (today)
- [ ] Both branches notified of scheduled date

### Dispatch
- [ ] Central Admin can dispatch
- [ ] Logistics Coordinator can dispatch
- [ ] Inventory deducted from source branch
- [ ] Destination branch notified to prepare
- [ ] Status changes to in_transit

### Receive
- [ ] Only destination branch manager can receive
- [ ] Central Admin can override and receive
- [ ] Inventory added to destination branch
- [ ] All parties notified of completion
- [ ] Status changes to completed

### Notifications
- [ ] No duplicate notifications within 5 minutes
- [ ] All roles receive appropriate notifications
- [ ] Action required notifications are warning type
- [ ] Info notifications are info type
- [ ] Success notifications are success type

---

## Files Modified

1. **app/Controllers/TransferController.php**
   - Added `requestStore()` method for pull transfers
   - Added `schedule()`, `dispatch()`, `receive()` methods
   - Updated `approve()` and `reject()` to restrict to Central Admin
   - Added inventory management in dispatch and receive

2. **app/Views/transfers/index.php**
   - Added Request Transfer button and modal
   - Added Schedule Transfer modal
   - Added View Transfer Details modal
   - Updated action buttons based on role and status
   - Added JavaScript for modal management and product loading

3. **app/Libraries/NotificationService.php**
   - Updated `notifyTransferCreatedWorkflow()` - Central Admin approval
   - Updated `notifyTransferApprovedWorkflow()` - All parties notified
   - Updated `notifyTransferRejectedWorkflow()` - All parties notified
   - Updated `notifyTransferCompletedWorkflow()` - All parties notified

4. **app/Config/Routes.php**
   - Added `POST /transfers/request-store` route
   - Added `POST /transfers/{id}/schedule` route
   - Added `POST /transfers/{id}/dispatch` route
   - Added `POST /transfers/{id}/receive` route
   - Added `GET /transfers/get-details/{id}` route

5. **app/Database/Migrations/2025-12-16-000001_AddTrackingFieldsToTransfers.php**
   - Added tracking fields for complete workflow
   - Added scheduled, dispatched, received timestamps
   - Updated status enum

---

## Migration Required

Run the migration to add tracking fields:

```bash
php spark migrate
```

---

## Summary

The Transfer system now provides a complete, tracked workflow for inter-branch inventory transfers with:
- **Dual mechanisms:** Push (Create) and Pull (Request)
- **5-step workflow:** Create → Approve → Schedule → Dispatch → Receive
- **Two-stage inventory:** Deduct on dispatch, add on receive
- **Role-based access:** Only Central Admin approves, Logistics schedules/dispatches, Destination receives
- **Comprehensive notifications:** All involved parties notified at each stage
- **Modern UI:** Modal-based with Tailwind design

This ensures complete visibility, accountability, and accurate inventory tracking throughout the transfer lifecycle.
