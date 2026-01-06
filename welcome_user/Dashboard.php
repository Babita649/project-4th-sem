<?php
session_start();

/* 🔐 User-only protection */
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../signin.php");
    exit();
}

$username = 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Café Dashboard</title>
<link rel="stylesheet" href="dashboard.css">
</head>

<body>
<div class="main-container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($username) ?></h3>

    <div class="menu">
        <a href="Dashboard.php">🏠 Dashboard</a>
        <a href="select_pc.php">💻 Select PC</a>
        <a href="select_game.php">🎮 Select Games</a>
        <a href="foodanddrinks.php">🍔 Food & Drinks</a>
        <a href="duration.php">⏳ Duration</a>
        <a href="payment.php">💳 Payment</a>
        <a href="notification.php">🔔 Notifications</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main">

<div class="dashboard-header">
    <h2>Dashboard</h2>

    <div class="header-right">
        <div class="notification">
            🔔 <span class="badge">3</span>
        </div>

        <div class="profile-wrapper">
            <div class="profile-box" onclick="toggleProfile()">
                <span><?= htmlspecialchars($username) ?></span>
                <img src="https://img.icons8.com/color/96/user-female-circle.png">
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <a href="profile.php">👤 My Profile</a>
                <a href="settings.php">⚙ Settings</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="icon-grid">

    <div class="icon-box" onclick="location.href='select_pc.php'">
        <img src="../images/pcicon.png">
        <p>Select PC</p>
    </div>

    <div class="icon-box" onclick="location.href='select_game.php'">
        <img src="../images/select games.jpg">
        <p>Select Games</p>
    </div>

    <div class="icon-box" onclick="location.href='foodanddrinks.php'">
        <img src="../images/food.png">
        <p>Food & Drinks</p>
    </div>

    <div class="icon-box" onclick="location.href='duration.php'">
        <img src="https://img.icons8.com/ios/100/clock.png">
        <p>Duration</p>
    </div>

    <div class="icon-box" onclick="location.href='payment.php'">
        <img src="https://img.icons8.com/ios/100/wallet.png">
        <p>Payment</p>
    </div>

    <div class="icon-box" onclick="location.href='notification.php'">
        <p>Notifications</p>
    </div>

</div>
</div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
