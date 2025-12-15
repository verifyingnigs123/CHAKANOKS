# New Workflow: Pay First, Then Receive

## Updated Flow

Your system now follows this workflow:

```
1. Branch Manager
   └─> Create Purchase Request
        ↓
2. Central Admin
   └─> Approve & Create Purchase Order
        ↓
3. Supplier
   └─> Confirm & Prepare Order
        ↓
4. Logistics Coordinator
   └─> Schedule Delivery
        ↓
5. Logistics Coordinator
   └─> Dispatch Delivery (In Transit)
        ↓
6. Logistics Coordinator
   └─> Mark as Delivered (goods arrive at branch)
        ↓ 🔔 Notification sent to Central Admin
        ↓
7. Central Admin
   └─> Process PayPal Payment
        ↓ 🔔 Notification sent to Branch
        ↓
8. Branch Manager / Inventory Staff
   └─> Receive Delivery
        ↓ ✅ Inventory Updated!
        ↓
9. Complete!
```

## Key Changes

### Before (Old Flow):
- Branch receives delivery → Inventory updates → Central Admin pays

### After (New Flow):
- Delivery arrives → Central Admin pays → Branch receives → Inventory updates

## Step-by-Step Details

### Step 6: Logistics Marks as Delivered
**Who**: Logistics Coordinator
**Action**: Click "Mark as Delivered" when goods arrive at branch
**Result**:
- Delivery status changes to "delivered"
- Payment transaction created with status "pending"
- 🔔 Central Admin receives notification: "💰 Payment Required"

### Step 7: Central Admin Processes Payment
**Who**: Central Admin
**Action**: Click "Process PayPal Payment" button
**Requirements**: Must enter PayPal Transaction ID
**Result**:
- Payment status changes to "completed"
- Payment date recorded
- 🔔 Branch Manager receives notification: "✅ Payment Complete - Ready to Receive"
- 🔔 Inventory Staff receives notification: "✅ Payment Complete - Ready to Receive"

### Step 8: Branch Receives Delivery
**Who**: Branch Manager or Inventory Staff
**Action**: Click "Receive Delivery & Update Inventory" button
**Requirements**: 
- Delivery must be marked as "delivered"
- Payment must be "completed"
**Result**:
- Inventory quantities updated for all products
- Inventory history records created
- Purchase Order status changes to "completed"
- 🔔 All stakeholders notified of completion

## User Interface Changes

### For Logistics Coordinator
When viewing a delivery that's "In Transit":
```
┌─────────────────────────────────────────┐
│ What's Next?                             │
│ Driver is on the way. You can mark as   │
│ Delivered when goods arrive at branch.  │
│                                          │
│ [Mark as Delivered]                      │
└─────────────────────────────────────────┘
```

### For Central Admin
When delivery is marked as "delivered":
```
┌─────────────────────────────────────────┐
│ 💰 What's Next?                          │
│ Delivery arrived at branch! Process     │
│ PayPal payment first, then branch can   │
│ receive it.                              │
│                                          │
│ [Process PayPal Payment]                 │
└─────────────────────────────────────────┘
```

### For Branch Manager (Before Payment)
When delivery is "delivered" but payment not completed:
```
┌─────────────────────────────────────────┐
│ ⏳ Waiting for Payment                   │
│ Delivery has arrived at your branch.    │
│ Central Admin must process PayPal       │
│ payment before you can receive it and   │
│ update inventory.                        │
└─────────────────────────────────────────┘
```

### For Branch Manager (After Payment)
When payment is completed:
```
┌─────────────────────────────────────────┐
│ ✅ Payment Completed - Ready to Receive! │
│ Central Admin has processed the payment.│
│ You can now receive the delivery and    │
│ update inventory.                        │
│                                          │
│ [Receive Delivery & Update Inventory]   │
└─────────────────────────────────────────┘
```

## Notifications

### When Delivery Marked as Delivered
**To**: Central Admin
**Message**: "💰 Payment Required - Delivery DEL20251215000002 arrived at branch. Please process PayPal payment of ₱15,000.00 to supplier before branch can receive."
**Link**: Direct to delivery page

### When Payment Completed
**To**: Branch Manager, Inventory Staff
**Message**: "✅ Payment Complete - Ready to Receive - Payment completed for Delivery DEL20251215000002. You can now receive the delivery and update inventory."
**Link**: Direct to delivery page

**To**: Central Admin (confirmation)
**Message**: "✅ Payment Processed - PayPal payment of ₱15,000.00 completed for Main Branch. Branch can now receive delivery."
**Link**: Direct to delivery page

### When Delivery Received
**To**: All stakeholders (Central Admin, Branch Manager, Supplier, Logistics)
**Message**: "✅ Delivery Received - Delivery DEL20251215000002 has been received at Main Branch. Inventory updated."
**Link**: Direct to delivery page

## Error Prevention

### Branch Cannot Receive Before Payment
If Branch Manager tries to receive before payment:
```
❌ Error: Payment must be completed by Central Admin before you can 
receive this delivery. Please wait for payment confirmation.
```

### Cannot Receive Twice
If someone tries to receive the same delivery again:
```
ℹ️ Info: This delivery has already been received and inventory was updated.
```

### Delivery Must Be Marked as Delivered
If someone tries to receive before logistics marks it as delivered:
```
❌ Error: Delivery must be marked as "delivered" before you can receive it.
```

## Progress Tracker

The delivery page shows a visual progress bar:

```
Scheduled → In Transit → Delivered → Paid
    ✓           ✓           ✓        ✓
```

**Current Status Messages:**
- **Scheduled**: "Scheduled for Dec 15, 2025"
- **In Transit**: "In Transit - On the way to Main Branch"
- **Delivered (not paid)**: "Delivered - Awaiting Payment"
- **Delivered (paid, not received)**: "Delivered - Payment Complete - Ready to Receive"
- **Complete**: "Delivery Complete & Paid"

## Benefits of New Flow

1. **Financial Control**: Central Admin pays before goods are officially received
2. **Clear Accountability**: Payment is tracked before inventory changes
3. **Better Audit Trail**: Payment → Receive → Inventory Update sequence is clear
4. **Prevents Issues**: Branch can't receive without payment confirmation
5. **Proper Notifications**: Everyone knows what to do and when

## Testing the New Flow

1. **Create a test Purchase Order**
2. **Schedule Delivery** (Logistics)
3. **Dispatch** (Logistics) - Status: In Transit
4. **Mark as Delivered** (Logistics) - Status: Delivered
   - ✓ Central Admin gets notification
5. **Try to receive** (Branch) - Should show "Waiting for Payment" message
6. **Process PayPal Payment** (Central Admin)
   - ✓ Branch gets notification
7. **Receive Delivery** (Branch) - Should now work
   - ✓ Inventory updates
   - ✓ Everyone gets notification

## Troubleshooting

### "Waiting for Payment" message won't go away
- Check if Central Admin actually processed the payment
- Verify payment transaction status is "completed"
- Refresh the page

### Can't see "Receive Delivery" button
- Make sure delivery is marked as "delivered"
- Make sure payment is "completed"
- Make sure you're logged in as Branch Manager or Inventory Staff

### Inventory still not updating
- Make sure you clicked "Receive Delivery & Update Inventory" button
- Check logs in `writable/logs/`
- Run diagnostics: `/deliveries/{id}/diagnostics`

## Files Modified

1. `app/Controllers/DeliveryController.php`
   - Modified `updateStatus()` - Creates payment transaction when marked as delivered
   - Modified `receive()` - Checks payment status before allowing receive
   - Modified `processPayPalPayment()` - Notifies branch after payment

2. `app/Views/deliveries/view.php`
   - Updated progress messages
   - Added payment warning for branch
   - Updated receive form to show only after payment

3. `NEW_WORKFLOW_PAY_THEN_RECEIVE.md` - This documentation

## Summary

The new workflow ensures:
- ✅ Delivery arrives at branch
- ✅ Central Admin pays supplier via PayPal
- ✅ Branch receives delivery
- ✅ Inventory automatically updates

This gives you better financial control and a clear audit trail!
