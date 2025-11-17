<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Reports & Analytics';
$title = 'Reports';
?>

<div class="mb-4">
    <h4>Reports & Analytics</h4>
    <p class="text-muted">Generate comprehensive reports for your supply chain operations</p>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1 text-primary mb-3"></i>
                <h5>Inventory Report</h5>
                <p class="text-muted">View inventory levels by branch, category, and product</p>
                <a href="<?= base_url('reports/inventory') ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-cart-check fs-1 text-success mb-3"></i>
                <h5>Purchase Orders</h5>
                <p class="text-muted">Track purchase orders by status, supplier, and date</p>
                <a href="<?= base_url('reports/purchase-orders') ?>" class="btn btn-success">
                    <i class="bi bi-arrow-right"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-truck fs-1 text-info mb-3"></i>
                <h5>Delivery Report</h5>
                <p class="text-muted">Monitor delivery status and performance</p>
                <a href="<?= base_url('reports/deliveries') ?>" class="btn btn-info">
                    <i class="bi bi-arrow-right"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-graph-up fs-1 text-warning mb-3"></i>
                <h5>Supplier Performance</h5>
                <p class="text-muted">Analyze supplier delivery times and completion rates</p>
                <a href="<?= base_url('reports/supplier-performance') ?>" class="btn btn-warning">
                    <i class="bi bi-arrow-right"></i> View Report
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
                <h5>Wastage Report</h5>
                <p class="text-muted">Track expired items and inventory wastage</p>
                <a href="<?= base_url('reports/wastage') ?>" class="btn btn-danger">
                    <i class="bi bi-arrow-right"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

