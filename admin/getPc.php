<?php
include 'db.php'; // your DB connection

header("Content-Type: application/json");

// Fetch total PCs
$totalSql = "SELECT COUNT(*) AS total FROM pcs";
$totalResult = mysqli_query($conn, $totalSql);
$totalPcs = mysqli_fetch_assoc($totalResult)['total'];

// Fetch PCs in use
$busySql = "SELECT id FROM pcs WHERE status = 'busy'";
$busyResult = mysqli_query($conn, $busySql);

$pcsInUse = [];
while ($row = mysqli_fetch_assoc($busyResult)) {
    $pcsInUse[] = intval($row['id']);
}

// Send JSON
echo json_encode([
    "total" => $totalPcs,
    "in_use" => $pcsInUse
]);
?>
