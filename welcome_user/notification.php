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
<div class="main-container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($email) ?></h3>
    <div class="menu">
        <a href="Dashboard.php">🏠 Dashboard</a>
        <a href="select_pc.php">💻 Select PC</a>
        <a href="select_games.php">🎮 Select Games</a>
        <a href="FoodAndDrinks.php">🍔 Food & Drinks</a>
        <a href="Duration.php">⏳ Duration</a>
        <a href="Payment.php">💳 Payment</a>
        <a href="Notification.php">🔔 Notifications</a>
    </div>
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

        <button onclick="clearNotifications()">Clear All</button>
    </div>

    <script>
        // Sample notifications
let notifications = [
    { message: "Your session will expire in 10 minutes.", time: "10:20 AM" },
    { message: "New offer: Buy 1 hour, get 30 min free!", time: "9:30 AM" },
    { message: "Cafe Wi-Fi is currently stable.", time: "8:45 AM" }
];

// Get notifications container
const notificationsList = document.getElementById("notificationsList");

// Function to display notifications
function displayNotifications() {
    notificationsList.innerHTML = "";
    if(notifications.length === 0){
        notificationsList.innerHTML = "<p style='text-align:center;color:#888;'>No notifications.</p>";
        return;
    }
    notifications.forEach((notif, index) => {
        const notifDiv = document.createElement("div");
        notifDiv.classList.add("notification");
        notifDiv.innerHTML = `
            <span>${notif.message}</span>
            <span class="time">${notif.time}</span>
        `;
        notificationsList.appendChild(notifDiv);
    });
}

// Clear notifications
function clearNotifications() {
    notifications = [];
    displayNotifications();
}

// Initial display
displayNotifications();

    </script>
</body>
</html>
