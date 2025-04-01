
<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$formSubmitted = false;

// Check how many quotes user already has saved
$checkSaved = $conn->prepare("SELECT COUNT(*) FROM SavedQuotes WHERE UserId = ?");
$checkSaved->execute([$user_id]);
$savedCount = $checkSaved->fetchColumn();
$tooManyQuotes = ($savedCount >= 3);

// Fetch user info including DOB
$stmt = $conn->prepare("SELECT AnnualIncome, AnnualOutcome, CreditScore, EmploymentType, DOB FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Calculate user age
$dob = new DateTime($user['DOB']);
$today = new DateTime();
$userAge = $today->diff($dob)->y;

$matches = [];
$calcResults = [];
$loanAmount = 0;
$loanTerm = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$tooManyQuotes &&
    isset($_POST['property_value'], $_POST['deposit'], $_POST['loan_term'])) {

    $formSubmitted = true;

    $propertyValue = floatval($_POST['property_value']);
    $deposit = floatval($_POST['deposit']);
    $loanTerm = intval($_POST['loan_term']);
    $loanAmount = $propertyValue - $deposit;
    $totalMonths = $loanTerm * 12;

    $netIncome = $user['AnnualIncome'] - $user['AnnualOutcome'];
    $borrowingCapacity = $netIncome * 4.5;
    $affordableMonthly = $netIncome / 12;

    $stmt = $conn->prepare("SELECT * FROM Product WHERE MinIncome <= ? AND MinCreditScore <= ? AND (EmploymentType = ? OR EmploymentType = 'any')");
    $stmt->execute([$user['AnnualIncome'], $user['CreditScore'], $user['EmploymentType']]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        if (isset($product['MinAge']) && $userAge < intval($product['MinAge'])) {
            continue;
        }

        $rate = floatval($product['InterestRate']);
        $monthlyRate = $rate / 12;

        if ($loanAmount <= 0 || $loanTerm <= 0) continue;

        $monthlyPayment = ($monthlyRate > 0) ?
            ($loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $totalMonths)) / (pow(1 + $monthlyRate, $totalMonths) - 1)) :
            ($loanAmount / $totalMonths);

        $totalPayment = $monthlyPayment * $totalMonths;

        if ($monthlyPayment > $affordableMonthly || $loanAmount > $borrowingCapacity) continue;

        $calcResults[$product['ProductId']] = [
            'monthly' => round($monthlyPayment, 2),
            'total' => round($totalPayment, 2)
        ];
        $matches[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rose Mortgage Calculator</title>
  <link rel="stylesheet" href="css/global.css" />
  <link rel="icon" href="/src/images/Favicon.jpg" />
  <style>
    .form-grid {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 10px 20px;
      align-items: center;
      max-width: 500px;
      margin: 0 auto;
    }
    .form-grid input,
    .form-grid select {
      width: 100%;
    }
    .warning {
      color: red;
      text-align: center;
      font-weight: bold;
      padding: 10px;
    }
    .quote-results {
      margin-top: 30px;
      text-align: center;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <a href="/dashboard.php" class="navbar__title-link">
      <h1 class="navbar__title">ROSE BROKERS</h1>
    </a>
    <div class="navbar__buttons">
    <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
      <a href="settings.php"><button class="btn btn--register">Settings</button></a>
      <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
    </div>
  </header>

  <section class="intro-section">
    <div class="intro-section__content">
      <h4 class="intro-section__title">Mortgage Quotation</h4>
      <?php if ($tooManyQuotes): ?>
        <p class="warning">⚠️ You have already saved 3 mortgage quotes. Please delete one on your dashboard to request more quotes.</p>
      <?php else: ?>
        <form id="mortgageForm" method="POST" class="form-grid">
          <label for="income">Annual Income (£):</label>
          <input type="text" readonly class="profile-page__input" id="income" value="<?= htmlspecialchars($user['AnnualIncome']) ?>">

          <label for="outgoings">Annual Outgoings (£):</label>
          <input type="text" readonly class="profile-page__input" id="outgoings" value="<?= htmlspecialchars($user['AnnualOutcome']) ?>">

          <label for="property">Property Value (£):</label>
          <input type="number" name="property_value" class="profile-page__input" id="property" required placeholder="£200,000" />

          <label for="deposit">Deposit (£):</label>
          <input type="number" name="deposit" class="profile-page__input" id="deposit" required placeholder="£50,000" />

          <label for="loan_term">Term of loan:</label>
          <select name="loan_term" class="profile-page__input" id="loan_term" required>
            <?php for ($i = 1; $i <= 15; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?> Year<?= $i > 1 ? 's' : '' ?></option>
            <?php endfor; ?>
          </select>

          <div></div>
          <button type="submit" class="btn btn--login">Calculate</button>
        </form>
      <?php endif; ?>

      <div class="quote-results">
        <?php if (!empty($matches)): ?>
          <form method="GET" action="compare.php">
            <input type="hidden" name="loan_amount" value="<?= $loanAmount ?>">
            <input type="hidden" name="loan_term" value="<?= $loanTerm ?>">
            <h3>Available Products</h3>
            <p>Select up to 3 products to compare:</p>
            <div id="results">
              <?php foreach ($matches as $product): ?>
                <div class="card card--mortgage" style="display: flex; align-items: flex-start; gap: 10px;">
                  <input type="checkbox" name="ids[]" value="<?= $product['ProductId'] ?>" onclick="return limitSelection(this)" style="width: 16px; height: 16px; margin-top: 4px;">
                  <div>
                    <strong><?= htmlspecialchars($product['Lender']) ?></strong><br>
                    Rate: <?= $product['InterestRate'] * 100 ?>%<br>
                    Term: <?= $loanTerm ?> years<br>
                    Monthly: £<?= number_format($calcResults[$product['ProductId']]['monthly'], 2) ?><br>
                    Total: £<?= number_format($calcResults[$product['ProductId']]['total'], 2) ?>
                  </div>
                </div>
              <?php endforeach; ?>
              <button type="submit" class="btn btn--login" style="margin-top: 15px;">Compare Selected</button>
            </div>
          </form>
        <?php elseif ($formSubmitted && empty($matches)): ?>
          <p class="warning">⚠️ No valid mortgage products found. Try again with a different income, deposit, or property value.</p>
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
