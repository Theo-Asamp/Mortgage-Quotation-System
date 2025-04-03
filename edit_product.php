<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'headerFooter.php';

if (!isset($_GET['id'])) {
    header("Location: product_list.php");
    exit();
}

$productId = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Product WHERE ProductId = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lender = $_POST['lender'];
    $rate = floatval($_POST['rate']);
    $years = intval($_POST['term_years']);
    $months = intval($_POST['term_months']);
    $termInMonths = ($years * 12) + $months;
    $minIncome = floatval($_POST['min_income']);
    $minScore = intval($_POST['credit_score']);
    $employment = $_POST['employment_type'];
    $minAge = intval($_POST['min_age']);

    if ($termInMonths <= 0) {
        $error = 'Mortgage term must be greater than 0.';
    } else {
        $update = $conn->prepare("UPDATE Product SET Lender=?, InterestRate=?, MortgageTerm=?, MinIncome=?, MinCreditScore=?, EmploymentType=?, MinAge=? WHERE ProductId=?");
        $update->execute([$lender, $rate, $termInMonths, $minIncome, $minScore, $employment, $minAge, $productId]);
        header("Location: product_list.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/global.css" />
</head>
<body>
    <?php render_navbar() ?>
    <div class="container">
        <div class="add-section">
            <h2>Edit Product #<?= $product['ProductId'] ?></h2>

            <?php if ($error): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" class="add-form">
                <label>Lender:</label>
                <input type="text" name="lender" value="<?= htmlspecialchars($product['Lender']) ?>" required><br><br>

                <label>Interest Rate (%):</label><br>
                <small style="color: gray;">Enter as a percentage, e.g. 3.75 for 3.75%</small><br>
                <input type="number" step="0.01" min="0" max="100" name="rate" value="<?= $product['InterestRate'] ?>" required><br><br>

                <label>Mortgage Term:</label><br>
                <?php
                    $years = intdiv($product['MortgageTerm'], 12);
                    $months = $product['MortgageTerm'] % 12;
                ?>
                Years:
                <input type="number" name="term_years" min="0" value="<?= $years ?>" required>
                Months:
                <input type="number" name="term_months" min="0" max="11" value="<?= $months ?>" required><br><br>

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
