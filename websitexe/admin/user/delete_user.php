<?php
session_start();
require_once './../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ép kiểu để tránh lỗi SQL Injection

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['success'] = "Xóa người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa người dùng hoặc user không tồn tại!";
    }

    mysqli_stmt_close($stmt);
    header("Location: users.php");
    exit();
} else {
    $_SESSION['error'] = "Yêu cầu không hợp lệ!";
    header("Location: users.php");
    exit();
}
