<?= $this->extend('design/template') ?> 
<?= $this->section('content') ?>

<div class="container py-5">
  <div class="card shadow p-4">
    <h2 class="text-center mb-4">Welcome, <?= esc($username) ?>!</h2>
    <h5 class="text-center text-muted">Your Role: <?= esc(ucwords(str_replace('_', ' ', $role))) ?></h5>

    <hr>

    <?php if ($role === 'system_admin'): ?>
      <div class="alert alert-primary">System Administrator Panel — Manage everything here.</div>

    <?php elseif ($role === 'branch_manager'): ?>
      <div class="alert alert-success">Branch Manager Dashboard — Monitor branch performance.</div>

    <?php elseif ($role === 'inventory_staff'): ?>
      <div class="alert alert-warning">Inventory Staff Dashboard — Track and update stock.</div>

    <?php elseif ($role === 'supplier'): ?>
      <div class="alert alert-info">Supplier Dashboard — View and fulfill purchase orders.</div>

    <?php elseif ($role === 'logistics_coordinator'): ?>
      <div class="alert alert-secondary">Logistics Coordinator Dashboard — Manage deliveries and routes.</div>

    <?php elseif ($role === 'franchise_manager'): ?>
      <div class="alert alert-dark">Franchise Manager Dashboard — Oversee franchise operations.</div>

    <?php else: ?>
      <div class="alert alert-danger">Unknown role detected.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger">Logout</a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
