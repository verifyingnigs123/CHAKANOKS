# Inventory Update Debugging Guide

## Issue
Delivery is marked as received but inventory is not updating.

## Changes Made

### 1. Enhanced Logging in DeliveryController::receive()
Added comprehensive debug logging to trace:
- Products and quantities received from form
- Branch ID being used
- Each product being processed
- Inventory lookup results
- Update/insert operations
- Final verification of inventory updates

### 2. Enhanced InventoryModel::updateQuantity()
Added:
- Debug logging for all operations
- Error logging when update/insert fails
- Return value to indicate success/failure
- Better null handling for reserved_quantity

### 3. Fixed Inventory Item Creation
- Added refresh of inventory record after creation
- Ensures inventory_id exists before creating inventory items
- Added logging for batch/expiry tracking

### 4. Added Verification Step
After receiving delivery, the system now:
- Verifies each inventory record was actually created/updated
- Logs the verification results
- Returns count of successful updates in success message

## How to Debug

### Step 1: Check Application Logs
Location: `writable/logs/log-YYYY-MM-DD.php`

Look for entries like:
```
DEBUG - Receive Delivery - Products: [...]
DEBUG - Receive Delivery - Quantities: [...]
DEBUG - Processing Product ID: X, Quantity: Y
DEBUG - InventoryModel::updateQuantity called - Branch: X, Product: Y, Quantity: Z
DEBUG - Updating existing inventory ID: X
DEBUG - Update result: Success
```

### Step 2: Run Test Script
```bash
php test_inventory_update.php
```

This will:
- Test basic inventory update functionality
- Show if there are database connection issues
- Reveal any foreign key constraint problems
- Display error messages if update fails

### Step 3: Check Database Directly
```sql
-- Check if inventory table exists
SHOW TABLES LIKE 'inventory';

-- Check inventory records
SELECT * FROM inventory WHERE branch_id = X AND product_id = Y;

-- Check if products exist
SELECT id, name, sku FROM products LIMIT 10;

-- Check if branches exist
SELECT id, name FROM branches LIMIT 10;

-- Check foreign key constraints
SHOW CREATE TABLE inventory;
```

### Step 4: Verify Form Submission
When receiving delivery, check browser console for:
- Form data being submitted
- Any JavaScript errors
- Network tab to see POST data

Expected POST data:
```
products[]: [1, 2, 3]
quantities[]: [10, 20, 15]
batch_numbers[]: ["", "", ""]
expiry_dates[]: ["", "", ""]
```

## Common Issues & Solutions

### Issue 1: Product ID is NULL or 0
**Symptom**: Logs show "Processing Product ID: 0" or NULL
**Cause**: Form not passing product_id correctly
**Solution**: Check `app/Views/deliveries/view.php` line 500 - ensure hidden input exists

### Issue 2: Foreign Key Constraint Failure
**Symptom**: Database error about foreign key constraint
**Cause**: Product ID doesn't exist in products table
**Solution**: 
```sql
-- Check if product exists
SELECT * FROM products WHERE id = X;
```

### Issue 3: Branch ID is NULL
**Symptom**: Logs show "Branch ID: " (empty)
**Cause**: Purchase order doesn't have branch_id set
**Solution**:
```sql
-- Check PO branch_id
SELECT id, po_number, branch_id FROM purchase_orders WHERE id = X;
```

### Issue 4: Unique Constraint Violation
**Symptom**: Error about duplicate key for branch_id + product_id
**Cause**: Trying to insert when record already exists (shouldn't happen with current code)
**Solution**: Code now checks for existing record first

### Issue 5: Permissions Issue
**Symptom**: Update returns false but no error message
**Cause**: Database user doesn't have INSERT/UPDATE permissions
**Solution**: Check database user permissions

## Testing Checklist

After making changes, test:

1. ✓ Create a Purchase Order
2. ✓ Schedule a Delivery
3. ✓ Mark as In Transit
4. ✓ Receive the Delivery
5. ✓ Check inventory page - quantities should increase
6. ✓ Check inventory history - should show delivery_received entry
7. ✓ Check logs - should show successful updates
8. ✓ Verify Central Admin gets payment notification

## Success Indicators

When working correctly, you should see:
- Success message: "Delivery received successfully. X product(s) added to inventory."
- Inventory page shows increased quantities
- Inventory history shows new entries
- Central Admin receives PayPal payment notification
- Logs show "Update result: Success" for each product

## Next Steps if Still Failing

1. Share the complete log file from `writable/logs/`
2. Run test script and share output
3. Share database schema for inventory table
4. Check if migrations have been run: `php spark migrate:status`
5. Verify database connection in `.env` file
