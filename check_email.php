<?php
include 'db.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    echo ($stmt->num_rows > 0) ? "exists" : "available";

    $stmt->close();
}?>