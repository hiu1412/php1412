<?php
session_start();
require_once './../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role_id = $_POST['role_id'];

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Mật khẩu nhập lại không khớp";
        header("Location: add_user.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Mật khẩu phải có ít nhất 6 ký tự";
        header("Location: add_user.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['error'] = "Email đã tồn tại";
        header("Location: add_user.php");
        exit();
    }

    $query = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashed_password, $role_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Tạo tài khoản thành công!";
        header("Location: users.php");
        exit();
    } else {
        $_SESSION['error'] = "Lỗi khi tạo tài khoản!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="mt-8 p-6 bg-white shadow-md rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">Thêm User</h2>
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
        <form action="add_user.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">Username:</label>
                <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Email:</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Confirm Password:</label>
                <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Role:</label>
                <select name="role_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="1">User</option>
                    <option value="2">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">Create User</button>
        </form>
    </div>
</body>

</html>