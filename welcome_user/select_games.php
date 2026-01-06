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
   HANDLE GAME SELECTION
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_name'])) {

    $game_name  = $_POST['game_name'];
    $game_tags  = $_POST['game_tags'];
    $game_image = $_POST['game_image'];

    // Remove previous game
    $del = $conn->prepare("DELETE FROM game_selection WHERE user_id = ?");
    $del->bind_param("i", $user_id);
    $del->execute();

    // Insert new game
    $stmt = $conn->prepare("
        INSERT INTO game_selection (user_id, game_name, game_tags, game_image)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $user_id, $game_name, $game_tags, $game_image);
    $stmt->execute();

    // Go next
    header("Location: Duration.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select Games</title>
<link rel="stylesheet" href="selectgames.css">
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


<!-- CONTENT -->
<div class="content">

<input type="text" class="search-bar" placeholder="Search for games...">


<!-- FEATURED -->
<div class="featured-section">
    <div class="featured-img game"
         data-name="Featured Game 1"
         data-tags="Action,Adventure"
         data-img="../images/game1.jpg">
        <img src="../images/game1.jpg">
    </div>

    <div class="library-box">
        <h3>In Library</h3>

        <div class="game-entry game"
             data-name="GHOST OF YOTEI"
             data-tags="Physics-based,3D,Adventure"
             data-img="../images/game2.jpg">
            <img src="../images/game2.jpg">
            <strong>GHOST OF YOTEI</strong>
        </div>

        <div class="game-entry game"
             data-name="GHOST OF TSUSHIMA"
             data-tags="Action,Adventure"
             data-img="../images/game3.jpg">
            <img src="../images/game3.jpg">
            <strong>GHOST OF TSUSHIMA</strong>
        </div>

        <div class="game-entry game"
             data-name="FORZA HORIZON 5"
             data-tags="Open-world,Racing"
             data-img="../images/game4.jpg">
            <img src="../images/game4.jpg">
            <strong>FORZA HORIZON 5</strong>
        </div>
    </div>
</div>

<!-- BUTTONS -->
<div class="nav-buttons" style="margin-top:30px; display:flex; justify-content:flex-end; gap:15px;">
    <button onclick="location.href='select_pc.php'">⬅ Back</button>
    <button id="nextBtn" onclick="submitGame()">Next ➜</button>
</div>

</div>
</div>

<!-- JS -->
<script>
let selectedGame = null;

document.querySelectorAll('.game').forEach(game => {
    game.addEventListener('click', () => {

        document.querySelectorAll('.game')
            .forEach(g => g.classList.remove('selected'));

        game.classList.add('selected');
        selectedGame = game;
    });
});

function submitGame() {
    if (!selectedGame) return;

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "";

    form.innerHTML = `
        <input type="hidden" name="game_name" value="${selectedGame.dataset.name}">
        <input type="hidden" name="game_tags" value="${selectedGame.dataset.tags}">
        <input type="hidden" name="game_image" value="${selectedGame.dataset.img}">
    `;

    document.body.appendChild(form);
    form.submit();
}
</script>

</body>
</html>
