# Quick Fix: Transfer Inventory Not Updating

## The Problem
Transfer approved but inventory not updated.

## The Fix (Choose ONE)

### Option 1: Run PHP Script (Easiest) ⭐
```bash
cd /path/to/your/project
php fix_transfer_simple.php
```

### Option 2: Run SQL Commands
Open your database (phpMyAdmin, MySQL Workbench, etc.) and run:

```sql
-- 1. Deduct from Main Branch
UPDATE inventory 
SET quantity = quantity - 20,
    available_quantity = quantity - 20,
    last_updated_at = NOW()
WHERE branch_id = (SELECT from_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001')
  AND product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001'));

-- 2. Add to Mansor Malic Franchise (if record exists)
UPDATE inventory 
SET quantity = quantity + 20,
    available_quantity = quantity + 20,
    last_updated_at = NOW()
WHERE branch_id = (SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001')
  AND product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001'));

-- 3. Add to Mansor Malic Franchise (if record doesn't exist)
INSERT INTO inventory (branch_id, product_id, quantity, available_quantity, last_updated_at, created_at, updated_at)
SELECT 
    to_branch_id,
    (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001')),
    20,
    20,
    NOW(),
    NOW(),
    NOW()
FROM transfers 
WHERE transfer_number = 'TRF20251215001'
  AND NOT EXISTS (
      SELECT 1 FROM inventory 
      WHERE branch_id = (SELECT to_branch_id FROM transfers WHERE transfer_number = 'TRF20251215001')
        AND product_id = (SELECT product_id FROM transfer_items WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001'))
  );

-- 4. Update transfer status
UPDATE transfers 
SET status = 'completed',
    dispatched_by = approved_by,
    dispatched_at = NOW(),
    received_by = approved_by,
    received_at = NOW(),
    completed_at = NOW()
WHERE transfer_number = 'TRF20251215001';

-- 5. Update transfer items
UPDATE transfer_items 
SET quantity_received = quantity
WHERE transfer_id = (SELECT id FROM transfers WHERE transfer_number = 'TRF20251215001');
```

### Option 3: Via UI (Slowest)
1. Login as Central Admin
2. Go to Transfers
3. Reject the current transfer
4. Create new transfer with same items
5. Approve it

## Verify the Fix

### Check Inventory:
```sql
SELECT b.name, p.name, i.quantity
FROM inventory i
JOIN branches b ON b.id = i.branch_id
JOIN products p ON p.id = i.product_id
WHERE b.name IN ('Main Branch', 'Mansor Malic Franchise')
  AND p.name = 'Whole Chicken';
```

**Expected Result:**
- Main Branch: 80 (was 100, -20)
- Mansor Malic Franchise: 20 (was 0, +20)

### Check Transfer:
```sql
SELECT transfer_number, status, completed_at
FROM transfers
WHERE transfer_number = 'TRF20251215001';
```

**Expected Result:**
- Status: completed
- completed_at: [current timestamp]

## Future Transfers

All new transfers will work automatically:
1. Create transfer → pending
2. Approve transfer → **completed + inventory updated** ✅

No more manual steps needed!

## Need Help?

Check logs: `writable/logs/log-[date].log`

Look for:
- "Deducting X from branch Y"
- "Adding X to branch Y"
- "Transfer approved and completed"
