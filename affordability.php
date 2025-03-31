
<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$matches = [];
$borrowing_capacity = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income = floatval($_POST['income']);
    $outgoings = floatval($_POST['outgoings']);
    $employment = $_POST['employment_type'];
    $credit_score = intval($_POST['credit_score']);
    
    // Convert annual income to estimated net monthly income (roughly 75%)
    $monthly_net = ($income / 12) * 0.75;

    // Apply affordability formula
    $borrowing_capacity = ($monthly_net - $outgoings) * 4.5;

    // Get matching products
    $stmt = $conn->prepare("SELECT * FROM Product WHERE MinIncome <= ? AND MaxOutgoings >= ? AND MinCreditScore <= ? AND (EmploymentType = ? OR EmploymentType = 'any')");
    $stmt->execute([$income, $outgoings, $credit_score, $employment]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mortgage Affordability</title>
    <script>
        function limitSelection(checkbox) {
            const selected = document.querySelectorAll('input[name="ids[]"]:checked');
            if (selected.length > 3) {
                alert("You can only compare up to 3 products.");
                checkbox.checked = false;
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<h2>Mortgage Affordability Check</h2>
<form method="post">
    <label>Annual Income (£):</label><br>
    <input type="number" name="income" required><br><br>

    <label>Monthly Outgoings (£):</label><br>
    <input type="number" name="outgoings" required><br><br>

    <label>Employment Type:</label><br>
    <select name="employment_type" required>
        <option value="full-time">Full-Time</option>
        <option value="part-time">Part-Time</option>
        <option value="self-employed">Self-Employed</option>
        <option value="any">Other</option>
    </select><br><br>

    <label>Credit Score:</label><br>
    <input type="number" name="credit_score" min="0" max="999" required><br><br>

    <input type="submit" value="Check Affordability">
</form>

<?php if ($borrowing_capacity !== null): ?>
    <h3>Your Estimated Borrowing Capacity: £<?= number_format($borrowing_capacity, 2) ?></h3>
<?php endif; ?>

<?php if (!empty($matches)): ?>
    <h3>Matching Mortgage Products</h3>
    <form method="get" action="compare.php">
        <?php foreach ($matches as $match): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <input type="checkbox" name="ids[]" value="<?= $match['ProductId'] ?>" onclick="return limitSelection(this)">
                <strong><?= htmlspecialchars($match['Lender']) ?></strong><br>
                Rate: <?= $match['InterestRate'] ?>%<br>
                Term: <?= $match['MortgageTerm'] ?> years<br>
                Monthly: £<?= number_format($match['MonthlyRepayment'], 2) ?><br>
                Total: £<?= number_format($match['AmountPaidBack'], 2) ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Compare Selected</button>
    </form>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p>No matching mortgage products found based on your criteria.</p>
<?php endif; ?>

<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
