<?php
session_start();

function render_navbar() {
  $navbar = '<header class="navbar">
      <a href="index.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
      <div class="navbar__buttons">';

  if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
    $navbar .= '
        <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="settings.php"><button class="btn btn--register">Profile Settings</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>';
  } elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'broker') {
    $navbar .= '
        <a href="broker-dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="broker-setting.php"><button class="btn btn--register">Profile Settings</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>';
  } else {
    $navbar .= '
        <a href="register.php"><button class="btn btn--register">Register</button></a>
        <a href="login.php"><button class="btn btn--login">Log In</button></a>';
  }

  $navbar .= '</div></header>';
  echo $navbar;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages about</title>
</head>



<body>

<?php render_navbar(); ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h2 class="intro-section__title">About Rose brokers</h2>
            <p class="intro-section__text">About us - </p>
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



</body>



</html>






