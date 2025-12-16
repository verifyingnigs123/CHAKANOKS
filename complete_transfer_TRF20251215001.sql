-- SQL Script to Complete Transfer TRF20251215001 and Update Inventory
-- Run this in your database to fix the existing approved transfer

-- Step 1: Get transfer details
SELECT 
    t.id as transfer_id,
    t.transfer_number,
    t.from_branch_id,
    t.to_branch_id,
    t.status,
    fb.name as from_branch,
    tb.name as to_branch
FROM transfers t
JOIN branches fb ON fb.id = t.from_branch_id
JOIN branches tb ON tb.id = t.to_branch_id
WHERE t.transfer_number = 'TRF20251215001';

-- Step 2: Show transfer items
SELECT 
    ti.id,
    ti.product_id,
    p.name as product_name,
    ti.quantity,
    ti.quantity_received
FROM transfer_items ti
JOIN products p ON p.id = ti.product_id
WHERE ti.transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001');

-- Step 3: Show current inventory (BEFORE)
SELECT 
    i.id,
    i.branch_id,
    b.name as branch_name,
    i.product_id,
    p.name as product_name,
    i.quantity as current_quantity
FROM inventory i
JOIN branches b ON b.id = i.branch_id
JOIN products p ON p.id = i.product_id
WHERE i.branch_id IN (
    SELECT from_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001'
    UNION
    SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001'
)
AND i.product_id IN (
    SELECT product_id FROM transfer_items 
    WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001')
);

-- ============================================
-- EXECUTE THESE UPDATES TO FIX THE TRANSFER
-- ============================================

-- Step 4: Deduct from source branch (Main Branch)
-- Replace the values based on your actual data
UPDATE inventory 
SET 
    quantity = quantity - 20,  -- Deduct 20 Whole Chicken
    available_quantity = (quantity - 20) - COALESCE(reserved_quantity, 0),
    last_updated_at = NOW()
WHERE branch_id = (SELECT from_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001')
  AND product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001') LIMIT 1);

-- Step 5: Add to destination branch (Mansor Malic Franchise)
-- If inventory record exists, update it
UPDATE inventory 
SET 
    quantity = quantity + 20,  -- Add 20 Whole Chicken
    available_quantity = (quantity + 20) - COALESCE(reserved_quantity, 0),
    last_updated_at = NOW()
WHERE branch_id = (SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001')
  AND product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001') LIMIT 1);

-- If inventory record doesn't exist, create it
INSERT INTO inventory (branch_id, product_id, quantity, reserved_quantity, available_quantity, last_updated_at, created_at, updated_at)
SELECT 
    t.to_branch_id,
    ti.product_id,
    ti.quantity,
    0,
    ti.quantity,
    NOW(),
    NOW(),
    NOW()
FROM transfers t
JOIN transfer_items ti ON ti.transfer_id = t.id
WHERE t.transfer_number = 'TRF20251215001'
  AND NOT EXISTS (
      SELECT 1 FROM inventory 
      WHERE branch_id = t.to_branch_id 
        AND product_id = ti.product_id
  );

-- Step 6: Update transfer items received quantity
UPDATE transfer_items 
SET quantity_received = quantity
WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001');

-- Step 7: Update transfer status to completed
UPDATE transfers 
SET 
    status = 'completed',
    dispatched_by = approved_by,
    dispatched_at = NOW(),
    received_by = approved_by,
    received_at = NOW(),
    completed_at = NOW()
WHERE transfer_number = 'TRF20251215001';

-- Step 8: Verify the changes (AFTER)
SELECT 
    i.id,
    i.branch_id,
    b.name as branch_name,
    i.product_id,
    p.name as product_name,
    i.quantity as updated_quantity
FROM inventory i
JOIN branches b ON b.id = i.branch_id
JOIN products p ON p.id = i.product_id
WHERE i.branch_id IN (
    SELECT from_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001'
    UNION
    SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001'
)
AND i.product_id IN (
    SELECT product_id FROM transfer_items 
    WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001')
);

-- Step 9: Verify transfer status
SELECT 
    transfer_number,
    status,
    approved_at,
    dispatched_at,
    received_at,
    completed_at
FROM transfers 
WHERE transfer_number = 'TRF20251215001';
