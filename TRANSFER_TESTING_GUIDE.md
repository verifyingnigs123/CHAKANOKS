# Transfer Workflow Testing Guide

## Quick Test Scenarios

### Scenario 1: Create Transfer (Push) - Full Workflow

**Test Steps:**

1. **Login as Branch Manager (Branch A)**
   - Navigate to Transfers page
   - Click "Create Transfer" (green button)
   - Select FROM: Branch A, TO: Branch B
   - Add products with quantities
   - Click "Create Transfer"
   - ✅ **Expected:** Transfer created with status "pending"

2. **Check Notifications**
   - Login as Central Admin
   - ✅ **Expected:** Notification "🔔 Action Required: Approve Transfer Request"
   - Login as Logistics Coordinator
   - ✅ **Expected:** Notification "📋 New Transfer Request" (info)
   - Login as Branch B Manager
   - ✅ **Expected:** Notification "📥 Incoming Transfer Request" (info)

3. **Login as Central Admin - Approve**
   - Go to Transfers page
   - Find the pending transfer
   - Click "Approve"
   - ✅ **Expected:** Status changes to "approved"

4. **Check Notifications After Approval**
   - Login as Logistics Coordinator
   - ✅ **Expected:** Notification "🔔 Action Required: Schedule Transfer"
   - Login as Branch A Manager
   - ✅ **Expected:** Notification "✅ Transfer Approved"
   - Login as Branch B Manager
   - ✅ **Expected:** Notification "✅ Transfer Approved"

5. **Login as Logistics Coordinator - Schedule**
   - Go to Transfers page
   - Find the approved transfer
   - Click "Schedule"
   - Select a date
   - Submit
   - ✅ **Expected:** Status changes to "scheduled"

6. **Check Notifications After Scheduling**
   - Login as Branch A Manager
   - ✅ **Expected:** Notification about scheduled date
   - Login as Branch B Manager
   - ✅ **Expected:** Notification about scheduled date

7. **Login as Logistics Coordinator - Dispatch**
   - Go to Transfers page
   - Find the scheduled transfer
   - Click "Dispatch"
   - Confirm
   - ✅ **Expected:** Status changes to "in_transit"
   - ✅ **Expected:** Inventory DEDUCTED from Branch A

8. **Check Inventory After Dispatch**
   - Login as Branch A Manager
   - Go to Inventory page
   - ✅ **Expected:** Product quantities reduced by transfer amounts

9. **Check Notifications After Dispatch**
   - Login as Branch B Manager
   - ✅ **Expected:** Notification "🔔 Delivery In Transit - Prepare to Receive"
   - Login as Branch A Manager
   - ✅ **Expected:** Notification "🚚 Transfer Dispatched"

10. **Login as Branch B Manager - Receive**
    - Go to Transfers page
    - Find the in_transit transfer
    - Click "Receive"
    - Confirm
    - ✅ **Expected:** Status changes to "completed"
    - ✅ **Expected:** Inventory ADDED to Branch B

11. **Check Inventory After Receive**
    - Login as Branch B Manager
    - Go to Inventory page
    - ✅ **Expected:** Product quantities increased by transfer amounts

12. **Check Final Notifications**
    - Login as Central Admin
    - ✅ **Expected:** Notification "✅ Transfer Completed"
    - Login as Logistics Coordinator
    - ✅ **Expected:** Notification "✅ Transfer Delivered"
    - Login as Branch A Manager
    - ✅ **Expected:** Notification "✅ Transfer Completed"
    - Login as Branch B Manager
    - ✅ **Expected:** Notification "✅ Transfer Received"
    - Login as Inventory Staff (both branches)
    - ✅ **Expected:** Notifications about inventory updates

---

### Scenario 2: Request Transfer (Pull) - Full Workflow

**Test Steps:**

1. **Login as Branch Manager (Branch B)**
   - Navigate to Transfers page
   - Click "Request Transfer" (blue button)
   - Select FROM: Branch A (TO: Branch B auto-filled)
   - Add products with quantities
   - Click "Submit Request"
   - ✅ **Expected:** Transfer created with status "pending"

2. **Follow Steps 2-12 from Scenario 1**
   - The workflow is identical after creation
   - ✅ **Expected:** Same notifications and status changes

---

### Scenario 3: Transfer Rejection

**Test Steps:**

1. **Create Transfer (either Push or Pull)**
   - Follow Scenario 1 Step 1 or Scenario 2 Step 1

2. **Login as Central Admin - Reject**
   - Go to Transfers page
   - Find the pending transfer
   - Click "Reject"
   - Confirm
   - ✅ **Expected:** Status changes to "rejected"

3. **Check Notifications After Rejection**
   - Login as Source Branch Manager
   - ✅ **Expected:** Notification "❌ Transfer Request Rejected" with reason
   - Login as Destination Branch Manager
   - ✅ **Expected:** Notification "❌ Transfer Request Rejected" with reason
   - Login as Logistics Coordinator
   - ✅ **Expected:** Notification "❌ Transfer Request Rejected" with reason

---

### Scenario 4: Permission Testing

**Test Cases:**

1. **Branch Manager tries to approve transfer**
   - Login as Branch Manager
   - Go to Transfers page
   - ✅ **Expected:** No "Approve" or "Reject" buttons visible

2. **Branch Manager tries to schedule transfer**
   - Login as Branch Manager
   - Find approved transfer
   - ✅ **Expected:** No "Schedule" button visible

3. **Branch Manager tries to dispatch transfer**
   - Login as Branch Manager
   - Find scheduled transfer
   - ✅ **Expected:** No "Dispatch" button visible

4. **Branch A Manager tries to receive transfer to Branch B**
   - Login as Branch A Manager
   - Find in_transit transfer to Branch B
   - ✅ **Expected:** No "Receive" button visible

5. **Branch B Manager can receive transfer to Branch B**
   - Login as Branch B Manager
   - Find in_transit transfer to Branch B
   - ✅ **Expected:** "Receive" button visible and functional

---

### Scenario 5: Inventory Validation

**Test Steps:**

1. **Create Transfer with insufficient inventory**
   - Login as Branch Manager
   - Click "Create Transfer"
   - Select FROM: Branch A
   - Add product with quantity > available
   - Click "Create Transfer"
   - ✅ **Expected:** Error message "Insufficient inventory for one or more products"

2. **Create Transfer to same branch**
   - Login as Branch Manager
   - Click "Create Transfer"
   - Select FROM: Branch A, TO: Branch A
   - ✅ **Expected:** Error message "Cannot transfer to the same branch"

---

### Scenario 6: UI/UX Testing

**Test Cases:**

1. **Create Transfer Modal**
   - ✅ Green gradient header
   - ✅ FROM and TO branch dropdowns
   - ✅ Product selection with available quantities
   - ✅ Add/Remove product rows
   - ✅ Notes field
   - ✅ Cancel and Create buttons

2. **Request Transfer Modal**
   - ✅ Blue gradient header
   - ✅ FROM branch dropdown (TO auto-filled)
   - ✅ Product selection from source branch
   - ✅ Add/Remove product rows
   - ✅ Notes field
   - ✅ Cancel and Submit buttons

3. **View Transfer Details Modal**
   - ✅ Blue gradient header
   - ✅ Transfer information card with icons
   - ✅ Transfer items table
   - ✅ Dynamic action buttons based on role
   - ✅ Close button

4. **Schedule Transfer Modal**
   - ✅ Blue gradient header
   - ✅ Date picker with minimum date (today)
   - ✅ Cancel and Schedule buttons

5. **Status Badges**
   - ✅ Pending: Amber with clock icon
   - ✅ Approved: Blue with thumbs-up icon
   - ✅ Scheduled: Indigo with calendar icon
   - ✅ In Transit: Purple with truck icon
   - ✅ Completed: Green with check icon
   - ✅ Rejected: Red with X icon

6. **Search and Filter**
   - ✅ Search by transfer number
   - ✅ Search by branch names
   - ✅ Filter by status
   - ✅ "No results" message when no matches

---

## Common Issues to Check

### Issue 1: Notifications Not Appearing
**Check:**
- User has correct role in database
- User status is 'active'
- Notification service is logging correctly
- Check browser console for errors

### Issue 2: Inventory Not Updating
**Check:**
- Dispatch method is deducting from source branch
- Receive method is adding to destination branch
- Inventory records exist for products in both branches
- Check activity logs for inventory updates

### Issue 3: Permission Errors
**Check:**
- User role matches expected role
- Session data is correct
- Controller permission checks are working
- View is hiding/showing buttons correctly

### Issue 4: Modal Not Opening
**Check:**
- JavaScript is loaded
- No console errors
- Modal ID matches JavaScript function
- Tailwind classes are applied

---

## Database Verification Queries

### Check Transfer Status
```sql
SELECT id, transfer_number, status, from_branch_id, to_branch_id, 
       requested_by, approved_by, scheduled_by, dispatched_by, received_by
FROM transfers
ORDER BY created_at DESC
LIMIT 10;
```

### Check Transfer Items
```sql
SELECT ti.*, p.name as product_name
FROM transfer_items ti
JOIN products p ON p.id = ti.product_id
WHERE ti.transfer_id = [TRANSFER_ID];
```

### Check Inventory Changes
```sql
SELECT * FROM inventory
WHERE branch_id IN ([BRANCH_A_ID], [BRANCH_B_ID])
  AND product_id IN ([PRODUCT_IDS])
ORDER BY updated_at DESC;
```

### Check Notifications
```sql
SELECT n.*, u.username, u.role
FROM notifications n
JOIN users u ON u.id = n.user_id
WHERE n.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
ORDER BY n.created_at DESC;
```

### Check Activity Logs
```sql
SELECT * FROM activity_logs
WHERE action_type IN ('create', 'approve', 'reject', 'schedule', 'dispatch', 'receive')
  AND entity_type = 'transfer'
ORDER BY created_at DESC
LIMIT 20;
```

---

## Success Criteria

✅ **All scenarios pass without errors**
✅ **Notifications sent to correct roles at each stage**
✅ **Inventory updates correctly (deduct on dispatch, add on receive)**
✅ **Permissions enforced (only Central Admin approves)**
✅ **UI is responsive and user-friendly**
✅ **No duplicate notifications**
✅ **Activity logs record all actions**
✅ **Status flow is correct (pending → approved → scheduled → in_transit → completed)**

---

## Quick Smoke Test (5 minutes)

1. Create a transfer as Branch Manager
2. Approve as Central Admin
3. Schedule as Logistics
4. Dispatch as Logistics
5. Receive as Destination Branch Manager
6. Check inventory in both branches
7. Check notifications for all roles

If all 7 steps work, the implementation is successful! 🎉
