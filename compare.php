<?php
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
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages Compare</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 10px; text-align: center; }
        .actions { margin-top: 20px; }
    </style>
</head>
<body>


    <header class="navbar">
        <a href="/dashboard.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="register.php"><button class="btn btn--register">Register</button></a>
            <a href="login.php"><button class="btn btn--login">Log In</button></a>
        </div>
    </header>






<?php if (!empty($message)): ?><p style="color:green;"><?= $message ?></p><?php endif; ?>
<?php if (count($quotes) > 0): ?>
<section class="intro-section">

    


    <div class="intro-section__content">

        <h4 class="card__title">Quote comparison</h4>


    <form method="post">
        <table>
            <tr><th>Feature</th><?php foreach ($quotes as $q): ?><th><?= htmlspecialchars($q['Lender']) ?><br><input type="checkbox" name="save_ids[]" value="<?= $q['ProductId'] ?>"> Save</th><?php endforeach; ?></tr>
            <tr><td>Interest Rate</td><?php foreach ($quotes as $q): ?><td><?= $q['InterestRate'] ?>%</td><?php endforeach; ?></tr>
            <tr><td>Term</td><?php foreach ($quotes as $q): ?><td><?= $q['MortgageTerm'] ?> years</td><?php endforeach; ?></tr>
            <tr><td>Monthly Repayment</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['MonthlyRepayment'], 2) ?></td><?php endforeach; ?></tr>
            <tr><td>Total Paid Back</td><?php foreach ($quotes as $q): ?><td>£<?= number_format($q['AmountPaidBack'], 2) ?></td><?php endforeach; ?></tr>
        </table>
        
    
    
        <div class="actions">
            <button type="submit" class="btn btn--login">💾 Save Selected Quotes</button>
            <a href="affordability.php"> Back to Affordability</a> |
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </form>

    </div>


</section>





<?php else: ?>
    <p>No quotes selected for comparison.</p>
<?php endif; ?>


<footer class="footer">
        <p class="footer__text">
            <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
            <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
        </p>
    </footer>


</body>
</html>
