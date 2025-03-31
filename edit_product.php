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
    $stmt = $conn->prepare("UPDATE Product SET Lender=?, InterestRate=?, MortgageTerm=?, MinIncome=?, MaxOutgoings=?, MinCreditScore=?, EmploymentType=?, MonthlyRepayment=?, AmountPaidBack=? WHERE ProductId=?");
    $stmt->execute([
        $_POST['lender'], $_POST['rate'], $_POST['term'], $_POST['min_income'], $_POST['max_outgoings'], $_POST['credit_score'],
        $_POST['employment_type'], $_POST['repayment'], $_POST['paidback'], $id
    ]);
    header("Location: product_list.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM Product WHERE ProductId=?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) die("Product not found.");
?>
<!DOCTYPE html>
<html>
<head><title>Edit Mortgage Product</title></head>
<body>
<h2>Edit Product #<?= $product['ProductId'] ?></h2>
<form method="POST">
    <label>Lender:</label><input type="text" name="lender" value="<?= $product['Lender'] ?>" required><br><br>
    <label>Interest Rate (%):</label><input type="number" step="0.01" name="rate" value="<?= $product['InterestRate'] ?>" required><br><br>
    <label>Mortgage Term (Years):</label><input type="number" name="term" value="<?= $product['MortgageTerm'] ?>" required><br><br>
    <label>Min Income (£):</label><input type="number" name="min_income" value="<?= $product['MinIncome'] ?>" required><br><br>
    <label>Max Outgoings (£):</label><input type="number" name="max_outgoings" value="<?= $product['MaxOutgoings'] ?>" required><br><br>
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
<p><a href="product_list.php">⬅ Back to Product List</a></p>
</body>
</html>
