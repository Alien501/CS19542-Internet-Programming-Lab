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

        .float-container {
            position: fixed;
            margin: auto;
            bottom: 10px;
            height: 50px;
            background-color: #fff;
            width: max-content;
            min-width: 200px;
            display: flex;
            border-radius: 25px;
            border: 1px solid #aeaeae;
            box-shadow: 0px 0px 10px rgb(174, 174, 174,);
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            padding: 5px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            background-color: black;
            color: #fff;
            display: grid;
            place-content: center;
            border-radius: 50%;
            transition: .3s ease;
        }

        .icon-btn:hover {
            cursor: pointer;
            background-color: #fff;
            color: #000;
        }

        .icon-btn:hover svg {
            transform: rotate(360deg);
        }

        .download-button {
            width: 40px;
            height: 40px;
            display: grid;
            place-content: center;
            background-color: #fff;
            color: #000;
            border: 1px solid #efefef;
            border-radius: 5px;
            transition: .3s ease;
        }

        .download-button:hover {
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        .type-span {
            padding: 2px;
            background-color: black;
            color: #fff;
            border-radius: 25px;
            height: 23px;
            min-width: 30px;
            width: 50px;
            text-align: center; 
        }
    </style>
</head>
<body class="min-h-screen">
    <?php include '../partials/header.php'; ?>

    <?php include '../partials/menu.php'; ?>

    <main class="container mx-auto px-4 py-8">
        <div class="flex justify-center items-center">
            <h2 class="text-3xl font-bold mb-6 text-center sm:text-left">Welcome to REC Catalog</h2>
        </div>

        <div class="flex justify-center mb-6">
            <input type="text" id="searchInput" placeholder="Search Something..." onkeyup="filterUploads()" class="input-box p-2 w-full sm:w-1/2">
        </div>

        <h3 class="text-xl font-semibold mb-4 text-center sm:text-left">Recent Uploads</h3>
        <div id="recentUploads" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($recent_uploads as $upload): ?>
            <div class="card hover:shadow-sm flex justify-between flex-between items-center">
                <div>
                    <div class="flex items-center w-full">
                        <h4 class="font-bold"><?= htmlspecialchars($upload['title']) ?></h4>
                        <p class="type-span ml-2 text-sm"><?= htmlspecialchars($upload['type'] === 'question_paper' ? 'QP' : 'Notes') ?></p>
                    </div>
                    <div>
                        <p>Course: <?= htmlspecialchars($upload['course']) ?></p>
                        <p>Uploaded By: <?= htmlspecialchars($upload['name']) ?></p>
                    </div>
                </div>
                <div>
                    <a href="<?= htmlspecialchars($upload['file_path']) ?>" download class="download-button">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.50005 1.04999C7.74858 1.04999 7.95005 1.25146 7.95005 1.49999V8.41359L10.1819 6.18179C10.3576 6.00605 10.6425 6.00605 10.8182 6.18179C10.994 6.35753 10.994 6.64245 10.8182 6.81819L7.81825 9.81819C7.64251 9.99392 7.35759 9.99392 7.18185 9.81819L4.18185 6.81819C4.00611 6.64245 4.00611 6.35753 4.18185 6.18179C4.35759 6.00605 4.64251 6.00605 4.81825 6.18179L7.05005 8.41359V1.49999C7.05005 1.25146 7.25152 1.04999 7.50005 1.04999ZM2.5 10C2.77614 10 3 10.2239 3 10.5V12C3 12.5539 3.44565 13 3.99635 13H11.0012C11.5529 13 12 12.5528 12 12V10.5C12 10.2239 12.2239 10 12.5 10C12.7761 10 13 10.2239 13 10.5V12C13 13.1041 12.1062 14 11.0012 14H3.99635C2.89019 14 2 13.103 2 12V10.5C2 10.2239 2.22386 10 2.5 10Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </a>
                </div>
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
