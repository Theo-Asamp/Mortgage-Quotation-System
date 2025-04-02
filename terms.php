<?php
session_start();

require 'headerFooter.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages terms</title>
</head>



<body>

<?php render_navbar(); ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h2 class="intro-section__title"> Rose brokers terms</h2>
            <p class="intro-section__text">our terms - </p>
        </div>
    </section>



    <?php render_footer() ?>



</body>



</html>
