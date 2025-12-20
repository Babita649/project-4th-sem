<?php
session_start();
include 'db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Initialize variables
$email_error = '';
$password_error = '';
$email_value = '';

// Clear any previous flash messages on page load
if (!isset($_POST['email'])) {
    unset($_SESSION['login_error']);
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email_value = $email; // to keep in form if login fails

    // Prepare statement
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Bind results
    $stmt->bind_result($user_id, $user_email, $user_password);

    if ($stmt->fetch()) {
        // User exists, verify password
        if (password_verify($password, $user_password)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $user_email;

            // Clear POST data and flash messages
            $_POST = [];
            unset($_SESSION['login_error']);
            $email_error = '';
            $password_error = '';
            $email_value = '';

            // Redirect to dashboard
            header("Location: welcome_user/dashboard.php");
            exit;
        } else {
            $password_error = "Incorrect password";
        }
    } else {
        // No user found
        $email_error = "User not found";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign In</title>
<link rel="stylesheet" href="signin.css">
</head>
<body>
<form id="signinForm" method="POST" action="signin.php">
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <h2>Sign in</h2>

            <!-- Email -->
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email_value); ?>">
            <?php if ($email_error): ?>
                <div class="error" style="color:red; margin-bottom:10px;"><?php echo htmlspecialchars($email_error); ?></div>
            <?php endif; ?>

            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <?php if ($password_error): ?>
                <div class="error" style="color:red; margin-bottom:10px;"><?php echo htmlspecialchars($password_error); ?></div>
            <?php endif; ?>

            <a href="forgot_password.html" class="forget">Forget Password?</a>

            <div class="remember">
                <input type="checkbox" name="remember"> Remember me
            </div>

            <button class="login-btn" id="loginBtn" type="submit">Login</button>

            <div class="bottom">
                Don't have an account? <a href="register.html" id="registerLink">Register Now</a>
            </div>
        </div>
    </div>
    <script src="signin.js"></script>
</form>
</body>
</html>

