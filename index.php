<?php
session_start();

include 'headerFooter.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages</title>
  </head>

  <body>
  <?php render_navbar() ?>
    <section class="intro-section">
      <div class="intro-section__content">
        <h2 class="intro-section__title">Affordability Calculator from Rose Brokers</h2>
        <p class="intro-section__text">
          Whether you're a first-time buyer or looking for a better deal, we can
          help you find a mortgage quote that's right for you.
        </p>
        <p class="intro-section__text">
          If you already have a mortgage quote with us, log in to your account. If you want to discover how much you may be elegible to borrow, please use our affordability calculator below.
        </p>
      </div>
      <div class="intro-section__image">
        <img src="images/LogoPicBlue.png" alt="Rose Brokers Logo" />
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
          <a class="card__link" href="/affordability.php"
            >Affordability Calculator</a
          >
        </div>
      </div>
    </section>

    <?php render_Footer() ?>
  </body>
</html>
