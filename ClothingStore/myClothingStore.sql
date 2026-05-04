-- myClothingStore.sql
-- DDL and sample data for ClothingStore database

SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS ClothingStore;
CREATE DATABASE ClothingStore;
USE ClothingStore;

-- Table structure for tblUser
DROP TABLE IF EXISTS tblUser;
CREATE TABLE tblUser (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for tblAdmin
DROP TABLE IF EXISTS tblAdmin;
CREATE TABLE tblAdmin (
    admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for tblAorder
DROP TABLE IF EXISTS tblAorder;
CREATE TABLE tblAorder (
    order_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
);

-- Table structure for tblClothes
DROP TABLE IF EXISTS tblClothes;
CREATE TABLE tblClothes (
    clothes_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    size VARCHAR(10),
    quantity INT(11) DEFAULT 0,
    description TEXT
);

SET FOREIGN_KEY_CHECKS = 1;

-- Sample data for tblAdmin (30 entries)
INSERT INTO tblAdmin (username, email, password_hash, full_name) VALUES
('admin', 'admin@clothingstore.com', MD5('admin123'), 'System Administrator'),
('manager', 'manager@clothingstore.com', MD5('manager123'), 'Store Manager'),
('support', 'support@clothingstore.com', MD5('support123'), 'Customer Support'),
('inventory', 'inventory@clothingstore.com', MD5('inventory123'), 'Inventory Admin'),
('marketing', 'marketing@clothingstore.com', MD5('marketing123'), 'Marketing Lead'),
('sales1', 'sales1@clothingstore.com', MD5('sales123'), 'Sales Specialist 1'),
('sales2', 'sales2@clothingstore.com', MD5('sales123'), 'Sales Specialist 2'),
('accounting', 'accounting@clothingstore.com', MD5('accounts123'), 'Accounting Lead'),
('hr', 'hr@clothingstore.com', MD5('hr123'), 'HR Director'),
('designer', 'designer@clothingstore.com', MD5('design123'), 'Product Designer'),
('analytics', 'analytics@clothingstore.com', MD5('data123'), 'Data Analyst'),
('quality', 'quality@clothingstore.com', MD5('quality123'), 'Quality Manager'),
('operations', 'operations@clothingstore.com', MD5('ops123'), 'Operations Manager'),
('procurement', 'procurement@clothingstore.com', MD5('procure123'), 'Procurement Head'),
('digital', 'digital@clothingstore.com', MD5('digital123'), 'Digital Marketing'),
('warehouse', 'warehouse@clothingstore.com', MD5('warehouse123'), 'Warehouse Lead'),
('logistics', 'logistics@clothingstore.com', MD5('logistics123'), 'Logistics Manager'),
('retail', 'retail@clothingstore.com', MD5('retail123'), 'Retail Coordinator'),
('planning', 'planning@clothingstore.com', MD5('plan123'), 'Product Planner'),
('security', 'security@clothingstore.com', MD5('secure123'), 'Security Officer'),
('training', 'training@clothingstore.com', MD5('train123'), 'Training Lead'),
('comms', 'comms@clothingstore.com', MD5('comm123'), 'Communications Lead'),
('webadmin', 'webadmin@clothingstore.com', MD5('webadmin123'), 'Web Administrator'),
('finance', 'finance@clothingstore.com', MD5('finance123'), 'Finance Manager'),
('service', 'service@clothingstore.com', MD5('service123'), 'Service Coordinator'),
('research', 'research@clothingstore.com', MD5('research123'), 'Market Researcher'),
('events', 'events@clothingstore.com', MD5('events123'), 'Events Manager'),
('brand', 'brand@clothingstore.com', MD5('brand123'), 'Brand Manager'),
('content', 'content@clothingstore.com', MD5('content123'), 'Content Producer'),
('customer', 'customer@clothingstore.com', MD5('customer123'), 'Customer Relations Specialist');

-- Sample data for tblUser (30 entries)
INSERT INTO tblUser (username, email, password_hash, full_name, is_verified, is_admin) VALUES
('john.doe', 'john.doe@email.com', MD5('john123'), 'John Doe', 0, 0),
('jane.smith', 'jane.smith@email.com', MD5('jane123'), 'Jane Smith', 0, 0),
('bob.johnson', 'bob.johnson@email.com', MD5('bob123'), 'Bob Johnson', 1, 0),
('alice.brown', 'alice.brown@email.com', MD5('alice123'), 'Alice Brown', 1, 0),
('charlie.wilson', 'charlie.wilson@email.com', MD5('charlie123'), 'Charlie Wilson', 0, 0),
('sarah.davis', 'sarah.davis@email.com', MD5('sarah123'), 'Sarah Davis', 0, 0),
('mike.taylor', 'mike.taylor@email.com', MD5('mike123'), 'Mike Taylor', 1, 0),
('lisa.anderson', 'lisa.anderson@email.com', MD5('lisa123'), 'Lisa Anderson', 0, 0),
('tom.wilson', 'tom.wilson@email.com', MD5('tom123'), 'Tom Wilson', 0, 0),
('emma.white', 'emma.white@email.com', MD5('emma123'), 'Emma White', 1, 0),
('nina.fisher', 'nina.fisher@email.com', MD5('nina123'), 'Nina Fisher', 1, 0),
('kevin.hart', 'kevin.hart@email.com', MD5('kevin123'), 'Kevin Hart', 0, 0),
('olivia.king', 'olivia.king@email.com', MD5('olivia123'), 'Olivia King', 1, 0),
('chris.evans', 'chris.evans@email.com', MD5('chris123'), 'Chris Evans', 0, 0),
('paul.walker', 'paul.walker@email.com', MD5('paul123'), 'Paul Walker', 1, 0),
('mia.brown', 'mia.brown@email.com', MD5('mia123'), 'Mia Brown', 0, 0),
('noah.scott', 'noah.scott@email.com', MD5('noah123'), 'Noah Scott', 1, 0),
('ava.james', 'ava.james@email.com', MD5('ava123'), 'Ava James', 0, 0),
('liam.morris', 'liam.morris@email.com', MD5('liam123'), 'Liam Morris', 1, 0),
('sophia.moore', 'sophia.moore@email.com', MD5('sophia123'), 'Sophia Moore', 1, 0),
('mason.king', 'mason.king@email.com', MD5('mason123'), 'Mason King', 0, 0),
('isabella.wright', 'isabella.wright@email.com', MD5('isabella123'), 'Isabella Wright', 0, 0),
('logan.adams', 'logan.adams@email.com', MD5('logan123'), 'Logan Adams', 1, 0),
('harper.bell', 'harper.bell@email.com', MD5('harper123'), 'Harper Bell', 0, 0),
('lucas.young', 'lucas.young@email.com', MD5('lucas123'), 'Lucas Young', 1, 0),
('amelia.king', 'amelia.king@email.com', MD5('amelia123'), 'Amelia King', 0, 0),
('ethan.hill', 'ethan.hill@email.com', MD5('ethan123'), 'Ethan Hill', 1, 0),
('ella.wood', 'ella.wood@email.com', MD5('ella123'), 'Ella Wood', 0, 0),
('owen.lee', 'owen.lee@email.com', MD5('owen123'), 'Owen Lee', 1, 0),
('zoe.james', 'zoe.james@email.com', MD5('zoe123'), 'Zoe James', 0, 0);

-- Sample data for tblClothes (30 entries)
INSERT INTO tblClothes (name, category, price, size, quantity, description) VALUES
('Classic T-Shirt', 'Mens', 19.99, 'L', 50, 'Comfortable cotton t-shirt'),
('Slim Fit Jeans', 'Mens', 49.99, '32', 30, 'Stylish denim jeans'),
('Floral Dress', 'Womens', 39.99, 'M', 25, 'Summer floral dress'),
('Hoodie', 'Unisex', 59.99, 'XL', 40, 'Warm comfortable hoodie'),
('Leather Jacket', 'Mens', 129.99, 'L', 15, 'Premium biker leather jacket'),
('Running Shorts', 'Mens', 24.99, 'M', 45, 'Lightweight running shorts'),
('Denim Skirt', 'Womens', 34.99, 'S', 20, 'Stylish denim skirt'),
('Blouse', 'Womens', 29.99, 'M', 35, 'Elegantly tailored blouse'),
('Polo Shirt', 'Mens', 27.99, 'L', 40, 'Smart casual polo shirt'),
('Cargo Pants', 'Mens', 44.99, '34', 28, 'Durable cargo pants'),
('Summer Dress', 'Womens', 52.99, 'L', 22, 'Bright summer dress'),
('Sweatpants', 'Unisex', 39.99, 'XL', 33, 'Soft lounge sweatpants'),
('Denim Jacket', 'Unisex', 79.99, 'M', 18, 'Classic denim jacket'),
('Tank Top', 'Womens', 15.99, 'S', 55, 'Breathable tank top'),
('Button-Up Shirt', 'Mens', 31.99, 'M', 26, 'Formal button-up shirt'),
('Midi Skirt', 'Womens', 36.99, 'M', 19, 'Flowy midi skirt'),
('Graphic Tee', 'Unisex', 21.99, 'L', 48, 'Printed graphic t-shirt'),
('Summer Shorts', 'Mens', 22.99, 'M', 42, 'Comfort-fit summer shorts'),
('Evening Gown', 'Womens', 99.99, 'S', 12, 'Elegant evening gown'),
('Windbreaker', 'Unisex', 54.99, 'L', 20, 'Lightweight windbreaker jacket'),
('Chinos', 'Mens', 45.99, '33', 25, 'Versatile everyday chinos'),
('Maxi Dress', 'Womens', 59.99, 'L', 17, 'Casual maxi dress'),
('Sports Bra', 'Womens', 25.99, 'M', 30, 'Supportive sports bra'),
('Button-Front Shirt', 'Mens', 33.99, 'L', 22, 'Classic button-front shirt'),
('Corduroy Pants', 'Mens', 49.99, '32', 14, 'Textured corduroy pants'),
('Summer Romper', 'Womens', 29.99, 'S', 21, 'Easy one-piece romper'),
('Fleece Jacket', 'Unisex', 69.99, 'XL', 23, 'Cozy fleece jacket'),
('Leather Belt', 'Unisex', 14.99, 'One Size', 70, 'Genuine leather belt'),
('Denim Shorts', 'Mens', 28.99, 'M', 26, 'Casual denim shorts'),
('Silk Scarf', 'Womens', 18.99, 'One Size', 40, 'Soft silk scarf');

-- Sample data for tblAorder (30 entries)
INSERT INTO tblAorder (user_id, order_date, total_amount, status) VALUES
(1, '2026-05-01 10:15:00', 120.99, 'pending'),
(2, '2026-05-01 12:22:00', 89.50, 'completed'),
(3, '2026-05-02 09:20:00', 45.00, 'shipped'),
(4, '2026-05-02 16:45:00', 210.25, 'processing'),
(5, '2026-05-03 11:05:00', 75.99, 'delivered'),
(6, '2026-05-03 14:50:00', 99.90, 'pending'),
(7, '2026-05-04 08:35:00', 158.75, 'completed'),
(8, '2026-05-04 13:10:00', 37.49, 'cancelled'),
(9, '2026-05-05 09:40:00', 129.99, 'shipped'),
(10, '2026-05-05 15:20:00', 64.00, 'delivered'),
(11, '2026-05-06 11:12:00', 52.30, 'processing'),
(12, '2026-05-06 17:05:00', 199.99, 'pending'),
(13, '2026-05-07 10:55:00', 88.60, 'completed'),
(14, '2026-05-07 12:40:00', 144.20, 'shipped'),
(15, '2026-05-08 14:00:00', 212.75, 'delivered'),
(16, '2026-05-08 16:30:00', 59.99, 'pending'),
(17, '2026-05-09 10:05:00', 76.10, 'processing'),
(18, '2026-05-09 13:45:00', 183.33, 'completed'),
(19, '2026-05-10 09:25:00', 39.99, 'cancelled'),
(20, '2026-05-10 15:30:00', 119.99, 'shipped'),
(21, '2026-05-11 11:30:00', 54.99, 'delivered'),
(22, '2026-05-11 14:53:00', 159.95, 'processing'),
(23, '2026-05-12 09:12:00', 200.00, 'pending'),
(24, '2026-05-12 16:10:00', 44.44, 'completed'),
(25, '2026-05-13 10:35:00', 75.50, 'shipped'),
(26, '2026-05-13 13:55:00', 133.75, 'delivered'),
(27, '2026-05-14 08:50:00', 99.99, 'processing'),
(28, '2026-05-14 12:10:00', 27.90, 'cancelled'),
(29, '2026-05-15 15:00:00', 186.20, 'completed'),
(30, '2026-05-15 16:45:00', 149.99, 'pending');
