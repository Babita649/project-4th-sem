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

// Handle game selection submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_game'])) {
    $selectedGame = $_POST['selected_game'];

    // Optionally, store the selected game in session or database
    $_SESSION['selected_game'] = $selectedGame;

    // Redirect to next page (Duration or Food & Drinks)
    header("Location: duration.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Select Games</title>
<link rel="stylesheet" href="selectgames.css">
<style>
.selected { border: 3px solid #c57c7c; }
button:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
</head>
<body>
<div class="main-container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>Welcome, <?= htmlspecialchars($username) ?></h3>
        <div class="menu">
            <a href="Dashboard.php">🏠 Dashboard</a>
            <a href="select_pc.php">💻 Select PC</a>
            <a href="select_game.php">🎮 Select Games</a>
            <a href="food_drinks.php">🍔 Food & Drinks</a>
            <a href="duration.php">⏳ Duration</a>
            <a href="payment.php">💳 Payment</a>
            <a href="notification.php">🔔 Notifications</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <form method="POST" id="gameForm">
            <input type="text" placeholder="Search for games." class="search-bar" />

            <!-- Featured Section -->
            <div class="featured-section">
                <div class="featured-img game-box" 
                     data-name="Featured Game 1" 
                     data-tags="Action,Adventure" 
                     data-img="../images/game1.jpg">
                    <img src="../images/game1.jpg" alt="Featured Game 1" />
                </div>

                <div class="library-box">
                    <h3>In Library</h3>

                    <div class="game-entry game-box" data-name="GHOST OF YOTEI" data-tags="Physics-based,3D,Adventure" data-img="../images/game2.jpg">
                        <img src="../images/game2.jpg" />
                        <div>
                            <strong>GHOST OF YOTEI</strong><br />
                            <div class="tags">
                                <span>Physics-based</span>
                                <span>3D</span>
                                <span>Adventure</span>
                            </div>
                        </div>
                    </div>

                    <div class="game-entry game-box" data-name="GHOST OF TSUSHIMA" data-tags="Action,Adventure" data-img="../images/game3.jpg">
                        <img src="../images/game3.jpg" />
                        <div>
                            <strong>GHOST OF TSUSHIMA</strong><br />
                            <div class="tags">
                                <span>Action</span>
                                <span>Adventure</span>
                            </div>
                        </div>
                    </div>

                    <div class="game-entry game-box" data-name="FORZA HORIZON 5" data-tags="Open-world,Racing" data-img="../images/game4.jpg">
                        <img src="../images/game4.jpg" />
                        <div>
                            <strong>FORZA HORIZON 5</strong><br />
                            <div class="tags">
                                <span>Open-world</span>
                                <span>Racing</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommend Section -->
            <div class="recommend-section">
                <h3>You Might Like this</h3>
                <div class="recommend-row">
                    <img class="game-box" src="../images/game5.jpg" data-name="Game 5" data-tags="Adventure" data-img="../images/game5.jpg"/>
                    <img class="game-box" src="../images/game6.jpg" data-name="Game 6" data-tags="Racing" data-img="../images/game6.jpg"/>
                    <img class="game-box" src="../images/game7.jpg" data-name="Game 7" data-tags="Action" data-img="../images/game7.jpg"/>
                    <img class="game-box" src="../images/game8.jpg" data-name="Game 8" data-tags="Puzzle" data-img="../images/game8.jpg"/>
                </div>
            </div>

            <!-- Hidden input to store selection -->
            <input type="hidden" name="selected_game" id="selected_game">

            <!-- Navigation Buttons -->
            <div class="nav-buttons" style="margin-top:30px; display:flex; justify-content:flex-end; gap:15px; padding-right:20px;">
                <button type="button" id="btnBack" style="padding:12px 20px; background:#c57c7c; color:white; border:none; border-radius:10px; font-weight:bold; cursor:pointer;">⬅ Back</button>
                <button type="submit" id="btnNext" style="padding:12px 20px; background:#c57c7c; color:white; border:none; border-radius:10px; font-weight:bold; cursor:pointer;" disabled>Next ➜</button>
            </div>
        </form>
    </div>
</div>

<script>
// JS to select a game and enable Next button
let selectedGame = null;
const hiddenInput = document.getElementById('selected_game');
const nextBtn = document.getElementById('btnNext');

document.querySelectorAll('.game-box').forEach(el => {
    el.addEventListener('click', function() {
        // Remove previous selection
        if(selectedGame) selectedGame.classList.remove('selected');
        el.classList.add('selected');
        selectedGame = el;

        // Store in hidden input
        hiddenInput.value = JSON.stringify({
            name: el.dataset.name || el.alt || '',
            tags: el.dataset.tags || '',
            image: el.dataset.img || ''
        });

        // Enable Next button
        nextBtn.disabled = false;
        nextBtn.classList.add('active');
    });
});

// Back button
document.getElementById('btnBack').onclick = () => {
    window.location.href = "select_pc.php";
};
</script>
</body>
</html>
