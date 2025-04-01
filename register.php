<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['FullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $DOB= $_POST['DOB'];
    $CreditScore = $_POST['CreditScore'];
    $Employmenttype = $_POST['EmploymentType'];
    $AnnualIncome = $_POST['AnnualIncome'];
    $AnnualOutcome = $_POST['AnnualOutcome'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO Users (FullName, Email, Password, DOB, EmploymentType, AnnualIncome, AnnualOutcome, CreditScore) VALUES (?, ?, ?,?,?,?,?,?)");
        $stmt->execute([$fullname, $email, $hashed_password, $DOB, $Employmenttype, $AnnualIncome, $AnnualOutcome , $CreditScore]);
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Brokers Register</title>
</head>
<body>

     <header class="navbar">
     <a href="index.html" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
        <div class="navbar__buttons">
        </div>
    </header>

    <div class="container">
        <div class="register-section">
            <h2>Register</h2>

            <form class="login-form" method="POST">
                <label for="FullName" >Full Name<span style="color: red;">*</span></label>
                <input type="text" id="FullName" name="FullName" required placeholder="John Doe">

                <label for="email" >Email<span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" required placeholder="Test@test.com">

                <label for="password" >Password<span style="color: red;">*</span></label>
                <input type="password" id="password" name="password" required placeholder="*******">


                <label for="DOB">Date of birth <span style="color: red;">*</span></label>
                <input type="DOB" id="DOB" name="DOB" required placeholder="1-1-1970">

                <label for="EmploymentType">Type of employment<span style="color: red;">*</span></label>
                <input type="EmploymentType" id="EmploymentType" name="EmploymentType" required>

            
                <label for="CreditScore">Credit Score<span style="color: red;">*</span></label>
                <input type="CreditScore" id="CreditScore" name="CreditScore" required>

                <label for="AnnualIncome">Annual income<span style="color: red;">*</span></label>
                <input type="AnnualIncome" id="AnnualIncome" name="AnnualIncome" required>

                <label for="AnnualOutcome">Annual outgoings<span style="color: red;">*</span></label>
                <input type="AnnualOutcome" id="AnnualOutcome" name="AnnualOutcome" required>

                <button type="submit" class="btn btn--register">Register</button>
            </form>
            <a href="/register-broker.php">Register as a broker</button></a>


            <a href="login.php"><button class="btn btn--login">Log In</button></a>
        </div>
    </div>

    <footer class="footer">
        <p class="footer__text">
            © Rose Brokers 2025</p>

            <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
            <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
        </p>
    </footer>

</body>
</html>
