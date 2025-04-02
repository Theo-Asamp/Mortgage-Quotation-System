<?php
session_start();

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
        <a href="index.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
            <div class="navbar__buttons">
                <a href="broker-dashboard.php"><button class="btn btn--register">Dashboard</button></a>
                <a href="broker-setting.php"><button class="btn btn--register">Profile Settings</button></a>
                <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
            </div>
    </header>

    <section class="intro-section">
        <div class="intro-section__content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
            <h2 class="intro-section__title">Broker Portal</h2>
            <a href="add_product.php" style="text-decoration: none;" ><button class="broker_dashbutton">+ Add New Mortgage Product</button></a>
            <a href="product_list.php" style="text-decoration: none;" ><button class="broker_dashbutton">Managing List of Product</button></p></a>
        </div>
        <div class="intro-section__image">
            <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo">
        </div>
    </section>

    <footer class="footer">
      <p class="footer__text">© Rose Brokers 2025</p>
        <a href="/about.php">About</a> |
        <a href="/privacy.php">Privacy Policy</a> |
        <a href="/terms.php">Terms of Use</a> |
        <a href="/contact.php">Contact Us</a>
      </p>
    </footer>
</html>
