<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) die("No product ID.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("
        UPDATE Product 
        SET Lender = ?, 
            InterestRate = ?, 
            MortgageTerm = ?, 
            MinIncome = ?, 
            MinCreditScore = ?, 
            EmploymentType = ?, 
            MinAge = ?
        WHERE ProductId = ?
    ");
    $stmt->execute([
        $_POST['lender'], 
        $_POST['rate'], 
        $_POST['term'], 
        $_POST['min_income'], 
        $_POST['credit_score'],
        $_POST['employment_type'], 
        $_POST['min_age'],
        $id
    ]);
    header("Location: product_list.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM Product WHERE ProductId = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) die("Product not found.");
?>

<!DOCTYPE html>
<<<<<<< HEAD
<html>
=======
>>>>>>> 3f3ed77529acef46068826aeb1d93f83f5b84670
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
<<<<<<< HEAD
    <title>Edit Mortgage Product</title></head>
<body>
<header class="navbar">
    <a href="/broker-dashboard.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="broker-dashboard.php"><button class="btn btn--register">Dashboard</button></a>
            <a href="broker-setting.php"><button class="btn btn--register">Profile</button></a>
            <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
        </div>
</header>
<h2>Edit Product #<?= $product['ProductId'] ?></h2>
<form method="POST">
    <label>Lender:</label>
    <input type="text" name="lender" value="<?= $product['Lender'] ?>" required><br><br>

    <label>Interest Rate (%):</label>
    <input type="number" step="0.01" name="rate" value="<?= $product['InterestRate'] ?>" required><br><br>

    <label>Mortgage Term (Years):</label>
    <input type="number" name="term" value="<?= $product['MortgageTerm'] ?>" required><br><br>

    <label>Min Income (£):</label>
    <input type="number" name="min_income" value="<?= $product['MinIncome'] ?>" required><br><br>

    <label>Min Credit Score:</label>
    <input type="number" name="credit_score" value="<?= $product['MinCreditScore'] ?>" required><br><br>

    <label>Employment Type:</label>
    <select name="employment_type" required>
        <option value="Full-Time Employed" <?= $product['EmploymentType'] === 'Full-Time Employed' ? 'selected' : '' ?>>Full-Time Employed</option>
        <option value="Part-Time Employed" <?= $product['EmploymentType'] === 'Part-Time Employed' ? 'selected' : '' ?>>Part-Time Employed</option>
        <option value="Self-Employed" <?= $product['EmploymentType'] === 'Self-Employed' ? 'selected' : '' ?>>Self-Employed</option>
        <option value="any" <?= $product['EmploymentType'] === 'any' ? 'selected' : '' ?>>Any</option>
    </select><br><br>

    <label>Minimum Age:</label>
    <input type="number" name="min_age" min="18" max="100" value="<?= $product['MinAge'] ?>" required><br><br>

    <input type="submit" value="Update Product">
</form>
<p><a href="product_list.php">⬅ Back to Product List</a></p>
=======
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
            <h2>Edit Product #<?= $product['ProductId'] ?></h2>
            <form method="POST" class="add-form">
                <label>Lender:</label><input type="text" name="lender" value="<?= $product['Lender'] ?>" required><br><br>
                <label>Interest Rate (%):</labe><input type="number" step="0.01" name="rate" value="<?= $product['InterestRate'] ?>" required><br><br>
                <label>Mortgage Term (Years):</label><input type="number" name="term" value="<?= $product['MortgageTerm'] ?>" required><br><br>
                <label>Min Income (£):</labe><input type="number" name="min_income" value="<?= $product['MinIncome'] ?>" required><br><br>
                <label>Min Credit Score:</label><input type="number" name="credit_score" value="<?= $product['MinCreditScore'] ?>" required><br><br>
                <label>Employment Type:</label>
                <select name="employment_type" required>
                    <option value="full-time" <?= $product['EmploymentType'] == 'full-time' ? 'selected' : '' ?>>Full-Time</option>
                    <option value="part-time" <?= $product['EmploymentType'] == 'part-time' ? 'selected' : '' ?>>Part-Time</option>
                    <option value="self-employed" <?= $product['EmploymentType'] == 'self-employed' ? 'selected' : '' ?>>Self-Employed</option>
                    <option value="any" <?= $product['EmploymentType'] == 'any' ? 'selected' : '' ?>>Any</option>
                </select><br><br>
                <label>Monthly Repayment (£):</label><input type="number" step="0.01" name="repayment" value="<?= $product['MonthlyRepayment'] ?>" required><br><br>
                <label>Total Paid Back (£):</label><input type="number" step="0.01" name="paidback" value="<?= $product['AmountPaidBack'] ?>" required><br><br>
                <input type="submit" value="Update Product">
            </form>
            <div class="text-center mt-3">
                <a href="broker-dashboard.php" class="btn btn-custom">🏠 Back to Dashboard</a>
            </div>
        </div>
    </div>
>>>>>>> 3f3ed77529acef46068826aeb1d93f83f5b84670
</body>
</html>
