<?php
// DBConnMySqli.php - improved MySQLi connection include
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ClothingStore";

$conn = new mysqli($servername, $username, $password);
$conn->set_charset("utf8mb4");

if (!$conn->select_db($dbname)) {
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($dbname);
}
