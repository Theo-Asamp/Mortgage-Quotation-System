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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages</title>
  </head>

  <body>
  <?php render_navbar(); ?>
    <section class="intro-section">
      <div class="intro-section__content">
        <h2 class="intro-section__title">Mortgages from Rose Brokers</h2>
        <p class="intro-section__text">
          Whether you're a first-time buyer or looking for a better deal, we can
          help you find a mortgage that's right for you.
        </p>
        <p class="intro-section__text">
          If you already have a mortgage with us, log in to your account, find
          out how to switch deals or get help here.
        </p>
      </div>
      <div class="intro-section__image">
        <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo" />
      </div>
    </section>

    <hr class="divider" />

    <section class="mortgage-options">
      <h2 class="mortgage-options__title">
        Find a mortgage that's right for you
      </h2>
      <p class="mortgage-options__subtitle">
        Our range of mortgages covers different borrowing needs.
      </p>

      <div class="options-container">
        <div class="card card--mortgage">
          <img src="images/Calculator.png" alt="Calculator Logo" />
          <h4 class="card__title">Affordability Calculator</h4>
          <p class="card__description">
            Input some personal details and see what lenders you might be
            eligible for.
          </p>
          <a class="card__link" href="/affordability.php"
            >Affordability Calculator</a
          >
        </div>
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
