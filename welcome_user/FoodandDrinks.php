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
   HANDLE FOOD ORDER
-------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food'])) {

    // Remove previous food orders
    $del = $conn->prepare("DELETE FROM food_orders WHERE user_id = ?");
    $del->bind_param("i", $user_id);
    $del->execute();

    foreach ($_POST['food'] as $item) {
        if ($item['qty'] > 0) {
            $stmt = $conn->prepare("
                INSERT INTO food_orders (user_id, item_name, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "isii",
                $user_id,
                $item['name'],
                $item['qty'],
                $item['price']
            );
            $stmt->execute();
        }
    }

    header("Location: Payment.php");
    exit();
}

/* -------------------------
   FETCH FOOD ITEMS
-------------------------- */
$foods = $conn->query("SELECT * FROM food_items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Food & Drinks - Cyber Cafe</title>
<link rel="stylesheet" href="food.css">
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
<h2>Select Food & Drinks</h2>

<form method="POST" id="foodForm">

<div class="food-list">
<?php while($food = $foods->fetch_assoc()): ?>
    <div class="food-item">
        <span><?= htmlspecialchars($food['name']) ?></span>
        <span>Rs.<?= $food['price'] ?></span>

        <input type="number"
               min="0"
               value="0"
               data-price="<?= $food['price'] ?>"
               data-name="<?= htmlspecialchars($food['name']) ?>">
    </div>
<?php endwhile; ?>
</div>

<div class="total">
    <strong>Total: Rs.<span id="totalAmount">0</span></strong>
</div>

<button type="button" onclick="submitFood()">Proceed to Payment</button>

</form>
</div>
</div>

<!-- JS -->
<script>
const inputs = document.querySelectorAll('.food-item input');
const totalAmount = document.getElementById('totalAmount');

function calculateTotal() {
    let total = 0;
    inputs.forEach(input => {
        total += input.value * input.dataset.price;
    });
    totalAmount.innerText = total;
}

inputs.forEach(input => {
    input.addEventListener('input', calculateTotal);
});

function submitFood() {
    let hasItem = false;

    inputs.forEach(input => {
        if (input.value > 0) hasItem = true;
    });

    if (!hasItem) {
        alert("Please select at least one item.");
        return;
    }

    const form = document.getElementById('foodForm');

    inputs.forEach(input => {
        if (input.value > 0) {
            const wrapper = document.createElement("div");
            wrapper.innerHTML = `
                <input type="hidden" name="food[][name]" value="${input.dataset.name}">
                <input type="hidden" name="food[][qty]" value="${input.value}">
                <input type="hidden" name="food[][price]" value="${input.dataset.price}">
            `;
            form.appendChild(wrapper);
        }
    });

    form.submit();
}
</script>

</body>
</html>
