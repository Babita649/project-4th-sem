<?php
session_start();
include 'db.php';

$email_error = '';
$password_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // ✅ Prepared statement (secure)
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $data = $res->fetch_assoc();

        // Verify password
        if (password_verify($password, $data['password'])) {

            session_regenerate_id(true);

            $_SESSION['id'] = $data['id'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['role'] = $data['role'];

            // Role-based redirect
            if ($data['role'] === 'admin') {
                header("Location: admin/dashboard.html");
            } else {
                header("Location: welcome_user/dashboard.php");
            }
            exit();

        } else {
            $password_error = "Incorrect password";
        }

    } else {
        $email_error = "User not found";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<link rel="stylesheet" href="signin.css">
</head>
<body>

<form id="signinForm" method="POST" action="signin.php">
<div class="container">

    <div class="left"></div>

    <div class="right">
        <h2>Sign in</h2>

        <label>Email</label>
        <input type="email" id="email" name="email" required>

        <?php if (!empty($email_error)) echo "<p style='color:red;'>$email_error</p>"; ?>

        <label>Password</label>
        <input type="password" id="password" name="password" required>

        <?php if (!empty($password_error)) echo "<p style='color:red;'>$password_error</p>"; ?>

        <button class="loginBtn" type="submit">Login</button>

        <div class="bottom">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>

</div>
    <script src="signin.js"></script>
</form>

</body>
</html>
