<?php
session_start();

if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_type'] === 'user') {
    header("Location: dashboard.php");
    exit();
}

if ($_SESSION['user_type'] !== 'broker') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$brokerId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT FullName, Email, CompanyName FROM Broker WHERE BrokerId = ?");
$stmt->execute([$brokerId]);
$broker = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullname = $_POST['FullName'];
        $email = $_POST['email'];
        $company = $_POST['CompanyName'];

        try {
            $stmt = $conn->prepare("UPDATE Broker SET FullName = ?, Email = ?, CompanyName = ? WHERE BrokerId = ?");
            $stmt->execute([$fullname, $email, $company, $brokerId]);
            $_SESSION['fullname'] = $fullname;
            $success = "Profile updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }

    } elseif ($action === 'update_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT Password FROM Broker WHERE BrokerId = ?");
        $stmt->execute([$brokerId]);
        $broker_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($old_password, $broker_data['Password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                try {
                    $stmt = $conn->prepare("UPDATE Broker SET Password = ? WHERE BrokerId = ?");
                    $stmt->execute([$hashed_password, $brokerId]);
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
        <title>Rose Brokers - Broker Settings</title>
    </head>

    <body>

        <header class="navbar">
                <a href="index.php" class="navbar__title-link"><h1 class="navbar__title">ROSE BROKERS</h1></a>
                    <div class="navbar__buttons">
                        <a href="broker-dashboard.php"><button class="btn btn--register">Dashboard</button></a>
                        <a href="broker-setting.php"><button class="btn btn--register">Profile Settings</button></a>
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
                        <input type="text" id="FullName" name="FullName" class="profile-page__input" value="<?php echo htmlspecialchars($broker['FullName']); ?>" required>

                        <label for="email" class="profile-page__label">Email</label>
                        <input type="email" id="email" name="email" class="profile-page__input" value="<?php echo htmlspecialchars($broker['Email']); ?>" required>

                        <label for="CompanyName" class="profile-page__label">Company Name <span style="font-weight: normal; color: #888;">(optional)</span></label>
                        <input type="text" id="CompanyName" name="CompanyName" class="profile-page__input" value="<?php echo htmlspecialchars($broker['CompanyName']); ?>">

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
