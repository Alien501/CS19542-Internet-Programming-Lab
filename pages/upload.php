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
<body class="min-h-screen p-8">
    <?php include '../partials/header.php' ?>

    <?php include '../partials/menu.php'; ?>
    
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
                <button type="submit" class="bg-black text-white w-full p-1 flex justify-center items-center rounded-lg">
                    <div class="flex justify-evenly items-center w-max p-1 ">
                        <span>
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.81825 1.18188C7.64251 1.00615 7.35759 1.00615 7.18185 1.18188L4.18185 4.18188C4.00611 4.35762 4.00611 4.64254 4.18185 4.81828C4.35759 4.99401 4.64251 4.99401 4.81825 4.81828L7.05005 2.58648V9.49996C7.05005 9.74849 7.25152 9.94996 7.50005 9.94996C7.74858 9.94996 7.95005 9.74849 7.95005 9.49996V2.58648L10.1819 4.81828C10.3576 4.99401 10.6425 4.99401 10.8182 4.81828C10.994 4.64254 10.994 4.35762 10.8182 4.18188L7.81825 1.18188ZM2.5 9.99997C2.77614 9.99997 3 10.2238 3 10.5V12C3 12.5538 3.44565 13 3.99635 13H11.0012C11.5529 13 12 12.5528 12 12V10.5C12 10.2238 12.2239 9.99997 12.5 9.99997C12.7761 9.99997 13 10.2238 13 10.5V12C13 13.104 12.1062 14 11.0012 14H3.99635C2.89019 14 2 13.103 2 12V10.5C2 10.2238 2.22386 9.99997 2.5 9.99997Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        </span>
                        <span>
                            Upload
                        </span>
                    </div>
                </button>
            </form>
        </div>
        <!-- <div class="mt-4 text-center">
            <a href="./index.php" class="text-blue-500 hover:underline">Back to Dashboard</a>
        </div> -->
    </div>

    <script>
        if (document.cookie.includes('theme=dark')) {
            document.body.classList.add('dark-theme');
        }
    </script>
</body>
</html>