# System Roles and Permissions Guide

## Overview
This document outlines all user roles in the Supply Chain Management System (SCMS), their responsibilities, and the sidebar pages they can access.

---

## All System Roles

The system has **7 user roles** defined:

1. **system_admin** - System Administrator
2. **central_admin** - Central Office Administrator  
3. **branch_manager** - Branch Manager
4. **inventory_staff** - Inventory Staff
5. **supplier** - Supplier Account
6. **logistics_coordinator** - Logistics Coordinator
7. **franchise_manager** - Franchise Manager

---

## 1. System Administrator (`system_admin`)

### **Role Description:**
Full system access with complete administrative control over all modules and settings.

### **Responsibilities:**
- Manage all users and their roles
- Manage branches and locations
- View and manage inventory across all branches
- Manage suppliers and contracts
- Approve/reject purchase requests
- Create and manage purchase orders
- View system-wide reports and analytics
- Access activity logs
- Configure system settings

### **Sidebar Pages Access:**
- **Dashboard** - System-wide overview with statistics
- **User Management** (`/admin/users`) - Create, edit, and manage all users
- **Branches** (`/admin/branches`) - Manage all branch locations
- **Inventory Overview** (`/inventory`) - View inventory across all branches
- **Suppliers** (`/suppliers`) - Manage supplier database
- **Purchase Requests** (`/purchase-requests`) - View and approve/reject all purchase requests
- **Purchase Orders** (`/purchase-orders`) - Create and manage purchase orders
- **Contracts** (`/suppliers/contracts`) - Manage supplier contracts
- **Reports & Dashboards** (`/reports`) - System-wide analytics and reports
- **Activity Logs** (`/logs`) - View all user activity logs
- **System Settings** (`/settings`) - Configure system parameters

---

## 2. Central Admin (`central_admin`)

### **Role Description:**
Central office administrator with similar permissions to system admin but focused on operational oversight rather than system configuration.

### **Responsibilities:**
- View all branches and their inventory
- Approve/reject purchase requests from branches
- Manage products, branches, and suppliers
- View consolidated reports
- Monitor all stock alerts
- Create and manage purchase orders
- Access activity logs

### **Sidebar Pages Access:**
⚠️ **Note:** Currently, `central_admin` does not have a dedicated sidebar menu in the code. It shares the same dashboard access as `system_admin` but may need a custom sidebar menu. Based on the codebase, they have similar permissions but may have limited access to:
- **Dashboard** - System-wide overview
- **Inventory Overview** - View all branches
- **Purchase Requests** - Approve/reject requests
- **Purchase Orders** - Manage orders
- **Reports** - View analytics
- **Activity Logs** - View logs

**Recommendation:** Consider adding a dedicated sidebar menu for `central_admin` with access to operational modules but excluding system settings.

---

## 3. Branch Manager (`branch_manager`)

### **Role Description:**
Manages operations for a specific branch location, including inventory, orders, and transfers.

### **Responsibilities:**
- View branch-specific inventory
- Manage orders for their branch
- Create purchase requests for branch needs
- Approve/reject inter-branch transfers
- Monitor low stock items for their branch
- View branch-specific reports

### **Sidebar Pages Access:**
- **Dashboard** - Branch-specific overview
- **Orders** (`/orders`) - Manage branch orders
- **Inventory** (`/inventory`) - View branch inventory
- **Create Purchase Request** (`/purchase-requests/create`) - Request supplies for branch
- **Transfers** (`/transfers`) - Manage inter-branch transfers
- **Branch Reports** (`/reports/branch`) - View branch-specific reports

---

## 4. Inventory Staff (`inventory_staff`)

### **Role Description:**
Handles day-to-day inventory operations including stock updates, receiving deliveries, and monitoring stock levels.

### **Responsibilities:**
- Update inventory levels
- Scan barcodes for quick stock updates
- View and acknowledge stock alerts
- Receive deliveries and update inventory
- Request supplies from suppliers

### **Sidebar Pages Access:**
- **Dashboard** - Inventory overview
- **Stock Overview** (`/inventory/overview`) - View current stock levels
- **Update Stock** (`/inventory/update`) - Update inventory quantities
- **Request Supply** (`/suppliers/request`) - Request supplies from suppliers

---

## 5. Supplier (`supplier`)

### **Role Description:**
External supplier account that can view and manage purchase orders, deliveries, and invoices related to their supplies.

### **Responsibilities:**
- View purchase orders assigned to them
- Manage delivery status
- Create and manage invoices
- Track order fulfillment

### **Sidebar Pages Access:**
- **Dashboard** - Supplier portal overview
- **Purchase Orders** (`/supplier/orders`) - View and manage purchase orders
- **Deliveries** (`/supplier/deliveries`) - Track delivery status
- **Invoices** (`/supplier/invoices`) - Create and manage invoices

---

## 6. Logistics Coordinator (`logistics_coordinator`)

### **Role Description:**
Manages delivery operations, route planning, fleet management, and driver coordination.

### **Responsibilities:**
- Track active deliveries
- Plan delivery routes
- Manage fleet vehicles
- Coordinate drivers
- Monitor delivery status

### **Sidebar Pages Access:**
- **Dashboard** - Logistics overview
- **Active Deliveries** (`/logistics/deliveries`) - Track all active deliveries
- **Route Planning** (`/logistics/routes`) - Plan and optimize delivery routes
- **Fleet** (`/logistics/fleet`) - Manage delivery vehicles
- **Drivers** (`/logistics/drivers`) - Manage driver information

---

## 7. Franchise Manager (`franchise_manager`)

### **Role Description:**
Manages franchise operations including applications, supply allocation, and royalty tracking.

### **Responsibilities:**
- Process franchise applications
- Allocate supplies to franchises
- Track royalty payments
- Manage franchise relationships

### **Sidebar Pages Access:**
- **Dashboard** - Franchise operations overview
- **Applications** (`/franchise/applications`) - Process franchise applications
- **Supply Allocation** (`/franchise/supplies`) - Allocate supplies to franchises
- **Royalties** (`/franchise/payments`) - Track and manage royalty payments

---

## Role Comparison Summary

| Role | User Management | Branch Management | Inventory Access | Purchase Orders | Reports | System Settings |
|------|----------------|-------------------|------------------|-----------------|---------|-----------------|
| **system_admin** | ✅ Full | ✅ All Branches | ✅ All Branches | ✅ Full | ✅ Full | ✅ Full |
| **central_admin** | ❌ No | ✅ View All | ✅ All Branches | ✅ Full | ✅ Full | ❌ No |
| **branch_manager** | ❌ No | ❌ No | ✅ Own Branch | ✅ Create Requests | ✅ Branch Only | ❌ No |
| **inventory_staff** | ❌ No | ❌ No | ✅ Own Branch | ❌ No | ❌ No | ❌ No |
| **supplier** | ❌ No | ❌ No | ❌ No | ✅ View Own | ❌ No | ❌ No |
| **logistics_coordinator** | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No |
| **franchise_manager** | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No |

---

## Default Test Users

Based on the UserSeeder, default test users are:

1. **System Admin**
   - Username: `sysadmin`
   - Password: `admin123`
   - Email: `sysadmin@scms.com`

2. **Branch Manager**
   - Username: `branchmanager`
   - Password: `branch123`
   - Email: `branchmanager@scms.com`

3. **Inventory Staff**
   - Username: `inventory`
   - Password: `inventory123`
   - Email: `inventory@scms.com`

4. **Supplier**
   - Username: `supplier`
   - Password: `supplier123`
   - Email: `supplier@scms.com`

5. **Logistics Coordinator**
   - Username: `logistics`
   - Password: `logistics123`
   - Email: `logistics@scms.com`

6. **Franchise Manager**
   - Username: `franchise`
   - Password: `franchise123`
   - Email: `franchise@scms.com`

---

## Notes

- All roles require authentication to access the system
- Role-based access control is enforced at both the controller and view levels
- Activity logs track all user actions
- The `central_admin` role currently shares dashboard access with `system_admin` but may need a dedicated sidebar menu
- Branch-specific roles (`branch_manager`, `inventory_staff`) are typically assigned to a specific branch via `branch_id`

---

**Last Updated:** Based on current codebase analysis
**File Location:** `app/Views/design/sidebar.php` and `app/Database/Seeds/UserSeeder.php`

