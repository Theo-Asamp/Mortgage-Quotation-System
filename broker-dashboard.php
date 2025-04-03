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



require 'headerFooter.php';



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
    <?php render_navbar() ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
            <h2 class="intro-section__title">Broker Portal</h2>
            <p>Here you can add a new mortgage product, click the button below to get started</p>
            <a href="add_product.php" style="text-decoration: none;" ><button class="broker_dashbutton">+ Add New Mortgage Product</button></a>
            <p>Here you can edit, delete and view the current products you have made. Just click the button below to get started</p>
            <a href="product_list.php" style="text-decoration: none;" ><button class="broker_dashbutton">Managing List of Product</button></p></a>
        </div>
        <div class="intro-section__image">
            <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo">
        </div>
    </section>

    <?php render_footer() ?>
</html>
