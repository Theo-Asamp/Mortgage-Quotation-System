<?php





session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}



require 'db.php';



$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT  AnnualIncome, AnnualOutcome, CreditScore, EmploymentType FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$matches = [];
$borrowing_capacity = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Annualincome = floatval($_POST['AnnualIncome']);
    $AnnualOutcome = floatval($_POST['AnnualOutcome']);



    $monthly_net = ($Annualincome - $Annualincome) ;
    $borrowing_capacity = ($monthly_net) * 4.5;




    
    $stmt = $conn->prepare("SELECT * FROM Product WHERE MinIncome <= ? AND MaxOutgoings >= ? AND MinCreditScore <= ? AND (EmploymentType = ? OR EmploymentType = 'any')");
    $stmt->execute([$Annualincome, $Annualincome]);
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
      <a href="/dashboard.php" class="navbar__title-link">
        <h1 class="navbar__title">ROSE BROKERS</h1>
      </a>
      <div class="navbar__buttons">
        <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
      </div>
    </header>

    <section class="intro-section">
      <div class="intro-section__content">
        <h4 class="intro-section__title">Morgage Quotation</h4>
        <form id="mortgageForm" method="POST">
          <div>

          <label class="card__title">Annual Income (£):</label><br />
          <input type="AnnualIncome" id="AnnualIncome" name="AnnualIncome" class="profile-page__input" value="<?php echo htmlspecialchars($user['AnnualIncome']); ?>">

          <label class="card__title">Annual Outgoings (£):</label><br />
          <input type="AnnualOutcome" id="AnnualOutcome" name="AnnualOutcome" class="profile-page__input" value="<?php echo htmlspecialchars($user['AnnualOutcome']); ?>">

          <label class="card__title">Property value</label><br />
          <input type="number" name="outgoings" required placeholder="£40,000"/><br /><br />

          <label class="card__title">deposit:</label><br />
          <input type="number" name="outgoings" required placeholder="£40,000"/><br /><br />

          <label class="card__title">Term of loan:</label><br />
          <input type="number" name="outgoings" required placeholder="£40,000"/><br /><br />

          <button type="submit" class="btn btn--login">Calculate</button>

          </div>


        </form>

        <div>


        




        </div>

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

      income = gethtl
    </script>
  </body>
</html>
