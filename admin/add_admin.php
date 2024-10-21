<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO admins (email, password) VALUES (:email, :password)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['email' => $email, 'password' => $password])) {
        $success = "Admin added successfully!";
    } else {
        $error = "Error adding admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Add New Admin</h1>
        <?php if ($error): ?>
            <div class="text-red-500"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="text-green-500"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="border px-2 py-1 w-full" required>
            </div>
            <div class="mt-2">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="border px-2 py-1 w-full" required>
            </div>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Add Admin</button>
        </form>
    </div>
    <script>
    </script>
</body>
</html>
