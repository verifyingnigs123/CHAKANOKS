<?php 
$role = session()->get('userRole') ?? session()->get('role') ?? 'guest';
$roleLower = strtolower($role);
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

  /* Bubble-style nav for modern sidebar */
  .modern-sidebar .sidebar-menu {
    position: relative;
    padding-left: 18px;
  }

  .modern-sidebar .sidebar-menu .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    margin: 8px 12px;
    border-radius: 999px;
    background: transparent;
    transition: transform 220ms cubic-bezier(.2,.9,.2,1), background 220ms ease, box-shadow 220ms ease;
  }

  .modern-sidebar .sidebar-menu .nav-link:hover {
    transform: translateX(6px) scale(1.02);
    background: rgba(255,255,255,0.03);
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
  }

  .modern-sidebar .sidebar-menu .nav-link.active {
    background: linear-gradient(90deg, rgba(255,112,67,0.12), rgba(255,112,67,0.02));
    box-shadow: 0 10px 26px rgba(11,59,90,0.14);
    color: #fff;
    transform: translateX(4px) scale(1.01);
  }

  .modern-sidebar #navIndicatorDesign {
    position: absolute;
    left: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.95), rgba(255,255,255,0.6));
    box-shadow: 0 8px 22px rgba(124,58,237,0.12);
    transition: top 360ms cubic-bezier(.22,.9,.28,1), opacity 240ms ease;
    opacity: 0;
    z-index: 5;
    pointer-events: none;
  }

  .sidebar-header {
    padding: 22px 20px;
    background: linear-gradient(90deg, rgba(255,112,67,0.06), transparent 60%);
    border-bottom: 1px solid rgba(255,255,255,0.03);
    text-align: center;
  }
<?php 
$role = session()->get('userRole') ?? session()->get('role') ?? 'guest';
$roleLower = strtolower($role);
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
  <div id="navIndicatorDesign" aria-hidden="true"></div>
  <div class="sidebar-header">
    <div class="sidebar-user" role="group" aria-label="User info">
      <?php
      $roleDisplay = ucwords(str_replace('_', ' ', $role));
      // Normalize display for special roles
      if ($roleLower === 'central_admin') {
          $roleDisplay = 'Central Admin';
      } elseif ($roleLower === 'branch_manager') {
          $branchName = session()->get('branch_name');
          $roleDisplay = $branchName ? $branchName : 'Branch Manager';
      }
      // Get first letter of role display for avatar
      $avatarInitial = strtoupper(substr($roleDisplay, 0, 1));
      ?>
      <div class="sidebar-user-avatar" aria-hidden="true"><?= $avatarInitial ?></div>
      <div class="sidebar-user-info">
        <p class="sidebar-user-name"><?= esc($roleDisplay) ?></p>
        <p class="sidebar-user-role"><?= esc($roleDisplay) ?></p>
      </div>
    </div>
  </div>

  <?php
  // Treat as supplier if role indicates supplier or session has supplier_id set
  $isSupplier = ($roleLower === 'supplier' || !empty(session()->get('supplier_id')));
  ?>

  <ul class="sidebar-menu" role="menu" aria-label="Sidebar menu">
    <?php if ($roleLower === 'central_admin'): ?>
      <li class="menu-label">Administrator</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('users') ?>" class="nav-link"><i class="bi bi-people"></i><span>User Management</span></a></li>
      <li><a href="<?= base_url('branches') ?>" class="nav-link"><i class="bi bi-building"></i><span>Branches</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Inventory</span></a></li>
      <li><a href="<?= base_url('suppliers') ?>" class="nav-link"><i class="bi bi-briefcase"></i><span>Suppliers</span></a></li>
      <li class="menu-divider"></li>

      <li class="menu-label">Procurement</li>
      <?php if (! $isSupplier): ?>
      <li><a href="<?= base_url('purchase-requests') ?>" class="nav-link"><i class="bi bi-journal-plus"></i><span>Purchase Requests</span></a></li>
      <?php endif; ?>
      <li><a href="<?= base_url('purchase-orders') ?>" class="nav-link"><i class="bi bi-receipt"></i><span>Purchase Orders</span></a></li>
      <li class="menu-divider"></li>

      <li class="menu-label">Analytics & Operations</li>
      <li><a href="<?= base_url('reports') ?>" class="nav-link"><i class="bi bi-graph-up"></i><span>Reports</span></a></li>
      <li><a href="<?= base_url('activity-logs') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Activity Logs</span></a></li>
      <li><a href="<?= base_url('settings') ?>" class="nav-link"><i class="bi bi-gear"></i><span>Settings</span></a></li>

    <?php elseif ($roleLower === 'branch_manager'): ?>
      <li class="menu-label">Branch Operations</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Inventory</span></a></li>
      <li><a href="<?= base_url('purchase-requests/create') ?>" class="nav-link"><i class="bi bi-journal-plus"></i><span>Create Purchase Request</span></a></li>
      <?php if (! $isSupplier): ?>
      <li><a href="<?= base_url('transfers') ?>" class="nav-link"><i class="bi bi-arrow-left-right"></i><span>Transfers</span></a></li>
      <?php endif; ?>
      <li class="menu-divider"></li>

      <li class="menu-label">Reports</li>
      <li><a href="<?= base_url('reports') ?>" class="nav-link"><i class="bi bi-graph-up"></i><span>Branch Reports</span></a></li>

    <?php elseif ($roleLower === 'inventory_staff'): ?>
      <li class="menu-label">Inventory</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-box-seam"></i><span>Stock Overview</span></a></li>
      <li><a href="<?= base_url('inventory') ?>" class="nav-link"><i class="bi bi-pencil-square"></i><span>Update Stock</span></a></li>
      <li class="menu-divider"></li>
      <li class="menu-label">Suppliers</li>
      <li><a href="<?= base_url('purchase-requests/create') ?>" class="nav-link"><i class="bi bi-truck"></i><span>Request Supply</span></a></li>

    <?php elseif ($roleLower === 'supplier'): ?>
      <li class="menu-label">Supplier</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('purchase-orders') ?>" class="nav-link"><i class="bi bi-file-earmark-text"></i><span>Purchase Orders</span></a></li>

    <?php elseif ($roleLower === 'logistics_coordinator'): ?>
      <li class="menu-label">Logistics</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <?php if (! $isSupplier): ?>
      <li><a href="<?= base_url('deliveries') ?>" class="nav-link"><i class="bi bi-truck"></i><span>Active Deliveries</span></a></li>
      <?php endif; ?>

    <?php elseif ($roleLower === 'franchise_manager'): ?>
      <li class="menu-label">Franchising</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
      <li><a href="<?= base_url('suppliers') ?>" class="nav-link"><i class="bi bi-briefcase"></i><span>Suppliers</span></a></li>

    <?php else: ?>
      <li class="menu-label">Navigation</li>
      <li><a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
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

  const currentPath = window.location.pathname.replace(/\/+$, '');
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

  // Indicator movement for modern sidebar
  (function() {
    const nav = document.querySelector('.modern-sidebar .sidebar-menu');
    const indicator = document.getElementById('navIndicatorDesign');
    if (!nav || !indicator) return;

    function updateIndicator() {
      const active = nav.querySelector('.nav-link.active');
      if (!active) {
        indicator.style.opacity = '0';
        return;
      }
      // compute position relative to modern-sidebar container
      const sidebarEl = document.querySelector('.modern-sidebar');
      const sidebarRect = sidebarEl.getBoundingClientRect();
      const actRect = active.getBoundingClientRect();
      const top = (actRect.top - sidebarRect.top) + (active.offsetHeight / 2) - (indicator.offsetHeight / 2) + nav.scrollTop;
      indicator.style.top = top + 'px';
      indicator.style.opacity = '1';
    }

    nav.addEventListener('click', function(e) {
      const link = e.target.closest('.nav-link');
      if (!link) return;
      nav.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
      link.classList.add('active');
      updateIndicator();
    });

    updateIndicator();
    window.addEventListener('resize', updateIndicator);
    setTimeout(updateIndicator, 300);
  })();
});
</script>
