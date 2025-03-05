<?php
session_start();
require_once '../config/config.php';

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();
$car_id = $_POST['car_id'] ?? null;
$action = $_POST['action'] ?? null;

if ($car_id && $action) {
    if ($user_id) {
        $query = "UPDATE basket SET quantity = GREATEST(1, quantity " . ($action == 'increase' ? "+ 1" : "- 1") . ") WHERE user_id = ? AND car_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $car_id);
    } else {
        $query = "UPDATE car_session SET quantity = GREATEST(1, quantity " . ($action == 'increase' ? "+ 1" : "- 1") . ") WHERE session_id = ? AND car_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $session_id, $car_id);
    }

    mysqli_stmt_execute($stmt);
}

header("Location: basket.php");
exit;
