-- Mark tracking fields migration as complete (if not already)
INSERT IGNORE INTO migrations (version, class, `group`, namespace, time, batch)
VALUES ('2025-12-16-000001', 'App\\Database\\Migrations\\AddTrackingFieldsToTransfers', 'default', 'App', UNIX_TIMESTAMP(), 4);

-- Add driver fields to transfers table (if they don't exist)
SET @dbname = DATABASE();
SET @tablename = 'transfers';

SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'driver_id');

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE transfers 
        ADD COLUMN driver_id INT UNSIGNED NULL AFTER dispatched_at,
        ADD COLUMN driver_name VARCHAR(255) NULL AFTER driver_id,
        ADD COLUMN driver_phone VARCHAR(50) NULL AFTER driver_name,
        ADD COLUMN vehicle_info VARCHAR(255) NULL AFTER driver_phone',
    'SELECT "Driver fields already exist" AS message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Mark driver migration as complete
INSERT IGNORE INTO migrations (version, class, `group`, namespace, time, batch)
VALUES ('2025-12-16-100001', 'App\\Database\\Migrations\\AddDriverToTransfers', 'default', 'App', UNIX_TIMESTAMP(), 4);

SELECT 'Migrations completed successfully!' AS status;
