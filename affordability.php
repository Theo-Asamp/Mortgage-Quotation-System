<?php
session_start();

$borrowing_capacity = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income = floatval($_POST['income']);
    $outgoings = floatval($_POST['outgoings']);
    $monthly_net = $income - $outgoings;
    $borrowing_capacity = $monthly_net * 4.5;
}


require 'headerFooter.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Rose Mortgage Calculator</title>
  <link rel="stylesheet" href="/css/global.css" />
  <link rel="icon" href="/src/images/Favicon.jpg" />
</head>
<body>
  <?php render_navbar() ?>

  <section class="intro-section">
    <div class="intro-section__content">
      <h4 class="intro-section__title">Affordability Calculator</h4>
      <h4 class="card__title">What am I eligible for?</h4>
      <p>
        Use our Affordability calculator to get a rough idea of your borrowing capacity in just seconds.
      </p>

      <form id="mortgageForm" method="POST">
        <label class="card__title">Annual Income (£):</label><br />
        <p>This is how much you earn yearly in pounds and pence.</p>
        <input type="number" name="income" required placeholder="£100,000"/><br /><br />

        <label class="card__title">Annual Outgoings (£):</label><br />
        <p>This is, on average, how much you spend yearly in pounds and pence.
        </p>
        <input type="number" name="outgoings" required placeholder="£40,000"/><br /><br />

        <button type="submit" class="btn btn--login">Calculate my borrowing capacity</button>
      </form>

      <div id="results">
        <?php if ($borrowing_capacity !== null): ?>
          <h3>Your Estimated Borrowing Capacity: £<?= number_format($borrowing_capacity, 2) ?></h3>
          <?php endif?>
      </div>
    </div>
  </section>

  <?php render_footer() ?>

</body>
</html>
