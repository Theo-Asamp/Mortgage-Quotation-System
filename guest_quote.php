<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $income = floatval($_POST['income']);
    $outgoings = floatval($_POST['outgoings']);
    $multiplier = 4.5;
    if ($income > 0 && $outgoings >= 0) {
        $borrowing = ($income - $outgoings) * $multiplier;
        $borrowing = max(0, $borrowing);
    } else {
        $error = "Please enter valid numbers.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Guest Mortgage Estimator</title></head>
<body>
    <h2>Estimate Your Mortgage (No Login Needed)</h2>
    <form method="post">
        <label>Annual Income (£):</label>
        <input type="number" name="income" required><br><br>
        <label>Monthly Outgoings (£):</label>
        <input type="number" name="outgoings" required><br><br>
        <input type="submit" value="Estimate">
    </form>
    <?php if (isset($borrowing)): ?>
        <h3>Estimated Maximum Mortgage: £<?= number_format($borrowing, 2) ?></h3>
    <?php elseif (isset($error)): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>
