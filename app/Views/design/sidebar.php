<?php 
$role = session()->get('userRole') ?? session()->get('role') ?? 'guest';
$username = session()->get('username') ?? session()->get('userEmail') ?? 'User';
?>

<!-- Modern Role-Based Sidebar -->
<style>
  :root{
    --sidebar-width: 280px;
    --navbar-height: 56px;
  }

  .modern-sidebar {
    width: var(--sidebar-width);
    height: calc(100vh - var(--navbar-height)); /* Fixed height */
    background: linear-gradient(180deg, #0f172a 0%, #0b3b5a 50%, #0f172a 100%);
    position: fixed;
    left: 0;
    top: var(--navbar-height);
    box-shadow: 4px 0 30px rgba(0,0,0,0.20);
    z-index: 1030;
    overflow-y: auto;
    color: #e6eef8;
  }

  .sidebar-header {
    padding: 22px 20px;
    background: linear-gradient(90deg, rgba(255,112,67,0.06), transparent 60%);
    border-bottom: 1px solid rgba(255,255,255,0.03);
    text-align: center;
  }

  .sidebar-user { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
  .sidebar-user-avatar {
    width:44px; height:44px; border-radius:50%;
    background: linear-gradient(135deg,#ff7043,#ff8a65);
    display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.05rem;
    border: 2px solid rgba(255,255,255,0.06);
  }
  .sidebar-user-name { margin:0; font-weight:700; color:#fff; font-size:0.95rem; }
  .sidebar-user-role { margin:0; color: rgba(255,255,255,0.6); font-size:0.74rem; text-transform:capitalize; }

  .sidebar-menu { padding: 14px 0; margin: 0; list-style: none; }
  .sidebar-menu li { margin: 0; }
  .sidebar-menu .nav-link {
    color: rgba(230,238,248,0.9);
    padding: 12px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: background 0.22s, padding-left 0.22s;
    border-left: 3px solid transparent;
    font-weight: 500;
    position: relative;
  }
  .sidebar-menu .nav-link i { font-size: 1.05rem; width: 28px; text-align:center; color: rgba(230,238,248,0.9); }
  .sidebar-menu .nav-link:hover {
    background: rgba(255,255,255,0.03);
    color: #fff;
    border-left-color: #ff7043;
    padding-left: 28px;
  }
  .sidebar-menu .nav-link.active {
    background: linear-gradient(90deg, rgba(255,112,67,0.12), rgba(255,112,67,0.02));
    border-left-color: #ff7043;
    color: #fff;
    font-weight: 700;
  }

  .menu-divider { height: 1px; background: rgba(255,255,255,0.04); margin: 12px 16px; }
  .menu-label { padding: 10px 22px; color: rgba(255,255,255,0.45); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.4px; font-weight: 700; }

  .nav-badge {
    background: #ff4757; color: #fff; padding: 2px 8px; border-radius: 999px; font-size:0.72rem; font-weight:700;
    margin-left: auto;
  }

  @media (max-width: 992px) {
    .modern-sidebar { transform: translateX(-100%); position: fixed; }
    .modern-sidebar.show { transform: translateX(0); box-shadow: 8px 0 42px rgba(0,0,0,0.28); }
  }

  .modern-sidebar::-webkit-scrollbar { width: 8px; }
  .modern-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); border-radius:4px; }
</style>

<div class="modern-sidebar" role="navigation" aria-label="Primary sidebar">
  <div class="sidebar-header">
    <div class="sidebar-user" role="group" aria-label="User info">
      <div class="sidebar-user-avatar" aria-hidden="true"><?= strtoupper(substr($username, 0, 1)) ?></div>
      <div class="sidebar-user-info">
        <p class="sidebar-user-name"><?= esc($username) ?></p>
        <p class="sidebar-user-role"><?= esc(ucwords(str_replace('_',' ',$role))) ?></p>
      </div>
    </div>
  </div>

  <ul class="sidebar-menu" role="menu" aria-label="Sidebar menu">
    <!-- System Admin -->
    <?php if ($role === 'system_admin'): ?>
      <li class="menu-label">Administrator</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('admin/users') ?>" class="nav-link"><i class="bi bi-people"></i><span>User Management</span></a></li>
      <li><a href="<?= base_url('admin/branches') ?>" class="nav-link"><i class="bi bi-building"></i><span>Branches</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Inventory Overview</span></a></li>
      <li><a href="<?= base_url('suppliers') ?>" class="nav-link"><i class="bi bi-briefcase"></i><span>Suppliers</span></a></li>
      <li class="menu-divider"></li>

      <li class="menu-label">Procurement</li>
      <li><a href="<?= base_url('purchase-requests') ?>" class="nav-link"><i class="bi bi-journal-plus"></i><span>Purchase Requests</span></a></li>
      <li><a href="<?= base_url('purchase-orders') ?>" class="nav-link"><i class="bi bi-receipt"></i><span>Purchase Orders</span></a></li>
      <li><a href="<?= base_url('suppliers/contracts') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Contracts</span></a></li>
      <li class="menu-divider"></li>

      <li class="menu-label">Analytics & Operations</li>
      <li><a href="<?= base_url('reports') ?>" class="nav-link"><i class="bi bi-graph-up"></i><span>Reports & Dashboards</span></a></li>
      <li><a href="<?= base_url('logs') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Activity Logs</span></a></li>
      <li><a href="<?= base_url('settings') ?>" class="nav-link"><i class="bi bi-gear"></i><span>System Settings</span></a></li>

    <!-- Branch Manager -->
    <?php elseif ($role === 'branch_manager'): ?>
      <li class="menu-label">Branch Operations</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('orders') ?>" class="nav-link"><i class="bi bi-cart-check"></i><span>Orders</span><span class="nav-badge">15</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Inventory</span><span class="nav-badge">7</span></a></li>
      <li><a href="<?= base_url('purchase-requests/create') ?>" class="nav-link"><i class="bi bi-journal-plus"></i><span>Create Purchase Request</span></a></li>
      <li><a href="<?= base_url('transfers') ?>" class="nav-link"><i class="bi bi-arrow-left-right"></i><span>Transfers</span></a></li>
      <li class="menu-divider"></li>

      <li class="menu-label">Reports</li>
      <li><a href="<?= base_url('reports/branch') ?>" class="nav-link"><i class="bi bi-graph-up"></i><span>Branch Reports</span></a></li>

    <!-- Inventory Staff -->
    <?php elseif ($role === 'inventory_staff'): ?>
      <li class="menu-label">Inventory</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('inventory/overview') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Stock Overview</span><span class="nav-badge">12</span></a></li>
      <li><a href="<?= base_url('inventory/update') ?>" class="nav-link"><i class="bi bi-pencil-square"></i><span>Update Stock</span></a></li>
      <li class="menu-divider"></li>
      <li class="menu-label">Suppliers</li>
      <li><a href="<?= base_url('suppliers/request') ?>" class="nav-link"><i class="bi bi-truck"></i><span>Request Supply</span></a></li>

    <!-- Supplier -->
    <?php elseif ($role === 'supplier'): ?>
      <li class="menu-label">Supplier</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('supplier/orders') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Purchase Orders</span><span class="nav-badge">23</span></a></li>
      <li><a href="<?= base_url('supplier/deliveries') ?>" class="nav-link"><i class="bi bi-truck"></i><span>Deliveries</span></a></li>
      <li><a href="<?= base_url('supplier/invoices') ?>" class="nav-link"><i class="bi bi-receipt"></i><span>Invoices</span></a></li>

    <!-- Logistics Coordinator -->
    <?php elseif ($role === 'logistics_coordinator'): ?>
      <li class="menu-label">Logistics</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('logistics/deliveries') ?>" class="nav-link"><i class="bi bi-truck"></i><span>Active Deliveries</span><span class="nav-badge">34</span></a></li>
      <li><a href="<?= base_url('logistics/routes') ?>" class="nav-link"><i class="bi bi-geo-alt"></i><span>Route Planning</span></a></li>
      <li><a href="<?= base_url('logistics/fleet') ?>" class="nav-link"><i class="bi bi-truck-flatbed"></i><span>Fleet</span></a></li>
      <li class="menu-divider"></li>
      <li class="menu-label">Management</li>
      <li><a href="<?= base_url('logistics/drivers') ?>" class="nav-link"><i class="bi bi-person-badge"></i><span>Drivers</span></a></li>

    <!-- Franchise Manager -->
    <?php elseif ($role === 'franchise_manager'): ?>
      <li class="menu-label">Franchising</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('franchise/applications') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Applications</span></a></li>
      <li><a href="<?= base_url('franchise/supplies') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Supply Allocation</span></a></li>
      <li><a href="<?= base_url('franchise/payments') ?>" class="nav-link"><i class="bi bi-currency-dollar"></i><span>Royalties</span></a></li>

    <!-- Default / Guest -->
    <?php else: ?>
      <li class="menu-label">Navigation</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('profile') ?>" class="nav-link"><i class="bi bi-person"></i><span>Profile</span></a></li>
    <?php endif; ?>

  </ul>
</div>

<!-- Mobile Toggle Button -->
<button class="btn btn-primary d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar" style="position: fixed; top: 18px; left: 16px; z-index: 1100;">
  <i class="bi bi-list"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.modern-sidebar');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      sidebar.classList.toggle('show');
    });

    document.addEventListener('click', function(event) {
      if (window.innerWidth < 992) {
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
          sidebar.classList.remove('show');
        }
      }
    });

    sidebar.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }

  const currentPath = window.location.pathname.replace(/\/+$/, '');
  const navLinks = document.querySelectorAll('.sidebar-menu .nav-link');
  navLinks.forEach(link => {
    link.classList.remove('active');
    const href = link.getAttribute('href') || '';
    const normalizedHref = href.replace(/^\/+|\/+$/g, '');
    if (!normalizedHref) return;
    if (currentPath.includes('/' + normalizedHref) || ('/' + normalizedHref) === currentPath) {
      link.classList.add('active');
    } else {
      const hrefSeg = normalizedHref.split('/').pop();
      const curSeg = currentPath.split('/').pop();
      if (hrefSeg && hrefSeg === curSeg) link.classList.add('active');
    }
  });
});
</script>
