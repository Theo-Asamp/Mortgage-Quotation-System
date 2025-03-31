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
<head><title>All Mortgage Products</title></head>
<body>
<h2>All Mortgage Products</h2>
<table border="1" cellpadding="10">
<tr>
    <th>ID</th><th>Lender</th><th>Rate</th><th>Term</th>
    <th>Income</th><th>Outgoings</th><th>Score</th><th>Type</th>
    <th>Monthly</th><th>Total</th><th>Actions</th>
</tr>
<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['ProductId'] ?></td>
    <td><?= htmlspecialchars($p['Lender']) ?></td>
    <td><?= $p['InterestRate'] ?>%</td>
    <td><?= $p['MortgageTerm'] ?>y</td>
    <td>£<?= $p['MinIncome'] ?></td>
    <td>£<?= $p['MaxOutgoings'] ?></td>
    <td><?= $p['MinCreditScore'] ?></td>
    <td><?= $p['EmploymentType'] ?></td>
    <td>£<?= $p['MonthlyRepayment'] ?></td>
    <td>£<?= $p['AmountPaidBack'] ?></td>
    <td>
        <a href="edit_product.php?id=<?= $p['ProductId'] ?>">Edit</a> |
        <a href="delete_product.php?id=<?= $p['ProductId'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
<p><a href="broker-dashboard.php">🏠 Back to Dashboard</a></p>
</body>
</html>
