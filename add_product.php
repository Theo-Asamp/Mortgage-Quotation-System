<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'headerFooter.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("INSERT INTO Product 
            (Lender, InterestRate, MortgageTerm, MinIncome, MinCreditScore, EmploymentType, MinAge)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_POST['lender'],
            $_POST['rate'],
            $_POST['term'],
            $_POST['min_income'],
            $_POST['credit_score'],
            $_POST['employment_type'],
            $_POST['minage'],
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
    <?php render_navbar(); ?>
    <div class="container">
        <div class="add-section">
            <h2>Add New Mortgage Product</h2>
            <?php if ($message) echo $message; ?>
            <form method="POST" class="add-form">
                <label>Lender:</label><input type="text" name="lender" required><br><br>
                <label>Interest Rate (%):</label>
                <small style="color: gray;">Enter as a percentage, e.g. 3.75 for 3.75%</small><br>
                <input type="number" step="0.01" min="0" max="100" name="rate" required>
                <label>Mortgage Term (Years):</label><input type="number" name="term" required><br><br>
                <label>Min Income (£):</label><input type="number" name="min_income" required><br><br>
                <label>Min Credit Score:</label><input type="number" name="credit_score" required><br><br>
                <label>Employment Type:</label>
                <select name="employment_type" required>
                    <option value="Full-Time Employed">Full-Time Employed</option>
                    <option value="Part-Time Employed">Part-Time Employed</option>
                    <option value="Self-Employed">Self-Employed</option>
                    <option value="any">Any</option>
                </select><br><br>
                <label>Min Age:</label><input type="number" step="0.01" name="minage" required><br><br>
                <input type="submit" value="Add Product">
            </form>
            <div class="text-center mt-3">
                <a href="broker-dashboard.php" class="btn btn-custom" id="broker-dashboard-back">🏠 Back to Dashboard</a>
            </div>
        </div>
    </div>
    <?php render_footer(); ?>
</body>

</html>