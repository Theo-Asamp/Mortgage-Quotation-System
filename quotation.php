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
$savedIdsStmt = $conn->prepare("SELECT ProductId FROM SavedQuotes WHERE UserId = ?");
$savedIdsStmt->execute([$user_id]);
$savedProductIds = $savedIdsStmt->fetchAll(PDO::FETCH_COLUMN, 0);

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
  $_SESSION['form_data'] = [
    'property_value' => $_POST['property_value'],
    'deposit' => $_POST['deposit'],
    'loan_term' => $_POST['loan_term'],
    'loan_term_months' => $_POST['loan_term_months']
  ];

  $propertyValue = floatval($_POST['property_value']);
  $deposit = floatval($_POST['deposit']);
  $loanTermYears = intval($_POST['loan_term']);
  $loanTerm = $loanTermYears;
  $loanTermMonths = isset($_POST['loan_term_months']) ? intval($_POST['loan_term_months']) : 0;
  $loanAmount = $propertyValue - $deposit;
  $totalMonths = ($loanTermYears * 12) + $loanTermMonths;
$termDisplay = '';
if ($formSubmitted) {
  $years = intdiv($totalMonths, 12);
  $months = $totalMonths % 12;

  if ($years > 0 && $months > 0) {
    $termDisplay = "Term of Loans: {$years} year" . ($years > 1 ? 's' : '') . " and {$months} month" . ($months > 1 ? 's' : '');
  } elseif ($years > 0) {
    $termDisplay = "Term of Loans: {$years} year" . ($years > 1 ? 's' : '');
  } elseif ($months > 0) {
    $termDisplay = "Term of Loans: {$months} month" . ($months > 1 ? 's' : '');
  }
}

  $netIncome = $user['AnnualIncome'] - $user['AnnualOutcome'];
  $borrowingCapacity = $netIncome * 4.5;
  $affordableMonthly = $netIncome / 12;

  $stmt = $conn->prepare("SELECT * FROM Product WHERE MinIncome <= ? AND MinCreditScore <= ? AND (EmploymentType = ? OR EmploymentType = 'any')");
  $stmt->execute([$user['AnnualIncome'], $user['CreditScore'], $user['EmploymentType']]);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($products as $product) {
    if ($totalMonths > ($product['MortgageTerm'] * 12)) {
        continue;
    }
    if (isset($product['MinAge']) && $userAge < intval($product['MinAge'])) {
      continue;
    }

    $rate = floatval($product['InterestRate']);
    $monthlyRate = ($rate / 100) / 12;

    if ($loanAmount <= 0 || $totalMonths <= 0) continue;

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
    <div class="intro-section__content" id="quotation-div">
      <h4 class="intro-section__title">Mortgage Quotation</h4>
      <?php if ($tooManyQuotes): ?>
        <p class="warning">⚠️ You have already saved 3 mortgage quotes. Please delete one on your dashboard to request more quotes.</p>
      <?php else: ?>
      <form id="mortgageForm" method="POST" class="form-grid">
        <label for="property">Property Value (£):</label>
        <input type="number" name="property_value" class="profile-page__input" id="property"
          required placeholder="£"
          value="<?php echo isset($_POST['property_value']) ? htmlspecialchars($_POST['property_value']) : ''; ?>" />

        <label for="deposit">Deposit (£):</label>
        <input type="number" name="deposit" class="profile-page__input" id="deposit" required placeholder="£" value="<?php echo isset($_POST['deposit']) ? htmlspecialchars($_POST['deposit']) : ''; ?>" />
        <label for="loan_term">Term of Loan (Years):</label>
        <select name="loan_term" class="profile-page__input" id="loan_term" required>
        <?php for ($i = 0; $i <= 40; $i++): ?>
        <option value="<?= $i ?>" <?= (isset($_POST['loan_term']) && $_POST['loan_term'] == $i) ? 'selected' : '' ?>>
        <?= $i ?> Year<?= $i !== 1 ? 's' : '' ?>
        </option>
        <?php endfor; ?>
        </select>
        <label for="loan_term_months">Term of Loan (Months):</label>
        <select name="loan_term_months" class="profile-page__input" id="loan_term_months" required>
        <?php for ($i = 0; $i <= 12; $i++): ?>
        <option value="<?= $i ?>" <?= (isset($_POST['loan_term_months']) && $_POST['loan_term_months'] == $i) ? 'selected' : '' ?>>
        <?= $i ?> Month<?= $i !== 1 ? 's' : '' ?>
        </option>
        <?php endfor; ?>
        </select>
      </form>
      <div id="btn-calculate">
        <button type="submit" class="btn btn--login" form="mortgageForm">Submit</button>
      </div>


      <?php endif; ?>

      <div class="quote-results">
        <?php if (!empty($matches)): ?>
          <form method="GET" action="compare.php">
            <input type="hidden" name="loan_amount" value="<?= $loanAmount ?>">
            <input type="hidden" name="loan_term" value="<?= $loanTerm ?>">
            <input type="hidden" name="loan_term_months" value="<?= $loanTermMonths ?>">
            <?php foreach ($matches as $product): ?>
              <input type="hidden" name="monthly[<?= $product['ProductId'] ?>]" value="<?= $calcResults[$product['ProductId']]['monthly'] ?>">
              <input type="hidden" name="total[<?= $product['ProductId'] ?>]" value="<?= $calcResults[$product['ProductId']]['total'] ?>">
            <?php endforeach; ?>
            <input type="hidden" name="loan_term_months" value="<?= $loanTermMonths ?>">
            <?php foreach ($matches as $product): ?>
              <input type="hidden" name="monthly[<?= $product['ProductId'] ?>]" value="<?= round($calcResults[$product['ProductId']]['monthly'], 2) ?>">
              <input type="hidden" name="total[<?= $product['ProductId'] ?>]" value="<?= round($calcResults[$product['ProductId']]['total'], 2) ?>">
            <?php endforeach; ?>
            <h3>Available Products</h3>
            <p>Select up to 3 products to compare:</p>
            <div id="results">
              <?php foreach ($matches as $product): ?>
                <div class="card card--mortgage" style="display: flex; align-items: flex-start; gap: 10px; margin: 30px">
                <?php $alreadySaved = in_array($product['ProductId'], $savedProductIds); ?>
                <input type="checkbox"
                      name="ids[]"
                      value="<?= $product['ProductId'] ?>"
                      <?= $alreadySaved ? 'disabled' : 'onclick="return limitSelection(this)"' ?>
                      style="width: 16px; height: 16px; margin-top: 4px;">
                <?php if ($alreadySaved): ?>
                  <span style="color: red; font-size: 0.9em;">(Already Saved)</span>
                <?php endif; ?>

                  <div>
                    <strong><?= htmlspecialchars($product['Lender']) ?></strong><br>
                    Minimum Age: <?= isset($product['MinAge']) ? $product['MinAge'] . ' years' : 'N/A' ?><br>
                    Interest Rate: <?= rtrim(rtrim(number_format($product['InterestRate'], 2, '.', ''), '0'), '.') ?>%<br>
                    <?= $termDisplay ?><br>
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