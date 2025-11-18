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
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'inventory') !== false) ? 'active' : '' ?>" href="<?= base_url('inventory') ?>">
                            <i class="bi bi-boxes"></i> Inventory
                        </a>
                    </li>
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
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'deliveries') !== false) ? 'active' : '' ?>" href="<?= base_url('deliveries') ?>">
                            <i class="bi bi-truck"></i> Deliveries
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'products') !== false) ? 'active' : '' ?>" href="<?= base_url('products') ?>">
                            <i class="bi bi-tags"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'suppliers') !== false) ? 'active' : '' ?>" href="<?= base_url('suppliers') ?>">
                            <i class="bi bi-truck"></i> Suppliers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'branches') !== false) ? 'active' : '' ?>" href="<?= base_url('branches') ?>">
                            <i class="bi bi-building"></i> Branches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'transfers') !== false) ? 'active' : '' ?>" href="<?= base_url('transfers') ?>">
                            <i class="bi bi-arrow-left-right"></i> Transfers
                        </a>
                    </li>
                    <?php if (session()->get('role') == 'system_admin' || session()->get('role') == 'central_admin'): ?>
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
                    <li class="nav-item">
                        <a class="nav-link <?= (strpos(uri_string(), 'reports') !== false) ? 'active' : '' ?>" href="<?= base_url('reports') ?>">
                            <i class="bi bi-graph-up"></i> Reports
                        </a>
                    </li>
                    <?php if (session()->get('role') == 'system_admin'): ?>
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
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                                    0
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" id="notificationList" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li id="noNotifications"><a class="dropdown-item text-muted">No new notifications</a></li>
                            </ul>
                        </div>
                        <div>
                            <span class="text-muted">Welcome, <strong><?= session()->get('username') ?></strong></span>
                            <span class="badge bg-secondary ms-2"><?= ucfirst(str_replace('_', ' ', session()->get('role'))) ?></span>
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
                        badge.textContent = data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                });

            fetch('<?= base_url('notifications/get-unread') ?>')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notificationList');
                    const noNotifications = document.getElementById('noNotifications');
                    
                    // Remove existing notification items (keep header and divider)
                    const items = list.querySelectorAll('.notification-item');
                    items.forEach(item => item.remove());
                    
                    if (data.notifications && data.notifications.length > 0) {
                        noNotifications.style.display = 'none';
                        data.notifications.forEach(notif => {
                            const li = document.createElement('li');
                            const typeClass = {
                                'info': 'text-primary',
                                'success': 'text-success',
                                'warning': 'text-warning',
                                'danger': 'text-danger'
                            }[notif.type] || 'text-info';
                            
                            li.className = 'notification-item';
                            li.innerHTML = `
                                <a class="dropdown-item ${typeClass}" href="${notif.link || '#'}" onclick="markAsRead(${notif.id})">
                                    <div class="d-flex justify-content-between">
                                        <strong>${notif.title}</strong>
                                        <small class="text-muted">${new Date(notif.created_at).toLocaleTimeString()}</small>
                                    </div>
                                    <small>${notif.message}</small>
                                </a>
                            `;
                            list.insertBefore(li, noNotifications);
                        });
                        const markAll = document.createElement('li');
                        markAll.innerHTML = '<hr class="dropdown-divider"><li><a class="dropdown-item text-center" href="#" onclick="markAllAsRead(); return false;">Mark all as read</a></li>';
                        list.appendChild(markAll);
                    } else {
                        noNotifications.style.display = 'block';
                    }
                });
        }

        function markAsRead(id) {
            fetch(`<?= base_url('notifications/mark-as-read/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => loadNotifications());
        }

        function markAllAsRead() {
            fetch('<?= base_url('notifications/mark-all-read') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => loadNotifications());
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

