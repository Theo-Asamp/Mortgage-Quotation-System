<?php
$database_file = 'rose-mortgage-database.db';

try {
    $conn = new PDO("sqlite:" . $database_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
