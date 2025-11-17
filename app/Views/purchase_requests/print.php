<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request - <?= esc($request['request_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info-section { margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
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
        <h2>PURCHASE REQUEST</h2>
        <h3>Request Number: <?= esc($request['request_number']) ?></h3>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div>
                <strong>Branch:</strong> <?= esc($request['branch_name']) ?><br>
                <strong>Requested By:</strong> <?= esc($request['requested_by_name']) ?><br>
                <strong>Priority:</strong> <?= ucfirst($request['priority']) ?>
            </div>
            <div>
                <strong>Status:</strong> <?= ucfirst($request['status']) ?><br>
                <strong>Date:</strong> <?= date('M d, Y H:i', strtotime($request['created_at'])) ?><br>
                <?php if ($request['approved_at']): ?>
                    <strong>Approved At:</strong> <?= date('M d, Y H:i', strtotime($request['approved_at'])) ?>
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
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $grandTotal = 0;
            foreach ($items as $item): 
                $grandTotal += $item['total_price'];
            ?>
                <tr>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['sku']) ?></td>
                    <td><?= esc($item['unit']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>₱<?= number_format($item['unit_price'], 2) ?></td>
                    <td>₱<?= number_format($item['total_price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Grand Total:</th>
                <th>₱<?= number_format($grandTotal, 2) ?></th>
            </tr>
        </tfoot>
    </table>

    <?php if ($request['notes']): ?>
    <div style="margin-top: 30px;">
        <strong>Notes:</strong><br>
        <?= nl2br(esc($request['notes'])) ?>
    </div>
    <?php endif; ?>
</body>
</html>

