<?php
include 'db.php';

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Broker WHERE Email = ?");
    $stmt->execute([$email]);
    $broker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($broker && password_verify($password, $broker['Password'])) {
        $_SESSION['user_type'] = 'broker';
        $_SESSION['user_id'] = $broker['BrokerId'];
        $_SESSION['email'] = $broker['Email'];
        $_SESSION['fullname'] = $broker['FullName'];
        header("Location: broker-dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_type'] = 'user';
        $_SESSION['user_id'] = $user['UserId'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['fullname'] = $user['FullName'];
        header("Location: dashboard.php");
        exit();
    }

    $error = "Invalid email or password.";
}


require 'headerFooter.php';


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Brokers Login</title>
</head>

<body>

    <?php render_navbar() ?>


    <div class="container">
        <div class="login-section">
            <h2>Welcome!</h2>
            <p>Enter your email and password to continue...</p>

            <?php if (isset($error)) {
                echo "<p style='color: red;'>$error</p>";
            } ?>

            <form class="login-form" method="POST">
                <label for="email">Email<span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password<span style="color: red;">*</span></label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn btn--login">Sign In</button>
            </form>

            <div class="or-text">Or</div>
            <a href="register.php"><button class="btn btn--register">Register</button></a>
        </div>

        <div class="logo-section">
            <img src="images/homelg.png" alt="Bank Logo">
        </div>
    </div>

    <?php render_footer() ?>

</body>

</html>