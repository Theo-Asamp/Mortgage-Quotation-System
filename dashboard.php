
<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FullName FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quote_id'])) {
    $deleteStmt = $conn->prepare("DELETE FROM SavedQuotes WHERE QuoteId = ? AND UserId = ?");
    $deleteStmt->execute([$_POST['delete_quote_id'], $user_id]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rose Mortgage Dashboard</title>
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
    .saved-section__content {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .card--mortgage {
      width: 300px;
    }
  </style>
</head>
<body>
  <header class="navbar">
      <a href="index.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
      <div class="navbar__buttons">
      <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="settings.php"><button class="btn btn--register">Profile Settings</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
      </div>
  </header>

  <section class="intro-section">
    <div class="intro-section__content">
      <h2 class="intro-section__title">Welcome back, <?= htmlspecialchars($user['FullName']) ?></h2>
      <p class="intro-section__text">
        Whether you're a first-time buyer or looking for a better deal, we can
        help you find a mortgage that's right for you.
      </p>
    </div>
    <div class="intro-section__image">
      <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo" />
    </div>
  </section>

  <hr class="divider" />

  <section class="saved-section">
    <h2 class="saved-option__title">Your Saved Quotations:</h2>
    <div class="saved-section__content">
      <?php
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

      <?php if (count($savedQuotes) > 0): ?>
        <?php foreach ($savedQuotes as $quote): ?>
          <div class="card card--mortgage">
            <div>
              <strong><?= htmlspecialchars($quote['Lender']) ?></strong><br>
              Interest Rate: <?= $quote['InterestAnnually'] * 100 ?>%<br>
              Term: <?= $quote['MortgageLength'] ?> years<br>
              Monthly Repayment: £<?= number_format($quote['MonthlyRepayment'], 2) ?><br>
              Total Paid Back: £<?= number_format($quote['AmountPaidBack'], 2) ?><br>
              <form method="POST" action="dashboard.php" onsubmit="return confirm('Are you sure you want to delete this quote?');">
                <input type="hidden" name="delete_quote_id" value="<?= $quote['QuoteId'] ?>">
                <button type="submit" class="btn btn--login">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="saved-section__text">You haven’t saved any quotes yet.</p>
      <?php endif; ?>
    </div>
  </section>

  <hr class="divider" />

  <section class="mortgage-options">
    <h2 class="mortgage-options_title">Mortgage options</h2>
    <div class="options-container">
      <div class="card card--mortgage">
        <img src="images/Home mortgage.png" alt="Calculator Logo" />
        <h4 class="card__title">Affordability Calculator</h4>
        <a class="card__link" href="/affordability.php">Calculate how much you can borrow</a>
      </div>

      <div class="card card--repayments">
        <img src="images/Home mortgage.png" alt="Calculator Logo" />
        <h4 class="card__title">Mortgage Quotation</h4>
        <a class="card__link" href="/quotation.php">Check available products</a>
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
