-- QUICK FIX: Complete Transfer TRF20251215001
-- Copy and paste these commands into your database (phpMyAdmin, MySQL Workbench, etc.)

-- Step 1: Get the transfer ID and branch IDs
SET @transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001');
SET @from_branch = (SELECT from_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001');
SET @to_branch = (SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001');
SET @product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = @transfer_id LIMIT 1);

-- Step 2: Deduct 20 from Main Branch
UPDATE inventory 
SET 
    quantity = quantity - 20,
    available_quantity = quantity - 20,
    last_updated_at = NOW()
WHERE branch_id = @from_branch
  AND product_id = @product_id;

-- Step 3: Add 20 to Mansor Malic Franchise (if exists)
UPDATE inventory 
SET 
    quantity = quantity + 20,
    available_quantity = quantity + 20,
    last_updated_at = NOW()
WHERE branch_id = @to_branch
  AND product_id = @product_id;

-- Step 4: Create inventory if doesn't exist
INSERT INTO inventory (branch_id, product_id, quantity, reserved_quantity, available_quantity, last_updated_at, created_at, updated_at)
SELECT 
    @to_branch,
    @product_id,
    20,
    0,
    20,
    NOW(),
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM inventory 
    WHERE branch_id = @to_branch 
      AND product_id = @product_id
);

-- Step 5: Update transfer items
UPDATE transfer_items 
SET quantity_received = quantity
WHERE transfer_id = @transfer_id;

-- Step 6: Complete the transfer
UPDATE transfers 
SET 
    status = 'completed',
    dispatched_by = approved_by,
    dispatched_at = NOW(),
    received_by = approved_by,
    received_at = NOW(),
    completed_at = NOW()
WHERE id = @transfer_id;

-- Step 7: Verify the fix
SELECT 'Transfer Status:' as Info;
SELECT transfer_number, status, completed_at FROM transfers WHERE id = @transfer_id;

SELECT 'Inventory After Fix:' as Info;
SELECT 
    b.name as branch_name,
    p.name as product_name,
    i.quantity
FROM inventory i
JOIN branches b ON b.id = i.branch_id
JOIN products p ON p.id = i.product_id
WHERE i.branch_id IN (@from_branch, @to_branch)
  AND i.product_id = @product_id;
