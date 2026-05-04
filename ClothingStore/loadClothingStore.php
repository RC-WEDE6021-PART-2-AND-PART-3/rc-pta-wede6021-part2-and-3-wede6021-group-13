<?php
// loadClothingStore.php - Load ClothingStore DDL using MySQLi and exported SQL
include 'DBConnMySqli.php';

$sqlFile = __DIR__ . '/myClothingStore.sql';
if (!file_exists($sqlFile)) {
    die('SQL file not found: ' . $sqlFile);
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    die('Unable to read SQL file: ' . $sqlFile);
}

// Remove SQL comments that can interfere with multi_query execution
$sql = preg_replace('/^\s*(?:--|#).*$/m', '', $sql);

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    echo '<p>✅ ClothingStore database and tables have been created or refreshed successfully.</p>';
} else {
    die('<p>❌ SQL execution error: ' . $conn->error . '</p>');
}

$conn->close();
