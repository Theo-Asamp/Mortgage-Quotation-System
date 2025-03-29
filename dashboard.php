<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!--Add stuff here-->

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
        <h1 class="navbar__title">ROSE BROKERS</h1>
        <ul>
            <li><class="dropbox">
        </ul>
        <div class="navbar__buttons">
            <a href="logout.php"><button class="btn btn--register">Logout</button></a>
        </div>
    </header>




    <section class="intro-section">
        <div class="intro-section__content">
            <h1>Welcome, <?php echo $_SESSION['fullname']; ?>!</h1>
            <h2 class="intro-section__title">My profile</h2>
            <p class="intro-section__text">settings/defaults-</p>
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
