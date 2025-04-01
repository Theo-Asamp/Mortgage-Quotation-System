<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$stmt = $conn->query("SELECT * FROM Product ORDER BY ProductId DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Manage products</title>
</head>
<body>
<header class="navbar">
    <a href="/broker-dashboard.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="broker-setting.php"><button class="btn btn--register">Profile</button></a>
            <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
        </div>
    </header>  
    <div class="container mt-4">
    <div class="table-container">
        <h2 class="text-center mb-4">🏡 All Mortgage Products</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Lender</th><th>Rate</th><th>Term</th>
                        <th>Income</th><th>Outgoings</th><th>Score</th><th>Type</th>
                        <th>Monthly</th><th>Total</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['ProductId'] ?></td>
                        <td><?= htmlspecialchars($p['Lender']) ?></td>
                        <td><?= $p['InterestRate'] ?>%</td>
                        <td><?= $p['MortgageTerm'] ?>y</td>
                        <td>£<?= number_format($p['MinIncome'], 2) ?></td>
                        <td>£<?= number_format($p['MaxOutgoings'], 2) ?></td>
                        <td><?= $p['MinCreditScore'] ?></td>
                        <td><?= $p['EmploymentType'] ?></td>
                        <td>£<?= number_format($p['MonthlyRepayment'], 2) ?></td>
                        <td>£<?= number_format($p['AmountPaidBack'], 2) ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $p['ProductId'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_product.php?id=<?= $p['ProductId'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <a href="broker-dashboard.php" class="btn btn-custom">🏠 Back to Dashboard</a>
        </div>
    </div>
</div>
</html>
