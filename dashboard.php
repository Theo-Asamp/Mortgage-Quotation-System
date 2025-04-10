<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
  header("Location: login.php");
  exit();
}

require 'db.php';
require 'headerFooter.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FullName FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quote_id'])) {
  $deleteStmt = $conn->prepare("DELETE FROM SavedQuotes WHERE QuoteId = ? AND UserId = ?");
  $deleteStmt->execute([$_POST['delete_quote_id'], $user_id]);
}

$savedStmt = $conn->prepare("
  SELECT 
    SQ.QuoteId,
    P.Lender, 
    SQ.InterestAnnually,
    SQ.MortgageLength,
    SQ.MonthlyRepayment,
    SQ.AmountPaidBack
  FROM SavedQuotes SQ
  JOIN Product P ON SQ.ProductId = P.ProductId
  WHERE SQ.UserId = ?
");

$savedStmt->execute([$user_id]);
$savedQuotes = $savedStmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rose Mortgage Dashboard</title>
  <link rel="stylesheet" href="/css/global.css" />
  <link rel="icon" href="/src/images/Favicon.jpg" />

</head>

<body>
  <?php render_navbar() ?>

  <section class="intro-section">
    <div class="intro-section__content">
      <?php
      $firstName = explode(' ', $user['FullName'])[0];
      $hour = date('H'); // 24-hour format
      $greeting = ($hour < 12) ? 'Good morning' : 'Good afternoon';
      ?>
      <h2 class="intro-section__title"><?= $greeting ?> <?= htmlspecialchars($firstName) ?></h2>

      Welcome back <?= htmlspecialchars($user['FullName']) ?>, are you ready to explore and find a quote thats right for you? looking to view your saved quotes? you're at the right place.
      </p>
      <p>Scroll down to explore your dashboard </p>
    </div>
    <div class="intro-section__image">
      <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo" />
    </div>
  </section>

  <hr class="divider" />

  <section class="saved-section">
    <h2 class="saved-option__title">Your Saved Quotations:</h2>
    <div class="saved-section__content">
      <?php if (count($savedQuotes) > 0): ?>
        <?php foreach ($savedQuotes as $quote): ?>
          <div class="card card--mortgage">
            <div>
              <strong><?= htmlspecialchars($quote['Lender']) ?></strong><br>
              Interest Rate: <?= rtrim(rtrim(number_format($quote['InterestAnnually'], 2, '.', ''), '0'), '.') ?>%<br>
              <?php
              $years = intdiv($quote['MortgageLength'], 12);
              $months = $quote['MortgageLength'] % 12;
              if ($years > 0 && $months > 0) {
                $termDisplay = "{$years} year" . ($years > 1 ? 's' : '') . " and {$months} month" . ($months > 1 ? 's' : '');
              } elseif ($years > 0) {
                $termDisplay = "{$years} year" . ($years > 1 ? 's' : '');
              } elseif ($months > 0) {
                $termDisplay = "{$months} month" . ($months > 1 ? 's' : '');
              } else {
                $termDisplay = "N/A";
              }
              ?>
              <p>Term of Loans: <?= $termDisplay ?><br></p>
              <p>Monthly Repayment: £<?= number_format($quote['MonthlyRepayment'], 2) ?><br></p>
              <p>Total Repayment: £<?= number_format($quote['AmountPaidBack'], 2) ?><br></p>
              <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this quote?');">
                <input type="hidden" name="delete_quote_id" value="<?= $quote['QuoteId'] ?>">
                <button type="submit" class="btn btn--login">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="saved-section__text">You haven't saved any quotes yet.</p>
      <?php endif; ?>
    </div>
  </section>

  <hr class="divider" />




  <section class="mortgage-options">
    <h2 class="mortgage-options__title">
      Find a mortgage quote that's right for you
    </h2>
    <p class="mortgage-options__subtitle">
      Our range of mortgage quotes covers different demographics, use our affordabiility calculator to find how much you may be elegible to borrow.
    </p>

    <div class="options-container">
      <div class="card card--mortgage">
        <img src="images/Calculator.png" alt="Calculator Logo" />
        <h4 class="card__title">Affordability Calculator</h4>
        <p class="card__description">
          Input some personal details and see what lenders you might be
          eligible for.
        </p>
        <a class="card__link" href="/affordability.php">Affordability Calculator</a>
      </div>


      <div class="card card--repayments">
        <img src="images/Home mortgage.png" alt="Calculator Logo" />
        <h4 class="card__title">Mortgage Quotation</h4>
        <p class="card__description">
          Search and recieve a quote based on your personal details.
        </p>
        <a class="card__link" href="/quotation.php">Check available products</a>
      </div>

    </div>

    </div>
  </section>

  <?php render_footer() ?>

</body>

</html>