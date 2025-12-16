-- Fix Payment Transactions and Purchase Order Status
-- Update all pending payment transactions to completed (paid)
-- Update purchase orders to completed status when delivery is done

-- Step 1: Update all payment transactions from pending to completed
UPDATE payment_transactions 
SET status = 'completed',
    payment_date = NOW(),
    notes = CONCAT(notes, ' - Auto-updated to paid status')
WHERE status = 'pending';

-- Step 2: Update purchase orders to completed when they have delivered deliveries
UPDATE purchase_orders po
INNER JOIN deliveries d ON d.purchase_order_id = po.id
SET po.status = 'completed'
WHERE d.status = 'delivered' 
AND po.status != 'completed';

-- Step 3: Verify the changes
SELECT 'Payment Transactions Updated:' as info;
SELECT id, transaction_number, status, payment_date, amount 
FROM payment_transactions 
ORDER BY created_at DESC 
LIMIT 10;

SELECT 'Purchase Orders Updated:' as info;
SELECT po.id, po.po_number, po.status, d.delivery_number, d.status as delivery_status
FROM purchase_orders po
LEFT JOIN deliveries d ON d.purchase_order_id = po.id
ORDER BY po.created_at DESC
LIMIT 10;
