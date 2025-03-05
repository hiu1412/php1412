<?php
require_once '../config/config.php';

function transferCartSessionToBasket($conn, $user_id, $old_session_id)
{
    if (!$conn) {
        die("Lỗi kết nối database: " . mysqli_connect_error());
    }

    // Kiểm tra xem có sản phẩm nào trong giỏ hàng session cũ không
    $query_check = "SELECT COUNT(*) FROM car_session WHERE session_id = ?";
    $stmt_check = mysqli_prepare($conn, $query_check);

    if (!$stmt_check) {
        die("Lỗi chuẩn bị truy vấn: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_check, "s", $old_session_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $count);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    if ($count > 0) {
        // Chuyển dữ liệu từ car_session sang bảng basket
        $query_transfer = "INSERT INTO basket (user_id, car_id, quantity, created_at) 
                           SELECT ?, car_id, quantity, created_at FROM car_session WHERE session_id = ?
                           ON DUPLICATE KEY UPDATE quantity = basket.quantity + VALUES(quantity)";
        $stmt_transfer = mysqli_prepare($conn, $query_transfer);

        if (!$stmt_transfer) {
            die("Lỗi chuẩn bị truy vấn INSERT: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt_transfer, "is", $user_id, $old_session_id);
        mysqli_stmt_execute($stmt_transfer);
        mysqli_stmt_close($stmt_transfer);

        // Xóa dữ liệu cũ trong car_session
        $query_delete = "DELETE FROM car_session WHERE session_id = ?";
        $stmt_delete = mysqli_prepare($conn, $query_delete);

        if (!$stmt_delete) {
            die("Lỗi chuẩn bị truy vấn DELETE: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt_delete, "s", $old_session_id);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);
    }
}
