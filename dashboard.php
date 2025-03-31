<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages profile</title>
</head>

<body>
<header class="navbar">
    <a href="index.html" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
    <div class="navbar__buttons">
        <a href="settings.php"><button class="btn btn--register">Profile</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
    </div>
</header>

<section class="intro-section">
    <div class="intro-section__content">
        <h1>Welcome, <?php echo $_SESSION['fullname']; ?>!</h1>
        <h2 class="intro-section__title">Mortgages from Rose Brokers</h2>
        <p class="intro-section__text">Whether you're a first-time buyer or looking for a better deal, we can help you find a
            mortgage that's right for you.</p>
        <p class="intro-section__text">If you already have a mortgage with us, log in to your account, find out how to switch
            deals or get help here.</p>
    </div>
    <div class="intro-section__image">
        <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo">
    </div>
</section>

<hr class="divider">

<?php
require 'db.php';

// Fetch saved products for logged-in user
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT p.ProductId, p.Lender, p.InterestRate, p.MortgageTerm, p.MonthlyRepayment, p.AmountPaidBack
                        FROM SavedQuotes s
                        JOIN Product p ON s.ProductId = p.ProductId
                        WHERE s.UserId = ?");
$stmt->execute([$userId]);
$savedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($savedProducts) > 0): ?>
<section class="saved-products">
    <h2 class="mortgage-options__title">📌 Your Saved Mortgage Products</h2>
    <div class="options-container">
        <?php foreach ($savedProducts as $p): ?>
        <div class="card card--mortgage">
            <h4 class="card__title"><?= htmlspecialchars($p['Lender']) ?></h4>
            <p class="card__description">
                Interest Rate: <?= $p['InterestRate'] ?>%<br>
                Term: <?= $p['MortgageTerm'] ?> years<br>
                Monthly: £<?= number_format($p['MonthlyRepayment'], 2) ?><br>
                Total Repayable: £<?= number_format($p['AmountPaidBack'], 2) ?>
            </p>
            <a href="delete_saved_quote.php?product_id=<?= $p['ProductId'] ?>" 
               class="btn btn--danger" 
               onclick="return confirm('Are you sure you want to delete this quote?')">🗑 Delete</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<hr class="divider">

<section class="mortgage-options">
    <h2 class="mortgage-options__title">Find a mortgage that's right for you</h2>
    <p class="mortgage-options__subtitle">Our range of mortgages covers different borrowing needs.</p>

    <div class="options-container">

        <div class="card card--mortgage">
            <img src="images/Calculator.png" alt="Calculator Logo">
            <h4 class="card__title">Mortgage Calculator</h4>
            <p class="card__description">Find out how much we can lend you, compare our available deals, and see
                what your payments might be.</p>
            <a class="card__link" href="affordability.php">Mortgage Calculator</a>
        </div>

        <div class="card card--repayments">
            <img src="images/Home mortgage.png" alt="Calculator Logo">
            <h4 class="card__title">Repayments Calculator</h4>
            <p class="card__description">Find out how much we can lend you, compare our available deals, and see
                what your payments might be.</p>
            <a class="card__link" href="/affordibility.html">Calculate your Repayment</a>
        </div>
    </div>
</section>

<footer class="footer">
    <p class="footer__text">
        <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
        <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
    </p>
</footer>
</body>
</html>

