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
    <title>Duration Submitted</title>
    <link rel="stylesheet" href="duration.css">
</head>
<body>
    <div class="sidebar">
        <h3>Welcome, User</h3>
        <a href="Dashboard.html">Dashboard</a>
        <a href="Select PC.html">Select PC</a>
        <a href="Select Games.html">Select Games</a>
        <a href="Duration.html">Duration</a>
        <a href="FoodandDrinks.html">Foods & Drinks</a>
        <a href="Payment.html">Payment</a>
        <a href="Notification.html">Notification</a>
    </div>

    <div class="container">
        <h2><?php echo $message ?? ''; ?></h2>
        <a href="Payment.html"><button id="proceed-btn">Go to Payment</button></a>
    </div>
</body>
</html>
