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

// Handle PC selection submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_pc'])) {
    $pc_id = intval($_POST['selected_pc']);

    // Check if PC is already booked
    $stmt = $conn->prepare("SELECT is_booked FROM pcs WHERE pc_id = ?");
    $stmt->bind_param("i", $pc_id);
    $stmt->execute();
    $stmt->bind_result($is_booked);
    $stmt->fetch();
    $stmt->close();

    if ($is_booked) {
        $error = "This PC is already booked!";
    } else {
        // Mark PC as booked
        $stmt = $conn->prepare("UPDATE pcs SET is_booked = 1 WHERE pc_id = ?");
        $stmt->bind_param("i", $pc_id);
        $stmt->execute();
        $stmt->close();

        // Insert into user_pc_selection
        $stmt = $conn->prepare("INSERT INTO user_pc_selection (user_id, pc_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $pc_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to next page (e.g., select_games.php)
        header("Location: select_game.php");
        exit();
    }
}

// Fetch PCs from database
$pcs = $conn->query("SELECT * FROM pcs ORDER BY pc_number ASC");
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
        <h3>Welcome, <?= htmlspecialchars($username) ?></h3>
        <div class="menu">
            <a href="Dashboard.php">🏠 Dashboard</a>
            <a href="select_pc.php">💻 Select PC</a>
            <a href="select_games.php">🎮 Select Games</a>
            <a href="food_drinks.php">🍔 Food & Drinks</a>
            <a href="duration.php">⏳ Duration</a>
            <a href="payment.php">💳 Payment</a>
            <a href="notification.php">🔔 Notifications</a>
        </div>
    </div>

<!-- Main Page -->
<div class="main">
    <div class="top-bar">
        <input type="text" class="search-bar" placeholder="Search for PCs...">
        <div class="profile-box">
            <span><?= htmlspecialchars($username) ?></span>
            <img src="https://img.icons8.com/color/96/user-female-circle.png">
        </div>
    </div>

    <div class="box">
        <h2>Pick Computer</h2>
        
        <div class="grid">
             <div class="pc-box" data-pc="1" data-console="PS5" data-gpu="RTX 3060" data-room="VIP">
                    <span class="pc-number">PC 1</span><img src="../images/pcicon.png">
             </div>

            <div class="pc-box" data-pc="2" data-console="Xbox" data-gpu="GTX 1660" data-room="Regular">
                <span class="pc-number">PC 2</span> <img src="../images/pcicon.png"> 
            </div>
            <div class="pc-box" data-pc="3" data-console="None" data-gpu="Integrated"> 
                <span class="pc-number">PC 3</span><img src="../images/pcicon.png">
            </div>
            <div class="pc-box booked" data-pc="4"> <span class="pc-number">PC 4</span>
                <img src="../images/pcicon.png">
            </div>
            <div class="pc-box booked" data-pc="5"> <span class="pc-number">PC 5</span>
                <img src="../images/pcicon.png">
            </div>

            <div class="pc-box" data-pc="6"> <span class="pc-number">PC 6</span>
                <img src="../images/pcicon.png">
            </div>
            <div class="pc-box" data-pc="7"> <span class="pc-number">PC 7</span>
                <img src="../images/pcicon.png">
            </div>
            <div class="pc-box booked" data-pc="8"> <span class="pc-number">PC 8</span>
                <img src="../images/pcicon.png">
            </div>
            <div class="pc-box" data-pc="9"> <span class="pc-number">PC 9</span>
                <img src="../images/pcicon.png">
            </div>
            <input type="hidden" name="selected_pc" id="selected_pc">
        </div>
</div>


        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="POST" id="pcForm">
            <div class="grid">
                <?php while ($pc = $pcs->fetch_assoc()): ?>
                    <div class="pc-box <?= $pc['is_booked'] ? 'booked' : '' ?>" 
                         data-pc-id="<?= $pc['pc_id'] ?>" 
                         data-pc-number="<?= $pc['pc_number'] ?>">
                        <span class="pc-number">PC <?= $pc['pc_number'] ?></span>
                        <p><?= htmlspecialchars($pc['console']) ?> | <?= htmlspecialchars($pc['gpu']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
            <input type="hidden" name="selected_pc" id="selected_pc">

            <div class="buttons">
                <button type="button" class="btn back-btn" onclick="location.href='Dashboard.php'">⬅ Back</button>
                <button type="submit" class="btn next-btn" id="nextBtn"disabled >Next ➜</button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
/**let selectedPC = null;
let nextBtn = document.getElementById("nextBtn");
const hiddenInput = document.getElementById("selected_pc");

document.querySelectorAll(".pc-box").forEach(pc => {
    pc.addEventListener("click", function() {
        if (pc.classList.contains("booked")) return;

        document.querySelectorAll(".pc-box").forEach(p => p.classList.remove("selected"));

        pc.classList.add("selected");
        selectedPC = pc.dataset.pcId;
        hiddenInput.value = selectedPC;
    });
});**/
let selectedPC = null;
let nextBtn = document.getElementById("nextBtn");
const hiddenInput = document.getElementById("selected_pc");

document.querySelectorAll(".pc-box").forEach(pc => {
    pc.addEventListener("click", function() {
        if (pc.classList.contains("booked")) return;

        // Remove previous selection
        document.querySelectorAll(".pc-box").forEach(p => p.classList.remove("selected"));

        // Highlight current selection
        pc.classList.add("selected");

        // Store selected PC
        selectedPC = pc.dataset.pcId;
        hiddenInput.value = selectedPC;

        // ✅ Enable Next button
        nextBtn.disabled = false;      // remove disabled attribute
        nextBtn.classList.add('active'); // optional: for styling
    });
});

</script>

</body>
</html>
