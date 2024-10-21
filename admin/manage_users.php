<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();

    if (!$user) {
        die('User not found');
    }
} else {
    die('User ID not specified');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage User</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Manage User: <?= htmlspecialchars($user['email']) ?></h1>
        <p>Uploads Count: <?= htmlspecialchars($user['uploads_count']) ?></p>
        <form action="delete_user.php" method="POST" class="mt-4">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete User</button>
        </form>
    </div>
</body>
</html>
