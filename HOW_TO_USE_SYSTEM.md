# How to Use the SCMS System
## Complete Step-by-Step Guide

---

## 🎯 Quick Start (5 Minutes)

### 1. Setup Test Data
Run the SQL file `setup_test_data.sql` in phpMyAdmin to create:
- Test users (password: `password`)
- Sample branches
- Sample suppliers
- Sample products

### 2. Login
- URL: `http://localhost/CHAKANOKS/login`
- Email: `admin@scms.com` or `central@scms.com`
- Password: `password`

### 3. Create a Product (Your Current Task)
1. Click **"Products"** in sidebar
2. Click **"Add Product"** button
3. Fill in the form:
   - **Product Name***: `Burger` (or any name)
   - **SKU***: `BURGER-001` (must be unique - change the number if exists)
   - **Barcode**: `1234567890123` (optional)
   - **Category**: `Food` or `Snacks`
   - **Description**: `Delicious burger`
   - **Unit**: Select `Pack` (or any unit)
   - **Min Stock Level***: `10`
   - **Max Stock Level**: `100`
   - **Cost Price**: `39.00`
   - **Selling Price**: `50.00`
   - **Status**: `Active`
   - **Is Perishable**: ☑ (check if it expires)
   - **Shelf Life (Days)**: `7` (if perishable)
4. Click **"Create Product"**
5. ✅ **Success!** You'll see the product in the list

---

## 📋 Complete System Workflow

### **Phase 1: Setup (One Time)**
1. **Create Branches** → Products → Branches → Add Branch
2. **Create Suppliers** → Suppliers → Add Supplier
3. **Create Products** → Products → Add Product ✅ (You're here!)

### **Phase 2: Inventory Management**
4. **Add Inventory** → Inventory → Update quantities
5. **Monitor Alerts** → Inventory → Alerts (low stock warnings)

### **Phase 3: Purchasing**
6. **Create Purchase Request** → Purchase Requests → Create Request
7. **Approve Request** → Purchase Requests → Approve (as admin)
8. **Create Purchase Order** → Purchase Orders → Create from approved request
9. **Send PO to Supplier** → Purchase Orders → View → Send

### **Phase 4: Delivery**
10. **Schedule Delivery** → Deliveries → Schedule Delivery
11. **Update Status** → Deliveries → View → Update to "In Transit"
12. **Receive Delivery** → Deliveries → View → Receive & Update Inventory
13. ✅ **Inventory automatically updated!**

### **Phase 5: Reports**
14. **View Dashboard** → Dashboard → See all reports and statistics

---

## 🔧 How Each Feature Works

### **Product Creation** ⭐
- **Purpose**: Add products to the system
- **Required Fields**: Name, SKU, Min Stock Level
- **Unique Fields**: SKU (must be unique), Barcode (if provided, must be unique)
- **What Happens**: Product saved to database, can be used in inventory and purchase requests

### **Inventory Management**
- **Purpose**: Track stock levels per branch
- **Features**: 
  - Update quantities (add, subtract, set)
  - Barcode scanning
  - Low stock alerts
- **What Happens**: Real-time inventory tracking

### **Purchase Requests**
- **Purpose**: Request items from branches
- **Workflow**: Create → Pending → Approved/Rejected
- **What Happens**: Generates request number, tracks items needed

### **Purchase Orders**
- **Purpose**: Order from suppliers
- **Workflow**: Create from approved request → Send to supplier → Confirmed → Completed
- **What Happens**: Links to supplier, tracks order status

### **Deliveries**
- **Purpose**: Track incoming deliveries
- **Workflow**: Scheduled → In Transit → Delivered
- **What Happens**: When received, inventory automatically updates

### **Dashboard**
- **Purpose**: Overview of entire system
- **Shows**: Statistics, branch reports, supplier performance, recent activities
- **Updates**: Real-time data from database

---

## ✅ Testing Checklist

After creating a product, test:

1. **Product Created?**
   - Go to Products list
   - Should see your product

2. **Can Edit?**
   - Click Edit on product
   - Change something
   - Save
   - Should update

3. **Can Add to Inventory?**
   - Go to Inventory
   - Find your product
   - Click Update
   - Set quantity to 50
   - Should update

4. **Can Create Purchase Request?**
   - Go to Purchase Requests
   - Create request
   - Add your product
   - Should create successfully

---

## 🎬 Demonstration Flow

**For your presentation, follow this order:**

1. **Login** → Show authentication
2. **Dashboard** → Show overview
3. **Create Product** → Show product creation ✅
4. **View Products** → Show product list
5. **Add Inventory** → Show inventory management
6. **Create Purchase Request** → Show purchasing workflow
7. **Approve Request** → Show approval process
8. **Create PO** → Show supplier integration
9. **Schedule Delivery** → Show delivery management
10. **Receive Delivery** → Show automatic inventory update
11. **Dashboard Reports** → Show analytics

---

## 💡 Key Points to Remember

1. **SKU Must Be Unique** - Each product needs different SKU
2. **Barcode Must Be Unique** - If provided, must be different
3. **Required Fields** - Name, SKU, Min Stock Level are mandatory
4. **Status Matters** - Many features depend on status (pending, approved, etc.)
5. **Role-Based Access** - Different users see different features
6. **Real-Time Updates** - Dashboard and reports update automatically

---

## 🐛 If Something Doesn't Work

### Product Creation Fails?
1. Check SKU is unique
2. Check barcode is unique (if provided)
3. Check all required fields filled
4. Look at error message displayed

### Can't See Products?
1. Make sure you created them
2. Check you're logged in
3. Refresh the page
4. Check database has records

### Forms Not Submitting?
1. Check you're logged in
2. Check browser console for errors
3. Check network tab in browser
4. Try refreshing the page

---

## 📞 Quick Reference

**Login URLs:**
- Login: `/login`
- Dashboard: `/dashboard`

**Main Modules:**
- Products: `/products`
- Inventory: `/inventory`
- Purchase Requests: `/purchase-requests`
- Purchase Orders: `/purchase-orders`
- Deliveries: `/deliveries`
- Branches: `/branches`
- Suppliers: `/suppliers`

**Test Users:**
- Admin: `admin@scms.com` / `password`
- Central: `central@scms.com` / `password`
- Manager: `manager@scms.com` / `password`
- Staff: `staff@scms.com` / `password`

---

**The system is fully functional! Try creating a product now! 🚀**

