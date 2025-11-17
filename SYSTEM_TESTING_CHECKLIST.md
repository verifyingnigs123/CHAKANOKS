# SCMS System Testing Checklist
## Verify All Features Are Working

---

## ✅ Product Creation Test

### Test Case 1: Create Product Successfully
1. Go to Products → Add Product
2. Fill in:
   - Name: `Test Product`
   - SKU: `TEST-001` (unique)
   - Barcode: `1234567890123` (optional)
   - Category: `Test`
   - Min Stock: `10`
   - Cost Price: `100`
   - Selling Price: `150`
3. Click "Create Product"
4. **Expected:** Success message, redirected to products list, product appears

### Test Case 2: Duplicate SKU
1. Try to create product with existing SKU
2. **Expected:** Error message "SKU already exists"

### Test Case 3: Duplicate Barcode
1. Try to create product with existing barcode
2. **Expected:** Error message "Barcode already exists"

### Test Case 4: Missing Required Fields
1. Try to create product without name or SKU
2. **Expected:** Validation error, form shows error message

---

## ✅ Inventory Management Test

### Test Case 5: Update Inventory
1. Go to Inventory
2. Click "Update" on a product
3. Select action: "Set Quantity"
4. Enter quantity: `50`
5. Click "Update"
6. **Expected:** Quantity updated, success message

### Test Case 6: Barcode Scan
1. Go to Inventory
2. Click "Scan Barcode"
3. Enter barcode of existing product
4. Enter quantity: `10`
5. Click "Scan & Update"
6. **Expected:** Product found, inventory updated

### Test Case 7: Low Stock Alert
1. Update product quantity below min_stock_level
2. Go to Inventory → Alerts
3. **Expected:** Low stock alert appears

---

## ✅ Purchase Request Workflow Test

### Test Case 8: Create Purchase Request
1. Go to Purchase Requests → Create Request
2. Select branch
3. Add products with quantities
4. Click "Submit Request"
5. **Expected:** Request created, request number generated

### Test Case 9: Approve Request
1. As Central Admin, go to Purchase Requests
2. Find pending request
3. Click "Approve"
4. **Expected:** Status changes to "approved"

### Test Case 10: Reject Request
1. Find pending request
2. Click "Reject"
3. Enter rejection reason
4. **Expected:** Status changes to "rejected"

---

## ✅ Purchase Order Test

### Test Case 11: Create PO from Request
1. Go to Purchase Orders → Create
2. Find approved request
3. Click "Create PO"
4. Select supplier
5. Review prices
6. Click "Create Purchase Order"
7. **Expected:** PO created, linked to request

### Test Case 12: Send PO
1. View PO (status: draft)
2. Click "Send to Supplier"
3. **Expected:** Status changes to "sent"

---

## ✅ Delivery Management Test

### Test Case 13: Schedule Delivery
1. Go to Deliveries → Schedule Delivery
2. Select PO
3. Enter delivery details
4. Click "Schedule Delivery"
5. **Expected:** Delivery created, status: "scheduled"

### Test Case 14: Update Delivery Status
1. View delivery
2. Update status to "in_transit"
3. **Expected:** Status updated

### Test Case 15: Receive Delivery
1. View delivery (status: in_transit)
2. Enter received quantities
3. Enter batch/expiry if applicable
4. Click "Receive Delivery"
5. **Expected:** 
   - Delivery status: "delivered"
   - Inventory quantities increased
   - PO status updated

---

## ✅ Dashboard & Reports Test

### Test Case 16: Dashboard Statistics
1. Go to Dashboard
2. **Expected:** All stat cards show numbers

### Test Case 17: Branch Inventory Report
1. View Dashboard (as Central Admin)
2. Scroll to "Branch Inventory Summary"
3. **Expected:** Table shows all branches with totals

### Test Case 18: Supplier Performance Report
1. View Dashboard (as Central Admin)
2. Scroll to "Supplier Performance"
3. **Expected:** Table shows suppliers with metrics

---

## ✅ Integration Test

### Test Case 19: Complete Workflow
1. Create product ✅
2. Add inventory ✅
3. Create purchase request ✅
4. Approve request ✅
5. Create PO ✅
6. Send PO ✅
7. Schedule delivery ✅
8. Receive delivery ✅
9. **Expected:** Inventory automatically updated

---

## 🐛 Common Issues & Fixes

### Issue: "Product creation failed"
**Fix:**
- Check SKU is unique
- Check barcode is unique (if provided)
- Check all required fields filled
- Check database connection

### Issue: "Inventory not updating"
**Fix:**
- Check delivery status is correct
- Check quantities are positive
- Check product exists
- Check branch_id is set

### Issue: "Forms not submitting"
**Fix:**
- Check if logged in
- Check browser console for errors
- Check CSRF token is present
- Check network request

### Issue: "Dashboard shows no data"
**Fix:**
- Create some test data first
- Check database has records
- Refresh page
- Check user role has access

---

## 📊 System Status

After testing, mark what works:

- [ ] Product Creation
- [ ] Product Editing
- [ ] Product Deletion
- [ ] Inventory Updates
- [ ] Barcode Scanning
- [ ] Stock Alerts
- [ ] Purchase Requests
- [ ] Request Approval
- [ ] Purchase Orders
- [ ] PO Sending
- [ ] Delivery Scheduling
- [ ] Delivery Receiving
- [ ] Inventory Auto-Update
- [ ] Dashboard Reports
- [ ] Branch Summary
- [ ] Supplier Performance

---

**All features should be working now! 🎉**

