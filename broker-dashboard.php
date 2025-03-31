<?php
session_start();

// Kick out users who try to access this page
if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_type'] === 'user') {
    header("Location: dashboard.php");
    exit();
}

if ($_SESSION['user_type'] !== 'broker') {
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
    <title>Rose Brokers Dashboard</title>
</head>

<body>
    <header class="navbar">
    <a href="index.html" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
            <a href="broker-setting.php"><button class="btn btn--register">Profile</button></a>
            <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
        </div>
    </header>

    <section class="intro-section">
        <div class="intro-section__content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
            <h2 class="intro-section__title">Broker Portal – Rose Mortgages</h2>
            <p><a href="add_product.php" style="font-size:16px; color:blue;">➕ Add New Mortgage Product</a></p>
            <p><a href="product_list.php" style="font-size:16px; color:blue;">➕ Managing List of Product</a></p>
        
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
            <h2 class="intro-section__title">Broker Portal – Rose Mortgages</h2>
        </div>
        <div class="intro-section__image">
            <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo">
        </div>
    </section>

    </section>

    <footer class="footer">
        <p class="footer__text">
            <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
            <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
        </p>
    </footer>
</body>
</html>
