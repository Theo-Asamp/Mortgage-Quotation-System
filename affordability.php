<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'user';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rose Mortgage Calculator</title>
    <link rel="stylesheet" href="/css/global.css" />
    <link rel="icon" href="/src/images/Favicon.jpg">
    <script src="/js/script.js"></script>
</head>

<body>
    <header class="navbar">
        <a href="index.html" class="navbar__title-link">
            <h1 class="navbar__title">ROSE BROKERS</h1>
        </a>
        <div class="navbar__buttons">
      <?php if (!$isLoggedIn): ?>
        <a href="register.php"><button class="btn btn--register">Register</button></a>
        <a href="login.php"><button class="btn btn--login">Log In</button></a>
      <?php else: ?>
        <a href="settings.php"><button class="btn btn--register">Profile</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
      <?php endif; ?>
        </div>
    </header>

    <section class="intro-section">
        <div class="intro-section__content">
            <form id="mortgageForm" method="POST">
                <h4 class="card__title">How Much Can I Borrow?</h4>
                <p>
                    Use our mortgage calculator to get a rough idea of what you could borrow -
                    in just minutes. To fill it in, you'll need to know:
                </p>

                <p class="card__title">Your main income details</p>
                <p class="bullet-point">A rough idea of the property value</p>
                <p class="bullet-point">Your deposit or loan amount</p>

                <hr />

                <h4 class="card__title">Your details</h4>

                <h3 class="card__title">Your base income before tax</h3>
                <input type="text" id="income" name="income" class="input-field" placeholder="£">

                <h3 class="card__title">Your bonus</h3>
                <p class="description">A rough total of all the bonuses you were paid in the last year.</p>
                <input type="text" id="bonus" name="bonus" class="input-field" placeholder="£">

                <h3 class="card__title">Your overtime and commission</h3>
                <p class="description">A rough total of all overtime and commission you were paid in the last year.</p>
                <input type="text" id="overtime" name="overtime" class="input-field" placeholder="£">

                <h3 class="card__title">Your outgoings</h3>
                <p class="description">Your yearly basic outgoings</p>
                <input type="text" id="outcome" name="outcome" class="input-field" placeholder="£">

                <hr />

                <h3 class="card__title">Estimated property value</h3>
                <p class="description">Value of the property</p>
                <input type="text" id="property" name="property" class="input-field" placeholder="£">

                <h3 class="card__title">I want to borrow</h3>
                <p class="description">Enter how much you would like to borrow</p>
                <input type="text" id="borrow" name="borrow" class="input-field" placeholder="£">

                <br>

                <h3 class="card__title">Pay back mortgage over</h3>
                <div class="duration-container">
                    <div>
                        <label for="years" class="duration-label">Years</label>
                        <select name="years" id="years" class="duration-select">
                            <option value="">Years</option>
                            <option value="40">40 years</option>
                            <option value="35">35 years</option>
                            <option value="30">30 years</option>
                            <option value="25">25 years</option>
                            <option value="20">20 years</option>
                            <option value="15">15 years</option>
                            <option value="10">10 years</option>
                            <option value="5">5 years</option>
                            <option value="1">1 year</option>
                        </select>
                    </div>

                    <div>
                        <label for="months" class="duration-label">Months</label>
                        <select name="months" id="months" class="duration-select">
                            <option value="0" selected="selected">0 months</option>
                            <option value="6">6 months</option>
                            <option value="12">12 months</option>
                        </select>
                    </div>
                </div>

                <button type="button" id="calculateBtn" class="btn btn--login">Submit</button>
            </form>

            <div id="results"></div>
                <?php if ($isLoggedIn): ?>
                    <button type="button" id="saveQuoteBtn" class="btn btn--register" style="display: none;">Save Quote</button>
                <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <p class="footer__text">
            <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
            <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
        </p>
    </footer>
</body>

</html>
