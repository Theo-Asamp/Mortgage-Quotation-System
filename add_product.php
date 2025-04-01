<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO Product 
            (Lender, InterestRate, MortgageTerm, MinIncome, MaxOutgoings, MinCreditScore, EmploymentType, MonthlyRepayment, AmountPaidBack)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_POST['lender'],
            $_POST['rate'],
            $_POST['term'],
            $_POST['min_income'],
            $_POST['max_outgoings'],
            $_POST['credit_score'],
            $_POST['employment_type'],
            $_POST['repayment'],
            $_POST['paidback']
        ]);

        $message = "<h3>✅ Product added successfully.</h3>";
    } catch (Exception $e) {
        $message = "<h3 style='color:red;'>❌ Error: " . $e->getMessage() . "</h3>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Add products</title>
</head>
<body>
<header class="navbar">
    <a href="/broker-dashboard.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="broker-setting.php"><button class="btn btn--register">Profile</button></a>
            <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
        </div>
    </header>
    <div class="container">
    <div class="add-section">
        <h2>Add New Mortgage Product</h2>
        <?php if ($message) echo $message; ?>
        <form method="POST" class="add-form">
            <label>Lender:</label><input type="text" name="lender" required><br><br>
            <label>Interest Rate (%):</label><input type="number" step="0.01" name="rate" required><br><br>
            <label>Mortgage Term (Years):</label><input type="number" name="term" required><br><br>
            <label>Min Income (£):</label><input type="number" name="min_income" required><br><br>
            <label>Max Outgoings (£):</label><input type="number" name="max_outgoings" required><br><br>
            <label>Min Credit Score:</label><input type="number" name="credit_score" required><br><br>
            <label>Employment Type:</label>
            <select name="employment_type" required>
                <option value="full-time">Full-Time</option>
                <option value="part-time">Part-Time</option>
                <option value="self-employed">Self-Employed</option>
                <option value="any">Any</option>
            </select><br><br>
            <label>Monthly Repayment (£):</label><input type="number" step="0.01" name="repayment" required><br><br>
            <label>Total Paid Back (£):</label><input type="number" step="0.01" name="paidback" required><br><br>
            <input type="submit" value="Add Product" >
        </form>
        <div class="text-center mt-3">
            <a href="broker-dashboard.php" class="btn btn-custom">🏠 Back to Dashboard</a>
        </div>    
    </div>
    </div>
</body>
</html>
