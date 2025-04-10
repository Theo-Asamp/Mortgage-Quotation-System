<?php

function render_navbar()
{
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



function render_footer()
{
  $footer = '
    <footer class="footer">
        <p class="footer__company">© Rose Brokers 2025</p>
        <p class="footer__text">
            <a href="/about.php">About</a> |
            <a href="/privacy.php">Privacy Policy</a> |
            <a href="/terms.php">Terms of Use</a> |
            <a href="/contact.php">Contact Us</a>
        </p>
    </footer> 
    
    <style>
        .footer {
            background-color: #01A7F0;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }

        .footer__company {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer__text a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }

        .footer__text a:hover {
            text-decoration: underline;
        }
    </style>';

  echo $footer;
}
