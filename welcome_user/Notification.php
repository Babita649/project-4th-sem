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
   CLEAR ALL NOTIFICATIONS
-------------------------- */
if (isset($_POST['clear'])) {
    $stmt = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

/* -------------------------
   FETCH NOTIFICATIONS
-------------------------- */
$result = $conn->prepare("
    SELECT message, created_at 
    FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$result->bind_param("i", $user_id);
$result->execute();
$notifications = $result->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Notifications - Cyber Cafe</title>
<link rel="stylesheet" href="notification.css">
</head>
<body>

<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($email) ?></h3>
    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="select_pc.php">💻 Select PC</a>
    <a href="select_games.php">🎮 Select Games</a>
    <a href="FoodAndDrinks.php">🍔 Foods & Drinks</a>
    <a href="Duration.php">⏳ Duration</a>
    <a href="Payment.php">💳 Payment</a>
    <a href="Notification.php">🔔 Notification</a>
</div>

<div class="container">
<h2>Notifications</h2>

<div class="notifications-list">

<?php if ($notifications->num_rows === 0): ?>
    <p style="text-align:center;color:#888;">No notifications.</p>
<?php else: ?>
    <?php while ($row = $notifications->fetch_assoc()): ?>
        <div class="notification">
            <span><?= htmlspecialchars($row['message']) ?></span>
            <span class="time">
                <?= date("h:i A", strtotime($row['created_at'])) ?>
            </span>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

</div>

<form method="POST" style="text-align:center;margin-top:20px;">
    <button type="submit" name="clear">Clear All</button>
</form>

</div>
</body>
</html>
