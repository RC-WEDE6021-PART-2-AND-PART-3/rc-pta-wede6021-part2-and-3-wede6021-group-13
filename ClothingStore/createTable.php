<?php
// createTable.php - Complete working version
include 'DBConn.php';

try {
    // Step 1: Disable foreign key checks temporarily
    echo "🔧 Disabling foreign key checks...<br>";
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "✓ Foreign key checks disabled<br><br>";
    
    // Step 2: Drop tables in correct order
    echo "Dropping existing tables...<br>";
    
    $conn->exec("DROP TABLE IF EXISTS tblAorder");
    echo "✓ Dropped tblAorder table<br>";
    
    $conn->exec("DROP TABLE IF EXISTS tblUser");
    echo "✓ Dropped tblUser table<br>";
    
    $conn->exec("DROP TABLE IF EXISTS tblAdmin");
    echo "✓ Dropped tblAdmin table<br>";
    
    $conn->exec("DROP TABLE IF EXISTS tblClothes");
    echo "✓ Dropped tblClothes table<br>";
    
    // Step 3: Re-enable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✓ Foreign key checks re-enabled<br><br>";
    
    // Step 4: Create fresh tables
    echo "Creating fresh tables...<br>";
    
    // Create tblUser
    $sql = "CREATE TABLE tblUser (
        user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        is_verified TINYINT(1) DEFAULT 0,
        is_admin TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "✓ Created tblUser table<br>";
    
    // Create tblAdmin
    $sql = "CREATE TABLE tblAdmin (
        admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->exec($sql);
    echo "✓ Created tblAdmin table<br>";
    
    // Create tblAorder
    $sql = "CREATE TABLE tblAorder (
        order_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'pending',
        FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
    )";
    $conn->exec($sql);
    echo "✓ Created tblAorder table<br>";
    
    // Create tblClothes
    $sql = "CREATE TABLE tblClothes (
        clothes_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        size VARCHAR(10),
        quantity INT(11) DEFAULT 0,
        description TEXT
    )";
    $conn->exec($sql);
    echo "✓ Created tblClothes table<br><br>";
    
    // Step 5: Insert default admin
    echo "Inserting default admin...<br>";
    $conn->exec("INSERT INTO tblAdmin (username, email, password_hash, full_name) VALUES 
                ('admin', 'admin@clothingstore.com', MD5('admin123'), 'System Administrator')");
    echo "✓ Added admin account (username: admin, password: admin123)<br><br>";
    
    // Step 6: Load users from userData.txt
    echo "Loading users from userData.txt...<br>";
    echo str_repeat("-", 50) . "<br>";
    
    $file_path = __DIR__ . "/userData.txt";
    
    if (file_exists($file_path)) {
        $file = fopen($file_path, "r");
        
        if ($file) {
            $stmt = $conn->prepare("INSERT INTO tblUser (username, email, password_hash, full_name, is_verified, is_admin) 
                                    VALUES (?, ?, MD5(?), ?, ?, ?)");
            
            $line_count = 0;
            $error_count = 0;
            
            while (($line = fgets($file)) !== false) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $data = explode('|', $line);
                if (count($data) == 6) {
                    try {
                        $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5]]);
                        $line_count++;
                        echo "✅ Loaded: " . $data[0] . " - " . $data[3] . "<br>";
                    } catch (PDOException $e) {
                        $error_count++;
                        echo "❌ Failed: " . $data[0] . " - " . $e->getMessage() . "<br>";
                    }
                } else {
                    $error_count++;
                    echo "❌ Invalid line format: " . htmlspecialchars($line) . "<br>";
                }
            }
            fclose($file);
            
            echo str_repeat("-", 50) . "<br>";
            echo "✅ Successfully loaded " . $line_count . " users<br>";
            if ($error_count > 0) {
                echo "⚠️ Failed to load " . $error_count . " entries<br>";
            }
            
        } else {
            echo "❌ Could not open userData.txt file<br>";
        }
    } else {
        echo "❌ userData.txt not found at: " . $file_path . "<br>";
        echo "💡 Creating sample userData.txt for you...<br>";
        
        // Create the file automatically
        $sample_data = "John Doe|john.doe@email.com|john123|John Doe|0|0\n";
        $sample_data .= "Jane Smith|jane.smith@email.com|jane123|Jane Smith|0|0\n";
        $sample_data .= "Bob Johnson|bob.johnson@email.com|bob123|Bob Johnson|1|0\n";
        $sample_data .= "Alice Brown|alice.brown@email.com|alice123|Alice Brown|1|0\n";
        $sample_data .= "Charlie Wilson|charlie.wilson@email.com|charlie123|Charlie Wilson|0|0";
        
        file_put_contents($file_path, $sample_data);
        echo "✅ Created userData.txt with sample data!<br>";
        echo "🔄 Please refresh this page to load the data.<br>";
    }
    
    // Step 7: Insert sample clothes
    echo "<br>Inserting sample products...<br>";
    $conn->exec("INSERT INTO tblClothes (name, category, price, size, quantity, description) VALUES
                ('Classic T-Shirt', 'Mens', 19.99, 'L', 50, 'Comfortable cotton t-shirt'),
                ('Slim Fit Jeans', 'Mens', 49.99, '32', 30, 'Stylish denim jeans'),
                ('Floral Dress', 'Womens', 39.99, 'M', 25, 'Summer floral dress'),
                ('Hoodie', 'Unisex', 59.99, 'XL', 40, 'Warm comfortable hoodie')");
    echo "✓ Added 4 sample products<br>";
    
    // Step 8: Verify data was loaded
    $user_count = $conn->query("SELECT COUNT(*) as total FROM tblUser")->fetch();
    $admin_count = $conn->query("SELECT COUNT(*) as total FROM tblAdmin")->fetch();
    $product_count = $conn->query("SELECT COUNT(*) as total FROM tblClothes")->fetch();
    
    echo "<br>" . str_repeat("=", 50) . "<br>";
    echo "<h3>📊 DATABASE SUMMARY</h3>";
    echo "👥 Users in database: " . $user_count['total'] . "<br>";
    echo "👑 Admin accounts: " . $admin_count['total'] . "<br>";
    echo "👕 Products in store: " . $product_count['total'] . "<br>";
    echo str_repeat("=", 50) . "<br>";
    
    echo "<br><div style='background: #d4edda; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745;'>";
    echo "<strong>✅ SUCCESS! Database setup completed!</strong><br>";
    echo "🔐 Admin Login: username='admin', password='admin123'<br>";
    echo "👤 Test User: username='Bob Johnson', email='bob.johnson@email.com', password='bob123'<br>";
    echo "</div>";
    
    echo "<br><a href='login.php' style='display:inline-block; padding:10px 20px; background:#4CAF50; color:white; text-decoration:none; border-radius:5px;'>🔐 Go to Login Page</a>";
    echo "&nbsp;&nbsp;";
    echo "<a href='admin_login.php' style='display:inline-block; padding:10px 20px; background:#dc3545; color:white; text-decoration:none; border-radius:5px;'>👑 Admin Login</a>";
    
} catch(PDOException $e) {
    // Make sure foreign key checks are re-enabled even if there's an error
    try {
        $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch(Exception $ex) {
        // Ignore
    }
    
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
    echo "<strong>❌ Database Error:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>💡 Troubleshooting tips:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP<br>";
    echo "2. Check that database 'ClothingStore' exists<br>";
    echo "3. Try running reset_all_tables.php first<br>";
    echo "</div>";
}
?>