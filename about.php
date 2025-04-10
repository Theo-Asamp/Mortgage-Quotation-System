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
    <title>Rose Mortgages about</title>
</head>



<body>

<?php render_navbar(); ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h2 class="intro-section__title">About Rose Brokers</h2>
            <p class="intro-section__text"> Rose Brokers is a UK-based comparison service that started by helping customers find the best car insurance quotes. 
                With a growing customer base and a passion for making things easier, we're now expanding our services to include mortgage quotations.
                We understand that searching for the right mortgage can feel overwhelming, so we've created a simple and user-friendly Mortgage Quote System. 
                It's designed to give you quick, reliable estimates based on your personal details, helping you make more informed choices.
                At Rose Brokers, we aim to make financial decisions easier.
            </p>

            <h2 class="intro-section__title">Our Values </h2>

            <h1>Transparency</h1>
            <p>We provide clear and honest information so users can understand their options without confusion or hidden details.</p>
            
            <h1>Reliability</h1>
            <p>Our tools are built to deliver accurate estimates that you can trust.</p>

            <h1>Customer Focus</h1>
            <p>We always put the needs of our users first, designing everything to be simple and helpful.</p>

            <h1>Innovation</h1>
            <p>We're constantly looking for new and better ways to improve our services and make financial decisions easier.</p>
            
        </div>
    </section>



    <?php render_footer(); ?>



</body>



</html>






