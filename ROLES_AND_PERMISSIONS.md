# System Roles and Permissions Guide

## Overview
This document outlines all user roles in the Supply Chain Management System (SCMS), their responsibilities, and the sidebar pages they can access.

---

## All System Roles

The system has **6 user roles** defined:

1. **central_admin** - Central Administrator (formerly System Administrator)
2. **branch_manager** - Branch Manager
3. **inventory_staff** - Inventory Staff
4. **supplier** - Supplier Account
5. **logistics_coordinator** - Logistics Coordinator
6. **franchise_manager** - Franchise Manager

---

## 1. Central Admin (`central_admin`)

### **Role Description:**
Full system access with complete administrative control over all modules and settings. This role combines the responsibilities of the former System Administrator and Central Office Administrator roles.

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
- Monitor all stock alerts
- Manage products, branches, and suppliers

### **Sidebar Pages Access:**
- **Dashboard** - System-wide overview with statistics
- **User Management** (`/users`) - Create, edit, and manage all users
- **Branches** (`/branches`) - Manage all branch locations
- **Inventory Overview** (`/inventory`) - View inventory across all branches
- **Suppliers** (`/suppliers`) - Manage supplier database
- **Purchase Requests** (`/purchase-requests`) - View and approve/reject all purchase requests
- **Purchase Orders** (`/purchase-orders`) - Create and manage purchase orders
- **Reports & Dashboards** (`/reports`) - System-wide analytics and reports
- **Activity Logs** (`/activity-logs`) - View all user activity logs
- **System Settings** (`/settings`) - Configure system parameters

---

## 2. Branch Manager (`branch_manager`)

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

## 3. Inventory Staff (`inventory_staff`)

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

## 4. Supplier (`supplier`)

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

## 5. Logistics Coordinator (`logistics_coordinator`)

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

## 6. Franchise Manager (`franchise_manager`)

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
| **central_admin** | ✅ Full | ✅ All Branches | ✅ All Branches | ✅ Full | ✅ Full | ✅ Full |
| **branch_manager** | ❌ No | ❌ No | ✅ Own Branch | ✅ Create Requests | ✅ Branch Only | ❌ No |
| **inventory_staff** | ❌ No | ❌ No | ✅ Own Branch | ❌ No | ❌ No | ❌ No |
| **supplier** | ❌ No | ❌ No | ❌ No | ✅ View Own | ❌ No | ❌ No |
| **logistics_coordinator** | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No |
| **franchise_manager** | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No | ❌ No |

---

## Default Test Users

Based on the UserSeeder, default test users are:

1. **Central Admin**
   - Username: `centraladmin`
   - Password: `admin123`
   - Email: `centraladmin@scms.com`

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
- The `central_admin` role has full system access including user management, branch management, and system settings
- Branch-specific roles (`branch_manager`, `inventory_staff`) are typically assigned to a specific branch via `branch_id`
- **Note:** The `system_admin` role has been merged into `central_admin`. All system administrator responsibilities are now handled by the Central Admin role.

---

**Last Updated:** Based on current codebase analysis
**File Location:** `app/Views/design/sidebar.php` and `app/Database/Seeds/UserSeeder.php`

