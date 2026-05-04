<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clothing Store - Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #333; color: white; padding: 15px; }
        .products { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .btn-logout { background-color: red; color: white; padding: 10px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Clothing Store</h1>
        <div>
            Welcome, <?php echo $_SESSION['full_name']; ?>! 
            <a href="logout.php" class="btn-logout">Logout</a>
            <?php if ($_SESSION['is_admin'] == 1): ?>
                | <a href="admin_dashboard.php" style="color: white;">Admin Panel</a>
            <?php endif; ?>
        </div>
    </div>
    
    <h2>Our Products</h2>
    <div class="products">
        <?php
        $products = $conn->query("SELECT * FROM tblClothes")->fetchAll();
        foreach ($products as $product):
        ?>
        <div class="product">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p>Category: <?php echo $product['category']; ?></p>
            <p>Price: $<?php echo $product['price']; ?></p>
            <p>Size: <?php echo $product['size']; ?></p>
            <p>In Stock: <?php echo $product['quantity']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>