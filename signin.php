<?php
session_start();
include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Redirect if already logged in
if (isset($_SESSION['email'])) {
    header("Location: welcome_user/Dashboard.php");
    exit;
}

// Only allow POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signin.html");
    exit;
}

// Get form values
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Basic empty field check
if ($email === '' || $password === '') {
    header("Location: signin.html?error=empty_fields");
    exit;
}

// Email format validation (same as JS)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: signin.html?error=invalid_email");
    exit;
}

// Password validation — at least 6 chars + 1 capital letter
if (!preg_match('/^(?=.*[A-Z]).{6,}$/', $password)) {
    header("Location: signin.html?error=weak_password");
    exit;
}

try {
    // Fetch user
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // If no user found → redirect to register
    if (!$user) {
        header("Location: register.html?error=not_registered");
        exit;
    }

    // Password check (hashed)
    if (!password_verify($password, $user['password'])) {
        header("Location: signin.html?error=invalid_password");
        exit;
    }

    // Login success → create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];

    // Redirect to dashboard
    header("Location: welcome_user/Dashboard.html");
    exit;

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    header("Location: signin.html?error=server_error");
    exit;
}
?>
