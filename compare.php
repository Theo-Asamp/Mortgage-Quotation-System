<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$ids = isset($_GET['ids']) ? array_slice($_GET['ids'], 0, 3) : [];
$loanAmount = floatval($_GET['loan_amount'] ?? 0);
$loanTerm = intval($_GET['loan_term'] ?? 0);

$stmt = $conn->prepare("SELECT DOB FROM Users WHERE UserId = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

function calculateAge($dob) {
    $dobObj = new DateTime($dob);
    $now = new DateTime();
    return $dobObj->diff($now)->y;
}
$userAge = calculateAge($user['DOB']);

// Fetch products
$quotes = [];
if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM Product WHERE ProductId IN ($placeholders)");
    $stmt->execute($ids);
    $fetched = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($fetched as $q) {
        if (isset($q['MinAge']) && $userAge < intval($q['MinAge'])) {
            continue;
        }
        $quotes[] = $q;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_ids'])) {
    foreach ($_POST['save_ids'] as $pid) {
        $monthly = floatval($_POST['monthly'][$pid]);
        $total = floatval($_POST['total'][$pid]);

                $insert = $conn->prepare("INSERT INTO SavedQuotes (UserId, ProductId, InterestAnnually, MortgageLength, MonthlyRepayment, AmountPaidBack) VALUES (?, ?, ?, ?, ?, ?);");
        $insert->execute([$_SESSION['user_id'], $pid, $q['InterestRate'], $loanTerm, $monthly, $total]);
    }
    $message = "✅ Selected quotes saved and updated.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compare Mortgage Quotes</title>
    <link rel="stylesheet" href="css/global.css" />
    <style>
        .compare-wrapper {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 15px;
        }

        th {
            background-color: #009fe3;
            color: #fff;
        }

        .actions {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 15px;
            cursor: pointer;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
        }

        .footer__text a {
            margin: 0 10px;
        }
    </style>
</head>
<body>

<header class="navbar">
    <a href="index.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
    <div class="navbar__buttons">
    <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
      <a href="settings.php"><button class="btn btn--register">Profile Settings</button></a>
      <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
    </div>
</header>

<section class="compare-wrapper">
    <h2>Quote comparison</h2>

    <?php if (!empty($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!empty($quotes)): ?>
        <form method="post">
            <table>
                <tr>
                    <th>Feature</th>
                    <?php foreach ($quotes as $q): ?>
                        <th><?= htmlspecialchars($q['Lender']) ?><br>
                            <input type="checkbox" name="save_ids[]" value="<?= $q['ProductId'] ?>"> Save
                        </th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Interest Rate</td>
                    <?php foreach ($quotes as $q): ?>
                        <td><?= $q['InterestRate'] * 100 ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Term</td>
                    <?php foreach ($quotes as $q): ?>
                        <td><?= $loanTerm ?> years</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Monthly Repayment</td>
                    <?php foreach ($quotes as $q): ?>
                        <?php
                        $monthlyRate = $q['InterestRate'] / 12;
                        $months = $loanTerm * 12;
                        $monthly = ($monthlyRate > 0)
                            ? $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1)
                            : $loanAmount / $months;
                        ?>
                        <td>
                            £<?= number_format($monthly, 2) ?>
                            <input type="hidden" name="monthly[<?= $q['ProductId'] ?>]" value="<?= round($monthly, 2) ?>">
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Total Paid Back</td>
                    <?php foreach ($quotes as $q): ?>
                        <?php
                        $monthlyRate = $q['InterestRate'] / 12;
                        $months = $loanTerm * 12;
                        $monthly = ($monthlyRate > 0)
                            ? $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1)
                            : $loanAmount / $months;
                        $total = $monthly * $months;
                        ?>
                        <td>
                            £<?= number_format($total, 2) ?>
                            <input type="hidden" name="total[<?= $q['ProductId'] ?>]" value="<?= round($total, 2) ?>">
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>

            <div class="actions">
                <button type="submit" class="btn btn--login">💾 Save Selected Quotes</button><br><br>
                <a href="quotation.php">← Back to Quotation</a> |
                <a href="dashboard.php">Back to Dashboard</a>
            </div>
        </form>
    <?php else: ?>
        <p>No eligible products to compare.</p>
    <?php endif; ?>
</section>

<footer class="footer">
      <p class="footer__text">© Rose Brokers 2025</p>
        <a href="/about.php">About</a> |
        <a href="/privacy.php">Privacy Policy</a> |
        <a href="/terms.php">Terms of Use</a> |
        <a href="/contact.php">Contact Us</a>
      </p>
    </footer>
</body>
</html>

