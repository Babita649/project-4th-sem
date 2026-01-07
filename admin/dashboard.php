<?php
session_start();
include "db.php";

/* 🔐 Admin Login Check */
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

/* 📊 Dashboard Counts */
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users1")->fetch_assoc()['total'];
$totalPCs   = $conn->query("SELECT COUNT(*) AS total FROM pcs")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="main-container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>Welcome, Admin</h3>

    <ul class="menu">
        <li class="active">🏠 Dashboard</li>
        <li><a href="pcselected.php">💻 PC Selected</a></li>
        <li>🎮 Games</li>
        <li>🍔 Food Menus</li>
        <li>⏳ Duration</li>
        <li>💳 Payment</li>
        <li>🔔 Notification</li>
    </ul>

    <div class="admin-footer">
        <div class="circle">A</div>
        <p>Admin</p>
    </div>
</div>

<!-- CONTENT -->
<div class="content">
    <h1>Dashboard</h1>

    <div class="cards">
        <div class="card">
            <div class="icon">👤</div>
            <div>
                <p>Total Users</p>
                <h2><?= $totalUsers ?></h2>
            </div>
        </div>

        <div class="card">
            <div class="icon">🖥️</div>
            <div>
                <p>Total PCs</p>
                <h2><?= $totalPCs ?></h2>
            </div>
        </div>
    </div>

    <!-- BUTTONS -->
    <div class="actions">
        <button onclick="openUser()">Add User</button>
        <button onclick="openBalance()">Add Balance</button>
    </div>
</div>
</div>

<!-- ADD USER -->
<div class="modal" id="userModal">
<form class="modal-content" method="POST" action="save_user.php">
    <h2>Add User</h2>
    <input name="name" placeholder="Full Name" required>
    <input name="phone" placeholder="Phone" required>
    <input name="pc" type="number" placeholder="PC No" required>
    <input name="duration" placeholder="Duration" required>
    <button type="submit">Save</button>
    <button type="button" onclick="closeAll()">Close</button>
</form>
</div>

<!-- ADD BALANCE -->
<div class="modal" id="balanceModal">
<form class="modal-content" method="POST" action="add_balance.php">
    <h2>Add Balance</h2>
    <input name="phone" placeholder="User Phone" required>
    <input name="amount" type="number" placeholder="Amount" required>
    <button type="submit">Add</button>
    <button type="button" onclick="closeAll()">Close</button>
</form>
</div>

<script>
function openUser(){ document.getElementById("userModal").style.display="flex";}
function openBalance(){ document.getElementById("balanceModal").style.display="flex";}
function closeAll(){
    document.getElementById("userModal").style.display="none";
    document.getElementById("balanceModal").style.display="none";
}
</script>

</body>
</html>
