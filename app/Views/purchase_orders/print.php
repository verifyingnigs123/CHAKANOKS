<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?= esc($po['po_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info-section { margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .totals { margin-top: 20px; }
        .totals table { width: 300px; margin-left: auto; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <div class="header">
        <h2>PURCHASE ORDER</h2>
        <h3>PO Number: <?= esc($po['po_number']) ?></h3>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div>
                <strong>Supplier:</strong> <?= esc($po['supplier_name']) ?><br>
                <strong>Email:</strong> <?= esc($po['supplier_email'] ?? 'N/A') ?><br>
                <strong>Phone:</strong> <?= esc($po['supplier_phone'] ?? 'N/A') ?>
            </div>
            <div>
                <strong>Branch:</strong> <?= esc($po['branch_name']) ?><br>
                <strong>Order Date:</strong> <?= date('M d, Y', strtotime($po['order_date'])) ?><br>
                <strong>Expected Delivery:</strong> <?= $po['expected_delivery_date'] ? date('M d, Y', strtotime($po['expected_delivery_date'])) : 'N/A' ?>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['sku']) ?></td>
                    <td><?= $item['quantity'] ?> <?= esc($item['unit']) ?></td>
                    <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                    <td>₱<?= number_format($item['total_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <th>Subtotal:</th>
                <td class="text-right">₱<?= number_format($po['subtotal'], 2) ?></td>
            </tr>
            <tr>
                <th>Tax (12%):</th>
                <td class="text-right">₱<?= number_format($po['tax'], 2) ?></td>
            </tr>
            <tr>
                <th><strong>Total Amount:</strong></th>
                <td class="text-right"><strong>₱<?= number_format($po['total_amount'], 2) ?></strong></td>
            </tr>
        </table>
    </div>

    <?php if ($po['notes']): ?>
    <div style="margin-top: 30px;">
        <strong>Notes:</strong><br>
        <?= nl2br(esc($po['notes'])) ?>
    </div>
    <?php endif; ?>

    <div style="margin-top: 50px; text-align: right;">
        <p>Prepared by: <?= esc($po['created_by_name']) ?></p>
        <p>Date: <?= date('M d, Y H:i', strtotime($po['created_at'])) ?></p>
    </div>
</body>
</html>

