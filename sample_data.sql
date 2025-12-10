-- ============================================
-- CHAKANOKS SCMS - Sample Data Import
-- Run this in phpMyAdmin or MySQL
-- ============================================

-- ============================================
-- CATEGORIES
-- ============================================
INSERT INTO categories (name, description, status, created_at, updated_at) VALUES
('Beverages', 'Drinks, juices, sodas, water, coffee, tea', 'active', NOW(), NOW()),
('Snacks', 'Chips, crackers, cookies, candies', 'active', NOW(), NOW()),
('Canned Goods', 'Canned meat, fish, vegetables, fruits', 'active', NOW(), NOW()),
('Dairy Products', 'Milk, cheese, butter, yogurt', 'active', NOW(), NOW()),
('Frozen Foods', 'Ice cream, frozen meat, frozen vegetables', 'active', NOW(), NOW()),
('Rice & Grains', 'Rice, oats, corn, wheat products', 'active', NOW(), NOW()),
('Condiments', 'Sauces, vinegar, soy sauce, ketchup, mayonnaise', 'active', NOW(), NOW()),
('Personal Care', 'Soap, shampoo, toothpaste, hygiene products', 'active', NOW(), NOW()),
('Household Items', 'Cleaning supplies, detergent, tissue', 'active', NOW(), NOW()),
('Cooking Essentials', 'Oil, sugar, salt, flour, spices', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ============================================
-- PRODUCTS
-- ============================================
-- Note: category_id values assume categories are inserted in order above (1-10)

INSERT INTO products (name, sku, barcode, description, category_id, unit, min_stock_level, max_stock_level, cost_price, selling_price, status, created_at, updated_at) VALUES
-- Beverages (category_id = 1)
('Coca-Cola 1.5L', 'BEV-COKE-1.5L', '4800100012345', 'Coca-Cola soft drink 1.5 liter bottle', 1, 'bottle', 50, 500, 45.00, 55.00, 'active', NOW(), NOW()),
('Sprite 1.5L', 'BEV-SPRITE-1.5L', '4800100012346', 'Sprite lemon-lime soft drink 1.5L', 1, 'bottle', 50, 500, 45.00, 55.00, 'active', NOW(), NOW()),
('Nestea Iced Tea 1L', 'BEV-NESTEA-1L', '4800100012347', 'Nestea lemon iced tea 1 liter', 1, 'bottle', 30, 300, 35.00, 45.00, 'active', NOW(), NOW()),
('C2 Green Tea 500ml', 'BEV-C2-500ML', '4800100012348', 'C2 green tea apple flavor 500ml', 1, 'bottle', 100, 1000, 18.00, 25.00, 'active', NOW(), NOW()),
('Nescafe 3in1 Original', 'BEV-NESCAFE-3IN1', '4800100012349', 'Nescafe 3-in-1 original coffee mix 28g', 1, 'sachet', 200, 2000, 8.00, 12.00, 'active', NOW(), NOW()),

-- Snacks (category_id = 2)
('Piattos Cheese 85g', 'SNK-PIATTOS-85G', '4800200012345', 'Piattos cheese flavored chips 85g', 2, 'pack', 100, 1000, 22.00, 30.00, 'active', NOW(), NOW()),
('Nova Multigrain 78g', 'SNK-NOVA-78G', '4800200012346', 'Nova multigrain snacks 78g', 2, 'pack', 100, 1000, 20.00, 28.00, 'active', NOW(), NOW()),
('Oishi Prawn Crackers 60g', 'SNK-OISHI-60G', '4800200012347', 'Oishi prawn crackers original 60g', 2, 'pack', 150, 1500, 15.00, 22.00, 'active', NOW(), NOW()),
('Skyflakes Crackers 250g', 'SNK-SKYFLAKES-250G', '4800200012348', 'Skyflakes saltine crackers 250g', 2, 'pack', 80, 800, 35.00, 45.00, 'active', NOW(), NOW()),
('Chippy BBQ 110g', 'SNK-CHIPPY-110G', '4800200012349', 'Chippy BBQ flavored corn chips 110g', 2, 'pack', 100, 1000, 18.00, 25.00, 'active', NOW(), NOW()),

-- Canned Goods (category_id = 3)
('Century Tuna Flakes 180g', 'CAN-TUNA-180G', '4800300012345', 'Century tuna flakes in oil 180g', 3, 'can', 100, 1000, 38.00, 48.00, 'active', NOW(), NOW()),
('Argentina Corned Beef 260g', 'CAN-CORNED-260G', '4800300012346', 'Argentina corned beef 260g', 3, 'can', 80, 800, 55.00, 68.00, 'active', NOW(), NOW()),
('555 Sardines 155g', 'CAN-SARDINES-155G', '4800300012347', '555 sardines in tomato sauce 155g', 3, 'can', 150, 1500, 22.00, 30.00, 'active', NOW(), NOW()),
('Spam Luncheon Meat 340g', 'CAN-SPAM-340G', '4800300012348', 'Spam classic luncheon meat 340g', 3, 'can', 50, 500, 180.00, 220.00, 'active', NOW(), NOW()),
('Del Monte Fruit Cocktail 432g', 'CAN-FRUIT-432G', '4800300012349', 'Del Monte fruit cocktail 432g', 3, 'can', 60, 600, 75.00, 95.00, 'active', NOW(), NOW()),

-- Dairy Products (category_id = 4)
('Alaska Evaporated Milk 370ml', 'DAI-ALASKA-370ML', '4800400012345', 'Alaska evaporated filled milk 370ml', 4, 'can', 100, 1000, 32.00, 42.00, 'active', NOW(), NOW()),
('Bear Brand Sterilized 140ml', 'DAI-BEARBRAND-140ML', '4800400012346', 'Bear Brand sterilized milk 140ml', 4, 'can', 150, 1500, 18.00, 25.00, 'active', NOW(), NOW()),
('Magnolia Butter 225g', 'DAI-BUTTER-225G', '4800400012347', 'Magnolia gold butter salted 225g', 4, 'pack', 30, 300, 120.00, 150.00, 'active', NOW(), NOW()),
('Eden Cheese 160g', 'DAI-EDEN-160G', '4800400012348', 'Eden original cheese spread 160g', 4, 'pack', 50, 500, 65.00, 82.00, 'active', NOW(), NOW()),
('Nestle All Purpose Cream 250ml', 'DAI-CREAM-250ML', '4800400012349', 'Nestle all purpose cream 250ml', 4, 'pack', 60, 600, 48.00, 62.00, 'active', NOW(), NOW()),

-- Rice & Grains (category_id = 6)
('Sinandomeng Rice 25kg', 'RIC-SINANDOMENG-25KG', '4800600012345', 'Premium Sinandomeng rice 25kg sack', 6, 'sack', 20, 200, 1350.00, 1550.00, 'active', NOW(), NOW()),
('Jasmine Rice 25kg', 'RIC-JASMINE-25KG', '4800600012346', 'Thai Jasmine rice 25kg sack', 6, 'sack', 15, 150, 1500.00, 1750.00, 'active', NOW(), NOW()),
('Dinorado Rice 5kg', 'RIC-DINORADO-5KG', '4800600012347', 'Dinorado premium rice 5kg', 6, 'pack', 50, 500, 320.00, 380.00, 'active', NOW(), NOW()),
('Quaker Oats 800g', 'RIC-OATS-800G', '4800600012348', 'Quaker quick cooking oats 800g', 6, 'pack', 40, 400, 145.00, 175.00, 'active', NOW(), NOW()),
('Lucky Me Pancit Canton 60g', 'RIC-PANCIT-60G', '4800600012349', 'Lucky Me pancit canton original 60g', 6, 'pack', 200, 2000, 12.00, 16.00, 'active', NOW(), NOW()),

-- Condiments (category_id = 7)
('Silver Swan Soy Sauce 1L', 'CON-SOY-1L', '4800700012345', 'Silver Swan soy sauce 1 liter', 7, 'bottle', 50, 500, 55.00, 70.00, 'active', NOW(), NOW()),
('Datu Puti Vinegar 1L', 'CON-VINEGAR-1L', '4800700012346', 'Datu Puti white vinegar 1 liter', 7, 'bottle', 50, 500, 35.00, 45.00, 'active', NOW(), NOW()),
('UFC Banana Ketchup 550g', 'CON-KETCHUP-550G', '4800700012347', 'UFC banana ketchup 550g', 7, 'bottle', 60, 600, 48.00, 62.00, 'active', NOW(), NOW()),
('Lady Choice Mayonnaise 470ml', 'CON-MAYO-470ML', '4800700012348', 'Lady Choice real mayonnaise 470ml', 7, 'jar', 40, 400, 95.00, 120.00, 'active', NOW(), NOW()),
('Maggi Magic Sarap 8g', 'CON-MAGGI-8G', '4800700012349', 'Maggi magic sarap all-in-one 8g', 7, 'sachet', 300, 3000, 3.00, 5.00, 'active', NOW(), NOW()),

-- Cooking Essentials (category_id = 10)
('Baguio Oil 1L', 'COK-OIL-1L', '4801000012345', 'Baguio pure coconut oil 1 liter', 10, 'bottle', 50, 500, 85.00, 105.00, 'active', NOW(), NOW()),
('White Sugar 1kg', 'COK-SUGAR-1KG', '4801000012346', 'Refined white sugar 1kg', 10, 'pack', 100, 1000, 65.00, 78.00, 'active', NOW(), NOW()),
('Iodized Salt 1kg', 'COK-SALT-1KG', '4801000012347', 'Iodized fine salt 1kg', 10, 'pack', 80, 800, 25.00, 35.00, 'active', NOW(), NOW()),
('All Purpose Flour 1kg', 'COK-FLOUR-1KG', '4801000012348', 'All purpose flour 1kg', 10, 'pack', 60, 600, 55.00, 68.00, 'active', NOW(), NOW()),
('Knorr Chicken Cube 10g', 'COK-KNORR-10G', '4801000012349', 'Knorr chicken broth cube 10g', 10, 'cube', 200, 2000, 5.00, 8.00, 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ============================================
-- DRIVERS
-- ============================================
INSERT INTO drivers (name, phone, license_number, vehicle_number, status, created_at, updated_at) VALUES
('Juan Dela Cruz', '09171234567', 'N01-12-345678', 'ABC-1234', 'active', NOW(), NOW()),
('Pedro Santos', '09181234567', 'N02-12-345679', 'XYZ-5678', 'active', NOW(), NOW()),
('Maria Garcia', '09191234567', 'N03-12-345680', 'DEF-9012', 'active', NOW(), NOW()),
('Carlos Rodriguez', '09201234567', 'N04-12-345681', 'GHI-3456', 'active', NOW(), NOW()),
('Ana Martinez', '09211234567', 'N05-12-345682', 'JKL-7890', 'active', NOW(), NOW()),
('Jose Reyes', '09221234567', 'N06-12-345683', 'MNO-1234', 'active', NOW(), NOW()),
('Lorna Bautista', '09231234567', 'N07-12-345684', 'PQR-5678', 'active', NOW(), NOW()),
('Roberto Cruz', '09241234567', 'N08-12-345685', 'STU-9012', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ============================================
-- DONE!
-- ============================================
-- Categories: 10 records
-- Products: 35 records
-- Drivers: 8 records
