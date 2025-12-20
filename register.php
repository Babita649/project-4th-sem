<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check password match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Email already registered!";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, created_at, updated_at) VALUES (?, ?, ?,?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $hashedPassword, $created_at,$updated_at);
        $stmt->execute();

        // ✅ Redirect to signin page after successful registration
        header("Location: signin.php");
        exit;

    } catch (mysqli_sql_exception $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
