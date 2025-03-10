<?php
session_start();
require_once '../config/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Lấy sản phẩm trong giỏ hàng
$query = "SELECT b.car_id, b.quantity, c.price FROM basket b JOIN cars c ON b.car_id = c.id WHERE b.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$basket_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($basket_items)) {
    $_SESSION['error_message'] = "Giỏ hàng của bạn trống, không thể thanh toán!";
    header("Location: basket.php");
    exit();
}

// Tính tổng tiền
$total_price = 0;
foreach ($basket_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Xử lý thanh toán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST['payment_method'] ?? null;

    if (!$payment_method) {
        $_SESSION['error_message'] = "Vui lòng chọn phương thức thanh toán!";
        header("Location: checkout.php");
        exit();
    }

    // Tạo đơn hàng
    $order_query = "INSERT INTO orders (user_id, total_price, payment_method, created_at) VALUES (?, ?, ?, NOW())";
    $order_stmt = mysqli_prepare($conn, $order_query);
    mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total_price, $payment_method);
    if (mysqli_stmt_execute($order_stmt)) {
        $order_id = mysqli_insert_id($conn);

        // Lưu chi tiết đơn hàng
        $detail_query = "INSERT INTO order_details (order_id, car_id, quantity, price) VALUES (?, ?, ?, ?)";
        $detail_stmt = mysqli_prepare($conn, $detail_query);
        foreach ($basket_items as $item) {
            mysqli_stmt_bind_param($detail_stmt, "iiid", $order_id, $item['car_id'], $item['quantity'], $item['price']);
            mysqli_stmt_execute($detail_stmt);
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        $delete_query = "DELETE FROM basket WHERE user_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
        mysqli_stmt_execute($delete_stmt);

        // Chuyển hướng đến trang thành công
        $_SESSION['success_message'] = "Đơn hàng của bạn đã được đặt thành công!";
        header("Location: order_success.php?order_id=" . $order_id);
        exit();
    } else {
        $_SESSION['error_message'] = "Lỗi khi xử lý thanh toán. Vui lòng thử lại!";
        header("Location: checkout.php");
        exit();
    }
}
?>

<?php require_once '../templates/header.php'; ?>

<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold text-center">Thanh Toán</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-500 text-white p-4 mt-4 text-center">
            <?= $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <form action="checkout.php" method="POST" class="max-w-lg mx-auto bg-white p-6 shadow-md mt-4">
        <p class="text-xl font-semibold">Tổng tiền: <?= number_format($total_price) ?> VND</p>

        <label class="block mt-4 font-semibold">Chọn phương thức thanh toán:</label>
        <select name="payment_method" class="border p-2 w-full rounded" required>
            <option value="">-- Chọn phương thức --</option>
            <option value="cash">Tiền mặt</option>
            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
        </select>

        <button type="checkout.php" class="bg-green-500 text-white px-6 py-2 rounded mt-4 w-full">
            Xác nhận thanh toán
        </button>
    </form>
</div>

<?php require_once '../templates/footer.php'; ?>