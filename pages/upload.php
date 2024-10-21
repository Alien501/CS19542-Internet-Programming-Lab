<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $student_id = $_SESSION['student_id'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $course = $_POST['course'];
    $file = $_FILES['file'];
    
    $upload_dir = '../uploads/';
    $file_name = uniqid() . '_' . basename($file['name']);
    $file_path = $upload_dir . $file_name;
    
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $sql = "INSERT INTO uploads (student_id, title, type, course, file_path) VALUES (:student_id, :title, :type, :course, :file_path)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'student_id' => $student_id,
            'title' => $title,
            'type' => $type,
            'course' => $course,
            'file_path' => $file_path
        ]);

        if ($result) {
            $message = "File uploaded successfully!";
            $messageType = "success";
        } else {
            $message = "Error uploading file to database.";
            $messageType = "error";
        }
    } else {
        $message = "Error moving uploaded file.";
        $messageType = "error";
    }
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - REC Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #333333;
            --input-bg: #f0f0f0;
            --input-border: #cccccc;
            --button-bg: #4CAF50;
            --button-hover: #45a049;
        }

        .dark-theme {
            --bg-color: #1f2937;
            --text-color: #f3f4f6;
            --input-bg: #374151;
            --input-border: #4b5563;
            --button-bg: #10b981;
            --button-hover: #059669;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        input, select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-color);
        }

        .btn-primary {
            background-color: var(--button-bg);
        }

        .btn-primary:hover {
            background-color: var(--button-hover);
        }
    </style>
</head>
<body class="min-h-screen p-8">
    <?php include '../partials/header.php' ?>
    <div class="container mx-auto max-w-md">
        <h1 class="text-3xl font-bold mb-8 text-center">Upload File</h1>

        <?php if ($message): ?>
            <div class="mb-4 p-4 rounded <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="title" class="block mb-1">Title:</label>
                    <input type="text" id="title" name="title" required class="w-full px-3 py-2 rounded border">
                </div>
                <div>
                    <label for="type" class="block mb-1">Type:</label>
                    <select id="type" name="type" required class="w-full px-3 py-2 rounded border">
                        <option value="note">Note</option>
                        <option value="question_paper">Question Paper</option>
                    </select>
                </div>
                <div>
                    <label for="course" class="block mb-1">Course:</label>
                    <input type="text" id="course" name="course" required class="w-full px-3 py-2 rounded border">
                </div>
                <div>
                    <label for="file" class="block mb-1">File:</label>
                    <input type="file" id="file" name="file" required class="w-full px-3 py-2 rounded border">
                </div>
                <button type="submit" class="btn-primary text-white font-bold py-2 px-4 rounded w-full">Upload</button>
            </form>
        </div>
        <div class="mt-4 text-center">
            <a href="./index.php" class="text-blue-500 hover:underline">Back to Dashboard</a>
        </div>
    </div>

    <script>
        if (document.cookie.includes('theme=dark')) {
            document.body.classList.add('dark-theme');
        }
    </script>
</body>
</html>