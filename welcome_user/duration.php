<?php
session_start();
include '../db.php';

/* -------------------------
   AUTH CHECK
-------------------------- */
if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['id'];
$email   = $_SESSION['email'] ?? 'User';

/* -------------------------
   HANDLE DURATION SAVE
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['duration'])) {

    $duration = intval($_POST['duration']);

    // Remove previous duration
    $del = $conn->prepare("DELETE FROM duration_selection WHERE user_id = ?");
    $del->bind_param("i", $user_id);
    $del->execute();

    // Insert new duration
    $stmt = $conn->prepare("
        INSERT INTO duration_selection (user_id, duration_minutes)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $user_id, $duration);
    $stmt->execute();

    // Go next
    header("Location: FoodAndDrinks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cyber Cafe - Select Duration</title>
<link rel="stylesheet" href="duration.css">
</head>
<body>

<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($email) ?></h3>
    <div class="menu">
        <a href="Dashboard.php">🏠 Dashboard</a>
        <a href="select_pc.php">💻 Select PC</a>
        <a href="select_games.php">🎮 Select Games</a>
        <a href="Duration.php">⏳ Duration</a>
        <a href="FoodAndDrinks.php">🍔 Food & Drinks</a>
        <a href="Payment.php">💳 Payment</a>
        <a href="Notification.php">🔔 Notifications</a>
    </div>
</div>

<div class="container">
    <h2>Select Duration</h2>

    <form method="POST" id="durationForm">
        <div id="durations">
            <button type="button" class="duration-btn" data-duration="30">30 Minutes</button>
            <button type="button" class="duration-btn" data-duration="60">1 Hour</button>
            <button type="button" class="duration-btn" data-duration="90">1.5 Hours</button>
            <button type="button" class="duration-btn" data-duration="120">2 Hours</button>
            <button type="button" class="duration-btn" data-duration="180">3 Hours</button>
        </div>

        <input type="hidden" name="duration" id="durationInput">

        <button type="submit" id="proceed-btn">Proceed</button>
    </form>

    <div class="navigation-buttons">
        <button type="button" class="back-btn"
            onclick="location.href='select_games.php'">⬅ Back</button>

        <button type="button" class="next-btn"
            onclick="document.getElementById('durationForm').submit()">Next ➡</button>
    </div>
</div>

<!-- JS -->
<script>
const buttons = document.querySelectorAll('.duration-btn');
const durationInput = document.getElementById('durationInput');
let selectedDuration = null;

buttons.forEach(button => {
    button.addEventListener('click', () => {

        buttons.forEach(btn => btn.classList.remove('selected'));
        button.classList.add('selected');

        selectedDuration = button.dataset.duration;
        durationInput.value = selectedDuration;
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
