<?php
require_once './../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <?php require_once '../slidebar.php'; ?>
    <div class="ml-64 p-6">
        <h1 class="text-2xl font-bold mb-4">Danh sách User</h1>
        <a href="add_user.php" class="mb-4 inline-block bg-green-500 text-white px-4 py-2 rounded">Thêm User</a>
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Tên</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Vai trò</th>
                    <th class="p-3">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='border-b text-center'>
                            <td class='p-3'>{$row['id']}</td>
                            <td class='p-3'>{$row['username']}</td>
                            <td class='p-3'>{$row['email']}</td>
                            <td class='p-3'>{$row['role_name']}</td>
                            <td class='p-3'>
                                <a href='edit_user.php?id={$row['id']}' class='text-blue-500 mr-2'>Sửa</a>
                                <form action ='delete_user.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <button type='submit' class='text-red-500 bg-transparent border-none cursor-pointe' onclick='return confirm('Bạn có chắc chắn muốn xóa?');'>Xóa</button>                                    </form>
                                <a href='detail_user.php?id={$row['id']}' class='text-green-500 ml-2'>Chi tiết</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>