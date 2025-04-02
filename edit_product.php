<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'headerFooter.php';

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
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/global.css" />
        <title>Edit Mortgage Product</title>
    </head>
    <body>
    <?php render_navbar() ?>
    <div class="container">
    <div class="add-section">
        <h2>Edit Product #<?= $product['ProductId'] ?></h2>
        <form method="POST" class="add-form">
            <label>Lender:</label>
            <input type="text" name="lender" value="<?= $product['Lender'] ?>" required><br><br>

            <label>Interest Rate (%):</label>
            <small style="color: gray;">Enter as a percentage, e.g. 3.75 for 3.75%</small>
            <input type="number" step="0.01" min="0" max="100" name="rate" value="<?= $product['InterestRate'] ?>" required>


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
        <p><a href="product_list.php">⬅ Back to Product List</a></p>
    </form>
    </div>
    </div>

    <?php render_footer() ?>
    </body>
</html>
