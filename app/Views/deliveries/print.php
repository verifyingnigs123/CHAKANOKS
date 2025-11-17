<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Receipt - <?= esc($delivery['delivery_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info-section { margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
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
        <h2>DELIVERY RECEIPT</h2>
        <h3>Delivery Number: <?= esc($delivery['delivery_number']) ?></h3>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div>
                <strong>PO Number:</strong> <?= esc($delivery['po_number']) ?><br>
                <strong>Supplier:</strong> <?= esc($delivery['supplier_name']) ?><br>
                <strong>Branch:</strong> <?= esc($delivery['branch_name']) ?>
            </div>
            <div>
                <strong>Status:</strong> <?= ucfirst(str_replace('_', ' ', $delivery['status'])) ?><br>
                <strong>Scheduled Date:</strong> <?= $delivery['scheduled_date'] ? date('M d, Y', strtotime($delivery['scheduled_date'])) : 'N/A' ?><br>
                <?php if ($delivery['driver_name']): ?>
                    <strong>Driver:</strong> <?= esc($delivery['driver_name']) ?><br>
                    <strong>Vehicle:</strong> <?= esc($delivery['vehicle_number']) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Unit</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($po_items as $item): ?>
                <tr>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['sku']) ?></td>
                    <td><?= esc($item['unit']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($delivery['notes']): ?>
    <div style="margin-top: 30px;">
        <strong>Notes:</strong><br>
        <?= nl2br(esc($delivery['notes'])) ?>
    </div>
    <?php endif; ?>

    <?php if ($delivery['received_by_name']): ?>
    <div style="margin-top: 50px;">
        <p><strong>Received by:</strong> <?= esc($delivery['received_by_name']) ?></p>
        <p><strong>Received at:</strong> <?= date('M d, Y H:i', strtotime($delivery['received_at'])) ?></p>
    </div>
    <?php endif; ?>
</body>
</html>

