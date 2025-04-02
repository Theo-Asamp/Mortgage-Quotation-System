<?php
include 'db.php';
require 'headerfooter.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['FullName'];
    $email = $_POST['email'];
    $company = $_POST['company'] ?? null;
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO Broker (FullName, Email, CompanyName, Password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fullname, $email, $company, $hashed_password]);
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
    <title>Rose Brokers Register as Broker</title>
</head>
<body>


<?php render_navbar() ?>

    <div class="container">
        <div class="register-section">
            <h2>Register as Broker</h2>

            <form class="login-form" method="POST">
                <label for="FullName">Full Name<span style="color: red;">*</span></label>
                <input type="text" id="FullName" name="FullName" required>

                <label for="email">Email<span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" required>

                <label for="company">Company Name <span style="font-weight: normal; color: #888;">(optional)</span></label>
                <input type="text" id="company" name="company">


                <label for="password">Password<span style="color: red;">*</span></label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn btn--register">Register</button>
            </form>
            <div class="or-text">Or</div>
            <a href="login.php"><button class="btn btn--login">Log In</button></a>
        </div>
    </div>

    <?php render_footer() ?>

</body>
</html>
