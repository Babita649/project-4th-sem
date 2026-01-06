<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cybercafe";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = $_POST['duration'] ?? '';

    if ($duration) {
        $stmt = $conn->prepare("INSERT INTO sessions (duration) VALUES (?)");
        $stmt->bind_param("i", $duration);
        if ($stmt->execute()) {
            $message = "Duration saved successfully!";
        } else {
            $message = "Error saving duration.";
        }
        $stmt->close();
    } else {
        $message = "Please select a duration.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Cafe - Select Duration</title>
    <link rel="stylesheet" href="duration.css">
</head>
<body>
  <body>
    <div class="sidebar">
        <h3>Welcome, User</h3>
        <div class="menu">
            <a href="Dashboard.php">🏠 Dashboard</a>
            <a href="select_pc.php">💻 Select PC</a>
            <a href="select_games.php">🎮 Select Games</a>
            <a href="FoodandDrinks.php">🍔 Food & Drinks</a>
            <a href="duration.php">⏳ Duration</a>
            <a href="Payment.php">💳 Payment</a>
            <a href="notification.php">🔔 Notifications</a>
        </div>
    </div>
    <div class="container">
        <h2>Select Duration</h2>
        <form action="duration.php" method="POST" id="durationForm">
            <div id="durations">
                <button type="button" class="duration-btn" data-duration="30" onclick="goToPayment(1)">30 Minutes</button>
                <button type="button" class="duration-btn" data-duration="60" onclick="goToPayment(2)">1 Hour</button>
                <button type="button" class="duration-btn" data-duration="90" onclick="goToPayment(3)">1.5 Hours</button>
                <button type="button" class="duration-btn" data-duration="120" onclick="goToPayment(4)">2 Hours</button>
            </div>
            <input type="hidden" name="duration" id="durationInput">
            <button type="submit" id="proceed-btn">Proceed</button>
        </form>
    <div class="navigation-buttons">
       <button type="button" class="back-btn" onclick="location.href='Select Games.html'">⬅ Back</button>
       <button type="button" class="next-btn" onclick="location.href='Food and Drinks.html'">Next ➡</button>
    </div>
</div>
    <script>
        const buttons = document.querySelectorAll('.duration-btn');
        const durationInput = document.getElementById('durationInput');
        let selectedDuration = null;

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('selected'));
                button.classList.add('selected');
                selectedDuration = button.getAttribute('data-duration');
                durationInput.value = selectedDuration; // set hidden input
            });
        });

        document.getElementById('durationForm').addEventListener('submit', (e) => {
            if (!selectedDuration) {
                e.preventDefault();
                alert('Please select a duration first.');
            }
        });
        function goToPayment(hours) {
            // Redirect to payment page with selected duration
            window.location.href = `Payment.html?duration=${hours}`;
        }
    </script>
</body>
</html>

