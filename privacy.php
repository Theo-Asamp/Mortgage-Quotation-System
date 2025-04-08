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
    <title>Rose Mortgages privacy</title>
</head>



<body>

<?php render_navbar(); ?>

    <section class="intro-section">
        <div class="intro-section__content">
            <h2 class="intro-section__title"> PRIVACY NOTICE</h2>
            <DIV id = "Role_Privacy"></DIV>
            <h1>Our Role in your Privacy</h1>
            <p>At Rose Brokers, your privacy is our priority. We are committed to protecting your personal information and 
              ensuring it is handled with the utmost care, transparency, and security. Our role is to collect only what we need, 
              use it responsibly, and never share it without your consent—so you can trust us with your data every step of the way.</p>
            
            <div class = "dropdown">
              <h1>How we collect and use your Information</h1></div>
            <div class = "Intro_Section">
              <p class = "Intro_Class">Under data protection law, an organisation
               that decides how the personal information it collects
               and holds is used is called a ‘data controller’.
               We call this use of personal information ‘processing’.
              If you’re a member or customer of Rose Brokers, the data controller of your personal information is Rose Brokers.
            </p></div>
            <div class id ="your_responsbility" >
              <h1>Your Responsibility</h1>
              <p>At Rose Brokers, we believe privacy is a shared responsibility.
                 We’re committed to safeguarding your data, but we also encourage you to stay informed
                  about how your information is used. Have you reviewed your privacy settings and understood your rights? 
                 Knowing your responsibilities helps us protect what matters most—your trust.</p>
            </div>
            <div class = "dropdown1">
              <h1>The information we collect and hold</h1></div>
            <div class = "Dropdown_Section_Information">
              <p class = "Information_Class">The information we might collect and hold about you includes:
<br>Personal information to identify and contact you, such as your name, address, contact details and 
date of birth. We also rely on biometric information, which may be held by Rose Brokers and our trusted 
third parties, like your fingerprint or a biometric map of your face and the selfie you provide.
<br>Information about your work or profession, your nationality, education and social and economic demographic.
<br>Details of the accounts and products you hold and/or previously held with us, and how you use them.
<br>Information about your financial position and history, which may include source of funds and wealth.
<br>Personal information gathered from when you’ve applied for a product
 or service.

            </p></div>
          
    </section>






    <?php render_footer() ?>



</body>



</html>
