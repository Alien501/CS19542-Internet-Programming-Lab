<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT COUNT(*) AS total_users FROM students";
$stmt = $pdo->query($sql);
$total_users = $stmt->fetchColumn();

$sql = "SELECT COUNT(*) AS total_uploads FROM uploads";
$stmt = $pdo->query($sql);
$total_uploads = $stmt->fetchColumn();

$sql = "SELECT u.*, s.name FROM uploads u JOIN students s ON u.student_id = s.id ORDER BY u.uploaded_at DESC LIMIT 5";
$stmt = $pdo->query($sql);
$recent_uploads = $stmt->fetchAll();

$cpu_load = sys_getloadavg();
$cpu_usage = $cpu_load[0]; 

$free_output = shell_exec('free -m');
$free_lines = explode("\n", $free_output);
$memory_info = preg_split('/\s+/', $free_lines[1]);
$memory_total = $memory_info[1];
$memory_used = $memory_info[2];
$memory_free = $memory_info[3];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .card {
            background-color: #f9f9f9;
            border: 1px solid #e5e5e5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">

    <?php include '../partials/header.php'; ?>

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

        <!-- System Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="card">
                <h3 class="text-xl font-semibold">Total Users</h3>
                <p class="text-2xl"><?= htmlspecialchars($total_users) ?></p>
            </div>
            <div class="card">
                <h3 class="text-xl font-semibold">Total Uploads</h3>
                <p class="text-2xl"><?= htmlspecialchars($total_uploads) ?></p>
            </div>
            <div class="card">
                <h3 class="text-xl font-semibold">Server CPU Usage</h3>
                <p class="text-2xl"><?= htmlspecialchars($cpu_usage) ?>%</p>
            </div>
            <div class="card">
                <h3 class="text-xl font-semibold">Memory Usage</h3>
                <p class="text-lg">Total: <?= htmlspecialchars($memory_total) ?> MB</p>
                <p class="text-lg">Used: <?= htmlspecialchars($memory_used) ?> MB</p>
                <p class="text-lg">Free: <?= htmlspecialchars($memory_free) ?> MB</p>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-4">Recent Uploads</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($recent_uploads as $upload): ?>
            <div class="card">
                <h4 class="font-bold"><?= htmlspecialchars($upload['title']) ?></h4>
                <p>Type: <?= htmlspecialchars($upload['type']) ?></p>
                <p>Course: <?= htmlspecialchars($upload['course']) ?></p>
                <p>Uploaded By: <?= htmlspecialchars($upload['name']) ?></p>
                <p>Uploaded At: <?= htmlspecialchars($upload['uploaded_at']) ?></p>
                <a href="<?= htmlspecialchars($upload['file_path']) ?>" download class="text-blue-500 hover:underline">Download</a>
            </div>
            <?php endforeach; ?>
        </div>

    </main>
</body>
</html>
