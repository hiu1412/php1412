<?php
session_start();
require_once './../../config/config.php';

// Lấy danh sách đơn hàng chưa thanh toán (pending)
$query_pending = "SELECT o.id, u.username AS user_name, o.total_price, o.payment_method, o.status, o.created_at 
                  FROM orders o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE o.status = 'pending'
                  ORDER BY o.created_at DESC";
$result_pending = mysqli_query($conn, $query_pending);
$pendingOrders = mysqli_fetch_all($result_pending, MYSQLI_ASSOC);

// Lấy danh sách đơn hàng đã thanh toán (completed)
$query_completed = "SELECT o.id, u.username AS user_name, o.total_price, o.payment_method, o.status, o.created_at 
                    FROM orders o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    WHERE o.status = 'completed'
                    ORDER BY o.created_at DESC";
$result_completed = mysqli_query($conn, $query_completed);
$completedOrders = mysqli_fetch_all($result_completed, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách Đơn hàng</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4 text-center">Danh sách Đơn hàng</h1>

    <h2 class="text-xl font-semibold mb-2">Đơn hàng chưa thanh toán</h2>
    <?php if (!empty($pendingOrders)): ?>
        <table class="min-w-full bg-white shadow-md rounded-lg mb-6">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Mã Đơn</th>
                    <th class="py-2 px-4 border-b">Người Mua</th>
                    <th class="py-2 px-4 border-b">Tổng Tiền</th>
                    <th class="py-2 px-4 border-b">Phương Thức</th>
                    <th class="py-2 px-4 border-b">Ngày Đặt</th>
                    <th class="py-2 px-4 border-b">Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingOrders as $order): ?>
                    <tr class="text-center">
                        <td class="py-2 px-4 border-b"><?= $order['id'] ?></td>
                        <td class="py-2 px-4 border-b"><?= $order['user_name'] ?: 'Khách vãng lai' ?></td>
                        <td class="py-2 px-4 border-b"><?= number_format($order['total_price']) ?> VND</td>
                        <td class="py-2 px-4 border-b"><?= ucfirst($order['payment_method']) ?></td>
                        <td class="py-2 px-4 border-b"><?= $order['created_at'] ?></td>
                        <td class="py-2 px-4 border-b"><?= ucfirst($order['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="mb-6 text-center">Không có đơn hàng chưa thanh toán.</p>
    <?php endif; ?>

    <h2 class="text-xl font-semibold mb-2">Đơn hàng đã thanh toán</h2>
    <?php if (!empty($completedOrders)): ?>
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Mã Đơn</th>
                    <th class="py-2 px-4 border-b">Người Mua</th>
                    <th class="py-2 px-4 border-b">Tổng Tiền</th>
                    <th class="py-2 px-4 border-b">Phương Thức</th>
                    <th class="py-2 px-4 border-b">Ngày Đặt</th>
                    <th class="py-2 px-4 border-b">Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($completedOrders as $order): ?>
                    <tr class="text-center">
                        <td class="py-2 px-4 border-b"><?= $order['id'] ?></td>
                        <td class="py-2 px-4 border-b"><?= $order['user_name'] ?: 'Khách vãng lai' ?></td>
                        <td class="py-2 px-4 border-b"><?= number_format($order['total_price']) ?> VND</td>
                        <td class="py-2 px-4 border-b"><?= ucfirst($order['payment_method']) ?></td>
                        <td class="py-2 px-4 border-b"><?= $order['created_at'] ?></td>
                        <td class="py-2 px-4 border-b"><?= ucfirst($order['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Không có đơn hàng đã thanh toán.</p>
    <?php endif; ?>
</body>

</html>