-- Check current branches
SELECT id, name, is_franchise FROM branches;

-- Update Mansor Malik branch to be marked as franchise
UPDATE branches 
SET is_franchise = 1 
WHERE name LIKE '%Mansor%' OR name LIKE '%Malik%';

-- Verify the update
SELECT id, name, is_franchise FROM branches;
