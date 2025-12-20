<?php
include "db.php";

/* Total games */
$totalGames = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM games")
)['total'];

/* Games in use */
$gamesInUse = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM games WHERE status='in_use'")
)['total'];

/* Get games */
$games = mysqli_query($conn, "SELECT * FROM games");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Games</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

<!-- Sidebar -->
<aside class="sidebar">
    <h2>Welcome, Admin</h2>
    <a href="#">Dashboard</a>
    <a class="active" href="#">Games</a>
    <a href="#">Payment</a>

    <div class="admin">
        <div class="avatar">A</div>
        Admin
    </div>
</aside>

<!-- Main Content -->
<main class="content">
    <h2>Available Games</h2>

    <!-- CLICKABLE STATS -->
    <div class="stats">
        <a href="games_in_use.php" class="stat-box">
            <strong><?= $gamesInUse ?></strong>
            <span>Games in use</span>
        </a>

        <a href="all_games.php" class="stat-box">
            <strong><?= $totalGames ?></strong>
            <span>Games in system</span>
        </a>
    </div>

    <!-- Games Grid -->
    <div class="games-grid">
        <?php while ($row = mysqli_fetch_assoc($games)) { ?>
            <a href="game_details.php?id=<?= $row['id'] ?>" class="game-card">
                <img src="<?= $row['image'] ?>">
            </a>
        <?php } ?>
    </div>

</main>
</div>

</body>
</html>
