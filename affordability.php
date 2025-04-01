<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}
require 'db.php';

$matches = [];
$borrowing_capacity = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income = floatval($_POST['income']);
    $outgoings = floatval($_POST['outgoings']);
    $employment = $_POST['employment_type'];
    $credit_score = intval($_POST['credit_score']);

    $monthly_net = ($income / 12) * 0.75;
    $borrowing_capacity = ($monthly_net - $outgoings) * 4.5;

    $stmt = $conn->prepare("SELECT * FROM Product WHERE MinIncome <= ? AND MaxOutgoings >= ? AND MinCreditScore <= ? AND (EmploymentType = ? OR EmploymentType = 'any')");
    $stmt->execute([$income, $outgoings, $credit_score, $employment]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      .intro-section__content input,
      .intro-section__content select {
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
      <a href="index.html" class="navbar__title-link">
        <h1 class="navbar__title">ROSE BROKERS</h1>
      </a>
      <div class="navbar__buttons">
        <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
      </div>
    </header>

    <section class="intro-section">
      <div class="intro-section__content">
        <form id="mortgageForm" method="POST">
          <label>Annual Income (£):</label><br />
          <input type="number" name="income" required /><br /><br />

          <label>Monthly Outgoings (£):</label><br />
          <input type="number" name="outgoings" required /><br /><br />

          <label>Employment Type:</label><br />
          <select name="employment_type" required>
            <option value="full-time">Full-Time</option>
            <option value="part-time">Part-Time</option>
            <option value="self-employed">Self-Employed</option>
            <option value="any">Other</option>
          </select><br /><br />

          <label>Credit Score:</label><br />
          <input type="number" name="credit_score" min="0" max="999" required /><br /><br />

          <button type="submit" class="btn btn--login">Compare</button>
        </form>

        <div id="results" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <?php if ($borrowing_capacity !== null): ?>
            <h3>Your Estimated Borrowing Capacity: £<?= number_format($borrowing_capacity, 2) ?></h3>
        <?php endif; ?>

        <?php if (!empty($matches)): ?>
            <form method="get" action="compare.php">
              <?php foreach ($matches as $match): ?>
                <div class="card card--mortgage" style="display: flex; align-items: flex-start; gap: 10px;">
                  <input type="checkbox" name="ids[]" value="<?= $match['ProductId'] ?>" onclick="return limitSelection(this)" style="width: 16px; height: 16px; margin-top: 4px;">
                  <div>
                    <strong><?= htmlspecialchars($match['Lender']) ?></strong><br>
                    Rate: <?= $match['InterestRate'] ?>%<br>
                    Term: <?= $match['MortgageTerm'] ?> years<br>
                    Monthly: £<?= number_format($match['MonthlyRepayment'], 2) ?><br>
                    Total: £<?= number_format($match['AmountPaidBack'], 2) ?>
                  </div>
                </div>
              <?php endforeach; ?>
              <button type="submit" class="btn btn--login" style="margin-top: 15px;">Compare Selected</button>
            </form>
            </form>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No matching mortgage products found based on your criteria.</p>
        <?php endif; ?>
        </div>
      </div>
    </section>

    <footer class="footer">
      <p class="footer__text">
        <a href="/about.html">About</a> |
        <a href="/privacy.html">Privacy Policy</a> |
        <a href="/terms.html">Terms of Use</a> |
        <a href="/contact.html">Contact Us</a>
      </p>
    </footer>

    <script>
      function limitSelection(checkbox) {
        const selected = document.querySelectorAll('input[name="ids[]"]:checked');
        if (selected.length > 3) {
          alert("You can only compare up to 3 products.");
          checkbox.checked = false;
          return false;
        }
        return true;
      }
    </script>
  </body>
</html>
