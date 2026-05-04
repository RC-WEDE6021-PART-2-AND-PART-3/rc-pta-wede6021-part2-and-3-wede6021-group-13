<?php
// DBConn.php - Database connection file
$servername = "localhost";
$username = "root";      // XAMPP default username
$password = "";          // XAMPP default password (empty)
$dbname = "ClothingStore";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    echo "Connected successfully to ClothingStore database!<br>";
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>