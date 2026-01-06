<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Default role
    //$role = 'user';

    if ($password !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Check email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email already registered!");
    }
    $check->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare(
        "INSERT INTO users (fullname, email, password, role) 
         VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $fullname, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        header("Location: signin.php");
        exit();
    } else {
        echo "Registration failed!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
<form id="registerForm" method="POST" action="register.php">
<div class="register_container">
     <div class="left-box"></div>
     <div class="right-box">
    <h2>Create an Account</h2>

    <label for="fullname">Full Name</label>
    <input type="text" id="fullname" name="fullname" placeholder="Enter your name">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
       <span id="err_email" style="color:red;"></span><br>
    <label for="password">Password</label>
    <input type="password" id="regPassword" name="password" placeholder="Create password">

    <label for = "confirmPassword">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password">
     
    <button class="register-btn" id="registerBtn" type="submit">Register</button>



    <div class="bottom">
        Already have an account? <a href="signin.php">Login</a>
    </div>
    </div>
</div>
<script src="register.js">
</script>
</form>
</body>
</html>
