<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch users and their uploads
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <h2 class="text-xl mt-4">User Uploads</h2>
        <table class="min-w-full border border-gray-300 mt-2">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Uploads Count</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['id']) ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['uploads_count']) ?></td>
                    <td class="border border-gray-300 px-4 py-2">
                        <a href="manage_users.php?id=<?= $user['id'] ?>" class="text-blue-500">Manage</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_admin.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Add New Admin</a>
    </div>
</body>
</html>
