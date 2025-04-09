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
            <h2 class="intro-section__title"> Terms of Use</h2>
            <p class="intro-section__text">By using this website, you agree to the following terms and conditions. Please read them carefully before using the service.</p>

            <h1> Accuracy Of Information</h1>
            <p class="intro-section__text">The mortgage quotes shown on this website are created based on the details you provide, such as your income, deposit, and loan term. 
                These quotes should not be considered as final offers. Actual mortgage offers may vary depending on the lender's terms and additional checks. 
                We do our best to ensure that the information provided is up to date, but we make no guarantees about its accuracy. 
            </p>

            <h1> No Financial Advice</h1>
            <p class="intro-section__text"> The tools on this website and the results are for informational purposes only. We do not provide financial, legal, or investment advice. 
                Users should not rely on any content from this website to make major financial decisions. It is highly recommended that you speak with a qualified mortgage advisor before making a decision.
            </p>

            <h1> Responsibility Of The User</h1>
            <p class="intro-section__text"> You are responsible for making sure all the information you enter on the website is accurate. 
                Submitting false information may result in incorrect quotations and may affect any future applications. You also agree to use the website in a lawful manner.
            </p>

            <h1> Privacy</h1>
            <p class="intro-section__text"> We here at Rose Brokers respect your privacy. Any personal or financial information you provide will be processed according to our Privacy Policy. 
                We will not sell or share your personal information with third parties without your consent, except where required by law.
            </p>

            <h1> Changes To The Terms</h1>
            <p class="intro-section__text"> We may update these Terms of Use from time to time so it is your responsibility to check this page regularly. 
                If you continue to use the website after any updates will mean you agree to the new terms.
            </p>
        </div>
    </section>



    <?php render_footer() ?>



</body>



</html>
