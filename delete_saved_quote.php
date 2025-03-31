<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $stmt = $conn->prepare("DELETE FROM SavedQuotes WHERE ProductId = ? AND UserId = ?");
    $stmt->execute([$_GET['product_id'], $_SESSION['user_id']]);
}

header("Location: dashboard.php?deleted=1");
exit();
?>
