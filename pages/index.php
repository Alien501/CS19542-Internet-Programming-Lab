<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT * FROM uploads WHERE student_id = :student_id ORDER BY uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['student_id' => $student_id]);
$my_uploads = $stmt->fetchAll();

$sql = "SELECT u.*, s.name FROM uploads u JOIN students s ON u.student_id = s.id ORDER BY u.uploaded_at DESC LIMIT 10";
$stmt = $pdo->query($sql);
$recent_uploads = $stmt->fetchAll();

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REC Catalog Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --input-bg: #f0f0f0;
            --input-border: #cccccc;
            --button-bg: #000000;
            --button-hover: #333333;
            --color-white: #ffffff;
            --color-black: #000000;
        }

        .dark-theme {
            --bg-color: #000000;
            --text-color: #ffffff;
            --input-bg: #333333;
            --input-border: #555555;
            --button-bg: #ffffff;
            --button-hover: #cccccc;
            --color-white: #000000;
            --color-black: #ffffff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .card {
            background-color: var(--bg-color);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
        }

        input, button {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            padding: 8px;
            border-radius: 4px;
            margin: 4px 0;
            width: 100%;
        }

        .btn-primary {
            background-color: var(--button-bg);
            color: var(--color-white);
        }

        .btn-primary:hover {
            background-color: var(--button-hover);
        }
    </style>
</head>
<body class="min-h-screen">
    <?php include '../partials/header.php'; ?>

    <div class="p-2">
        <button onclick="window.location.href='profile.php'" class="btn-primary">Profile</button>
        <button onclick="window.location.href='logout.php'" class="btn-primary">Logout</button>
        <button onclick="window.location.href='upload.php'" class="btn-primary">Upload</button>
    </div>

    <main class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-6 text-center sm:text-left">Welcome to REC Catalog</h2>

        <div class="flex justify-center mb-6">
            <input type="text" id="searchInput" placeholder="Search uploads..." onkeyup="filterUploads()" class="input-box p-2 w-full sm:w-1/2">
        </div>

        <h3 class="text-xl font-semibold mb-4 text-center sm:text-left">Recent Uploads</h3>
        <div id="recentUploads" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($recent_uploads as $upload): ?>
            <div class="card">
                <h4 class="font-bold"><?= htmlspecialchars($upload['title']) ?></h4>
                <p>Type: <?= htmlspecialchars($upload['type']) ?></p>
                <p>Course: <?= htmlspecialchars($upload['course']) ?></p>
                <p>Uploaded By: <?= htmlspecialchars($upload['name']) ?></p>
                <a href="<?= htmlspecialchars($upload['file_path']) ?>" download class="text-blue-500 hover:underline">Download</a>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
            const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', theme);
            document.cookie = `theme=${theme};path=/;max-age=31536000`;
        }

        if (document.cookie.includes('theme=dark')) {
            document.body.classList.add('dark-theme');
        }

        function filterUploads() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const uploads = document.querySelectorAll('.card');

            uploads.forEach(upload => {
                const title = upload.querySelector('h4').textContent.toLowerCase();
                if (title.includes(searchInput)) {
                    upload.style.display = 'block';
                } else {
                    upload.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
