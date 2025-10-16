<?= $this->extend('design/template') ?>

<?= $this->section('content') ?>

<!-- Quick Actions Section -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="quick-action-card">
            <div class="qa-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="bi bi-plus-circle"></i>
            </div>
            <h5>New Order</h5>
            <p>Create a new purchase order</p>
            <a href="<?= base_url('orders/create') ?>" class="qa-btn">Create Now</a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="quick-action-card">
            <div class="qa-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="bi bi-box-seam"></i>
            </div>
            <h5>Add Product</h5>
            <p>Add new product to inventory</p>
            <a href="<?= base_url('products/create') ?>" class="qa-btn">Add Product</a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="quick-action-card">
            <div class="qa-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="bi bi-truck"></i>
            </div>
            <h5>New Shipment</h5>
            <p>Track a new shipment</p>
            <a href="<?= base_url('shipments/create') ?>" class="qa-btn">Create Shipment</a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="quick-action-card">
            <div class="qa-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <h5>Generate Report</h5>
            <p>Create custom reports</p>
            <a href="<?= base_url('reports') ?>" class="qa-btn">View Reports</a>
        </div>
    </div>
</div>

<!-- Charts and Analytics Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="bi bi-graph-up"></i> Sales & Inventory Overview
                </h5>
                <div class="card-actions">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 3 Months</option>
                        <option>Last Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body-custom">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="bi bi-pie-chart"></i> Stock Distribution
                </h5>
            </div>
            <div class="card-body-custom">
                <canvas id="stockChart" height="300"></canvas>
                <div class="stock-legend mt-3">
                    <div class="legend-item">
                        <span class="legend-color" style="background: #3498db;"></span>
                        <span>In Stock (68%)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #e74c3c;"></span>
                        <span>Low Stock (22%)</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background: #95a5a6;"></span>
                        <span>Out of Stock (10%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Alerts -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="bi bi-clock-history"></i> Recent Activities
                </h5>
                <a href="<?= base_url('activities') ?>" class="view-all-link">View All</a>
            </div>
            <div class="card-body-custom p-0">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #e3f2fd; color: #2196f3;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">New product added to inventory</p>
                            <p class="activity-meta">
                                <span class="activity-user">John Doe</span> • 
                                <span class="activity-time">5 minutes ago</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #fff3e0; color: #ff9800;">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Order #ORD-2024-1523 completed</p>
                            <p class="activity-meta">
                                <span class="activity-user">System</span> • 
                                <span class="activity-time">15 minutes ago</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #f3e5f5; color: #9c27b0;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Shipment #SHP-8723 in transit</p>
                            <p class="activity-meta">
                                <span class="activity-user">Logistics Team</span> • 
                                <span class="activity-time">1 hour ago</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #e8f5e9; color: #4caf50;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">New supplier registered</p>
                            <p class="activity-meta">
                                <span class="activity-user">Maria Santos</span> • 
                                <span class="activity-time">2 hours ago</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: #fce4ec; color: #e91e63;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-title">Low stock alert for 3 items</p>
                            <p class="activity-meta">
                                <span class="activity-user">System</span> • 
                                <span class="activity-time">3 hours ago</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="bi bi-exclamation-circle"></i> Alerts & Notifications
                </h5>
                <a href="<?= base_url('alerts') ?>" class="view-all-link">View All</a>
            </div>
            <div class="card-body-custom p-0">
                <div class="alert-list">
                    <div class="alert-item alert-danger-custom">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h6>Critical Stock Level</h6>
                            <p>5 products are below minimum stock threshold</p>
                            <a href="<?= base_url('inventory?filter=low') ?>" class="alert-action">Review Items</a>
                        </div>
                    </div>
                    
                    <div class="alert-item alert-warning-custom">
                        <div class="alert-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h6>Pending Approvals</h6>
                            <p>8 purchase orders awaiting your approval</p>
                            <a href="<?= base_url('purchase-orders?status=pending') ?>" class="alert-action">Review Orders</a>
                        </div>
                    </div>
                    
                    <div class="alert-item alert-info-custom">
                        <div class="alert-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="alert-content">
                            <h6>Delayed Shipments</h6>
                            <p>3 shipments are experiencing delays</p>
                            <a href="<?= base_url('shipments?status=delayed') ?>" class="alert-action">Track Shipments</a>
                        </div>
                    </div>
                    
                    <div class="alert-item alert-success-custom">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h6>Monthly Target Achieved</h6>
                            <p>You've reached 102% of your monthly target</p>
                            <a href="<?= base_url('reports') ?>" class="alert-action">View Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="row g-4">
    <div class="col-12">
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="bi bi-cart-check"></i> Recent Orders
                </h5>
                <a href="<?= base_url('orders') ?>" class="view-all-link">View All Orders</a>
            </div>
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-hover custom-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>#ORD-2024-1526</strong></td>
                                <td>ABC Corporation</td>
                                <td>Industrial Equipment</td>
                                <td>50 units</td>
                                <td>₱125,000.00</td>
                                <td><span class="status-badge status-processing">Processing</span></td>
                                <td>Oct 16, 2025</td>
                                <td>
                                    <a href="<?= base_url('orders/view/1526') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2024-1525</strong></td>
                                <td>XYZ Supplies Ltd.</td>
                                <td>Office Supplies</td>
                                <td>200 units</td>
                                <td>₱45,500.00</td>
                                <td><span class="status-badge status-shipped">Shipped</span></td>
                                <td>Oct 15, 2025</td>
                                <td>
                                    <a href="<?= base_url('orders/view/1525') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2024-1524</strong></td>
                                <td>Global Trading Inc.</td>
                                <td>Raw Materials</td>
                                <td>1000 kg</td>
                                <td>₱350,000.00</td>
                                <td><span class="status-badge status-delivered">Delivered</span></td>
                                <td>Oct 14, 2025</td>
                                <td>
                                    <a href="<?= base_url('orders/view/1524') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2024-1523</strong></td>
                                <td>Tech Solutions Co.</td>
                                <td>Electronics</td>
                                <td>75 units</td>
                                <td>₱280,000.00</td>
                                <td><span class="status-badge status-delivered">Delivered</span></td>
                                <td>Oct 13, 2025</td>
                                <td>
                                    <a href="<?= base_url('orders/view/1523') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>#ORD-2024-1522</strong></td>
                                <td>BuildPro Constructions</td>
                                <td>Construction Materials</td>
                                <td>500 units</td>
                                <td>₱550,000.00</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>Oct 12, 2025</td>
                                <td>
                                    <a href="<?= base_url('orders/view/1522') ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Quick Action Cards */
    .quick-action-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .qa-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 32px;
        color: white;
    }
    
    .quick-action-card h5 {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .quick-action-card p {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .qa-btn {
        display: inline-block;
        padding: 10px 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .qa-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    /* Content Cards */
    .content-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        height: 100%;
    }
    
    .card-header-custom {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title-custom {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-title-custom i {
        color: #3498db;
    }
    
    .view-all-link {
        color: #3498db;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .view-all-link:hover {
        color: #2980b9;
    }
    
    .card-body-custom {
        padding: 25px;
    }
    
    .card-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Activity List */
    .activity-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        gap: 15px;
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background: #f8f9fa;
    }
    
    .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 5px 0;
    }
    
    .activity-meta {
        font-size: 12px;
        color: #95a5a6;
        margin: 0;
    }
    
    .activity-user {
        color: #3498db;
        font-weight: 600;
    }
    
    /* Alert List */
    .alert-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    
    .alert-item {
        display: flex;
        gap: 15px;
        padding: 20px 25px;
        border-left: 4px solid;
        background: white;
    }
    
    .alert-danger-custom {
        border-left-color: #e74c3c;
        background: #fef5f5;
    }
    
    .alert-warning-custom {
        border-left-color: #f39c12;
        background: #fffcf5;
    }
    
    .alert-info-custom {
        border-left-color: #3498db;
        background: #f5f9fc;
    }
    
    .alert-success-custom {
        border-left-color: #27ae60;
        background: #f5fdf8;
    }
    
    .alert-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .alert-danger-custom .alert-icon {
        background: #fadbd8;
        color: #e74c3c;
    }
    
    .alert-warning-custom .alert-icon {
        background: #fdebd0;
        color: #f39c12;
    }
    
    .alert-info-custom .alert-icon {
        background: #d6eaf8;
        color: #3498db;
    }
    
    .alert-success-custom .alert-icon {
        background: #d5f4e6;
        color: #27ae60;
    }
    
    .alert-content h6 {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 5px 0;
    }
    
    .alert-content p {
        font-size: 13px;
        color: #7f8c8d;
        margin: 0 0 10px 0;
    }
    
    .alert-action {
        font-size: 13px;
        font-weight: 600;
        color: #3498db;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .alert-action:hover {
        color: #2980b9;
        text-decoration: underline;
    }
    
    /* Stock Legend */
    .stock-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #5a6c7d;
    }
    
    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    /* Custom Table */
    .custom-table {
        margin: 0;
    }
    
    .custom-table thead {
        background: #f8f9fa;
    }
    
    .custom-table thead th {
        font-size: 13px;
        font-weight: 700;
        color: #5a6c7d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 20px;
        border: none;
    }
    
    .custom-table tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        font-size: 14px;
        color: #2c3e50;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .custom-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background: #cfe2ff;
        color: #084298;
    }
    
    .status-shipped {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-delivered {
        background: #d4edda;
        color: #155724;
    }
</style>

<!-- Chart.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Sales',
                data: [45000, 52000, 48000, 65000, 58000, 72000, 68000],
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }, {
                label: 'Orders',
                data: [35000, 42000, 38000, 55000, 48000, 62000, 58000],
                borderColor: '#e74c3c',
                backgroundColor: 'rgba(231, 76, 60, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 13,
                            weight: 600
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Stock Distribution Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [68, 22, 10],
                backgroundColor: ['#3498db', '#e74c3c', '#95a5a6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
</script>

<?= $this->endSection() ?>