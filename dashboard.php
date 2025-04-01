<?php


session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user') {
    header("Location: index.html");
    exit();
}


require 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT  FullName FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
        <a href="settings.php"><button class="btn btn--register">Settings</button></a>
        <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
      </div>
    </header>



    <section class="intro-section">
      <div class="intro-section__content">
        <h2 class="intro-section__title">Welcome back, <?php echo htmlspecialchars($user['FullName']); ?></h2>
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

    <h2 class="intro-section__title">Your saved Quotations: </h2>

    <!-- needs properly styling -->

    <section class="intro-section">
      <div class="intro-section__content">
        <p class="intro-section__text">

        <!--This holds the users saved quotes? -->

        </p>
      </div>
    </section>


    <hr class="divider" />



    <section class="mortgage-options">


    <div class="card card--repayments">
          <img src="images/Home mortgage.png" alt="Calculator Logo" />
          <h4 class="card__title">Affordability</h4>
          <p class="card__description">

          </p>
          <a class="card__link" href="/affordability.php"
            >Affordability</a
          >
        </div>


        <div class="card card--repayments">
          <img src="images/Home mortgage.png" alt="Calculator Logo" />
          <h4 class="card__title">QUOTES</h4>
          <p class="card__description">
          </p>
          <a class="card__link" href="/quotation.php"
            >Quotes</a
          >
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
