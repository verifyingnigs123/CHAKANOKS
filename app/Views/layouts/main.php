<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SCMS' ?> - Supply Chain Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .stat-card {
            border-left: 4px solid;
        }
        .stat-card.primary { border-left-color: #0d6efd; }
        .stat-card.success { border-left-color: #198754; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.danger { border-left-color: #dc3545; }
        
        /* Enhanced Notification Styles */
        #notificationBadge {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        #notificationList {
            width: 380px !important;
            max-height: 500px !important;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 0;
            margin-top: 8px;
        }
        
        #notificationList .dropdown-header {
            background: linear-gradient(135deg, #0f172a 0%, #0b3b5a 100%);
            color: white;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
            margin: 0;
            border-bottom: none;
        }
        
        .notification-item {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item .notification-link {
            padding: 14px 16px;
            display: block;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            position: relative;
            background: #fff;
        }
        
        .notification-item.unread .notification-link {
            background: linear-gradient(90deg, #f0f4f8 0%, #ffffff 100%);
            border-left: 4px solid;
            padding-left: 12px;
        }
        
        .notification-item.unread.notification-info .notification-link {
            border-left-color: #0b3b5a;
            background: linear-gradient(90deg, #e8f0f5 0%, #ffffff 100%);
        }
        
        .notification-item.unread.notification-warning .notification-link {
            border-left-color: #ffc107;
        }
        
        .notification-item.unread.notification-success .notification-link {
            border-left-color: #198754;
        }
        
        .notification-item.unread.notification-danger .notification-link {
            border-left-color: #dc3545;
        }
        
        .notification-item.read .notification-link {
            opacity: 0.7;
            background: #fafafa;
        }
        
        .notification-item.read .notification-link:hover {
            opacity: 0.9;
            background: #f5f5f5;
        }
        
        .notification-item .notification-link:hover {
            background: #f8f9fa;
            transform: translateX(2px);
        }
        
        .notification-item.read .notification-link:hover {
            transform: none;
        }
        
        .notification-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 4px;
            line-height: 1.4;
            color: #2c3e50;
        }
        
        .notification-message {
            font-size: 0.85rem;
            color: #6c757d;
            line-height: 1.5;
            margin-top: 6px;
            display: block;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #adb5bd;
            white-space: nowrap;
        }
        
        .notification-fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
                max-height: 200px;
                padding: 14px 16px;
                margin: 0;
            }
            to {
                opacity: 0;
                max-height: 0;
                padding: 0 16px;
                margin: 0;
                overflow: hidden;
            }
        }
        
        .mark-all-read-btn {
            background: linear-gradient(135deg, #0f172a 0%, #0b3b5a 100%);
            color: white;
            border: none;
            padding: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 6px;
        }
        
        .mark-all-read-btn:hover {
            background: linear-gradient(135deg, #0b3b5a 0%, #0f172a 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(11, 59, 90, 0.3);
            color: white;
        }
        
        #noNotifications {
            padding: 40px 20px;
            text-align: center;
            color: #adb5bd;
        }
        
        #noNotifications i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white"><i class="bi bi-box-seam"></i> SCMS</h4>
                    <small class="text-white-50">Supply Chain Management</small>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <?php if (session()->get('role') != 'supplier' && session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'inventory') !== false) ? 'active' : '' ?>" href="<?= base_url('inventory') ?>">
                            <i class="bi bi-boxes"></i> Inventory
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager' && session()->get('role') != 'inventory_staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'purchase-requests') !== false) ? 'active' : '' ?>" href="<?= base_url('purchase-requests') ?>">
                            <i class="bi bi-cart-plus"></i> Purchase Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'purchase-orders') !== false) ? 'active' : '' ?>" href="<?= base_url('purchase-orders') ?>">
                            <i class="bi bi-file-earmark-text"></i> Purchase Orders
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'franchise_manager' && session()->get('role') != 'inventory_staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'deliveries') !== false) ? 'active' : '' ?>" href="<?= base_url('deliveries') ?>">
                            <i class="bi bi-truck"></i> Deliveries
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager' && session()->get('role') != 'inventory_staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'products') !== false) ? 'active' : '' ?>" href="<?= base_url('products') ?>">
                            <i class="bi bi-tags"></i> Products
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'branch_manager' && session()->get('role') != 'inventory_staff' && session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager' && session()->get('role') != 'supplier'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'suppliers') !== false) ? 'active' : '' ?>" href="<?= base_url('suppliers') ?>">
                            <i class="bi bi-truck"></i> Suppliers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'franchise_manager'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'suppliers') !== false) ? 'active' : '' ?>" href="<?= base_url('suppliers') ?>">
                            <i class="bi bi-briefcase"></i> Suppliers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'branch_manager' && session()->get('role') != 'inventory_staff' && session()->get('role') != 'supplier' && session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'branches') !== false) ? 'active' : '' ?>" href="<?= base_url('branches') ?>">
                            <i class="bi bi-building"></i> Branches
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'logistics_coordinator' && session()->get('role') != 'franchise_manager' && session()->get('role') != 'inventory_staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'transfers') !== false) ? 'active' : '' ?>" href="<?= base_url('transfers') ?>">
                            <i class="bi bi-arrow-left-right"></i> Transfers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'central_admin' || session()->get('role') == 'central_admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'users') !== false) ? 'active' : '' ?>" href="<?= base_url('users') ?>">
                            <i class="bi bi-people"></i> User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'activity-logs') !== false) ? 'active' : '' ?>" href="<?= base_url('activity-logs') ?>">
                            <i class="bi bi-clock-history"></i> Activity Logs
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') != 'franchise_manager'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'reports') !== false) ? 'active' : '' ?>" href="<?= base_url('reports') ?>">
                            <i class="bi bi-graph-up"></i> Reports
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('role') == 'central_admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'settings') !== false) ? 'active' : '' ?>" href="<?= base_url('settings') ?>">
                            <i class="bi bi-gear"></i> Settings
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item mt-3">
                        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?= $page_title ?? 'Dashboard' ?></h2>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                                    0
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" id="notificationList">
                                <li><h6 class="dropdown-header"><i class="bi bi-bell-fill me-2"></i>Notifications</h6></li>
                                <li id="noNotifications" style="display: none;">
                                    <div class="text-center py-4">
                                        <i class="bi bi-bell-slash text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">No new notifications</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <?php 
                            $role = session()->get('role');
                            $roleDisplay = ucwords(str_replace('_', ' ', $role));
                            // For central_admin, show as "Central Admin"
                            if ($role === 'central_admin') {
                                $roleDisplay = 'Central Admin';
                            }
                            ?>
                            <span class="text-muted">Welcome, <strong><?= $roleDisplay ?></strong></span>
                            <span class="badge bg-secondary ms-2"><?= $roleDisplay ?></span>
                        </div>
                    </div>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load notifications
        function loadNotifications() {
            fetch('<?= base_url('notifications/get-unread-count') ?>')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });

            fetch('<?= base_url('notifications/get-unread') ?>')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    const noNotifications = document.getElementById('noNotifications');
                    
                    // Remove existing notification items and mark all button (keep header)
                    const items = list.querySelectorAll('.notification-item, .mark-all-container');
                    items.forEach(item => item.remove());
                    
                    if (data.notifications && data.notifications.length > 0) {
                        noNotifications.style.display = 'none';
                        
                        data.notifications.forEach(notif => {
                            const li = document.createElement('li');
                            const typeIcon = {
                                'info': 'bi-info-circle',
                                'success': 'bi-check-circle',
                                'warning': 'bi-exclamation-triangle',
                                'danger': 'bi-x-circle'
                            }[notif.type] || 'bi-bell';
                            
                            const typeColor = {
                                'info': '#0b3b5a',
                                'success': '#198754',
                                'warning': '#ffc107',
                                'danger': '#dc3545'
                            }[notif.type] || '#6c757d';
                            
                            const timeAgo = getTimeAgo(new Date(notif.created_at));
                            
                            li.className = `notification-item unread notification-${notif.type}`;
                            li.setAttribute('data-notification-id', notif.id);
                            li.innerHTML = `
                                <a class="notification-link" href="${notif.link || '#'}" onclick="markAsRead(${notif.id}, event)">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bi ${typeIcon}" style="color: ${typeColor}; font-size: 1.2rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="notification-title">${escapeHtml(notif.title)}</span>
                                                <span class="notification-time">${timeAgo}</span>
                                            </div>
                                            <span class="notification-message">${escapeHtml(notif.message)}</span>
                                        </div>
                                    </div>
                                </a>
                            `;
                            list.appendChild(li);
                        });
                        
                        // Add mark all button
                        const markAllContainer = document.createElement('li');
                        markAllContainer.className = 'mark-all-container';
                        markAllContainer.innerHTML = `
                            <hr class="dropdown-divider my-0">
                            <div class="text-center p-2">
                                <button class="btn btn-sm mark-all-read-btn w-100" onclick="markAllAsRead(); return false;">
                                    <i class="bi bi-check-all me-1"></i> Mark all as read
                                </button>
                            </div>
                        `;
                        list.appendChild(markAllContainer);
                    } else {
                        noNotifications.style.display = 'block';
                    }
                });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getTimeAgo(date) {
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // difference in seconds
            
            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
            return date.toLocaleDateString();
        }

        function markAsRead(id, event) {
            if (event) {
                event.preventDefault();
            }
            
            const notificationItem = document.querySelector(`.notification-item[data-notification-id="${id}"]`);
            const notificationLink = notificationItem ? notificationItem.querySelector('.notification-link') : null;
            const linkUrl = notificationLink ? notificationLink.getAttribute('href') : '#';
            
            // Mark as read immediately
            fetch(`<?= base_url('notifications/mark-as-read/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                // Update badge count immediately
                updateBadgeCount();
                
                if (notificationItem) {
                    // Add fade effect
                    notificationItem.classList.add('notification-fade-out');
                    
                    // Remove from list after animation
                    setTimeout(() => {
                        notificationItem.remove();
                        // Reload notifications to refresh the list
                        loadNotifications();
                        
                        // Navigate to the link if it's not just '#'
                        if (linkUrl && linkUrl !== '#') {
                            window.location.href = linkUrl;
                        }
                    }, 500);
                } else {
                    // If item not found, just reload
                    loadNotifications();
                    if (linkUrl && linkUrl !== '#') {
                        window.location.href = linkUrl;
                    }
                }
            });
        }

        function updateBadgeCount() {
            fetch('<?= base_url('notifications/get-unread-count') ?>')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });
        }

        function markAllAsRead() {
            const notificationItems = document.querySelectorAll('.notification-item.unread');
            
            // Add fade out animation to all items
            notificationItems.forEach(item => {
                item.classList.add('notification-fade-out');
            });
            
            // Mark all as read after animation
            setTimeout(() => {
                fetch('<?= base_url('notifications/mark-all-read') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    updateBadgeCount();
                    loadNotifications();
                });
            }, 300);
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            // Refresh every 30 seconds
            setInterval(loadNotifications, 30000);
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

