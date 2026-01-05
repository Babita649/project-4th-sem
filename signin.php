<?php
session_start();
include 'db.php';

$email_error = '';
$password_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare(
        "SELECT id, email, password, role FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result =$stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result -> fetch_assoc();
        if (password_verify($password, $row['password'])) {

            session_regenerate_id(true);
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: welcome_user/Dashboard.php");
            }
            exit();

        } else {
            $password_error = "Incorrect password";
        }

    } else {//else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email_error = "User not found";
    }
}
    //$stmt->close();
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
        <input type="email" name="email" required>
        <?php if (!empty($email_error)) echo "<p style='color:red;'>$email_error</p>"; ?>

        <label>Password</label>
        <input type="password" name="password" required>
        <?php if (!empty($password_error)) echo "<p style='color:red;'>$password_error</p>"; ?>

        <button class="login-btn" type="submit">Login</button>

        <div class="bottom">
            Don't have an account? <a href="register.html">Register</a>
        </div>
    </div>
</div>
</form>

</body>
</html>
