<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_upload_id'])) {
    $delete_upload_id = $_POST['delete_upload_id'];
    
    $sql = "SELECT file_path FROM uploads WHERE id = :id AND student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $delete_upload_id, 'student_id' => $student_id]);
    $upload = $stmt->fetch();

    if ($upload) {
        $file_path = $upload['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $sql = "DELETE FROM uploads WHERE id = :id AND student_id = :student_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $delete_upload_id, 'student_id' => $student_id]);

        header("Location: profile.php");
        exit();
    }
}

$sql = "SELECT * FROM uploads WHERE student_id = :student_id ORDER BY uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['student_id' => $student_id]);
$my_uploads = $stmt->fetchAll();

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - REC Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #333333;
        }

        .dark-theme {
            --bg-color: #1f2937;
            --text-color: #f3f4f6;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .profile-back-button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70px;
        }

        .profile-back-button-container a {
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            width: 80px;
            height: 40px;
            background-color: #000;
            color: #fff;
            border-radius: 25px;    
        }

        .profile-back-button-container a:hover {
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            width: 80px;
            height: 40px;
            background-color: #fff;
            color: #000;
            border-radius: 25px;    
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
    </style>
</head>
<body class="min-h-screen">
    <?php include '../partials/header.php' ?>
    <?php include '../partials/menu.php'; ?>

    <main class="container mx-auto px-4 pt-24 pb-8">
        <h2 class="text-3xl font-bold mb-8">My Profile</h2>
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">My Uploads</h3>
            <input type="text" id="search" placeholder="Search uploads..." class="mb-4 p-2 w-full border border-gray-300 rounded-md" onkeyup="filterUploads()">
            
            <div id="uploadsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($my_uploads as $upload): ?>
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-lg font-semibold mb-2"><?= htmlspecialchars($upload['title']) ?></h4>
                        <p class="text-sm mb-1"><strong>Type:</strong> <?= htmlspecialchars($upload['type']) ?></p>
                        <p class="text-sm mb-1"><strong>Course:</strong> <?= htmlspecialchars($upload['course']) ?></p>
                        <p class="text-sm mb-3"><strong>Uploaded At:</strong> <?= htmlspecialchars($upload['uploaded_at']) ?></p>
                        <a href="<?= htmlspecialchars($upload['file_path']) ?>" download class="text-blue-500 hover:underline">Download</a>

                        <!-- Delete button form -->
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this upload?');" class="mt-2">
                            <input type="hidden" name="delete_upload_id" value="<?= htmlspecialchars($upload['id']) ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="profile-back-button-container">
                <a href="./index.php" type="button">
                    <span>Home</span>
                </a>
            </div>
        </div>
    </main>

    <script>
        function filterUploads() {
            var input = document.getElementById("search");
            var filter = input.value.toLowerCase();
            var cards = document.querySelectorAll("#uploadsContainer > div");
            
            cards.forEach(function(card) {
                var title = card.querySelector("h4").textContent.toLowerCase();
                if (title.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }

        if (document.cookie.includes('theme=dark')) {
            document.body.classList.add('dark-theme');
        }
    </script>
</body>
</html>
