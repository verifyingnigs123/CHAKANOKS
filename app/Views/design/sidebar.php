<!-- Sidebar -->
<div class="sidebar bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
  <h4 class="text-center mb-4">⚙️ SCMS</h4>
  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a href="<?= base_url('dashboard') ?>" class="nav-link text-white">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="<?= base_url('inventory') ?>" class="nav-link text-white">
        <i class="bi bi-box-seam"></i> Inventory Management
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="<?= base_url('orders') ?>" class="nav-link text-white">
        <i class="bi bi-cart-check"></i> Orders & Requests
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="<?= base_url('suppliers') ?>" class="nav-link text-white">
        <i class="bi bi-truck"></i> Suppliers
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="<?= base_url('logistics') ?>" class="nav-link text-white">
        <i class="bi bi-geo-alt"></i> Logistics & Delivery
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="<?= base_url('reports') ?>" class="nav-link text-white">
        <i class="bi bi-graph-up"></i> Reports & Analytics
      </a>
    </li>
    <li class="nav-item mt-4">
      <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger w-100">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</div>
