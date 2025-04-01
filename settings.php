<?php
session_start();
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['user', 'broker'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FullName, Email, CreditScore, AnnualIncome, AnnualOutcome FROM Users WHERE UserId = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullname = $_POST['FullName'];
        $email = $_POST['email'];
        $CreditScore = $_POST ['CreditScore'];
        $AnnualIncome = $_POST ['AnnualIncome'];
        $AnnualOutcome = $_POST ['AnnualOutcome'];


        try {
            $stmt = $conn->prepare("UPDATE Users SET FullName = ?, Email = ? , CreditScore = ? , AnnualIncome = ? , AnnualOutcome = ? WHERE UserId = ?");
            $stmt->execute([$fullname, $email, $CreditScore, $AnnualIncome, $AnnualOutcome, $user_id]);
            $_SESSION['fullname'] = $fullname;
            $success = "Profile updated successfully!";
            header("Location: Settings.php");
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }

    } elseif ($action === 'update_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT Password FROM Users WHERE UserId = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($old_password, $user_data['Password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                try {
                    $stmt = $conn->prepare("UPDATE Users SET Password = ? WHERE UserId = ?");
                    $stmt->execute([$hashed_password, $user_id]);
                    $success = "Password updated successfully!";
                } catch (PDOException $e) {
                    $error = "Error updating password: " . $e->getMessage();
                }
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Old password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css" />
    <title>Rose Mortgages - Profile</title>
</head>

<body>

<header class="navbar">
<a href="index.html" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
    <div class="navbar__buttons">
        <a href="dashboard.php"><button class="btn btn--register">Dashboard</button></a>
        <a href="logout.php"><button class="btn btn--login">Log Out</button></a>
    </div>
</header>

<section class="profile-page__container">
    <div class="profile-page__content">

        <h2 class="profile-page__title">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>

        <div class="profile-page__section">
            <h3 class="profile-page__section-title">Profile Settings</h3>

            <?php if (isset($error)) echo "<p class='profile-page__msg profile-page__msg--error'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p class='profile-page__msg profile-page__msg--success'>$success</p>"; ?>

            <form class="profile-page__form" method="POST">
                <input type="hidden" name="action" value="update_profile">

                <label for="FullName" class="profile-page__label">Full Name</label>
                <input type="text" id="FullName" name="FullName" class="profile-page__input" value="<?php echo htmlspecialchars($user['FullName']); ?>">

                <label for="email" class="profile-page__label">Email</label>
                <input type="email" id="email" name="email" class="profile-page__input" value="<?php echo htmlspecialchars($user['Email']); ?>">

                <label for="CreditScore" class="profile-page__label">Credit Score</label>
                <input type="CreditScore" id="CreditScore" name="CreditScore" class="profile-page__input" value="<?php echo htmlspecialchars($user['CreditScore']); ?>">

                <label for="AnnualIncome" class="profile-page__label">Annual Income</label>
                <input type="AnnualIncome" id="AnnualIncome" name="AnnualIncome" class="profile-page__input" value="<?php echo htmlspecialchars($user['AnnualIncome']); ?>">

                <label for="AnnualOutcome" class="profile-page__label">Annual Outcome</label>
                <input type="AnnualOutcome" id="AnnualOutcome" name="AnnualOutcome" class="profile-page__input" value="<?php echo htmlspecialchars($user['AnnualOutcome']); ?>">

                <button type="submit" class="profile-page__btn profile-page__btn--save">Save</button>
            </form>
        </div>

        <div class="profile-page__section">
            <h3 class="profile-page__section-title">Password Settings</h3>

            <form class="profile-page__form" method="POST">
                <input type="hidden" name="action" value="update_password">

                <label for="old_password" class="profile-page__label">Old Password</label>
                <input type="password" id="old_password" name="old_password" class="profile-page__input">

                <label for="new_password" class="profile-page__label">New Password</label>
                <input type="password" id="new_password" name="new_password" class="profile-page__input">

                <label for="confirm_password" class="profile-page__label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="profile-page__input">

                <button type="submit" class="profile-page__btn profile-page__btn--save">Update Password</button>
            </form>
        </div>

        <div class="profile-page__section">
            <h3 class="profile-page__section-title">Saved Products</h3>
            <div class="profile-page__product-list">
                <div class="profile-page__product-item">
                    <p><strong>Sample Product 1</strong></p>
                    <p>Price: $100</p>
                </div>
                <div class="profile-page__product-item">
                    <p><strong>Sample Product 2</strong></p>
                    <p>Price: $150</p>
                </div>
            </div>
        </div>

    </div>
</section>

<footer class="footer">
    <p class="footer__text">
        <a href="/about.html">About</a> | <a href="/privacy.html">Privacy Policy</a> |
        <a href="/terms.html">Terms of Use</a> | <a href="/contact.html">Contact Us</a>
    </p>
</footer>

</body>
</html>
