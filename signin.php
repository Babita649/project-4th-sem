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
    $sql = "select * from users where email='$email'";
    $res = $conn->query($sql);
    //print_r($res);
 
if($res->num_rows == 1){
    $data = $res->fetch_assoc();
    $user_password = $data['password'];
        $user_id = $data['id'];
        $fullname = $data['fullname'];
        $user_email = $data['email'];

        // User exists, verify password
        if (password_verify($password, $user_password)) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $user_email;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['role'] = $data['role'];
             if ($data['role'] === 'admin') {
                header("Location: admin/dashboard.html");
            } else {
                header("Location: welcome_user/Dashboard.php");
            }
            exit();

            // Clear POST data and flash messages
            $_POST = [];
            unset($_SESSION['login_error']);
            $email_error = '';
            $password_error = '';
            $email_value = '';

            // Redirect to dashboard
            header("location:welcome_user/dashboard.html");
            exit;
        } else {
            $password_error = "Incorrect password";
        }
    } else {
        // No user found
        $email_error = "User not found";
    }

    // $stmt->close();
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

        <button class="login-btn" type="submit">Login</button>

        <div class="bottom">
            Don't have an account? <a href="register.">Register</a>
        </div>
    </div>

</div>
    <script src="signin.js"></script>
</form>
</body>
</html>

