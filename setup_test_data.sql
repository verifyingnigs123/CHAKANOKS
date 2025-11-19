-- SCMS Test Data Setup Script
-- Run this in phpMyAdmin or MySQL to create test users and sample data

-- ============================================
-- CREATE TEST USERS
-- ============================================
-- Password for all users: 'password'

-- Central Admin
INSERT INTO users (username, password, full_name, email, role, status, created_at) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Central Administrator', 'admin@scms.com', 'central_admin', 'active', NOW())
ON DUPLICATE KEY UPDATE username=username;

-- Branch Manager
INSERT INTO users (username, password, full_name, email, role, status, branch_id, created_at) 
VALUES ('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Branch Manager', 'manager@scms.com', 'branch_manager', 'active', 1, NOW())
ON DUPLICATE KEY UPDATE username=username;

-- Inventory Staff
INSERT INTO users (username, password, full_name, email, role, status, branch_id, created_at) 
VALUES ('staff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Inventory Staff', 'staff@scms.com', 'inventory_staff', 'active', 1, NOW())
ON DUPLICATE KEY UPDATE username=username;

-- ============================================
-- CREATE SAMPLE BRANCHES
-- ============================================
INSERT INTO branches (name, code, address, city, phone, email, status, created_at) 
VALUES 
('Main Branch', 'MB001', '123 Main Street', 'Manila', '09123456789', 'main@branch.com', 'active', NOW()),
('Branch 2', 'BR002', '456 Second Avenue', 'Quezon City', '09123456790', 'branch2@company.com', 'active', NOW()),
('Branch 3', 'BR003', '789 Third Street', 'Makati', '09123456791', 'branch3@company.com', 'active', NOW())
ON DUPLICATE KEY UPDATE name=name;

-- ============================================
-- CREATE SAMPLE SUPPLIERS
-- ============================================
INSERT INTO suppliers (name, code, contact_person, email, phone, address, payment_terms, delivery_terms, status, created_at) 
VALUES 
('ABC Supplies Inc.', 'SUP001', 'John Doe', 'john@abcsupplies.com', '09123456789', '123 Supplier Ave', 'Net 30', 'FOB', 'active', NOW()),
('XYZ Distributors', 'SUP002', 'Jane Smith', 'jane@xyzdist.com', '09123456790', '456 Distributor St', 'Net 15', 'CIF', 'active', NOW()),
('Global Foods Co.', 'SUP003', 'Bob Johnson', 'bob@globalfoods.com', '09123456791', '789 Food Blvd', 'Net 30', 'FOB', 'active', NOW())
ON DUPLICATE KEY UPDATE name=name;

-- ============================================
-- CREATE SAMPLE DRIVERS
-- ============================================
INSERT INTO drivers (name, vehicle_number, phone, license_number, status, created_at, updated_at) 
VALUES 
('Juan Dela Cruz', 'ABC-1234', '09123456789', 'DL-001234', 'active', NOW(), NOW()),
('Pedro Santos', 'XYZ-5678', '09187654321', 'DL-002345', 'active', NOW(), NOW()),
('Maria Garcia', 'DEF-9012', '09234567890', 'DL-003456', 'active', NOW(), NOW()),
('Carlos Rodriguez', 'GHI-3456', '09345678901', 'DL-004567', 'active', NOW(), NOW()),
('Ana Martinez', 'JKL-7890', '09456789012', 'DL-005678', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE name=name;

-- ============================================
-- CREATE SAMPLE PRODUCTS
-- ============================================
INSERT INTO products (name, sku, barcode, description, category, unit, min_stock_level, max_stock_level, cost_price, selling_price, status, created_at) 
VALUES 
('Rice 25kg', 'RICE-25KG-001', '1234567890123', 'Premium quality rice 25kg bag', 'Food', 'pcs', 50, 500, 1500.00, 1800.00, 'active', NOW()),
('Cooking Oil 1L', 'OIL-1L-001', '1234567890124', 'Vegetable cooking oil 1 liter', 'Food', 'pcs', 30, 300, 120.00, 150.00, 'active', NOW()),
('Sugar 1kg', 'SUGAR-1KG-001', '1234567890125', 'White sugar 1kg pack', 'Food', 'pcs', 40, 400, 80.00, 100.00, 'active', NOW()),
('Flour 1kg', 'FLOUR-1KG-001', '1234567890126', 'All-purpose flour 1kg', 'Food', 'pcs', 35, 350, 60.00, 75.00, 'active', NOW()),
('Salt 500g', 'SALT-500G-001', '1234567890127', 'Iodized salt 500g', 'Food', 'pcs', 50, 500, 25.00, 35.00, 'active', NOW())
ON DUPLICATE KEY UPDATE name=name;

-- ============================================
-- CREATE INITIAL INVENTORY (for Main Branch)
-- ============================================
-- Note: Adjust branch_id based on your actual branch IDs
INSERT INTO inventory (branch_id, product_id, quantity, reserved_quantity, available_quantity, last_updated_at, created_at) 
SELECT 1, id, 100, 0, 100, NOW(), NOW() FROM products
ON DUPLICATE KEY UPDATE quantity=quantity;

-- ============================================
-- NOTES
-- ============================================
-- 1. All users have password: 'password'
-- 2. Update branch_id in users table if needed
-- 3. Adjust branch_id in inventory inserts based on your branch IDs
-- 4. You can modify product data as needed
-- 5. Run this after migrations are complete

