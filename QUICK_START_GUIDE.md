# SCMS Quick Start Guide
## How to Use the System - Step by Step

---

## 🚀 Initial Setup

### Step 1: Create Admin User
Run this SQL in your database (phpMyAdmin or MySQL):

```sql
INSERT INTO users (username, password, full_name, email, role, status, created_at) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin@scms.com', 'system_admin', 'active', NOW());
```

**Password for all test users:** `password`

---

## 📝 Complete Workflow Demonstration

### **STEP 1: Login**
1. Go to: `http://localhost/CHAKANOKS/login`
2. Email: `admin@scms.com`
3. Password: `password`
4. Click **Login**

---

### **STEP 2: Create Branches**
1. Click **"Branches"** in sidebar
2. Click **"Add Branch"**
3. Fill in:
   - Branch Name: `Main Branch`
   - Branch Code: `MB001`
   - Address: `123 Main St`
   - City: `Manila`
   - Phone: `09123456789`
   - Status: `Active`
4. Click **"Create Branch"**
5. Create 2-3 more branches

---

### **STEP 3: Create Suppliers**
1. Click **"Suppliers"** in sidebar
2. Click **"Add Supplier"**
3. Fill in:
   - Supplier Name: `ABC Supplies`
   - Supplier Code: `SUP001`
   - Contact Person: `John Doe`
   - Email: `john@abc.com`
   - Phone: `09123456789`
   - Payment Terms: `Net 30`
   - Status: `Active`
4. Click **"Create Supplier"**
5. Create 2-3 more suppliers

---

### **STEP 4: Create Products** ⭐
1. Click **"Products"** in sidebar
2. Click **"Add Product"** button
3. Fill in the form:
   - **Product Name***: `Burger`
   - **SKU***: `BURGER-001` (must be unique)
   - **Barcode**: `1234567890123` (optional, must be unique if provided)
   - **Category**: `Food`
   - **Description**: `Delicious burger`
   - **Unit**: Select from dropdown (e.g., `Pack`)
   - **Min Stock Level***: `10`
   - **Max Stock Level**: `100`
   - **Cost Price**: `39.00`
   - **Selling Price**: `50.00`
   - **Status**: `Active`
   - **Is Perishable**: ☑ (check if applicable)
   - **Shelf Life (Days)**: `7` (if perishable)
4. Click **"Create Product"**
5. ✅ Product will be saved and you'll see success message
6. Create 5-10 products for testing

**Important Notes:**
- SKU must be unique
- Barcode must be unique (if provided)
- All fields with * are required

---

### **STEP 5: Add Initial Inventory**
1. Click **"Inventory"** in sidebar
2. For each product, click **"Update"** button
3. Select action: **"Set Quantity"**
4. Enter quantity: `100`
5. Click **"Update"**
6. Repeat for all products

**Or use Barcode Scanner:**
1. Click **"Scan Barcode"** button
2. Enter barcode number
3. Enter quantity
4. Click **"Scan & Update"**

---

### **STEP 6: Create Purchase Request**
1. Click **"Purchase Requests"** in sidebar
2. Click **"Create Request"** button
3. Fill in:
   - Branch: Select a branch
   - Priority: `Normal` or `High`
   - Notes: `Need to restock`
4. Add Products:
   - Click **"Add Product"** to add more rows
   - Select Product from dropdown
   - Enter Quantity needed
5. Click **"Submit Request"**
6. Note the Request Number

---

### **STEP 7: Approve Purchase Request**
1. As Central Admin, go to **"Purchase Requests"**
2. Find the pending request
3. Click **"View"** to see details
4. Click **"Approve"** button
   - Or click **"Reject"** and provide reason

---

### **STEP 8: Create Purchase Order**
1. Click **"Purchase Orders"** in sidebar
2. Click **"Create Purchase Order"**
3. Find approved request and click **"Create PO"**
4. Fill in:
   - Supplier: Select a supplier
   - Expected Delivery Date: Select date
   - Review/Adjust unit prices
   - Notes: `Please deliver on time`
5. Click **"Create Purchase Order"**
6. Note the PO Number

---

### **STEP 9: Send Purchase Order**
1. In Purchase Orders list, click **"View"** on the PO
2. Click **"Send to Supplier"** button
3. Status changes to "Sent"

---

### **STEP 10: Schedule Delivery**
1. Click **"Deliveries"** in sidebar
2. Click **"Schedule Delivery"** button
3. Fill in:
   - Purchase Order: Select the PO
   - Scheduled Date: Select date
   - Driver Name: `Juan Dela Cruz`
   - Vehicle Number: `ABC-1234`
4. Click **"Schedule Delivery"**

---

### **STEP 11: Update Delivery Status**
1. In Deliveries list, click **"View"**
2. Click **"Update Status"**
3. Select: **"In Transit"**
4. Click **"Update Status"**

---

### **STEP 12: Receive Delivery** ⭐
1. Click **"View"** on the delivery
2. Scroll to **"Receive Delivery"** section
3. For each item:
   - Verify Received Quantity
   - Enter Batch Number (if applicable)
   - Enter Expiry Date (if perishable)
4. Click **"Receive Delivery & Update Inventory"**
5. ✅ **Inventory is automatically updated!**

---

### **STEP 13: View Dashboard Reports**
1. Click **"Dashboard"** in sidebar
2. View:
   - **Statistics Cards**: Total branches, products, suppliers, etc.
   - **Branch Inventory Summary**: Table with branch inventory data
   - **Supplier Performance**: Table with supplier metrics
   - **Recent Activities**: Recent orders and deliveries

---

## 🔍 Key Features to Test

### ✅ Product Management
- Create product → Should save successfully
- Edit product → Should update
- Delete product → Should remove
- View products list → Should display all

### ✅ Inventory Management
- Update inventory → Should change quantity
- Scan barcode → Should find product and update
- View alerts → Should show low stock items

### ✅ Purchase Workflow
- Create request → Should generate request number
- Approve request → Should change status
- Create PO → Should link to request
- Send PO → Should change to "sent" status

### ✅ Delivery Management
- Schedule delivery → Should create delivery record
- Update status → Should change delivery status
- Receive delivery → Should update inventory automatically

### ✅ Reports
- Dashboard → Should show real-time data
- Branch summary → Should calculate totals
- Supplier performance → Should show metrics

---

## 🐛 Troubleshooting

### Product Creation Not Working?
1. **Check SKU uniqueness** - SKU must be unique
2. **Check Barcode uniqueness** - If provided, must be unique
3. **Check required fields** - Name, SKU, Min Stock Level are required
4. **Check database connection** - Make sure database is accessible
5. **Check for errors** - Look at error message displayed

### Forms Not Submitting?
1. **Check if logged in** - Must be authenticated
2. **Check browser console** - Look for JavaScript errors
3. **Check network tab** - See if request is being sent
4. **Check server logs** - Look in `writable/logs/` folder

### Inventory Not Updating?
1. **Check delivery status** - Must be "in_transit" or "scheduled"
2. **Check quantities** - Must be positive numbers
3. **Check product exists** - Product must be in database
4. **Check branch assignment** - User must have branch_id

---

## 📊 System Status Check

### Verify Everything Works:
- [ ] Can login
- [ ] Can create branches
- [ ] Can create suppliers
- [ ] Can create products ✅
- [ ] Can view products list
- [ ] Can edit products
- [ ] Can add inventory
- [ ] Can create purchase requests
- [ ] Can approve requests
- [ ] Can create purchase orders
- [ ] Can schedule deliveries
- [ ] Can receive deliveries
- [ ] Dashboard shows data
- [ ] Reports are working

---

## 🎯 Quick Test Scenario

**Complete End-to-End Test:**
1. Create 1 branch
2. Create 1 supplier
3. Create 3 products
4. Add inventory (100 each)
5. Create purchase request (order 50 of each)
6. Approve request
7. Create PO from request
8. Send PO
9. Schedule delivery
10. Update to "in_transit"
11. Receive delivery
12. ✅ Check inventory increased by 50

---

## 💡 Tips

1. **Use unique SKUs** - Each product needs unique SKU
2. **Fill required fields** - Fields with * are mandatory
3. **Check status** - Many features depend on status
4. **Use dashboard** - Great overview of system
5. **Check alerts** - Monitor low stock items

---

**The system is now fully functional! 🎉**

Try creating a product now - it should work perfectly!

