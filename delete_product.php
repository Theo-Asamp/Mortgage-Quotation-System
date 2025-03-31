<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) die("No product ID.");

$stmt = $conn->prepare("DELETE FROM Product WHERE ProductId = ?");
$stmt->execute([$id]);

header("Location: product_list.php");
exit();
