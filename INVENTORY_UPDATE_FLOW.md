# Inventory Update Flow - Visual Guide

## Complete Delivery Receive Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                    USER RECEIVES DELIVERY                        │
│  (Branch Manager / Inventory Staff / Central Admin)             │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 1: Form Submission                                         │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  POST /deliveries/{id}/receive                                   │
│                                                                   │
│  Data Sent:                                                       │
│  • products[] = [1, 2, 3]                                        │
│  • quantities[] = [10, 20, 15]                                   │
│  • batch_numbers[] = ["", "", ""]                                │
│  • expiry_dates[] = ["", "", ""]                                 │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 2: Validation & Logging                                    │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  ✓ Check user is logged in                                       │
│  ✓ Check user has permission                                     │
│  ✓ Validate delivery exists                                      │
│  ✓ Validate form data not empty                                  │
│  ✓ Log all incoming data                                         │
│                                                                   │
│  📝 LOG: "Receive Delivery - Products: [1,2,3]"                 │
│  📝 LOG: "Receive Delivery - Quantities: [10,20,15]"            │
│  📝 LOG: "Receive Delivery - Branch ID: 1"                      │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 3: Start Database Transaction                              │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  $db->transStart();                                              │
│                                                                   │
│  🔒 All operations now atomic (all-or-nothing)                   │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 4: Process Each Product (Loop)                             │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  For each product in products[]:                                 │
│                                                                   │
│  4a. Get Product & Quantity                                      │
│      📝 LOG: "Processing Product ID: 1, Quantity: 10"           │
│                                                                   │
│  4b. Check Current Inventory                                     │
│      Query: SELECT * FROM inventory                              │
│             WHERE branch_id = 1 AND product_id = 1               │
│      📝 LOG: "Inventory found: Yes, Previous Quantity: 50"      │
│                                                                   │
│  4c. Calculate New Quantity                                      │
│      If inventory exists: newQty = 50 + 10 = 60                 │
│      If not exists: newQty = 10                                  │
│      📝 LOG: "Updating existing inventory - New Quantity: 60"   │
│                                                                   │
│  4d. Update/Insert Inventory                                     │
│      InventoryModel::updateQuantity(1, 1, 60, userId)           │
│      📝 LOG: "InventoryModel::updateQuantity called..."         │
│      📝 LOG: "Update result: Success"                           │
│                                                                   │
│  4e. Record Inventory History                                    │
│      INSERT INTO inventory_history (...)                         │
│      • transaction_type = 'delivery_received'                    │
│      • quantity_added = 10                                       │
│      • previous_quantity = 50                                    │
│      • new_quantity = 60                                         │
│                                                                   │
│  4f. Add Inventory Items (if batch/expiry provided)             │
│      INSERT INTO inventory_items (...)                           │
│                                                                   │
│  4g. Update PO Item Received Quantity                            │
│      UPDATE purchase_order_items                                 │
│      SET quantity_received = quantity_received + 10              │
│                                                                   │
│  Repeat for all products...                                      │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 5: Update Delivery Status                                  │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  UPDATE deliveries SET                                           │
│    status = 'delivered',                                         │
│    delivery_date = NOW(),                                        │
│    received_by = userId,                                         │
│    received_at = NOW()                                           │
│  WHERE id = deliveryId                                           │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 6: Update Purchase Order Status                            │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  Check if all items received:                                    │
│  • If YES: status = 'completed'                                  │
│  • If NO: status = 'partial'                                     │
│                                                                   │
│  UPDATE purchase_orders SET status = 'completed'                 │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 7: Create Payment Transaction                              │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  INSERT INTO payment_transactions (                              │
│    transaction_number = 'TXN-...',                               │
│    purchase_order_id = poId,                                     │
│    delivery_id = deliveryId,                                     │
│    payment_method = 'paypal',                                    │
│    amount = totalAmount,                                         │
│    status = 'pending'                                            │
│  )                                                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 8: Verify Inventory Updates                                │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  For each product:                                               │
│    Query inventory to confirm update                             │
│    📝 LOG: "Verified inventory for Product ID 1: Qty = 60"     │
│    inventoryUpdateCount++                                        │
│                                                                   │
│  📝 LOG: "Delivery 123 received: 3 inventory records updated"   │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 9: Complete Transaction                                    │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  $db->transComplete();                                           │
│                                                                   │
│  if ($db->transStatus() === false) {                             │
│    ❌ ROLLBACK - All changes reverted                            │
│    Show error message                                            │
│  } else {                                                         │
│    ✅ COMMIT - All changes saved                                 │
│  }                                                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 10: Send Notifications                                     │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  🔔 Central Admin: "Payment Required - Process PayPal"          │
│  🔔 Branch Manager: "Delivery received successfully"             │
│  🔔 Supplier: "Delivery confirmed at branch"                     │
│  🔔 Logistics: "Delivery completed"                              │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│  STEP 11: Show Success Message                                   │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  ✅ "Delivery received successfully.                             │
│      3 product(s) added to inventory."                           │
│                                                                   │
│  Redirect to: /deliveries                                        │
└─────────────────────────────────────────────────────────────────┘
```

## Database Changes Summary

### Tables Updated:

1. **inventory**
   - Quantity increased for each product
   - Available quantity recalculated
   - Last updated timestamp set

2. **inventory_history**
   - New record for each product received
   - Links to delivery and PO
   - Records quantity changes

3. **inventory_items** (if batch/expiry provided)
   - New record for perishable tracking

4. **deliveries**
   - Status changed to 'delivered'
   - Received by and timestamp set

5. **purchase_orders**
   - Status changed to 'completed' or 'partial'

6. **purchase_order_items**
   - Quantity received updated

7. **payment_transactions**
   - New pending payment record created

## Logging Points

Throughout the process, logs are written at these key points:

```
📝 Receive Delivery - Products: [...]
📝 Receive Delivery - Quantities: [...]
📝 Receive Delivery - Branch ID: X
📝 Processing Product ID: X, Quantity: Y
📝 Inventory found: Yes/No, Previous Quantity: Z
📝 Updating existing inventory - New Quantity: Z
📝 InventoryModel::updateQuantity called - Branch: X, Product: Y, Quantity: Z
📝 Updating existing inventory ID: X
📝 Update result: Success/Failed
📝 Inventory update completed for Product ID: X
📝 Verified inventory for Product ID X: Quantity = Z
📝 Delivery X received: Y inventory records updated
```

## Error Handling

At each step, errors are caught and logged:

```
❌ No products or quantities provided
❌ Delivery not found
❌ Unauthorized to receive deliveries
❌ Failed to update inventory: [error details]
❌ Failed to insert inventory: [error details]
❌ Transaction failed
❌ Inventory verification failed for Product ID X
```

## Success Indicators

When everything works correctly:

✅ All log entries show "Success"
✅ Transaction completes without rollback
✅ Verification confirms all products updated
✅ Success message shows correct product count
✅ Inventory page shows increased quantities
✅ Inventory history shows new entries
✅ Central Admin receives payment notification

## Diagnostics Endpoint Output

Access `/deliveries/{id}/diagnostics` to see:

```json
{
  "delivery": { ... },
  "purchase_order": { ... },
  "po_items": [ ... ],
  "inventory_status": [
    {
      "product_id": 1,
      "product_name": "Product A",
      "ordered_quantity": 10,
      "received_quantity": 10,
      "inventory_exists": true,
      "inventory_quantity": 60,
      "inventory_id": 5
    }
  ],
  "inventory_history": [ ... ],
  "payment_transaction": { ... }
}
```

This shows the complete state of the delivery and confirms if inventory was updated.
