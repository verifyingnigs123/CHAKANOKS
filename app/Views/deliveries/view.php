<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'Delivery Details';
$title = 'Delivery Details';
?>

<!-- Action Bar -->
<div class="flex flex-wrap items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-gray-700"><?= esc($delivery['delivery_number']) ?></h3>
    <div class="flex gap-2">
        <a href="<?= base_url('deliveries') ?>" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
        <a href="<?= base_url('deliveries/print/' . $delivery['id']) ?>" target="_blank"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-print mr-2"></i> Print
        </a>
    </div>
</div>

<?php
// Delivery Progress Steps
$deliverySteps = [
    ['key' => 'scheduled', 'label' => 'Scheduled', 'icon' => 'fa-calendar-check', 'desc' => 'Delivery scheduled'],
    ['key' => 'in_transit', 'label' => 'In Transit', 'icon' => 'fa-truck', 'desc' => 'On the way'],
    ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'fa-box-open', 'desc' => 'Goods received'],
    ['key' => 'paid', 'label' => 'Paid', 'icon' => 'fa-check-circle', 'desc' => 'Payment complete'],
];

$currentDeliveryStep = 0;
if ($delivery['status'] == 'scheduled') {
    $currentDeliveryStep = 0;
} elseif ($delivery['status'] == 'in_transit') {
    $currentDeliveryStep = 1;
} elseif ($delivery['status'] == 'delivered') {
    $currentDeliveryStep = 2;
    if (!empty($payment_transaction) && $payment_transaction['status'] == 'completed') {
        $currentDeliveryStep = 3;
    }
}
?>

<!-- Delivery Progress Tracker -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-6 flex items-center">
        <i class="fas fa-shipping-fast text-blue-500 mr-2"></i>Delivery Progress
    </h3>
    
    <!-- Desktop Progress Bar -->
    <div class="hidden md:block">
        <div class="relative">
            <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
            <div class="absolute top-5 left-0 h-1 bg-blue-500 rounded-full transition-all duration-500" style="width: <?= ($currentDeliveryStep / (count($deliverySteps) - 1)) * 100 ?>%"></div>
            
            <div class="relative flex justify-between">
                <?php foreach ($deliverySteps as $index => $step): ?>
                <?php 
                    $isCompleted = $index < $currentDeliveryStep;
                    $isCurrent = $index == $currentDeliveryStep;
                ?>
                <div class="flex flex-col items-center" style="width: <?= 100 / count($deliverySteps) ?>%">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 <?= 
                        $isCompleted ? 'bg-blue-500 text-white' : 
                        ($isCurrent ? 'bg-blue-500 text-white ring-4 ring-blue-100' : 'bg-gray-200 text-gray-400')
                    ?>">
                        <?php if ($isCompleted): ?>
                        <i class="fas fa-check text-sm"></i>
                        <?php else: ?>
                        <i class="fas <?= $step['icon'] ?> text-sm"></i>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-xs font-semibold <?= $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') ?>"><?= $step['label'] ?></p>
                        <p class="text-xs <?= $isCurrent ? 'text-blue-500' : 'text-gray-400' ?> mt-0.5"><?= $step['desc'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Mobile Progress -->
    <div class="md:hidden space-y-4">
        <?php foreach ($deliverySteps as $index => $step): ?>
        <?php 
            $isCompleted = $index < $currentDeliveryStep;
            $isCurrent = $index == $currentDeliveryStep;
        ?>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 <?= 
                $isCompleted ? 'bg-blue-500 text-white' : 
                ($isCurrent ? 'bg-blue-500 text-white ring-4 ring-blue-100' : 'bg-gray-200 text-gray-400')
            ?>">
                <?php if ($isCompleted): ?>
                <i class="fas fa-check text-xs"></i>
                <?php else: ?>
                <i class="fas <?= $step['icon'] ?> text-xs"></i>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm font-semibold <?= $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') ?>"><?= $step['label'] ?></p>
                <p class="text-xs <?= $isCurrent ? 'text-blue-500' : 'text-gray-400' ?>"><?= $step['desc'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Current Status -->
    <div class="mt-6 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-<?= $currentDeliveryStep >= 2 ? 'emerald' : 'blue' ?>-100 flex items-center justify-center">
                <i class="fas <?= $currentDeliveryStep >= 3 ? 'fa-check-circle text-emerald-600' : ($currentDeliveryStep >= 2 ? 'fa-box-open text-emerald-600' : ($currentDeliveryStep == 1 ? 'fa-truck text-blue-600' : 'fa-calendar-check text-blue-600')) ?>"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">
                    <?php if ($currentDeliveryStep >= 3): ?>
                        Delivery Complete & Paid
                    <?php elseif ($currentDeliveryStep == 2): ?>
                        Delivered - <?= ($delivery['payment_method'] ?? 'pending') == 'pending' ? 'Awaiting Payment' : 'Payment Processing' ?>
                    <?php elseif ($currentDeliveryStep == 1): ?>
                        In Transit - On the way to <?= esc($delivery['branch_name']) ?>
                    <?php else: ?>
                        Scheduled for <?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'delivery' ?>
                    <?php endif; ?>
                </p>
                <p class="text-xs text-gray-500">
                    <?php if ($currentDeliveryStep < 3): ?>
                        Next: <?= $deliverySteps[$currentDeliveryStep + 1]['desc'] ?? 'Complete' ?>
                    <?php else: ?>
                        Transaction completed successfully
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Information Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i> Delivery Information
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Delivery Number</span>
                    <span class="font-semibold text-gray-800"><?= esc($delivery['delivery_number']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">PO Number</span>
                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= esc($delivery['po_number']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Supplier</span>
                    <span class="text-gray-800"><?= esc($delivery['supplier_name']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Branch</span>
                    <span class="text-gray-800"><?= esc($delivery['branch_name']) ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Scheduled Date</span>
                    <span class="text-gray-800"><?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : '-' ?></span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Status</span>
                    <?php if ($delivery['status'] == 'delivered'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                        <i class="fas fa-check-circle mr-1"></i> Delivered
                    </span>
                    <?php elseif ($delivery['status'] == 'in_transit'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                        <i class="fas fa-truck mr-1"></i> In Transit
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700">
                        <i class="fas fa-calendar mr-1"></i> Scheduled
                    </span>
                    <?php endif; ?>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Delivery Date</span>
                    <span class="text-gray-800"><?= $delivery['delivery_date'] ? date('M d, Y', strtotime($delivery['delivery_date'])) : '-' ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Driver</span>
                    <span class="text-gray-800"><?= esc($delivery['driver_name'] ?? '-') ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Vehicle</span>
                    <span class="text-gray-800"><?= esc($delivery['vehicle_number'] ?? '-') ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Payment Method</span>
                    <?php $pm = $delivery['payment_method'] ?? 'pending'; ?>
                    <?php if ($pm == 'cod'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                        <i class="fas fa-money-bill-wave mr-1"></i> COD
                    </span>
                    <?php elseif ($pm == 'paypal'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                        <i class="fab fa-paypal mr-1"></i> PayPal
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">Pending</span>
                    <?php endif; ?>
                </div>
                <?php if ($delivery['received_by_name']): ?>
                <div class="flex justify-between py-2 border-t border-gray-100">
                    <span class="text-gray-500">Received By</span>
                    <span class="text-gray-800"><?= esc($delivery['received_by_name']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($delivery['notes']): ?>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <p class="text-gray-500 text-sm mb-2">Notes</p>
            <p class="text-gray-700 bg-gray-50 p-3 rounded-lg"><?= esc($delivery['notes']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($delivery['status'] != 'delivered' && in_array($role, ['logistics_coordinator', 'central_admin'])): ?>
<!-- Logistics Actions Card -->
<div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-tasks text-purple-500 mr-2"></i>Logistics Actions
    </h3>
    
    <?php if ($delivery['status'] == 'scheduled'): ?>
    <!-- Scheduled - Ready to Dispatch -->
    <div class="bg-white rounded-lg p-4 border border-blue-200 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">Delivery Scheduled</h4>
                <p class="text-sm text-gray-600 mt-1">Driver: <?= esc($delivery['driver_name'] ?? 'Not assigned') ?> • Vehicle: <?= esc($delivery['vehicle_number'] ?? 'N/A') ?></p>
                <p class="text-sm text-gray-500 mt-1">Scheduled for: <?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'Not set' ?></p>
                <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="status" value="in_transit">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors font-medium">
                        <i class="fas fa-truck mr-2"></i>Dispatch Now
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($delivery['status'] == 'in_transit'): ?>
    <!-- In Transit -->
    <div class="bg-white rounded-lg p-4 border border-purple-200 mb-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-truck text-purple-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800">In Transit</h4>
                <p class="text-sm text-gray-600 mt-1">Driver: <?= esc($delivery['driver_name'] ?? 'Not assigned') ?> is on the way to <?= esc($delivery['branch_name']) ?></p>
                <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>" class="mt-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-medium">
                        <i class="fas fa-check-circle mr-2"></i>Mark as Delivered
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Manual Status Update -->
    <div class="bg-white rounded-lg p-4 border border-gray-200">
        <p class="text-sm text-gray-500 mb-3">Or manually update status:</p>
        <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>">
            <?= csrf_field() ?>
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="status" required class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-purple-500 outline-none text-sm">
                    <option value="scheduled" <?= $delivery['status'] == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="in_transit" <?= $delivery['status'] == 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="delivered" <?= $delivery['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors text-sm">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
<?php elseif ($delivery['status'] != 'delivered'): ?>
<!-- Update Status Card for other roles -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-sync-alt text-purple-500 mr-2"></i> Update Status
        </h3>
    </div>
    <div class="p-6">
        <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/update-status') ?>">
            <?= csrf_field() ?>
            <div class="flex flex-col sm:flex-row gap-4">
                <select name="status" required class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-purple-500 outline-none">
                    <option value="scheduled" <?= $delivery['status'] == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="in_transit" <?= $delivery['status'] == 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="delivered" <?= $delivery['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Status
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (($delivery['status'] == 'in_transit' || $delivery['status'] == 'scheduled') && in_array($role, ['central_admin', 'branch_manager', 'inventory_staff'])): ?>
<!-- Receive Delivery Card -->
<div class="bg-white rounded-xl shadow-sm border border-emerald-200 mb-6">
    <div class="px-6 py-4 border-b border-emerald-100 bg-emerald-50 rounded-t-xl">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-box-open text-emerald-500 mr-2"></i> Receive Delivery
        </h3>
    </div>
    <div class="p-6">
        <?php if (!empty($po) && ($po['payment_method'] ?? 'pending') == 'pending'): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4 flex items-start">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-3"></i>
            <div>
                <p class="font-medium text-amber-800">Payment Method Not Selected</p>
                <p class="text-amber-700 text-sm">Please update the payment method in the Purchase Order before receiving.</p>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
            <div>
                <p class="font-medium text-blue-800">Payment: <?= ($po['payment_method'] ?? 'pending') == 'cod' ? 'Cash on Delivery' : (($po['payment_method'] ?? 'pending') == 'paypal' ? 'PayPal' : 'Pending') ?></p>
                <p class="text-blue-700 text-sm">Total: <span class="font-semibold">₱<?= number_format($po['total_amount'] ?? 0, 2) ?></span></p>
            </div>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('deliveries/' . $delivery['id'] . '/receive') ?>" id="receiveForm">
            <?= csrf_field() ?>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Ordered</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Received</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Batch #</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Expiry</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($po_items as $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800"><?= esc($item['product_name']) ?></td>
                            <td class="px-4 py-3"><span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['sku']) ?></span></td>
                            <td class="px-4 py-3 text-center text-gray-700"><?= $item['quantity'] ?></td>
                            <td class="px-4 py-3">
                                <input type="hidden" name="products[]" value="<?= $item['product_id'] ?>">
                                <input type="number" name="quantities[]" class="w-20 mx-auto block px-3 py-2 bg-white border border-gray-200 rounded-lg text-center focus:border-emerald-500 outline-none" min="0" max="<?= $item['quantity'] ?>" value="<?= $item['quantity'] ?>" required>
                            </td>
                            <td class="px-4 py-3"><input type="text" name="batch_numbers[]" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:border-emerald-500 outline-none" placeholder="Optional"></td>
                            <td class="px-4 py-3"><input type="date" name="expiry_dates[]" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg focus:border-emerald-500 outline-none"></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <button type="submit" class="mt-6 w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-check-circle mr-2"></i> Receive Delivery & Update Inventory
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($payment_transaction)): ?>
<!-- Payment Information Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-credit-card text-emerald-500 mr-2"></i> Payment Information
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Transaction #</span>
                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded"><?= esc($payment_transaction['transaction_number']) ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Method</span>
                    <span class="text-gray-800"><?= $payment_transaction['payment_method'] == 'cod' ? 'Cash on Delivery' : 'PayPal' ?></span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Amount</span>
                    <span class="font-semibold text-gray-800">₱<?= number_format($payment_transaction['amount'], 2) ?></span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Status</span>
                    <?php if ($payment_transaction['status'] == 'completed'): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                        <i class="fas fa-check-circle mr-1"></i> Completed
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700">
                        <i class="fas fa-clock mr-1"></i> Pending
                    </span>
                    <?php endif; ?>
                </div>
                <?php if ($payment_transaction['payment_date']): ?>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Payment Date</span>
                    <span class="text-gray-800"><?= date('M d, Y H:i', strtotime($payment_transaction['payment_date'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Order Items Card -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800 flex items-center">
            <i class="fas fa-list text-gray-500 mr-2"></i> Order Items
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Unit</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Unit Price</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $grandTotal = 0; ?>
                <?php foreach ($po_items as $item): ?>
                <?php $grandTotal += $item['total_price']; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800"><?= esc($item['product_name']) ?></td>
                    <td class="px-6 py-4"><span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['sku']) ?></span></td>
                    <td class="px-6 py-4 text-gray-600"><?= esc($item['unit']) ?></td>
                    <td class="px-6 py-4 text-center text-gray-700"><?= $item['quantity'] ?></td>
                    <td class="px-6 py-4 text-right text-gray-700">₱<?= number_format($item['unit_price'], 2) ?></td>
                    <td class="px-6 py-4 text-right font-medium text-gray-800">₱<?= number_format($item['total_price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-right font-semibold text-gray-700">Grand Total</td>
                    <td class="px-6 py-4 text-right font-bold text-lg text-emerald-600">₱<?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
