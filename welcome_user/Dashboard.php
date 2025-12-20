<?php
session_start();

// Redirect to signin if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit;
}

// Get user info from session
$user_email = $_SESSION['email'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Café Dashboard</title>

<style>
.sidebar h3 {
    padding-left: 20px;
}
.icon-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
    margin-top: 30px;
}
.icon-box img {
    width: 80px;
}
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #1e1e2f; 
    color: #f1f5f9;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: #252540;  
    color: #cbd5e1;
    position: fixed;
    padding-top: 20px;
}

.sidebar a {
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    color: #f5f5f5;
}

.sidebar a:hover {
    background: #4fd1c5;  
    border-radius: 10px;
}

.main {
    margin-left: 260px;
    padding: 20px;
}

.icon-box {
    text-align: center;
    background: #2d2d4d; 
    padding: 20px;
    border-radius: 15px;
    cursor: pointer;
    transition: 0.3s;
}

.icon-box:hover {
    background: #428a83;
}

</style>
</head>

<body>

<div class="sidebar">
    <h3>Welcome, <?php echo htmlspecialchars($user_email); ?></h3>
    <a href="dashboard.php">🏠Dashboard</a>
    <a href="Select PC.php">💻Select PC</a>
    <a href="Select Games.php">🎮Select Games</a>
    <a href="Food and Drinks.php">🍔Food & Drinks</a>
    <a href="Duration.php">⏳Duration</a>
    <a href="Payment.php">💳Payment</a>
    <a href="../logout.php" style="margin-top:20px;color:#f56565;">🚪Logout</a>
</div>

<div class="main">
    <h2>Dashboard</h2>

    <div class="icon-grid">
        <div class="icon-box" onclick="window.location.href='Select PC.php'">
            <img src="../images/pcicon.png" alt="Select PC">
            <p>Select PC</p>
        </div>

        <div class="icon-box" onclick="window.location.href='Select Games.php'">
            <img src="../images/select games.jpg" alt="Select Games">
            <p>Select Games</p>
        </div>

        <div class="icon-box" onclick="saveData('device')">
            <img src="../images/select pc.jpg" alt="Select Device">
            <p>Select Device</p>
        </div>

        <div class="icon-box" onclick="saveData('food')">
            <img src="" alt="Food & Drinks">
            <p>Food & Drinks</p>
        </div>

        <div class="icon-box" onclick="saveData('duration')">
            <img src="https://img.icons8.com/ios/100/clock.png" alt="Duration">
            <p>Duration</p>
        </div>

        <div class="icon-box" onclick="saveData('payment')">
            <img src="https://img.icons8.com/ios/100/wallet.png" alt="Payment">
            <p>Payment</p>
        </div>
    </div>
</div>

<script>
function saveData(type) {
    let value = prompt("Enter " + type + " value:");

    if(value === "" || value === null){
        alert("No value entered");
        return;
    }

    // send to PHP
    fetch("save.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "type=" + encodeURIComponent(type) + "&value=" + encodeURIComponent(value)
    })
    .then(res => res.text())
    .then(data => alert(data))
    .catch(err => alert("Error: " + err));
}
</script>

</body>
</html>
