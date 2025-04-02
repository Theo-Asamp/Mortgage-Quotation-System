<?php
include 'db.php';
require 'headerFooter.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['FullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $DOB = $_POST['dob_year'] . '-' . $_POST['dob_month'] . '-' . $_POST['dob_day'];
    $CreditScore = $_POST['CreditScore'];
    $Employmenttype = $_POST['EmploymentType'];
    $AnnualIncome = $_POST['AnnualIncome'];
    $AnnualOutcome = $_POST['AnnualOutcome'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO Users (FullName, Email, Password, DOB, EmploymentType, AnnualIncome, AnnualOutcome, CreditScore) VALUES (?, ?, ?,?,?,?,?,?)");
        $stmt->execute([$fullname, $email, $hashed_password, $DOB, $Employmenttype, $AnnualIncome, $AnnualOutcome, $CreditScore]);
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

    
<?php render_navbar() ?>


    <div class="container">
        <div id="register-input-div">
            <h2>Register</h2>

            <form class="login-form" method="POST">

                <div id="name-input-div">
                    <label for="FullName">Full Name<span style="color: red;">*</span></label>
                    <input type="text" id="FullName" name="FullName" required placeholder="John Doe">
                </div>

                <div id="email-input-div">
                    <label for="email">Email<span style="color: red;">*</span></label>
                    <input type="email" id="email" name="email" required placeholder="Test@test.com">
                </div>

                <div id="password-input-div">
                    <label for="password">Password<span style="color: red;">*</span></label>
                    <input type="password" id="password" name="password" required placeholder="*******">
                </div>

                <div id="dob-input-div">
                    <label for="dob_day">Date of Birth:<span style="color: red;">*</span></label>
                    <div class="dob-selects">
                        <select id="dob_day" name="dob_day" required>
                            <option value="">Day</option>
                            <?php for ($d = 1; $d <= 31; $d++): ?>
                                <option value="<?= str_pad($d, 2, '0', STR_PAD_LEFT) ?>">
                                    <?= str_pad($d, 2, '0', STR_PAD_LEFT) ?>
                                </option>
                            <?php endfor; ?>
                        </select>

                        <select id="dob_month" name="dob_month" required>
                            <option value="">Month</option>
                            <?php
                            $months = [
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December'
                            ];
                            foreach ($months as $num => $name): ?>
                                <option value="<?= str_pad($num, 2, '0', STR_PAD_LEFT) ?>">
                                    <?= $name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select id="dob_year" name="dob_year" required>
                            <option value="">Year</option>
                            <?php
                            $currentYear = date('Y');
                            for ($y = $currentYear - 100; $y <= $currentYear; $y++): ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div id="employmentType-input-div">
                    <label for="EmploymentType">Type of employment<span style="color: red;">*</span></label>
                    <select type="EmploymentType" id="EmploymentType" name="EmploymentType" required>
                        <option value="Self-Employed" selected="selected">Self-Employed</option>
                        <option value="Part-Time Employed">Part-time Employed</option>
                        <option value="Full-Time Employed">Full-time Employed</option>
                        <option value="Unemployed">Unemployed</option>
                    </select>
                </div>





                <label for="CreditScore">Credit Score<span style="color: red;">*</span></label>
                <input type="CreditScore" id="CreditScore" name="CreditScore" required>

                <label for="AnnualIncome">Annual income<span style="color: red;">*</span></label>
                <input type="AnnualIncome" id="AnnualIncome" name="AnnualIncome" required>

                <label for="AnnualOutcome">Annual outgoings<span style="color: red;">*</span></label>
                <input type="AnnualOutcome" id="AnnualOutcome" name="AnnualOutcome" required>

                <button type="submit" class="btn btn--register">Register</button>
            </form>
            <a href="login.php"><button class="btn btn--login" style="margin-top: 20px" ;>Log In</button></a>
        </div>
    </div>

<<<<<<< HEAD

    <?php render_footer() ?>
=======
    <footer class="footer" id="footer-register">
        <p class="footer__text">© Rose Brokers 2025</p>
        <a href="/about.php">About</a> |
        <a href="/privacy.php">Privacy Policy</a> |
        <a href="/terms.php">Terms of Use</a> |
        <a href="/contact.php">Contact Us</a>
        </p>
    </footer>
>>>>>>> 6125f49859fce1d21059e123f3c383aa0b1866ca

</body>

</html>