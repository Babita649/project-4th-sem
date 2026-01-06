<?php
session_start();
include '../db.php'; // adjust path to your db connection

// ✅ User authentication check
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'] ?? $_SESSION['email'] ?? 'User';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['duration'])) {
    $duration = intval($_POST['duration']); // selected duration in minutes

    // Save duration in session
    $_SESSION['duration'] = $duration;

    // Optional: insert into DB if you have a table to track user session
    /*
    $stmt = $conn->prepare("UPDATE user_sessions SET duration = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $duration, $user_id);
    $stmt->execute();
    $stmt->close();
    */

    // Redirect to next page (Food & Drinks or Payment)
    header("Location: payment.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cyber Cafe - Select Duration</title>
<link rel="stylesheet" href="duration.css">
<style>
.duration-btn.selected { border: 2px solid #c57c7c; background:#ffe0e0; }
button:disabled { background:#ccc; cursor:not-allowed; }
</style>
</head>
<body>
<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($username) ?></h3>
    <div class="menu">
        <a href="Dashboard.php">🏠 Dashboard</a>
        <a href="select_pc.php">💻 Select PC</a>
        <a href="select_game.php">🎮 Select Games</a>
        <a href="duration.php">⏳ Duration</a>
        <a href="food_drinks.php">🍔 Food & Drinks</a>
        <a href="payment.php">💳 Payment</a>
        <a href="notification.php">🔔 Notifications</a>
    </div>
</div>

<div class="container">
    <h2>Select Duration</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="duration.php" method="POST" id="durationForm">
        <div id="durations">
            <button type="button" class="duration-btn" data-duration="30">30 Minutes</button>
            <button type="button" class="duration-btn" data-duration="60">1 Hour</button>
            <button type="button" class="duration-btn" data-duration="90">1.5 Hours</button>
            <button type="button" class="duration-btn" data-duration="120">2 Hours</button>
        </div>
        <input type="hidden" name="duration" id="durationInput">
        <button type="submit" id="proceed-btn" disabled>Proceed ➜</button>
    </form>

    <div class="navigation-buttons">
        <button type="button" class="back-btn" onclick="location.href='select_game.php'">⬅ Back</button>
    </div>
</div>

<script>
const buttons = document.querySelectorAll('.duration-btn');
const durationInput = document.getElementById('durationInput');
const proceedBtn = document.getElementById('proceed-btn');
let selectedDuration = null;

buttons.forEach(button => {
    button.addEventListener('click', () => {
        buttons.forEach(btn => btn.classList.remove('selected'));
        button.classList.add('selected');
        selectedDuration = button.getAttribute('data-duration');
        durationInput.value = selectedDuration;
        proceedBtn.disabled = false; // Enable Proceed button
    });
});

document.getElementById('durationForm').addEventListener('submit', (e) => {
    if (!selectedDuration) {
        e.preventDefault();
        alert('Please select a duration first.');
    }
});
</script>
</body>
</html>
