<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Default role
    $role = 'user';

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
