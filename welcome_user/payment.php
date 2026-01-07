<?php
session_start();
include '../db.php'; // Adjust path to your DB connection
include '../db.php';

// ✅ User authentication check
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../signin.php");
/* -------------------------
   AUTH CHECK
-------------------------- */
if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
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

$email   = $_SESSION['email'] ?? 'User';

/* -------------------------
   FETCH DURATION
-------------------------- */
$durationRow = $conn->query("
    SELECT duration_minutes 
    FROM duration_selection 
    WHERE user_id = $user_id
")->fetch_assoc();

$duration = $durationRow['duration_minutes'] ?? 0;

/* -------------------------
   FETCH FOOD TOTAL
-------------------------- */
$foodRow = $conn->query("
    SELECT SUM(quantity * price) AS food_total 
    FROM food_orders 
    WHERE user_id = $user_id
")->fetch_assoc();

$food_total = $foodRow['food_total'] ?? 0;

/* -------------------------
   PRICE CALCULATION
-------------------------- */
$rate_per_hour = 50; // Rs per hour
$pc_cost = ($duration / 60) * $rate_per_hour;
$grand_total = $pc_cost + $food_total;

/* -------------------------
   HANDLE PAYMENT
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        INSERT INTO payments 
        (user_id, duration_minutes, pc_total, food_total, grand_total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iiiii",
        $user_id,
        $duration,
        $pc_cost,
        $food_total,
        $grand_total
    );
    $stmt->execute();

    // Add notification
    $msg = "✅ Payment successful! Total paid: Rs.$grand_total";
    $notify = $conn->prepare("
        INSERT INTO notifications (user_id, message)
        VALUES (?, ?)
    ");
    $notify->bind_param("is", $user_id, $msg);
    $notify->execute();

    header("Location: Notification.php");
    exit();
}
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
    <h3>Welcome, <?= htmlspecialchars($email) ?></h3>
    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="select_pc.php">💻 Select PC</a>
    <a href="select_games.php">🎮 Select Games</a>
    <a href="duration.php">⏳ Duration</a>
    <a href="FoodAndDrinks.php">🍔 Foods & Drinks</a>
    <a href="payment.php">💳 Payment</a>
    <a href="notification.php">🔔 Notification</a>
    
</div>

<div class="payment-container">
    <h2>Payment</h2>
<h2>Payment Summary</h2>

<div class="summary">
    <p><strong>Duration:</strong> <?= $duration ?> minutes</p>
    <p><strong>PC Rate:</strong> Rs.<?= $rate_per_hour ?>/hr</p>
    <p><strong>PC Cost:</strong> Rs.<?= $pc_cost ?></p>
    <p><strong>Food & Drinks:</strong> Rs.<?= $food_total ?></p>
    <hr>
    <p><strong>Total Amount:</strong> Rs.<?= $grand_total ?></p>
</div>

    <div class="summary">
        <p><strong>PC Selected:</strong> <?= htmlspecialchars($selectedPC) ?></p>
        <p><strong>Game Selected:</strong> <?= htmlspecialchars($selectedGame) ?></p>
        <p><strong>Duration Selected:</strong> <?= htmlspecialchars($duration) ?> Minutes</p>
        <p><strong>Rate:</strong> ₹<?= $rate_per_hour ?>/hr</p>
        <p><strong>Total Amount:</strong> ₹<?= $total_amount ?></p>
    </div>
<form method="POST">
    <h3>Payment Details</h3>

    <form id="paymentForm" method="POST" action="process_payment.php">
        <h3>Payment Details</h3>
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required placeholder="John Doe">
    <label>Full Name</label>
    <input type="text" required placeholder="John Doe">

        <label for="card">Card Number</label>
        <input type="text" name="card" id="card" required placeholder="1234 5678 9012 3456" maxlength="19">
    <label>Card Number</label>
    <input type="text" required placeholder="1234 5678 9012 3456">

        <label for="expiry">Expiry Date</label>
        <input type="month" name="expiry" id="expiry" required>
    <label>Expiry</label>
    <input type="month" required>

        <label for="cvv">CVV</label>
        <input type="text" name="cvv" id="cvv" required placeholder="123" maxlength="4">
    <label>PIN</label>
    <input type="password" maxlength="4" required>

        <input type="hidden" name="amount" value="<?= $total_amount ?>">
        <button type="submit">Pay Now</button>
    </form>
    <button type="submit">Pay Now</button>
</form>

    <div id="successMessage" class="success-message" style="display:none;">
        Payment Successful! Thank you.
    </div>
<button onclick="location.href='FoodAndDrinks.php'" style="margin-top:10px;">
    ⬅ Back
</button>

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
   FETCH DURATION
-------------------------- */
$durationRow = $conn->query("
    SELECT duration_minutes 
    FROM duration_selection 
    WHERE user_id = $user_id
")->fetch_assoc();

$duration = $durationRow['duration_minutes'] ?? 0;

/* -------------------------
   FETCH FOOD TOTAL
-------------------------- */
$foodRow = $conn->query("
    SELECT SUM(quantity * price) AS food_total 
    FROM food_orders 
    WHERE user_id = $user_id
")->fetch_assoc();

$food_total = $foodRow['food_total'] ?? 0;

/* -------------------------
   PRICE CALCULATION
-------------------------- */
$rate_per_hour = 50; // Rs per hour
$pc_cost = ($duration / 60) * $rate_per_hour;
$grand_total = $pc_cost + $food_total;

/* -------------------------
   HANDLE PAYMENT
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("
        INSERT INTO payments 
        (user_id, duration_minutes, pc_total, food_total, grand_total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iiiii",
        $user_id,
        $duration,
        $pc_cost,
        $food_total,
        $grand_total
    );
    $stmt->execute();

    // Add notification
    $msg = "✅ Payment successful! Total paid: Rs.$grand_total";
    $notify = $conn->prepare("
        INSERT INTO notifications (user_id, message)
        VALUES (?, ?)
    ");
    $notify->bind_param("is", $user_id, $msg);
    $notify->execute();

    header("Location: Notification.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cyber Cafe Payment</title>
<link rel="stylesheet" href="payment.css">
</head>
<body>

<div class="sidebar">
    <h3>Welcome, <?= htmlspecialchars($email) ?></h3>
    <a href="Dashboard.php">🏠 Dashboard</a>
    <a href="select_pc.php">💻 Select PC</a>
    <a href="select_games.php">🎮 Select Games</a>
    <a href="Duration.php">⏳ Duration</a>
    <a href="FoodAndDrinks.php">🍔 Foods & Drinks</a>
    <a href="payment.php">💳 Payment</a>
    <a href="Notification.php">🔔 Notification</a>
</div>

<div class="payment-container">
<h2>Payment Summary</h2>

<div class="summary">
    <p><strong>Duration:</strong> <?= $duration ?> minutes</p>
    <p><strong>PC Rate:</strong> Rs.<?= $rate_per_hour ?>/hr</p>
    <p><strong>PC Cost:</strong> Rs.<?= $pc_cost ?></p>
    <p><strong>Food & Drinks:</strong> Rs.<?= $food_total ?></p>
    <hr>
    <p><strong>Total Amount:</strong> Rs.<?= $grand_total ?></p>
</div>

<form method="POST">
    <h3>Payment Details</h3>

    <label>Full Name</label>
    <input type="text" required placeholder="John Doe">

    <label>Card Number</label>
    <input type="text" required placeholder="1234 5678 9012 3456">

    <label>Expiry</label>
    <input type="month" required>

    <label>PIN</label>
    <input type="password" maxlength="4" required>

    <button type="submit">Pay Now</button>
</form>

<button onclick="location.href='FoodAndDrinks.php'" style="margin-top:10px;">
    ⬅ Back
</button>

</div>
</body>
</html>
