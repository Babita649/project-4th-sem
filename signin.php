<?php
session_start();
include 'db.php';

$email_error = '';
$password_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

<<<<<<< HEAD
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
=======
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
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c

            if ($row['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: welcome_user/Dashboard.php");
            }
            exit();

<<<<<<< HEAD
            // Redirect to dashboard
            header("location:welcome_user/dashboard.html");
            exit;
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
        } else {
            $password_error = "Incorrect password";
        }

    } else {//else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email_error = "User not found";
    }
<<<<<<< HEAD

    // $stmt->close();
=======
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
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
<<<<<<< HEAD

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

=======
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

>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
        <div class="bottom">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
<<<<<<< HEAD

</div>
    <script src="signin.js"></script>
=======
</div>
>>>>>>> c8f70b60a7d0644d49d956c63298ba01b5cbde0c
</form>

</body>
</html>
