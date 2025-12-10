<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

    <?php 
    $role = $role ?? session()->get('role') ?? 'guest';
    $roleDisplay = ucwords(str_replace('_', ' ', $role));
    if ($role === 'central_admin') {
        $roleDisplay = 'Central Admin';
    } elseif ($role === 'branch_manager') {
        $branchName = session()->get('branch_name');
        $roleDisplay = $branchName ?: 'Branch Manager';
    }
?>

<div class="space-y-6">
    <div class="rounded-xl bg-gradient-to-r from-brand-600 to-indigo-700 text-white p-6 shadow-lg">
        <h2 class="text-2xl font-semibold mb-1">Welcome, <?= esc($roleDisplay) ?>!</h2>
        <p class="text-white/80 text-sm">Your role: <?= esc($roleDisplay) ?></p>
  </div>

  <?php if ($role === 'central_admin'): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Total Branches</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">3</div>
                <div class="text-xs text-green-600 mt-1">Stable</div>
        </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Total Products</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">12</div>
                <div class="text-xs text-slate-500 mt-1">Tracked items</div>
      </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Pending Requests</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
                <div class="text-xs text-green-600 mt-1">Up to date</div>
    </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Active Alerts</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
                <div class="text-xs text-slate-500 mt-1">All clear</div>
      </div>
    </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">System Management</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <a href="<?= base_url('users') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-people"></i> Users</div>
                        <p class="text-sm text-slate-500 mt-1">Manage system users</p>
                    </a>
                    <a href="<?= base_url('branches') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-building"></i> Branches</div>
                        <p class="text-sm text-slate-500 mt-1">Oversee branches</p>
                    </a>
                    <a href="<?= base_url('settings') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-gear"></i> Settings</div>
                        <p class="text-sm text-slate-500 mt-1">Configure platform</p>
                    </a>
                    <a href="<?= base_url('reports') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-graph-up"></i> Reports</div>
                        <p class="text-sm text-slate-500 mt-1">View analytics</p>
              </a>
            </div>
            </div>
            <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">Operations</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <a href="<?= base_url('purchase-requests') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-journal-plus"></i> Purchase Requests</div>
                        <p class="text-sm text-slate-500 mt-1">Approve requests</p>
                    </a>
                    <a href="<?= base_url('purchase-orders') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-receipt"></i> Purchase Orders</div>
                        <p class="text-sm text-slate-500 mt-1">Track orders</p>
                    </a>
                    <a href="<?= base_url('deliveries') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-truck"></i> Deliveries</div>
                        <p class="text-sm text-slate-500 mt-1">Monitor shipments</p>
                    </a>
                    <a href="<?= base_url('suppliers') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 text-slate-800 font-semibold"><i class="bi bi-briefcase"></i> Suppliers</div>
                        <p class="text-sm text-slate-500 mt-1">Manage suppliers</p>
                    </a>
        </div>
      </div>
    </div>

  <?php elseif ($role === 'branch_manager'): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Pending Requests</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
        </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Pending Orders</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">1</div>
      </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">In Transit</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
    </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Alerts</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
      </div>
    </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">Quick Actions</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <a href="<?= base_url('purchase-requests/create') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-journal-plus"></i>Create Request</div>
                        <p class="text-sm text-slate-500 mt-1">Request supplies</p>
                    </a>
                    <a href="<?= base_url('inventory') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-box-seam"></i>Inventory</div>
                        <p class="text-sm text-slate-500 mt-1">Check stock</p>
                    </a>
                    <a href="<?= base_url('deliveries') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-truck"></i>Deliveries</div>
                        <p class="text-sm text-slate-500 mt-1">Track incoming</p>
                    </a>
                    <a href="<?= base_url('reports') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                        <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-graph-up"></i>Reports</div>
                        <p class="text-sm text-slate-500 mt-1">Branch insights</p>
              </a>
            </div>
          </div>
            <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">Status</h3>
                <ul class="space-y-3 text-sm text-slate-700">
                    <li class="flex items-center gap-2"><i class="bi bi-check-circle text-green-600"></i> All deliveries on time</li>
                    <li class="flex items-center gap-2"><i class="bi bi-box text-slate-500"></i> Inventory synced</li>
                    <li class="flex items-center gap-2"><i class="bi bi-bell text-amber-500"></i> No pending alerts</li>
                </ul>
      </div>
    </div>

  <?php elseif ($role === 'inventory_staff'): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Stock Alerts</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
        </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Pending Deliveries</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
      </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Adjustments Today</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
    </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Scans</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
      </div>
    </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <a href="<?= base_url('inventory') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-box-seam"></i>Stock Overview</div>
                <p class="text-sm text-slate-500 mt-1">View inventory</p>
            </a>
            <a href="<?= base_url('inventory/history') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-clock-history"></i>History</div>
                <p class="text-sm text-slate-500 mt-1">Audit trail</p>
            </a>
            <a href="<?= base_url('barcode') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-upc-scan"></i>Scan Items</div>
                <p class="text-sm text-slate-500 mt-1">Barcode scanning</p>
            </a>
            <a href="<?= base_url('inventory-adjustments') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-pencil-square"></i>Adjust Stock</div>
                <p class="text-sm text-slate-500 mt-1">Post adjustments</p>
            </a>
    </div>

  <?php elseif ($role === 'supplier'): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Pending Orders</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
        </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">In Transit</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
      </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Completed</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
        </div>
      </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
            <a href="<?= base_url('purchase-orders') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-receipt"></i>Purchase Orders</div>
                <p class="text-sm text-slate-500 mt-1">View and fulfill</p>
            </a>
            <a href="<?= base_url('deliveries') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-truck"></i>Deliveries</div>
                <p class="text-sm text-slate-500 mt-1">Track shipments</p>
            </a>
            <a href="<?= base_url('notifications') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-bell"></i>Notifications</div>
                <p class="text-sm text-slate-500 mt-1">Latest updates</p>
            </a>
    </div>

  <?php elseif ($role === 'logistics_coordinator'): ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Active Deliveries</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
        </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">Scheduled Today</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">0</div>
      </div>
            <div class="rounded-xl bg-white p-4 shadow-sm border border-slate-100">
                <div class="text-slate-500 text-sm">On-Time Rate</div>
                <div class="text-2xl font-semibold text-slate-800 mt-2">—</div>
        </div>
      </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
            <a href="<?= base_url('deliveries') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-truck"></i>Deliveries</div>
                <p class="text-sm text-slate-500 mt-1">Manage routes</p>
            </a>
            <a href="<?= base_url('deliveries/create') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-plus-circle"></i>Schedule</div>
                <p class="text-sm text-slate-500 mt-1">Create delivery</p>
            </a>
            <a href="<?= base_url('purchase-orders') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-receipt"></i>Purchase Orders</div>
                <p class="text-sm text-slate-500 mt-1">Coordinate shipments</p>
            </a>
    </div>

    <?php elseif ($role === 'franchise_manager'): ?>
        <div class="rounded-xl bg-white p-5 shadow-sm border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Franchise Operations</h3>
            <div class="grid sm:grid-cols-2 gap-3">
                <a href="<?= base_url('suppliers') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                    <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-briefcase"></i>Suppliers</div>
                    <p class="text-sm text-slate-500 mt-1">Manage partners</p>
                </a>
                <a href="<?= base_url('reports') ?>" class="rounded-lg border border-slate-200 p-3 hover:border-brand-500 hover:bg-indigo-50 transition">
                    <div class="flex items-center gap-2 font-semibold text-slate-800"><i class="bi bi-graph-up"></i>Reports</div>
                    <p class="text-sm text-slate-500 mt-1">Performance</p>
      </a>
    </div>
  </div>

<?php else: ?>
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-100 text-center">
            <div class="text-2xl text-slate-700 font-semibold mb-2"><i class="bi bi-lock"></i> Access Restricted</div>
            <p class="text-slate-500">You do not have permission to access this dashboard.</p>
  </div>
<?php endif; ?>
</div>

<?= $this->endSection() ?>
