<?php
session_start();
include '../db.php';

// ✅ User authentication
if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}
$user_id = $_SESSION['id'];
$email=$_SESSION['email'] ?? 'user';
$error='';
// ✅ Handle PC selection save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pc_id'])) {
    $pc_id = intval($_POST['pc_id']);

    // Remove previous selection
    $delete = $conn->prepare("DELETE FROM pc_selection WHERE user_id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    // Insert new selection
    $stmt = $conn->prepare("INSERT INTO pc_selection (user_id, pc_id, status, booked_at)
        VALUES (?, ?, 'selected', NOW())
    ");
    $stmt->bind_param("ii", $user_id, $pc_id);
    $stmt->execute();

    // Redirect to SelectGames.php after saving
    header("Location: select_games.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select PC</title>
<link rel="stylesheet" href="select_pc.css">
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

<!-- MAIN -->
<div class="main">

<!-- TOP BAR -->
<div class="top-bar">
    <input type="text" class="search-bar" placeholder="Search for PCs...">
    <div class="profile-box">
        <span><?= htmlspecialchars($email) ?></span>
        <img src="https://img.icons8.com/color/96/user-female-circle.png">
    </div>
</div>

<!-- PC SELECTION -->
<div class="box">
<h2>Pick Computer</h2>

<div class="grid">
    <div class="pc-box" data-pc="1">
        <span class="pc-number">PC 1</span><img src="../images/pcicon.png">
    </div>
    <div class="pc-box" data-pc="2">
        <span class="pc-number">PC 2</span><img src="../images/pcicon.png">
    </div>
    <div class="pc-box booked" data-pc="3">
        <span class="pc-number">PC 3</span><img src="../images/pcicon.png">
    </div>
    <div class="pc-box" data-pc="4">
        <span class="pc-number">PC 4</span><img src="../images/pcicon.png">
    </div>
     <div class="pc-box" data-pc="5">
        <span class="pc-number">PC 5</span><img src="../images/pcicon.png">
    </div>
     <div class="pc-box" data-pc="6">
        <span class="pc-number">PC 6</span><img src="../images/pcicon.png">
    </div>
     <div class="pc-box" data-pc="7">
        <span class="pc-number">PC 7</span><img src="../images/pcicon.png">
    </div>
     <div class="pc-box" data-pc="8">
        <span class="pc-number">PC 8</span><img src="../images/pcicon.png">
    </div>
     <div class="pc-box" data-pc="9">
        <span class="pc-number">PC 9</span><img src="../images/pcicon.png">
    </div>
</div>

<!-- BUTTONS -->
<div class="buttons">
    <!-- Back button -->
    <button class="btn back-btn" onclick="location.href='Dashboard.php'">
        ⬅ Back
    </button>

    <!-- Next button -->
    <button class="btn next-btn" id="nextBtn" onclick="goNext()">
        Next ➜
    </button>
</div>

</div>
</div>
</div>

<!-- JS -->
<script>
let selectedPC = null;
let nextBtn = document.getElementById("nextBtn");

// Next is inactive initially
nextBtn.classList.remove("active");

document.querySelectorAll(".pc-box").forEach(pc => {
    pc.addEventListener("click", function () {
        if (this.classList.contains("booked")) return;

        // Remove previous selection
        document.querySelectorAll(".pc-box")
            .forEach(p => p.classList.remove("selected"));

        // Highlight current PC
        this.classList.add("selected");

        // Save selected PC
        selectedPC = this.dataset.pc;

        // Enable NEXT button
        nextBtn.classList.add("active");
    });
});

function goNext() {
    if (!selectedPC) return;

    // Create form dynamically to POST selected PC
    const form = document.createElement("form");
    form.method = "POST";
    form.action = ""; // submit to same page

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "pc_id";
    input.value = selectedPC;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>

</body>
</html>
