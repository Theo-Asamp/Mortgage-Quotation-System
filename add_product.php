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
        $lender = $_POST['lender'];
        $rate = floatval($_POST['rate']);
        $years = intval($_POST['term_years']);
        $months = intval($_POST['term_months']);
        $termInMonths = ($years * 12) + $months;
        $minIncome = floatval($_POST['min_income']);
        $minScore = intval($_POST['credit_score']);
        $employment = $_POST['employment_type'];
        $minAge = intval($_POST['minage']);

        if ($termInMonths <= 0) {
            throw new Exception('Mortgage term must be greater than 0.');
        }

        $stmt = $conn->prepare("INSERT INTO Product 
            (Lender, InterestRate, MortgageTerm, MinIncome, MinCreditScore, EmploymentType, MinAge)
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $lender,
            $rate,
            $termInMonths,
            $minIncome,
            $minScore,
            $employment,
            $minAge,
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
        <div class="add-section-product">
            <h2>Add New Mortgage Product</h2>
            <?php if ($message) echo $message; ?>
            <form method="POST" class="add-form">
                <label>Lender:</label><input type="text" name="lender" required><br><br>

                <label>Interest Rate (%):</label>
                <small style="color: gray;">Enter as a percentage, e.g. 3.75 for 3.75%</small><br>
                <input type="number" step="0.01" min="0" max="100" name="rate" required><br><br>

                <label>Mortgage Term:</label><br>
                Years: <input type="number" name="term_years" min="0" required>
                Months: <input type="number" name="term_months" min="0" max="11" required><br><br>

                <label>Min Income (£):</label><input type="number" name="min_income" required><br><br>
                <label>Min Credit Score:</label><input type="number" name="credit_score" required><br><br>

                <label>Employment Type:</label>
                <select name="employment_type" required>
                    <option value="Full-Time Employed">Full-Time Employed</option>
                    <option value="Part-Time Employed">Part-Time Employed</option>
                    <option value="Self-Employed">Self-Employed</option>
                    <option value="any">Any</option>
                </select><br><br>

                <label>Min Age:</label><input type="number" name="minage" required><br><br>

                <input type="submit" value="Add Product">
            </form>
            <div class="text-center mt-3">
                <a href="product_list.php" class="btn btn-custom" id="broker-dashboard-back">📄 Go to Manage Product List</a>
            </div>
        </div>
    </div>
    <!-- Footer needs to be rendered manually due to css conflicts.. -->
    <footer class="footer-add-product">
        <p class="footer-add-product-text">© Rose Brokers 2025</p>
        <a href="/about.php">About</a> |
        <a href="/privacy.php">Privacy Policy</a> |
        <a href="/terms.php">Terms of Use</a> |
        <a href="/contact.php">Contact Us</a>
    </footer>

    <style>
        .footer-add-product {
            background-color: #01A7F0;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 140px;
        }

        .footer-add-product a {
            text-decoration: none;
            color: white;
            margin: 0 10px;
            font-size: 14px;
        }

        .footer-add-product a:hover {
            text-decoration: underline;
        }

        .add-section-product {
            justify-content: center;
            align-items: center;
            padding: 50px 10%;
            margin-top: 100px;
            position: relative;
        }

        .add-section-product h2 {
            font-size: 28px;
            color: black;
            margin-bottom: 10px;
        }

        .add-section-product p {
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
        }
    </style>

</body>

</html>