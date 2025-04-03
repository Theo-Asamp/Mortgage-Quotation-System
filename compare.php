<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'headerFooter.php';

$ids = isset($_GET['ids']) ? array_slice($_GET['ids'], 0, 3) : [];
$loanAmount = floatval($_GET['loan_amount'] ?? 0);
$loanTerm = isset($_GET['loan_term']) ? intval($_GET['loan_term']) : 0;
$loanTermMonths = isset($_GET['loan_term_months']) ? intval($_GET['loan_term_months']) : 0;
$totalMonths = ($loanTerm * 12) + $loanTermMonths;

$monthlyValues = $_GET['monthly'] ?? [];
$totalValues = $_GET['total'] ?? [];

$stmt = $conn->prepare("SELECT DOB FROM Users WHERE UserId = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

function calculateAge($dob) {
    $dobObj = new DateTime($dob);
    $now = new DateTime();
    return $dobObj->diff($now)->y;
}

$userAge = calculateAge($user['DOB']);

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
    $loanTerm = isset($_GET['loan_term']) ? intval($_GET['loan_term']) : 0;
    $loanTermMonths = isset($_GET['loan_term_months']) ? intval($_GET['loan_term_months']) : 0;
    $totalMonths = ($loanTerm * 12) + $loanTermMonths;

    foreach ($_POST['save_ids'] as $pid) {
        $monthly = floatval($_POST['monthly'][$pid]);
        $total = floatval($_POST['total'][$pid]);

        $rate = null;
        foreach ($quotes as $q) {
            if ($q['ProductId'] == $pid) {
                $rate = $q['InterestRate'];
                break;
            }
        }

        if ($rate !== null) {
            $insert = $conn->prepare("INSERT INTO SavedQuotes (
                UserId, ProductId, InterestAnnually, MortgageLength, MonthlyRepayment, AmountPaidBack
            ) VALUES (?, ?, ?, ?, ?, ?)");

            $insert->execute([
                $_SESSION['user_id'],
                $pid,
                $rate,
                $totalMonths,
                $monthly,
                $total
            ]);
        }
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

<?php render_navbar() ?>

<section class="compare-wrapper">
    <h2>Quote comparison</h2>

    <?php if (!empty($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <?php if (!empty($quotes)): ?>
        <form method="post">
        <?php foreach ($quotes as $q): ?>
            <input type="hidden" name="monthly[<?= $q['ProductId'] ?>]" value="<?= $monthlyValues[$q['ProductId']] ?? 0 ?>">
            <input type="hidden" name="total[<?= $q['ProductId'] ?>]" value="<?= $totalValues[$q['ProductId']] ?? 0 ?>">
            <?php endforeach; ?>
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
                        <td><?= rtrim(rtrim(number_format($q['InterestRate'], 2, '.', ''), '0'), '.') ?>%</td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Term of Loans</td>
                    <?php
                      $years = intdiv($totalMonths, 12);
                      $months = $totalMonths % 12;
                      if ($years > 0 && $months > 0) {
                          $termDisplay = "{$years} year" . ($years > 1 ? 's' : '') . " and {$months} month" . ($months > 1 ? 's' : '');
                      } elseif ($years > 0) {
                          $termDisplay = "{$years} year" . ($years > 1 ? 's' : '');
                      } elseif ($months > 0) {
                          $termDisplay = "{$months} month" . ($months > 1 ? 's' : '');
                      } else {
                          $termDisplay = "N/A";
                      }
                    ?>
                    <?php foreach ($quotes as $q): ?>
                        <td><?= $termDisplay ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Monthly Repayment</td>
                    <?php foreach ($quotes as $q): ?>
                        <td>£<?= number_format($monthlyValues[$q['ProductId']] ?? 0, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>Total Repayment</td>
                    <?php foreach ($quotes as $q): ?>
                        <td>£<?= number_format($totalValues[$q['ProductId']] ?? 0, 2) ?></td>
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

<?php render_footer() ?>
</body>
</html>
