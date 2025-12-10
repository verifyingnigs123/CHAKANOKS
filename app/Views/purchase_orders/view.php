<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Purchase Order Details';
$title = 'Purchase Order Details';
?>

<!-- Action Bar -->
<div class="flex flex-wrap items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-700"><?= $po['po_number'] ?></h3>
    <div class="flex flex-wrap gap-2">
        <a href="<?= base_url('purchase-orders') ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <a href="<?= base_url('purchase-orders/print/' . $po['id']) ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-print mr-2"></i>Print
        </a>
    </div>
</div>

<?php
// Determine current step based on PO status and delivery status
$steps = [
    ['key' => 'draft', 'label' => 'Draft', 'icon' => 'fa-file-alt', 'desc' => 'PO Created'],
    ['key' => 'sent', 'label' => 'Sent', 'icon' => 'fa-paper-plane', 'desc' => 'Sent to Supplier'],
    ['key' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-check', 'desc' => 'Supplier Confirmed'],
    ['key' => 'prepared', 'label' => 'Prepared', 'icon' => 'fa-box', 'desc' => 'Ready for Pickup'],
    ['key' => 'scheduled', 'label' => 'Scheduled', 'icon' => 'fa-calendar-check', 'desc' => 'Delivery Scheduled'],
    ['key' => 'in_transit', 'label' => 'In Transit', 'icon' => 'fa-truck', 'desc' => 'On the Way'],
    ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'fa-check-double', 'desc' => 'Received'],
];

// Map PO status to step index
$statusMap = [
    'draft' => 0,
    'sent' => 1,
    'confirmed' => 2,
    'prepared' => 3,
    'partial' => 6,
    'completed' => 6,
];

$currentStep = $statusMap[$po['status']] ?? 0;

// Check delivery status if exists
if (isset($delivery) && $delivery) {
    if ($delivery['status'] == 'scheduled') {
        $currentStep = max($currentStep, 4);
    } elseif ($delivery['status'] == 'in_transit') {
        $currentStep = max($currentStep, 5);
    } elseif ($delivery['status'] == 'delivered') {
        $currentStep = 6;
    }
}

// If PO is completed or partial, it means delivered
if (in_array($po['status'], ['completed', 'partial'])) {
    $currentStep = 6;
}
?>

<!-- Order Progress Tracker -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-6 flex items-center">
        <i class="fas fa-route text-emerald-500 mr-2"></i>Order Progress
    </h3>
    
    <!-- Desktop Progress Bar -->
    <div class="hidden md:block">
        <div class="relative">
            <!-- Progress Line Background -->
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
            <!-- Progress Line Active -->
            <div class="absolute top-5 left-0 h-1 bg-emerald-500 rounded-full transition-all duration-500" style="width: <?= ($currentStep / (count($steps) - 1)) * 100 ?>%"></div>
            
            <!-- Steps -->
            <div class="relative flex justify-between">
                <?php foreach ($steps as $index => $step): ?>
                <?php 
                    $isCompleted = $index < $currentStep;
                    $isCurrent = $index == $currentStep;
                    $isPending = $index > $currentStep;
                ?>
                <div class="flex flex-col items-center" style="width: <?= 100 / count($steps) ?>%">
                    <!-- Step Circle -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 <?= 
                        $isCompleted ? 'bg-emerald-500 text-white' : 
                        ($isCurrent ? 'bg-emerald-500 text-white ring-4 ring-emerald-100' : 
                        'bg-gray-200 text-gray-400')
                    ?>">
                        <?php if ($isCompleted): ?>
                        <i class="fas fa-check text-sm"></i>
                        <?php else: ?>
                        <i class="fas <?= $step['icon'] ?> text-sm"></i>
                        <?php endif; ?>
                    </div>
                    <!-- Step Label -->
                    <div class="mt-3 text-center">
                        <p class="text-xs font-semibold <?= $isCurrent ? 'text-emerald-600' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') ?>"><?= $step['label'] ?></p>
                        <p class="text-xs <?= $isCurrent ? 'text-emerald-500' : 'text-gray-400' ?> mt-0.5"><?= $step['desc'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Mobile Progress (Vertical) -->
    <div class="md:hidden">
        <div class="relative">
            <?php foreach ($steps as $index => $step): ?>
            <?php 
                $isCompleted = $index < $currentStep;
                $isCurrent = $index == $currentStep;
                $isPending = $index > $currentStep;
                $isLast = $index == count($steps) - 1;
            ?>
            <div class="flex items-start <?= !$isLast ? 'pb-6' : '' ?>">
                <!-- Vertical Line -->
                <?php if (!$isLast): ?>
                <div class="absolute left-4 mt-10 w-0.5 h-6 <?= $isCompleted ? 'bg-emerald-500' : 'bg-gray-200' ?>"></div>
                <?php endif; ?>
                
                <!-- Step Circle -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 <?= 
                    $isCompleted ? 'bg-emerald-500 text-white' : 
                    ($isCurrent ? 'bg-emerald-500 text-white ring-4 ring-emerald-100' : 
                    'bg-gray-200 text-gray-400')
                ?>">
                    <?php if ($isCompleted): ?>
                    <i class="fas fa-check text-xs"></i>
                    <?php else: ?>
                    <i class="fas <?= $step['icon'] ?> text-xs"></i>
                    <?php endif; ?>
                </div>
                
                <!-- Step Content -->
                <div class="ml-4">
                    <p class="text-sm font-semibold <?= $isCurrent ? 'text-emerald-600' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') ?>"><?= $step['label'] ?></p>
                    <p class="text-xs <?= $isCurrent ? 'text-emerald-500' : 'text-gray-400' ?>"><?= $step['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Current Status Message -->
    <div class="mt-6 pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-<?= $currentStep == 6 ? 'emerald' : 'amber' ?>-100 flex items-center justify-center">
                    <i class="fas <?= $currentStep == 6 ? 'fa-check-circle text-emerald-600' : 'fa-clock text-amber-600' ?>"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">
                        <?php if ($currentStep == 6): ?>
                            Order Completed
                        <?php elseif ($currentStep == 5): ?>
                            Delivery In Transit
                        <?php elseif ($currentStep == 4): ?>
                            Delivery Scheduled - Awaiting Pickup
                        <?php elseif ($currentStep == 3): ?>
                            Prepared - Ready for Delivery Scheduling
                        <?php elseif ($currentStep == 2): ?>
                            Confirmed - Supplier is Preparing
                        <?php elseif ($currentStep == 1): ?>
                            Sent - Awaiting Supplier Confirmation
                        <?php else: ?>
                            Draft - Ready to Send
                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-gray-500">
                        <?php if ($currentStep < 6): ?>
                            Next: <?= $steps[$currentStep + 1]['desc'] ?? 'Complete' ?>
                        <?php else: ?>
                            All items have been received
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <?php if (isset($delivery) && $delivery): ?>
            <a href="<?= base_url('deliveries/view/' . $delivery['id']) ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                View Delivery <i class="fas fa-arrow-right ml-1"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Timeline with Timestamps -->
    <div class="mt-6 pt-4 border-t border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Activity Timeline</p>
        <div class="space-y-3 text-sm">
            <?php if ($po['created_at']): ?>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                <span class="text-gray-600">Created</span>
                <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($po['created_at'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($po['sent_at'])): ?>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                <span class="text-gray-600">Sent to Supplier</span>
                <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($po['sent_at'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($po['confirmed_at'])): ?>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                <span class="text-gray-600">Confirmed by Supplier</span>
                <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($po['confirmed_at'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($po['prepared_at'])): ?>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                <span class="text-gray-600">Prepared for Pickup</span>
                <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($po['prepared_at'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (isset($delivery) && $delivery): ?>
                <?php if (!empty($delivery['created_at'])): ?>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                    <span class="text-gray-600">Delivery Scheduled</span>
                    <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($delivery['created_at'])) ?></span>
                </div>
                <?php endif; ?>
                <?php if ($delivery['status'] == 'in_transit' || $delivery['status'] == 'delivered'): ?>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                    <span class="text-gray-600">In Transit</span>
                    <span class="text-gray-400 text-xs ml-auto"><?= $delivery['delivery_date'] ? date('M d, Y', strtotime($delivery['delivery_date'])) : '-' ?></span>
                </div>
                <?php endif; ?>
                <?php if ($delivery['status'] == 'delivered' && !empty($delivery['received_at'])): ?>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <span class="text-gray-600">Delivered & Received</span>
                    <span class="text-gray-400 text-xs ml-auto"><?= date('M d, Y h:i A', strtotime($delivery['received_at'])) ?></span>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- What's Next Action Card - Shows clear next action for each role -->
<?php 
// Determine if there's a specific action for this user
$hasAction = false;
$actionMessage = '';

if ($currentStep == 0 && $role == 'central_admin') {
    $hasAction = true;
    $actionMessage = 'Send this Purchase Order to the supplier for confirmation.';
} elseif ($currentStep == 1 && $role == 'supplier') {
    $hasAction = true;
    $actionMessage = 'Review and <strong>Accept</strong> this order to start preparing the items.';
} elseif ($currentStep == 1 && $role != 'supplier') {
    $hasAction = false;
    $actionMessage = 'Waiting for supplier to accept the order.';
} elseif ($currentStep == 2 && $role == 'supplier') {
    $hasAction = true;
    $actionMessage = 'Pack all items and click <strong>Mark as Prepared</strong> when ready for pickup.';
} elseif ($currentStep == 2 && $role != 'supplier') {
    $hasAction = false;
    $actionMessage = 'Supplier is preparing the items. Please wait.';
} elseif ($currentStep == 3 && in_array($role, ['logistics_coordinator', 'central_admin'])) {
    $hasAction = true;
    $actionMessage = 'Items are ready! <strong>Schedule a delivery</strong> to pick up from supplier.';
} elseif ($currentStep == 3) {
    $hasAction = false;
    $actionMessage = 'Items are prepared. Waiting for logistics to schedule delivery.';
} elseif ($currentStep == 4 && in_array($role, ['logistics_coordinator', 'central_admin'])) {
    $hasAction = true;
    $actionMessage = 'Delivery is scheduled. Click <strong>Dispatch</strong> when driver leaves for pickup.';
} elseif ($currentStep == 4) {
    $hasAction = false;
    $actionMessage = 'Delivery scheduled. Waiting for dispatch.';
} elseif ($currentStep == 5 && in_array($role, ['branch_manager', 'inventory_staff', 'central_admin'])) {
    $hasAction = true;
    $actionMessage = 'Delivery is on the way! <strong>Receive the delivery</strong> when it arrives at your branch.';
} elseif ($currentStep == 5) {
    $hasAction = false;
    $actionMessage = 'Delivery is in transit to the branch.';
}
?>

<?php if ($currentStep < 6 && !empty($actionMessage)): ?>
<div class="bg-gradient-to-r <?= $hasAction ? 'from-amber-50 to-orange-50 border-amber-200' : 'from-gray-50 to-slate-50 border-gray-200' ?> rounded-xl border p-4 mb-6">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 <?= $hasAction ? 'bg-amber-100' : 'bg-gray-100' ?> rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas <?= $hasAction ? 'fa-hand-point-right text-amber-600' : 'fa-clock text-gray-500' ?> text-xl"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-semibold <?= $hasAction ? 'text-amber-800' : 'text-gray-700' ?> mb-1"><?= $hasAction ? "What's Next?" : "Status Update" ?></h4>
            <p class="text-sm <?= $hasAction ? 'text-amber-700' : 'text-gray-600' ?>"><?= $actionMessage ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Status Banner for Supplier -->
<?php if ($role === 'supplier'): ?>
<div class="bg-gradient-to-r <?= 
    $po['status'] == 'sent' ? 'from-amber-500 to-orange-500' : 
    ($po['status'] == 'confirmed' ? 'from-blue-500 to-cyan-500' : 
    ($po['status'] == 'prepared' ? 'from-purple-500 to-pink-500' : 
    ($po['status'] == 'completed' ? 'from-emerald-500 to-teal-500' : 'from-gray-500 to-gray-600')))
?> rounded-xl p-4 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas <?= 
                    $po['status'] == 'sent' ? 'fa-inbox' : 
                    ($po['status'] == 'confirmed' ? 'fa-check' : 
                    ($po['status'] == 'prepared' ? 'fa-box' : 
                    ($po['status'] == 'completed' ? 'fa-check-double' : 'fa-file-alt')))
                ?> text-xl"></i>
            </div>
            <div>
                <p class="text-sm opacity-90">Order Status</p>
                <p class="text-xl font-bold"><?= ucfirst($po['status']) ?></p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm opacity-90">Total Amount</p>
            <p class="text-2xl font-bold">₱<?= number_format($po['total_amount'], 2) ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- PO Information -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Order Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-file-alt text-emerald-500 mr-2"></i>Order Details
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-500">PO Number:</span>
                <span class="font-semibold text-gray-800"><?= $po['po_number'] ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Order Date:</span>
                <span class="text-gray-800"><?= date('M d, Y', strtotime($po['order_date'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Expected Delivery:</span>
                <span class="text-gray-800"><?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : '-' ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium <?= 
                    $po['status'] == 'completed' ? 'bg-emerald-100 text-emerald-700' : 
                    ($po['status'] == 'sent' ? 'bg-amber-100 text-amber-700' : 
                    ($po['status'] == 'confirmed' ? 'bg-blue-100 text-blue-700' : 
                    ($po['status'] == 'prepared' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')))
                ?>"><?= ucfirst($po['status']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Payment Method:</span>
                <span class="px-2 py-1 rounded-full text-xs font-medium <?= 
                    ($po['payment_method'] ?? 'pending') == 'cod' ? 'bg-green-100 text-green-700' : 
                    (($po['payment_method'] ?? 'pending') == 'paypal' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600')
                ?>">
                    <?= ($po['payment_method'] ?? 'pending') == 'cod' ? 'Cash on Delivery' : 
                        (($po['payment_method'] ?? 'pending') == 'paypal' ? 'PayPal' : 'Pending') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Supplier/Branch Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-building text-blue-500 mr-2"></i><?= $role === 'supplier' ? 'Delivery To' : 'Supplier Info' ?>
        </h3>
        <div class="space-y-3">
            <?php if ($role === 'supplier'): ?>
            <div class="flex justify-between">
                <span class="text-gray-500">Branch:</span>
                <span class="font-semibold text-gray-800"><?= $po['branch_name'] ?></span>
            </div>
            <?php else: ?>
            <div class="flex justify-between">
                <span class="text-gray-500">Supplier:</span>
                <span class="font-semibold text-gray-800"><?= $po['supplier_name'] ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="text-gray-800"><?= $po['supplier_email'] ?? '-' ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Phone:</span>
                <span class="text-gray-800"><?= $po['supplier_phone'] ?? '-' ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Branch:</span>
                <span class="text-gray-800"><?= $po['branch_name'] ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between">
                <span class="text-gray-500">Created By:</span>
                <span class="text-gray-800"><?= $po['created_by_name'] ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Actions -->
<?php if ($role === 'supplier'): ?>
<div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-tasks text-purple-500 mr-2"></i>Your Actions
    </h3>
    
    <?php if ($po['status'] == 'sent'): ?>
    <!-- Pending Acceptance -->
    <div class="bg-white rounded-lg p-4 border border-amber-200 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-inbox text-amber-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">New Order Received</h4>
                <p class="text-sm text-gray-600 mt-1">Please review the order details and accept to start processing.</p>
                <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/confirm') ?>" class="mt-3">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-medium">
                        <i class="fas fa-check mr-2"></i>Accept Order
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($po['status'] == 'confirmed'): ?>
    <!-- Ready to Prepare -->
    <div class="bg-white rounded-lg p-4 border border-blue-200 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box text-blue-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">Order Confirmed - Ready to Prepare</h4>
                <p class="text-sm text-gray-600 mt-1">Pack the items listed below. Once ready, mark as prepared for pickup.</p>
                <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/prepare') ?>" class="mt-3">
                    <?= csrf_field() ?>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors font-medium">
                        <i class="fas fa-box mr-2"></i>Mark as Prepared
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($po['status'] == 'prepared'): ?>
    <!-- Awaiting Pickup -->
    <div class="bg-white rounded-lg p-4 border border-purple-200 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-truck-loading text-purple-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">Order Prepared - Awaiting Pickup</h4>
                <p class="text-sm text-gray-600 mt-1">The logistics team has been notified. Your order is ready for pickup.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" onclick="openDeliveryModal()" class="inline-flex items-center px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors text-sm font-medium">
                        <i class="fas fa-truck mr-2"></i>Update Delivery Status
                    </button>
                    <?php if (empty($po['invoice_number'])): ?>
                    <button type="button" onclick="openInvoiceModal()" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors text-sm font-medium">
                        <i class="fas fa-file-invoice mr-2"></i>Submit Invoice
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($po['status'] == 'completed'): ?>
    <!-- Order Completed -->
    <div class="bg-white rounded-lg p-4 border border-emerald-200">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-emerald-800">Order Completed</h4>
                <p class="text-sm text-gray-600 mt-1">This order has been successfully delivered and received.</p>
                <?php if (empty($po['invoice_number'])): ?>
                <button type="button" onclick="openInvoiceModal()" class="mt-3 inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors text-sm font-medium">
                    <i class="fas fa-file-invoice mr-2"></i>Submit Invoice
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Invoice Info (if submitted) -->
<?php if (!empty($po['invoice_number'])): ?>
<div class="bg-emerald-50 rounded-xl border border-emerald-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-emerald-800 mb-4 flex items-center">
        <i class="fas fa-file-invoice text-emerald-500 mr-2"></i>Invoice Submitted
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-sm text-emerald-600">Invoice Number</p>
            <p class="font-semibold text-emerald-800"><?= $po['invoice_number'] ?></p>
        </div>
        <div>
            <p class="text-sm text-emerald-600">Invoice Date</p>
            <p class="font-semibold text-emerald-800"><?= $po['invoice_date'] ? date('M d, Y', strtotime($po['invoice_date'])) : '-' ?></p>
        </div>
        <div>
            <p class="text-sm text-emerald-600">Invoice Amount</p>
            <p class="font-semibold text-emerald-800">₱<?= number_format($po['invoice_amount'] ?? $po['total_amount'], 2) ?></p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Order Items -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list text-amber-500 mr-2"></i>Order Items
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Qty</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Received</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Unit Price</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-gray-50" data-product-id="<?= $item['product_id'] ?>">
                    <td class="px-6 py-4 font-medium text-gray-800"><?= $item['product_name'] ?></td>
                    <td class="px-6 py-4 text-gray-500 font-mono text-sm"><?= $item['sku'] ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= $item['unit'] ?></td>
                    <td class="px-6 py-4 text-center font-medium"><?= $item['quantity'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium <?= ($item['quantity_received'] == $item['quantity']) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                            <?= $item['quantity_received'] ?> / <?= $item['quantity'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-600">₱<?= number_format($item['unit_price'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-gray-800">₱<?= number_format($item['total_price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="6" class="px-6 py-3 text-right font-medium text-gray-600">Subtotal:</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-800">₱<?= number_format($po['subtotal'], 2) ?></td>
                </tr>
                <tr>
                    <td colspan="6" class="px-6 py-3 text-right font-medium text-gray-600">Tax (12%):</td>
                    <td class="px-6 py-3 text-right font-medium text-gray-800">₱<?= number_format($po['tax'], 2) ?></td>
                </tr>
                <tr class="border-t-2 border-gray-200">
                    <td colspan="6" class="px-6 py-4 text-right font-bold text-gray-800">Total Amount:</td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-600 text-lg">₱<?= number_format($po['total_amount'], 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Admin/Branch Actions -->
<?php if ($role !== 'supplier'): ?>
<div class="flex flex-wrap gap-3">
    <?php if ($po['status'] == 'draft'): ?>
    <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/send') ?>" class="inline">
        <?= csrf_field() ?>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
            <i class="fas fa-paper-plane mr-2"></i>Send to Supplier
        </button>
    </form>
    <?php endif; ?>
    
    <?php if ($po['status'] == 'sent' && $role !== 'supplier'): ?>
    <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/confirm') ?>" class="inline">
        <?= csrf_field() ?>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
            <i class="fas fa-check mr-2"></i>Mark as Confirmed
        </button>
    </form>
    <?php endif; ?>
    
    <?php if (in_array($po['status'], ['sent', 'confirmed', 'prepared']) && in_array($role, ['central_admin', 'logistics_coordinator'])): ?>
    <a href="<?= base_url('deliveries/create?po_id=' . $po['id']) ?>" class="inline-flex items-center px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
        <i class="fas fa-truck mr-2"></i>Schedule Delivery
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Delivery Status Modal -->
<div id="deliveryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeliveryModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/update-delivery-status') ?>">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-truck text-cyan-500 mr-2"></i>Update Delivery Status
                    </h3>
                    <button type="button" onclick="closeDeliveryModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Status</label>
                        <select name="delivery_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="">Select Status</option>
                            <option value="preparing">Preparing for Shipment</option>
                            <option value="shipped">Shipped / In Transit</option>
                            <option value="out_for_delivery">Out for Delivery</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tracking Number (Optional)</label>
                        <input type="text" name="tracking_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" placeholder="Enter tracking number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea name="delivery_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" placeholder="Any delivery notes..."></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeDeliveryModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div id="invoiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeInvoiceModal()"></div>
        
        <div class="relative bg-white rounded-xl shadow-xl transform transition-all sm:max-w-md sm:w-full mx-auto">
            <form method="post" action="<?= base_url('purchase-orders/' . $po['id'] . '/submit-invoice') ?>">
                <?= csrf_field() ?>
                
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-invoice text-emerald-500 mr-2"></i>Submit Invoice
                    </h3>
                    <button type="button" onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number <span class="text-red-500">*</span></label>
                        <input type="text" name="invoice_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g., INV-2024-001">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date <span class="text-red-500">*</span></label>
                        <input type="date" name="invoice_date" required value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Amount <span class="text-red-500">*</span></label>
                        <input type="number" name="invoice_amount" step="0.01" required value="<?= $po['total_amount'] ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-xs text-gray-500 mt-1">PO Total: ₱<?= number_format($po['total_amount'], 2) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea name="invoice_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Any invoice notes..."></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeInvoiceModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openDeliveryModal() {
    document.getElementById('deliveryModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeliveryModal() {
    document.getElementById('deliveryModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openInvoiceModal() {
    document.getElementById('invoiceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeliveryModal();
        closeInvoiceModal();
    }
});
</script>
<?= $this->endSection() ?>
