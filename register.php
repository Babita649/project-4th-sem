<?php
include 'db.php';
//   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?,?)");
        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);
        $stmt->execute();

        echo "Registration Successful!";
    } catch (mysqli_sql_exception $e) {
        echo "Database error: " . $e->getMessage();
    }
}

?>
