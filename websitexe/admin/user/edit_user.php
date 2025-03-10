<?php
session_start();
require_once './../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];

    $query = "UPDATE users SET username = ?, email = ?, role_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssii", $username, $email, $role_id, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Cập nhật người dùng thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi cập nhật người dùng!";
    }
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cập nhật User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="mt-8 p-6 bg-white shadow-md rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">Cập nhật User</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-2 mb-4 rounded">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500 text-white p-2 mb-4 rounded">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <form action="edit_user.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">User ID:</label>
                <input type="number" name="id" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-200">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Username:</label>
                <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Role:</label>
                <select name="role_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="1">User</option>
                    <option value="2">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">Update User</button>
        </form>
    </div>
</body>

</html>