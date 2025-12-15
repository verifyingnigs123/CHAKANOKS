# Auto-Create Purchase Request from Low Stock - COMPLETE ✅

## Summary
Implemented automatic purchase request creation from low stock items on the dashboard. Branch managers can now create purchase requests for all low stock items with a single click.

## What Was Implemented

### 1. Enhanced Low Stock Alert Widget (Branch Manager Dashboard)
**File**: `app/Views/dashboard/index.php`

**New Features**:
- **"Create Request for All" Button** - Automatically creates purchase requests for all low stock items
- **Item Count Display** - Shows total number of low stock items in header
- **Informational Banner** - Explains the feature to users
- **Suggested Quantity** - Shows how many units are needed for each item
- **Grouped Display** - Shows up to 6 items with count of remaining items

**How It Works**:
1. Branch Manager sees low stock alert widget on dashboard
2. Widget shows all products that are below minimum stock level
3. Click "Create Request for All" button
4. System automatically:
   - Groups items by supplier
   - Creates separate purchase requests for each supplier
   - Calculates quantity needed (min level - current + 10 buffer)
   - Sends notifications to Central Admin
   - Redirects to purchase requests page

### 2. New Controller Method
**File**: `app/Controllers/PurchaseRequestController.php`

**Method**: `createFromLowStock()`

**Functionality**:
- Fetches all low stock items for the branch
- Groups items by supplier (creates separate requests per supplier)
- Calculates quantity needed: `max(10, min_level - current_qty + 10)`
- Creates purchase request with status "pending"
- Adds all low stock items to the request
- Logs activity
- Sends notifications to Central Admin
- Returns success message with count of requests created

**Security**:
- Only Branch Manager and Central Admin can use this feature
- Validates user authentication
- Uses database transactions for data integrity
- CSRF protection via form token

### 3. New Route
**File**: `app/Config/Routes.php`

**Route**: `POST /purchase-requests/create-from-low-stock`
**Controller**: `PurchaseRequestController::createFromLowStock`
**Auth**: Required (filter: 'auth')

## User Workflow

### For Branch Manager:
1. **Login** and view dashboard
2. **See Low Stock Alert Widget** with list of low stock items
3. **Review Items** - See which products need restocking
4. **Click "Create Request for All"** button
5. **System Creates Requests**:
   - Groups items by supplier
   - Creates separate purchase request for each supplier
   - Calculates optimal quantity for each item
   - Sends notifications to Central Admin
6. **Redirected** to Purchase Requests page
7. **See Success Message**: "X purchase requests created successfully (grouped by supplier)"
8. **Wait for Approval** from Central Admin

### For Central Admin:
1. **Receive Notification** about new purchase request
2. **Review Request** - See it was auto-generated from low stock
3. **Approve or Reject** as normal
4. **Create Purchase Order** if approved

## Technical Details

### Quantity Calculation Logic:
```php
$quantityNeeded = max(10, $item['min_stock_level'] - $item['quantity'] + 10);
```

**Example**:
- Current Stock: 5
- Min Level: 20
- Calculation: max(10, 20 - 5 + 10) = max(10, 25) = 25 units
- Result: Request 25 units to restock

### Supplier Grouping:
- Items are grouped by supplier_id
- Each supplier gets a separate purchase request
- This allows different suppliers to fulfill their own orders
- Example: If 10 items are low stock from 3 suppliers, creates 3 purchase requests

### Request Number Format:
```
PR-YYYYMMDD-XXXXXX
```
Example: `PR-20251216-A3F9B2`

### Database Transaction:
- Uses transactions to ensure data integrity
- If any part fails, entire request is rolled back
- Prevents partial data corruption

## Benefits

### For Branch Managers:
1. **Time Saving** - No need to manually create requests for each item
2. **Accuracy** - System calculates optimal quantities automatically
3. **Convenience** - One click creates all needed requests
4. **Visibility** - See all low stock items at a glance
5. **Efficiency** - Faster restocking process

### For Central Admin:
1. **Clear Context** - Knows request was auto-generated from low stock
2. **Better Planning** - Can see which branches need urgent restocking
3. **Organized Requests** - Grouped by supplier for easier processing
4. **Notifications** - Immediately notified of new requests

### For Operations:
1. **Prevents Stockouts** - Proactive restocking
2. **Optimized Ordering** - Calculates buffer quantities
3. **Supplier Management** - Separate requests per supplier
4. **Audit Trail** - Activity logs track auto-generated requests
5. **Workflow Integration** - Fits into existing approval process

## Testing Checklist

### Branch Manager Dashboard:
- [ ] Login as Branch Manager
- [ ] Verify low stock alert widget appears when items are low
- [ ] Verify "Create Request for All" button is visible
- [ ] Verify item count is displayed correctly
- [ ] Verify suggested quantities are calculated correctly
- [ ] Click "Create Request for All" button
- [ ] Verify redirected to purchase requests page
- [ ] Verify success message appears
- [ ] Verify purchase requests were created

### Purchase Request Creation:
- [ ] Verify requests are grouped by supplier
- [ ] Verify each request has correct items
- [ ] Verify quantities are calculated correctly
- [ ] Verify request status is "pending"
- [ ] Verify request notes say "Auto-generated from low stock alert"
- [ ] Verify request number format is correct
- [ ] Verify branch_id is correct
- [ ] Verify supplier_id is correct

### Notifications:
- [ ] Login as Central Admin
- [ ] Verify notification received for new request
- [ ] Verify notification links to correct request
- [ ] Verify notification message is clear

### Activity Logs:
- [ ] Verify activity log entry created
- [ ] Verify log shows "Created purchase request from low stock alert"
- [ ] Verify user_id is correct
- [ ] Verify timestamp is correct

### Edge Cases:
- [ ] Test with no low stock items (should show error)
- [ ] Test with items from multiple suppliers (should create multiple requests)
- [ ] Test with items from one supplier (should create one request)
- [ ] Test with items that have no supplier (should show error)
- [ ] Test as unauthorized user (should show error)

## Files Modified

1. **app/Views/dashboard/index.php**
   - Enhanced low stock alert widget for Branch Manager
   - Added "Create Request for All" button
   - Added informational banner
   - Improved item display with suggested quantities
   - Added item count display

2. **app/Controllers/PurchaseRequestController.php**
   - Added `createFromLowStock()` method
   - Implements supplier grouping logic
   - Calculates optimal quantities
   - Creates purchase requests with items
   - Sends notifications
   - Logs activities

3. **app/Config/Routes.php**
   - Added route: `POST /purchase-requests/create-from-low-stock`
   - Protected with auth filter

## Example Scenario

**Scenario**: Main Branch has 8 low stock items

**Low Stock Items**:
- 5 items from Chicken Supplier
- 3 items from Meat Supplier

**Action**: Branch Manager clicks "Create Request for All"

**Result**:
- 2 purchase requests created:
  1. PR-20251216-A3F9B2 (Chicken Supplier, 5 items)
  2. PR-20251216-B7C4D1 (Meat Supplier, 3 items)
- Central Admin receives 2 notifications
- Branch Manager sees: "2 purchase requests created successfully (grouped by supplier)"
- Both requests have status "pending" awaiting approval

## Conclusion

✅ **Feature Complete!**

Branch managers can now:
- See all low stock items on their dashboard
- Create purchase requests for all low stock items with one click
- System automatically groups by supplier and calculates quantities
- Requests are sent to Central Admin for approval
- Entire workflow is automated and efficient

This feature significantly improves the restocking process and helps prevent stockouts by making it easy for branch managers to request inventory replenishment.
