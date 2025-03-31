<?php
<<<<<<< HEAD
session_start();
require 'db.php';

$ids = isset($_GET['ids']) ? $_GET['ids'] : [];
$ids = array_slice($ids, 0, 3);
$quotes = [];

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM Product WHERE ProductId IN ($placeholders)");
    $stmt->execute($ids);
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_ids'])) {
    $userId = $_SESSION['user_id'];
    foreach ($_POST['save_ids'] as $pid) {
        $insert = $conn->prepare("INSERT INTO SavedQuotes (UserId, ProductId) VALUES (?, ?)");
        $insert->execute([$userId, $pid]);
    }
    $message = "✅ Selected quotes have been saved.";
}
=======
$ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
$ids = array_slice($ids, 0, 3);
require 'db.php';
$quotes = [];
if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM SavedQuotes WHERE QuoteId IN ($placeholders)");
    $stmt->execute($ids);
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
>>>>>>> ad001b435fe06c3d38ccadabf746efe9c19f15c8
?>
<!DOCTYPE html>
<html>
<head>
<<<<<<< HEAD
    <title>Compare Mortgage Quotes</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: center; }
        .actions { margin-top: 20px; }
    </style>
</head>
<body>
<h2>Quote Comparison</h2>
<?php if (!empty($message)): ?><p style="color:green;"><?= $message ?></p><?php endif; ?>
<?php if (count($quotes) > 0): ?>
<form method="post">
    <table>
        <tr><th>Feature</th><?php foreach ($quotes as $q): ?><th><?= htmlspecialchars($q['Lender']) ?><br><input type="checkbox" name="save_ids[]" value="<?= $q['ProductId'] ?>"> Save</th><?php endforeach; ?></tr>
        <tr><td>Interest Rate</td><?php foreach ($quotes as $q): ?><td><?= $q['InterestRate'] ?>%</td><?php endforeach; ?></tr>
        <tr><td>Term</td><?php foreach ($quotes as $q): ?><td><?= $q['MortgageTerm'] ?> years</td><?php endforeach; ?></tr>
        <tr><td>Monthly Repayment</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['MonthlyRepayment'], 2) ?></td><?php endforeach; ?></tr>
        <tr><td>Total Paid Back</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['AmountPaidBack'], 2) ?></td><?php endforeach; ?></tr>
    </table>
    <div class="actions">
        <button type="submit">💾 Save Selected Quotes</button>
        <a href="affordability.php">🔙 Back to Affordability</a> |
        <a href="dashboard.php">🏠 Back to Dashboard</a>
    </div>
</form>
<?php else: ?>
    <p>No quotes selected for comparison.</p>
<?php endif; ?>
=======
<title>Compare Mortgage Quotes</title>
<style>table {border-collapse: collapse; width: 100%;} th, td {border: 1px solid #aaa; padding: 10px; text-align: center;}</style>
</head>
<body>
<h2>Quote Comparison</h2>
<?php if (count($quotes) > 0): ?>
<table>
<tr><th>Feature</th><?php foreach ($quotes as $q): ?><th>Quote #<?= $q['QuoteId'] ?></th><?php endforeach; ?></tr>
<tr><td>Borrow Amount</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['BorrowAmount'], 2) ?></td><?php endforeach; ?></tr>
<tr><td>Interest Rate</td><?php foreach ($quotes as $q): ?><td><?= $q['InterestAnnually'] ?>%</td><?php endforeach; ?></tr>
<tr><td>Term</td><?php foreach ($quotes as $q): ?><td><?= $q['MortgageLength'] ?> years</td><?php endforeach; ?></tr>
<tr><td>Monthly Repayment</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['MonthlyRepayment'], 2) ?></td><?php endforeach; ?></tr>
</table>
<?php else: ?><p>No quotes selected for comparison.</p><?php endif; ?>
>>>>>>> ad001b435fe06c3d38ccadabf746efe9c19f15c8
</body>
</html>
