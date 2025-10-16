<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ChakaNoks SCMS') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .main-header {
            height: var(--header-height);
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            width: var(--sidebar-width);
        }
        
        .logo-icon {
            width: 45px;
            height: 45px;
            background: var(--secondary-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        
        .logo-icon i {
            font-size: 24px;
            color: white;
        }
        
        .logo-text h1 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }
        
        .logo-text p {
            color: rgba(255,255,255,0.8);
            font-size: 11px;
            margin: 0;
        }
        
        .header-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 10px 40px 10px 15px;
            border-radius: 25px;
            width: 300px;
            transition: all 0.3s;
        }
        
        .search-box input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        
        .search-box input:focus {
            background: rgba(255,255,255,0.25);
            outline: none;
            width: 350px;
        }
        
        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.7);
        }
        
        .header-icon {
            color: white;
            font-size: 20px;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }
        
        .header-icon:hover {
            color: var(--secondary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        
        .user-profile:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin: 0;
        }
        
        .user-role {
            color: rgba(255,255,255,0.7);
            font-size: 11px;
            margin: 0;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 999;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
        .nav-section-title {
            padding: 15px 20px 10px;
            color: #999;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .nav-item {
            margin: 5px 10px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #5a6c7d;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }
        
        .nav-link i {
            font-size: 18px;
            margin-right: 12px;
            width: 25px;
            text-align: center;
        }
        
        .nav-link:hover {
            background: #f0f4f8;
            color: var(--secondary-color);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5dade2 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
        }
        
        .nav-link.active i {
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 10px 0;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }
        
        .breadcrumb-item a {
            color: var(--secondary-color);
            text-decoration: none;
        }
        
        /* Dashboard Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }
        
        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
            padding: 4px 10px;
            border-radius: 20px;
        }
        
        .stat-change.positive {
            background: #d4edda;
            color: #28a745;
        }
        
        .stat-change.negative {
            background: #f8d7da;
            color: #dc3545;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-box input {
                width: 200px;
            }
            
            .search-box input:focus {
                width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="logo-text">
                <h1>ChakaNoks</h1>
                <p>Supply Chain Management</p>
            </div>
        </div>
        
        <div class="header-actions">
            <div class="search-box">
                <input type="text" placeholder="Search inventory, orders, suppliers...">
                <i class="bi bi-search"></i>
            </div>
            
            <div class="header-icon">
                <i class="bi bi-bell"></i>
                <span class="notification-badge">5</span>
            </div>
            
            <div class="header-icon">
                <i class="bi bi-envelope"></i>
                <span class="notification-badge">3</span>
            </div>
            
            <div class="user-profile">
                <div class="user-avatar">CN</div>
                <div class="user-info">
                    <p class="user-name">Admin User</p>
                    <p class="user-role">Administrator</p>
                </div>
                <i class="bi bi-chevron-down" style="color: white; font-size: 12px;"></i>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <nav class="nav-menu">
            <div class="nav-section-title">Main Navigation</div>
            
            <div class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <div class="nav-section-title">Inventory Management</div>
            
            <div class="nav-item">
                <a href="<?= base_url('inventory') ?>" class="nav-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Inventory</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('products') ?>" class="nav-link">
                    <i class="bi bi-tag"></i>
                    <span>Products</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('stock-movement') ?>" class="nav-link">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Stock Movement</span>
                </a>
            </div>
            
            <div class="nav-section-title">Orders & Procurement</div>
            
            <div class="nav-item">
                <a href="<?= base_url('orders') ?>" class="nav-link">
                    <i class="bi bi-cart-check"></i>
                    <span>Orders</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('purchase-orders') ?>" class="nav-link">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Purchase Orders</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('suppliers') ?>" class="nav-link">
                    <i class="bi bi-building"></i>
                    <span>Suppliers</span>
                </a>
            </div>
            
            <div class="nav-section-title">Logistics</div>
            
            <div class="nav-item">
                <a href="<?= base_url('shipments') ?>" class="nav-link">
                    <i class="bi bi-truck"></i>
                    <span>Shipments</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('warehouses') ?>" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>Warehouses</span>
                </a>
            </div>
            
            <div class="nav-section-title">Reports & Analytics</div>
            
            <div class="nav-item">
                <a href="<?= base_url('reports') ?>" class="nav-link">
                    <i class="bi bi-graph-up"></i>
                    <span>Reports</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('analytics') ?>" class="nav-link">
                    <i class="bi bi-bar-chart"></i>
                    <span>Analytics</span>
                </a>
            </div>
            
            <div class="nav-section-title">Settings</div>
            
            <div class="nav-item">
                <a href="<?= base_url('users') ?>" class="nav-link">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= base_url('settings') ?>" class="nav-link">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title"><?= esc($page_title ?? 'Dashboard') ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($page_title ?? 'Dashboard') ?></li>
                </ol>
            </nav>
        </div>
        
        <!-- Dashboard Stats Example -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e3f2fd; color: #2196f3;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="stat-value">1,245</h3>
                    <p class="stat-label">Total Products</p>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 12.5%
                    </span>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff3e0; color: #ff9800;">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h3 class="stat-value">342</h3>
                    <p class="stat-label">Active Orders</p>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 8.3%
                    </span>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f3e5f5; color: #9c27b0;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="stat-value">87</h3>
                    <p class="stat-label">Suppliers</p>
                    <span class="stat-change positive">
                        <i class="bi bi-arrow-up"></i> 3.2%
                    </span>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e8f5e9; color: #4caf50;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3 class="stat-value">156</h3>
                    <p class="stat-label">Shipments</p>
                    <span class="stat-change negative">
                        <i class="bi bi-arrow-down"></i> 2.1%
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Content Section -->
        <?= $this->renderSection('content') ?>
    </main>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'btn btn-link text-white d-md-none';
            menuBtn.innerHTML = '<i class="bi bi-list fs-4"></i>';
            menuBtn.style.cssText = 'position: absolute; left: 10px;';
            
            document.querySelector('.main-header').prepend(menuBtn);
            
            menuBtn.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });
        });
    </script>
</body>
</html>