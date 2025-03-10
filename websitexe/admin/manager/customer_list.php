<?php
session_start();
require_once './../../config/config.php';

// Truy vấn lấy danh sách người mua (người dùng có đơn hàng) cùng với số đơn hàng của họ
$query = "SELECT u.id, u.username, u.email, COUNT(o.id) AS order_count
          FROM users u
          JOIN orders o ON u.id = o.user_id
          GROUP BY u.id, u.username, u.email
          ORDER BY order_count DESC";
$result = mysqli_query($conn, $query);
$buyers = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Xác định số đơn hàng lớn nhất để đánh dấu VIP
$maxOrderCount = 0;
foreach ($buyers as $buyer) {
    if ($buyer['order_count'] > $maxOrderCount) {
        $maxOrderCount = $buyer['order_count'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách Người Mua</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4 text-center">Danh sách Người Mua Hàng</h1>
    <table class="min-w-full bg-white shadow-md rounded-lg">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-2 px-4 border-b">User ID</th>
                <th class="py-2 px-4 border-b">Username</th>
                <th class="py-2 px-4 border-b">Email</th>
                <th class="py-2 px-4 border-b">Số đơn hàng</th>
                <th class="py-2 px-4 border-b">VIP</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buyers as $buyer): ?>
                <tr class="text-center border-b">
                    <td class="py-2 px-4"><?= htmlspecialchars($buyer['id']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($buyer['username']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($buyer['email']) ?></td>
                    <td class="py-2 px-4"><?= $buyer['order_count'] ?></td>
                    <td class="py-2 px-4"><?= ($buyer['order_count'] == $maxOrderCount) ? '<span class="text-green-500 font-bold">VIP</span>' : '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>