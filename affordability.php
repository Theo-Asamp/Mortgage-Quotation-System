<?php
session_start();

$borrowing_capacity = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income = floatval($_POST['income']);
    $outgoings = floatval($_POST['outgoings']);
    $monthly_net = $income - $outgoings;
    $borrowing_capacity = $monthly_net * 4.5;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rose Mortgage Calculator</title>
  <link rel="stylesheet" href="/css/global.css" />
  <link rel="icon" href="/src/images/Favicon.jpg" />
  <style>
    .intro-section__content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
    .intro-section__content input {
      width: 300px;
      margin-top: 5px;
    }
    #results {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #results .card--mortgage {
      margin: 10px;
    }
  </style>
</head>
<body>


  <header class="navbar">
    <a href="index.php" class="navbar__title-link">
      <h1 class="navbar__title">ROSE BROKERS</h1>
    </a>
    <div class="navbar__buttons">
      <?php if (isset($_SESSION['user_id'])): ?>

        <a href="/dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="/logout.php"><button class="btn btn--login">Log Out</button></a>
      <?php else: ?>

        <a href="/login.php"><button class="btn btn--register">Sign In</button></a>
        <a href="/register.php"><button class="btn btn--login">Register</button></a>
      <?php endif; ?>
    </div>
  </header>

  <section class="intro-section">
    <div class="intro-section__content">
      <h4 class="intro-section__title">Affordability Calculator</h4>
      <h4 class="card__title">What am I eligible for?</h4>
      <p>
        Use our Affordability calculator to get a rough idea of your borrowing capacity in just seconds.
      </p>

      <form id="mortgageForm" method="POST">
        <label class="card__title">Annual Income (£):</label><br />
        <input type="number" name="income" required placeholder="£100,000"/><br /><br />

        <label class="card__title">Annual Outgoings (£):</label><br />
        <input type="number" name="outgoings" required placeholder="£40,000"/><br /><br />

        <button type="submit" class="btn btn--login">Calculate</button>
      </form>

      <div id="results">
        <?php if ($borrowing_capacity !== null): ?>
          <h3>Your Estimated Borrowing Capacity: £<?= number_format($borrowing_capacity, 2) ?></h3>
        <?php endif; ?>
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
