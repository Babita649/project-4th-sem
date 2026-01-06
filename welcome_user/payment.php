<?php
session_start();
include '../db.php'; // Adjust path to your DB connection

// ✅ User authentication check
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'] ?? $_SESSION['email'] ?? 'User';

// Get selected PC, game, and duration from session
$selectedPC = $_SESSION['selected_pc'] ?? 'Not Selected';
$selectedGame = $_SESSION['selected_game']['name'] ?? 'Not Selected';
$duration = $_SESSION['duration'] ?? 60; // in minutes

// Define rate per hour (₹)
$rate_per_hour = 50;

// Calculate total amount
$total_amount = ($duration / 60) * $rate_per_hour;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Cafe Payment</title>
<link rel="stylesheet" href="payment.css">
<style>
.success-message { color: green; font-weight: bold; margin-top: 15px; }
</style>
</head>
<body>

<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($username) ?></h3>
    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="select_pc.php">💻 Select PC</a>
    <a href="select_game.php">🎮 Select Games</a>
    <a href="duration.php">⏳ Duration</a>
    <a href="food_drinks.php">🍔 Foods & Drinks</a>
    <a href="payment.php">💳 Payment</a>
    <a href="notification.php">🔔 Notification</a>
</div>

<div class="payment-container">
    <h2>Payment</h2>

    <div class="summary">
        <p><strong>PC Selected:</strong> <?= htmlspecialchars($selectedPC) ?></p>
        <p><strong>Game Selected:</strong> <?= htmlspecialchars($selectedGame) ?></p>
        <p><strong>Duration Selected:</strong> <?= htmlspecialchars($duration) ?> Minutes</p>
        <p><strong>Rate:</strong> ₹<?= $rate_per_hour ?>/hr</p>
        <p><strong>Total Amount:</strong> ₹<?= $total_amount ?></p>
    </div>

    <form id="paymentForm" method="POST" action="process_payment.php">
        <h3>Payment Details</h3>
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required placeholder="John Doe">

        <label for="card">Card Number</label>
        <input type="text" name="card" id="card" required placeholder="1234 5678 9012 3456" maxlength="19">

        <label for="expiry">Expiry Date</label>
        <input type="month" name="expiry" id="expiry" required>

        <label for="cvv">CVV</label>
        <input type="text" name="cvv" id="cvv" required placeholder="123" maxlength="4">

        <input type="hidden" name="amount" value="<?= $total_amount ?>">
        <button type="submit">Pay Now</button>
    </form>

    <div id="successMessage" class="success-message" style="display:none;">
        Payment Successful! Thank you.
    </div>

    <button onclick="goBack()" style="margin-top:10px;">Back</button>
</div>

<script>
// Simple JS for Back button
function goBack() {
    window.location.href = 'duration.php';
}

// Optionally handle form submission with JS
document.getElementById('paymentForm').addEventListener('submit', function(e){
    e.preventDefault();
    // Here you can do AJAX call to process_payment.php
    document.getElementById('successMessage').style.display = 'block';
});
</script>

</body>
</html>
