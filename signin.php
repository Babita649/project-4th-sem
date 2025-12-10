<?php
session_start();
include 'db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Redirect if already logged in
if (isset($_SESSION['email'])) {
    header("Location: welcome_user/Dashboard.php"); // folder without spaces
    exit;
}

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.html');
    exit;
}

// Get form values
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate fields
if ($email === '' || $password === '') {
    header("Location: signin.html?error=empty_fields");
    exit;
}

try {
    // Fetch user from DB
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header("Location: register.html"); // redirect if user not found
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        header("Location: signin.html?error=invalid_password");
        exit;
    }

    // Login successful → set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];

    // Redirect to dashboard
    header("Location: welcome_user/Dashboard.php");
    exit;

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    header("Location: signin.html?error=server_error");
    exit;
}
?>
