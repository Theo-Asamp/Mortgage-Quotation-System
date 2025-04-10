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
    <title>Rose Mortgages Contact</title>
</head>



<body>

<?php render_navbar(); ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h2 class="intro-section__title">Contact Rose Brokers</h2>
            <p class="intro-section__text">If you have any questions or need support, feel free to get in touch with us using the details below:</p>

            <h1>Phone Number</h1>
            <p class="intro-section__text">0800 220 99 98</p>

            <h1>Email</h1>
            <p class="intro-section__text">support@rosebrokers.com</p>

            <h1>Address</h1>
            <p class="intro-section__text">123 Finance Street<br>
            Sheffield, S1 4QL<br>
            United Kingdom</p>

            <h1>Office Hours</h1>
            <p class="intro-section__text">Monday-Friday: 9:00 AM -  5:00 PM</p> 

            <p class="intro-section__text">We look forward to hearing from you!</p>
        </div>
    </section>



<?php render_footer() ?>



</body>



</html>
