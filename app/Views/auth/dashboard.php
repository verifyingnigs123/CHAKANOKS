<?= $this->extend('design/template') ?> 
<?= $this->section('content') ?>

<style>
  .dashboard-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
  }
  
  .dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }
  
  .stat-card {
    background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
    color: white;
    padding: 25px;
    border-radius: 15px;
    height: 100%;
  }
  
  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 10px 0;
  }
  
  .stat-label {
    opacity: 0.9;
    font-size: 0.95rem;
  }
  
  .activity-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.2s;
  }
  
  .activity-item:hover {
    background-color: #f8f9fa;
  }
  
  .activity-item:last-child {
    border-bottom: none;
  }
  
  .quick-action-btn {
    padding: 15px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    background: white;
    transition: all 0.3s;
    text-decoration: none;
  }
  
  .quick-action-btn:hover {
    border-color: #ff7043;
    background: #fff5f3;
    transform: translateY(-3px);
  }
  
  .welcome-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
  }
</style>

<div class="container-fluid">
  
  <!-- Welcome Header -->
  <div class="welcome-header shadow">
    <h2 class="mb-2"><i class="fas fa-user-circle me-2"></i>Welcome, <?= esc($username) ?>!</h2>
    <h5 class="mb-0 opacity-75">Your Role: <?= esc(ucwords(str_replace('_', ' ', $role))) ?></h5>
  </div>

  <?php if ($role === 'system_admin'): ?>
    <!-- System Administrator Dashboard -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="alert alert-primary border-0 shadow-sm" style="border-left: 5px solid #0d6efd;">
          <h5 class="alert-heading mb-2"><i class="fas fa-crown me-2"></i>System Administrator Panel</h5>
          <p class="mb-0">Complete control over all system operations — Manage everything here.</p>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #667eea; --card-color-2: #764ba2;">
          <i class="fas fa-users fa-2x mb-2"></i>
          <div class="stat-label">Total Users</div>
          <div class="stat-number">1,247</div>
          <small><i class="fas fa-arrow-up"></i> 12% from last month</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #f093fb; --card-color-2: #f5576c;">
          <i class="fas fa-store fa-2x mb-2"></i>
          <div class="stat-label">Active Branches</div>
          <div class="stat-number">47</div>
          <small><i class="fas fa-check-circle"></i> All operational</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #4facfe; --card-color-2: #00f2fe;">
          <i class="fas fa-box fa-2x mb-2"></i>
          <div class="stat-label">Total Inventory</div>
          <div class="stat-number">89,234</div>
          <small><i class="fas fa-info-circle"></i> Items tracked</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #43e97b; --card-color-2: #38f9d7;">
          <i class="fas fa-dollar-sign fa-2x mb-2"></i>
          <div class="stat-label">Revenue (MTD)</div>
          <div class="stat-number">$428K</div>
          <small><i class="fas fa-arrow-up"></i> 8% increase</small>
        </div>
      </div>
    </div>

    <!-- Content Row -->
    <div class="row g-4 mb-4">
      <div class="col-lg-8">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-cogs text-primary me-2"></i>System Management</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <a href="<?= base_url('admin/users') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-users-cog fa-3x text-primary mb-3"></i>
                <h6>Manage Users</h6>
                <p class="small text-muted mb-0">Add, edit, or remove system users</p>
              </a>
            </div>
            <div class="col-md-6">
              <a href="<?= base_url('admin/branches') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-building fa-3x text-success mb-3"></i>
                <h6>Branch Management</h6>
                <p class="small text-muted mb-0">Oversee all branch operations</p>
              </a>
            </div>
            <div class="col-md-6">
              <a href="<?= base_url('admin/settings') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-sliders-h fa-3x text-warning mb-3"></i>
                <h6>System Settings</h6>
                <p class="small text-muted mb-0">Configure system parameters</p>
              </a>
            </div>
            <div class="col-md-6">
              <a href="<?= base_url('admin/reports') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                <h6>Analytics & Reports</h6>
                <p class="small text-muted mb-0">View comprehensive reports</p>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-bell text-warning me-2"></i>System Alerts</h5>
          <div class="activity-item">
            <div class="d-flex align-items-center">
              <div class="bg-danger text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation"></i>
              </div>
              <div class="flex-grow-1">
                <strong>Server Load High</strong>
                <p class="mb-0 small text-muted">5 minutes ago</p>
              </div>
            </div>
          </div>
          <div class="activity-item">
            <div class="d-flex align-items-center">
              <div class="bg-warning text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-plus"></i>
              </div>
              <div class="flex-grow-1">
                <strong>New User Registration</strong>
                <p class="mb-0 small text-muted">23 minutes ago</p>
              </div>
            </div>
          </div>
          <div class="activity-item">
            <div class="d-flex align-items-center">
              <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check"></i>
              </div>
              <div class="flex-grow-1">
                <strong>Backup Completed</strong>
                <p class="mb-0 small text-muted">1 hour ago</p>
              </div>
            </div>
          </div>
          <div class="activity-item">
            <div class="d-flex align-items-center">
              <div class="bg-info text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-database"></i>
              </div>
              <div class="flex-grow-1">
                <strong>Database Optimized</strong>
                <p class="mb-0 small text-muted">2 hours ago</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($role === 'branch_manager'): ?>
    <!-- Branch Manager Dashboard -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="alert alert-success border-0 shadow-sm" style="border-left: 5px solid #198754;">
          <h5 class="alert-heading mb-2"><i class="fas fa-store me-2"></i>Branch Manager Dashboard</h5>
          <p class="mb-0">Monitor and optimize your branch performance.</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #11998e; --card-color-2: #38ef7d;">
          <i class="fas fa-shopping-cart fa-2x mb-2"></i>
          <div class="stat-label">Today's Orders</div>
          <div class="stat-number">127</div>
          <small><i class="fas fa-clock"></i> 15 pending</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #eb3349; --card-color-2: #f45c43;">
          <i class="fas fa-chart-line fa-2x mb-2"></i>
          <div class="stat-label">Daily Revenue</div>
          <div class="stat-number">$9,450</div>
          <small><i class="fas fa-arrow-up"></i> Above target</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #fa709a; --card-color-2: #fee140;">
          <i class="fas fa-users fa-2x mb-2"></i>
          <div class="stat-label">Staff On Duty</div>
          <div class="stat-number">18</div>
          <small><i class="fas fa-check"></i> Full staffed</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #a8edea; --card-color-2: #fed6e3;">
          <i class="fas fa-box-open fa-2x mb-2"></i>
          <div class="stat-label">Low Stock Items</div>
          <div class="stat-number">7</div>
          <small><i class="fas fa-exclamation-triangle"></i> Needs attention</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-tasks text-success me-2"></i>Quick Actions</h5>
          <div class="row g-3">
            <div class="col-6">
              <a href="<?= base_url('orders/create') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-plus-circle fa-2x text-primary mb-2"></i>
                <div>New Order</div>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= base_url('inventory') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-boxes fa-2x text-warning mb-2"></i>
                <div>Check Stock</div>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= base_url('reports/branch') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-chart-bar fa-2x text-success mb-2"></i>
                <div>View Reports</div>
              </a>
            </div>
            <div class="col-6">
              <a href="<?= base_url('staff') ?>" class="quick-action-btn d-block text-center text-dark">
                <i class="fas fa-user-tie fa-2x text-info mb-2"></i>
                <div>Manage Staff</div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-clock text-primary me-2"></i>Recent Activities</h5>
          <div class="activity-item">
            <strong>Order #1247 completed</strong>
            <p class="mb-0 small text-muted">Customer: John Doe • 10 min ago</p>
          </div>
          <div class="activity-item">
            <strong>Stock replenished</strong>
            <p class="mb-0 small text-muted">Burgers +50 units • 45 min ago</p>
          </div>
          <div class="activity-item">
            <strong>Staff shift started</strong>
            <p class="mb-0 small text-muted">Morning crew • 2 hours ago</p>
          </div>
          <div class="activity-item">
            <strong>Daily report generated</strong>
            <p class="mb-0 small text-muted">Yesterday's performance • 3 hours ago</p>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($role === 'inventory_staff'): ?>
    <!-- Inventory Staff Dashboard -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="alert alert-warning border-0 shadow-sm" style="border-left: 5px solid #ffc107;">
          <h5 class="alert-heading mb-2"><i class="fas fa-boxes me-2"></i>Inventory Staff Dashboard</h5>
          <p class="mb-0">Track, update, and manage stock levels efficiently.</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="stat-card" style="--card-color-1: #ff6b6b; --card-color-2: #ee5a6f;">
          <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
          <div class="stat-label">Critical Stock</div>
          <div class="stat-number">12</div>
          <small><i class="fas fa-arrow-down"></i> Immediate action needed</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="--card-color-1: #ffa502; --card-color-2: #ff6348;">
          <i class="fas fa-clipboard-check fa-2x mb-2"></i>
          <div class="stat-label">Items Updated Today</div>
          <div class="stat-number">45</div>
          <small><i class="fas fa-check"></i> On schedule</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card" style="--card-color-1: #5f27cd; --card-color-2: #341f97;">
          <i class="fas fa-truck-loading fa-2x mb-2"></i>
          <div class="stat-label">Pending Deliveries</div>
          <div class="stat-number">8</div>
          <small><i class="fas fa-clock"></i> Expected today</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-box text-warning me-2"></i>Stock Alerts</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item</th>
                  <th>Current Stock</th>
                  <th>Min Required</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><i class="fas fa-drumstick-bite me-2 text-danger"></i>Chicken Patties</td>
                  <td><strong>45</strong></td>
                  <td>100</td>
                  <td><span class="badge bg-danger">Critical</span></td>
                  <td><a href="#" class="btn btn-sm btn-primary">Reorder</a></td>
                </tr>
                <tr>
                  <td><i class="fas fa-bread-slice me-2 text-success"></i>Burger Buns</td>
                  <td><strong>230</strong></td>
                  <td>200</td>
                  <td><span class="badge bg-success">Good</span></td>
                  <td><a href="#" class="btn btn-sm btn-outline-secondary">View</a></td>
                </tr>
                <tr>
                  <td><i class="fas fa-cheese me-2 text-warning"></i>Cheese Slices</td>
                  <td><strong>85</strong></td>
                  <td>150</td>
                  <td><span class="badge bg-warning">Low</span></td>
                  <td><a href="#" class="btn btn-sm btn-primary">Reorder</a></td>
                </tr>
                <tr>
                  <td><i class="fas fa-leaf me-2 text-success"></i>Lettuce</td>
                  <td><strong>180</strong></td>
                  <td>100</td>
                  <td><span class="badge bg-success">Good</span></td>
                  <td><a href="#" class="btn btn-sm btn-outline-secondary">View</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-bolt text-primary me-2"></i>Quick Actions</h5>
          <div class="d-grid gap-3">
            <a href="<?= base_url('inventory/update') ?>" class="btn btn-primary">
              <i class="fas fa-edit me-2"></i>Update Stock
            </a>
            <a href="<?= base_url('inventory/scan') ?>" class="btn btn-success">
              <i class="fas fa-qrcode me-2"></i>Scan Item
            </a>
            <a href="<?= base_url('inventory/report') ?>" class="btn btn-info">
              <i class="fas fa-file-alt me-2"></i>Generate Report
            </a>
            <a href="<?= base_url('suppliers/request') ?>" class="btn btn-warning">
              <i class="fas fa-shopping-cart me-2"></i>Request Supply
            </a>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($role === 'supplier'): ?>
    <!-- Supplier Dashboard -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="alert alert-info border-0 shadow-sm" style="border-left: 5px solid #0dcaf0;">
          <h5 class="alert-heading mb-2"><i class="fas fa-handshake me-2"></i>Supplier Dashboard</h5>
          <p class="mb-0">View and fulfill purchase orders efficiently.</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #12c2e9; --card-color-2: #c471ed;">
          <i class="fas fa-file-invoice fa-2x mb-2"></i>
          <div class="stat-label">Pending Orders</div>
          <div class="stat-number">23</div>
          <small><i class="fas fa-clock"></i> Awaiting fulfillment</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #f2994a; --card-color-2: #f2c94c;">
          <i class="fas fa-truck fa-2x mb-2"></i>
          <div class="stat-label">In Transit</div>
          <div class="stat-number">15</div>
          <small><i class="fas fa-shipping-fast"></i> Being delivered</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #56ab2f; --card-color-2: #a8e063;">
          <i class="fas fa-check-circle fa-2x mb-2"></i>
          <div class="stat-label">Completed (Month)</div>
          <div class="stat-number">187</div>
          <small><i class="fas fa-trophy"></i> Great performance</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #fc4a1a; --card-color-2: #f7b733;">
          <i class="fas fa-dollar-sign fa-2x mb-2"></i>
          <div class="stat-label">Revenue (MTD)</div>
          <div class="stat-number">$52K</div>
          <small><i class="fas fa-arrow-up"></i> 15% increase</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-12">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-list text-info me-2"></i>Recent Purchase Orders</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>PO Number</th>
                  <th>Branch</th>
                  <th>Items</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>PO-2024-1547</strong></td>
                  <td>Downtown Branch</td>
                  <td>15 items</td>
                  <td><strong>$2,450</strong></td>
                  <td><span class="badge bg-warning">Pending</span></td>
                  <td>Nov 15, 2025</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-success me-1">Fulfill</a>
                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                  </td>
                </tr>
                <tr>
                  <td><strong>PO-2024-1546</strong></td>
                  <td>Westside Branch</td>
                  <td>8 items</td>
                  <td><strong>$1,230</strong></td>
                  <td><span class="badge bg-info">In Transit</span></td>
                  <td>Nov 14, 2025</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-primary">Track</a>
                  </td>
                </tr>
                <tr>
                  <td><strong>PO-2024-1545</strong></td>
                  <td>Central Hub</td>
                  <td>22 items</td>
                  <td><strong>$3,890</strong></td>
                  <td><span class="badge bg-success">Delivered</span></td>
                  <td>Nov 12, 2025</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Details</a>
                  </td>
                </tr>
                <tr>
                  <td><strong>PO-2024-1544</strong></td>
                  <td>East Branch</td>
                  <td>12 items</td>
                  <td><strong>$1,780</strong></td>
                  <td><span class="badge bg-warning">Pending</span></td>
                  <td>Nov 16, 2025</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-success me-1">Fulfill</a>
                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  <?php elseif ($role === 'logistics_coordinator'): ?>
    <!-- Logistics Coordinator Dashboard -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="alert alert-secondary border-0 shadow-sm" style="border-left: 5px solid #6c757d;">
          <h5 class="alert-heading mb-2"><i class="fas fa-route me-2"></i>Logistics Coordinator Dashboard</h5>
          <p class="mb-0">Manage deliveries, routes, and fleet operations.</p>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #8e2de2; --card-color-2: #4a00e0;">
          <i class="fas fa-shipping-fast fa-2x mb-2"></i>
          <div class="stat-label">Active Deliveries</div>
          <div class="stat-number">34</div>
          <small><i class="fas fa-truck"></i> 12 vehicles in use</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #00d2ff; --card-color-2: #3a7bd5;">
          <i class="fas fa-clock fa-2x mb-2"></i>
          <div class="stat-label">Scheduled Today</div>
          <div class="stat-number">58</div>
          <small><i class="fas fa-calendar-check"></i> 24 remaining</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #f857a6; --card-color-2: #ff5858;">
          <i class="fas fa-map-marked-alt fa-2x mb-2"></i>
          <div class="stat-label">Routes Optimized</div>
          <div class="stat-number">142</div>
          <small><i class="fas fa-percent"></i> 18% fuel savings</small>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card" style="--card-color-1: #20bf55; --card-color-2: #01baef;">
          <i class="fas fa-check-double fa-2x mb-2"></i>
          <div class="stat-label">On-Time Rate</div>
          <div class="stat-number">96%</div>
          <small><i class="fas fa-thumbs-up"></i> Excellent performance</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="dashboard-card p-4">
          <h5 class="mb-4"><i class="fas fa-truck-loading text-secondary me-2"></i>Delivery Schedule</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Delivery ID </th>
                  <th>Destination</th>
                  <th>Vehicle</th>
                  <th>Driver</th>
                  <th>Status</th>
                  <th>ETA</th>
                  <th>Action</th>
  </tr>
  </thead>
<tbody>
  <tr>
    <td><strong>DLV-1001</strong></td>
    <td>Central Branch</td>
    <td>Truck #7</td>
    <td>Mark Santos</td>
    <td><span class="badge bg-info">En Route</span></td>
    <td>2:45 PM</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-primary">Track</a>
    </td>
  </tr>
  <tr>
    <td><strong>DLV-1002</strong></td>
    <td>East Branch</td>
    <td>Van #3</td>
    <td>Maria Reyes</td>
    <td><span class="badge bg-warning">Delayed</span></td>
    <td>3:10 PM</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-danger">Alert</a>
    </td>
  </tr>
  <tr>
    <td><strong>DLV-1003</strong></td>
    <td>North Warehouse</td>
    <td>Truck #12</td>
    <td>Carl Mendoza</td>
    <td><span class="badge bg-success">Delivered</span></td>
    <td>11:25 AM</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-secondary">Details</a>
    </td>
  </tr>
  <tr>
    <td><strong>DLV-1004</strong></td>
    <td>South Depot</td>
    <td>Truck #5</td>
    <td>Joey Cruz</td>
    <td><span class="badge bg-primary">Scheduled</span></td>
    <td>4:00 PM</td>
    <td>
      <a href="#" class="btn btn-sm btn-outline-primary">View</a>
    </td>
  </tr>
</tbody>
</table>
</div>
</div>
</div>
<div class="col-lg-4">
  <div class="dashboard-card p-4">
    <h5 class="mb-4"><i class="fas fa-tools text-primary me-2"></i>Quick Actions</h5>
    <div class="d-grid gap-3">
      <a href="<?= base_url('logistics/routes') ?>" class="btn btn-primary">
        <i class="fas fa-route me-2"></i>Manage Routes
      </a>
      <a href="<?= base_url('logistics/vehicles') ?>" class="btn btn-success">
        <i class="fas fa-truck me-2"></i>View Vehicles
      </a>
      <a href="<?= base_url('logistics/reports') ?>" class="btn btn-info">
        <i class="fas fa-file-alt me-2"></i>Generate Report
      </a>
      <a href="<?= base_url('logistics/drivers') ?>" class="btn btn-warning">
        <i class="fas fa-id-card me-2"></i>Manage Drivers
      </a>
    </div>
  </div>
</div>
</div>
<!-- End Logistics Coordinator Section -->

<?php else: ?>
  <div class="alert alert-secondary mt-5 text-center">
    <h5><i class="fas fa-user-lock me-2"></i>Access Restricted</h5>
    <p class="mb-0">You do not have permission to access this dashboard.</p>
  </div>
<?php endif; ?>

</div> <!-- End Container -->

<?= $this->endSection() ?>
