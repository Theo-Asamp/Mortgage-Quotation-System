<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'headerFooter.php';

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
    <?php render_navbar() ?>
    <div class="container mt-4">
        <div class="table-container">
            <h2>Mortgage Products</h2>
            <a href="add_product.php" id="no-underline">➕ Add New Product</a><br><br>

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
                        <td><?= rtrim(rtrim(number_format($p['InterestRate'], 2, '.', ''), '0'), '.') ?>%</td>
                        <td>
                            <?php
                                $months = (int) $p['MortgageTerm'];
                                $years = intdiv($months, 12);
                                $remainingMonths = $months % 12;

                                if ($years > 0 && $remainingMonths > 0) {
                                    echo "{$years} year" . ($years > 1 ? 's' : '') . " and {$remainingMonths} month" . ($remainingMonths > 1 ? 's' : '');
                                } elseif ($years > 0) {
                                    echo "{$years} year" . ($years > 1 ? 's' : '');
                                } elseif ($remainingMonths > 0) {
                                    echo "{$remainingMonths} month" . ($remainingMonths > 1 ? 's' : '');
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </td>
                        <td>£<?= number_format($p['MinIncome'], 2) ?></td>
                        <td><?= $p['MinCreditScore'] ?></td>
                        <td><?= $p['EmploymentType'] ?></td>
                        <td><?= $p['MinAge'] ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $p['ProductId'] ?>" id="no-underline">✏️ Edit</a> |
                            <a href="delete_product.php?id=<?= $p['ProductId'] ?>" onclick="return confirm('Are you sure?')" id="no-underline">🗑 Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>


    <?php render_footer() ?>
</body>

</html>