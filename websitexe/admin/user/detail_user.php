<?php
session_start();
require_once './../../config/config.php';

// Lấy thông tin user theo ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT username, email, role_id FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
} else {
    $_SESSION['error'] = "Không tìm thấy người dùng!";
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="mt-8 p-6 bg-white shadow-md rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">Chi tiết User</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2 mb-4 rounded">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <form class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">User ID:</label>
                <input type="number" value="<?= htmlspecialchars($id) ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-200">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Username:</label>
                <input type="text" value="<?= htmlspecialchars($user['username']) ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-200">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Email:</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-200">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Role:</label>
                <input type="text" value="<?= $user['role_id'] == 1 ? 'User' : 'Admin' ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-200">
            </div>
        </form>
    </div>
</body>

</html>