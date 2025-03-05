<?php
session_start();
require_once '../config/config.php';
require_once './basket_service.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt); // Lưu kết quả truy vấn tránh lỗi "Commands out of sync"
    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

    if (mysqli_stmt_fetch($stmt) && password_verify($password, $hashed_password)) {
        mysqli_stmt_close($stmt); // Đóng trước khi thực hiện truy vấn khác

        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;

        $session_id = session_id();
        if ($session_id) {
            transferCartSessionToBasket($conn, $id, $session_id);
        }

        session_regenerate_id(true);
        header("Location: index.php");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        $_SESSION['error'] = "Email hoặc mật khẩu không đúng";
        header("Location: login.php");
        exit();
    }
}
?>

<?php require_once '../templates/header.php'; ?>
<div class="container mx-auto mt-6">
    <h2 class="text-2xl font-bold text-center">Đăng Nhập</h2>
    <form action="login.php" method="POST" class="max-w-md mx-auto mt-4">
        <input type="email" name="email" placeholder="Email" required class="w-full border p-2 mb-2">
        <input type="password" name="password" placeholder="Mật khẩu" required class="w-full border p-2 mb-2">
        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Đăng nhập</button>
    </form>
</div>
<?php require_once '../templates/footer.php'; ?>