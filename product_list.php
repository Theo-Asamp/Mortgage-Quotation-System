<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$products = $conn->query("SELECT * FROM Product")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Product List</title>
</head>
<body>
<header class="navbar">
    <a href="/broker-dashboard.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="broker-dashboard.php"><button class="btn btn--register">Dashboard</button></a>
            <a href="broker-setting.php"><button class="btn btn--register">Profile</button></a>
            <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
        </div>
</header>
<h2>Mortgage Products</h2>
<a href="add_product.php">➕ Add New Product</a><br><br>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Lender</th>
        <th>Interest Rate</th>
        <th>Term</th>
        <th>Min Income</th>
        <th>Min Credit Score</th>
        <th>Employment Type</th>
        <th>Min Age</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['ProductId'] ?></td>
            <td><?= htmlspecialchars($p['Lender']) ?></td>
            <td><?= $p['InterestRate'] * 100 ?>%</td>
            <td><?= $p['MortgageTerm'] ?> yrs</td>
            <td>£<?= number_format($p['MinIncome'], 2) ?></td>
            <td><?= $p['MinCreditScore'] ?></td>
            <td><?= $p['EmploymentType'] ?></td>
            <td><?= $p['MinAge'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['ProductId'] ?>">✏️ Edit</a> |
                <a href="delete_product.php?id=<?= $p['ProductId'] ?>" onclick="return confirm('Are you sure?')">🗑 Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
