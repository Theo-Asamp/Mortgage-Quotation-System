<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
  header("Location: login.php");
  exit();
}

require 'db.php';
require 'headerFooter.php';

$user_id = $_SESSION['user_id'];
$formSubmitted = false;

$checkSaved = $conn->prepare("SELECT COUNT(*) FROM SavedQuotes WHERE UserId = ?");
$checkSaved->execute([$user_id]);
$savedCount = $checkSaved->fetchColumn();
$tooManyQuotes = ($savedCount >= 3);

$stmt = $conn->prepare("SELECT AnnualIncome, AnnualOutcome, CreditScore, EmploymentType, DOB FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$dob = new DateTime($user['DOB']);
$today = new DateTime();
$userAge = $today->diff($dob)->y;

$matches = [];
$calcResults = [];
$loanAmount = 0;
$loanTerm = 0;

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' && !$tooManyQuotes &&
  isset($_POST['property_value'], $_POST['deposit'], $_POST['loan_term'])
) {

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
    $monthlyRate = ($rate / 100) / 12;

    if ($loanAmount <= 0 || $loanTerm <= 0) continue;

    $monthlyPayment = ($monthlyRate > 0) ?
      ($loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $totalMonths)) / (pow(1 + $monthlyRate, $totalMonths) - 1)) : ($loanAmount / $totalMonths);

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
</head>

<body>

  <?php render_navbar() ?>

  <section class="intro-section">
    <div class="intro-section__content">
      <h4 class="intro-section__title">Mortgage Quotation</h4>
      <p><strong>Your Age:</strong> <?= $userAge ?> years</p>
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
                <div class="card card--mortgage" style="display: flex; align-items: flex-start; gap: 10px; margin: 30px">
                  <input type="checkbox" name="ids[]" value="<?= $product['ProductId'] ?>" onclick="return limitSelection(this)" style="width: 16px; height: 16px; margin-top: 4px;">
                  <div>
                    <strong><?= htmlspecialchars($product['Lender']) ?></strong><br>
                    Minimum Age: <?= isset($product['MinAge']) ? $product['MinAge'] . ' years' : 'N/A' ?><br>
                    Interest Rate: <?= rtrim(rtrim(number_format($product['InterestRate'], 2, '.', ''), '0'), '.') ?>%<br>
                    Yearly Term: <?= $loanTerm ?> years<br>
                    Monthly Payment: £<?= number_format($calcResults[$product['ProductId']]['monthly'], 2) ?><br>
                    Total Repayment: £<?= number_format($calcResults[$product['ProductId']]['total'], 2) ?>
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


  <?php render_footer() ?>

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