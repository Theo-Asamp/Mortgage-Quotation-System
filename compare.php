<?php
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
?>
<!DOCTYPE html>
<html>
<head>
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
</body>
</html>
