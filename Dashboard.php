<?php
session_start();

// If user is not logged in → send to signin
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .box {
            background: #f5f5f5;
            padding: 20px; 
            width: 400px; 
            border-radius: 10px;
        }
        a { text-decoration: none; color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="box">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>! 🎉</h2>

    <p>You are successfully logged in.</p>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>
