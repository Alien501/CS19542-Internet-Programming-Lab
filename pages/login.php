<?php
include '../config/database.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM students WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['student_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $sql = "SELECT * FROM admins WHERE username = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    }
}

// Theme toggle
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --input-bg: #e0e0e0;
            --input-border: #a0a0a0;
            --button-bg: #000000;
            --button-text: #ffffff;
            --link-color: #000000;
            --error-color: #ff0000;
        }

        .dark-theme {
            --bg-color: #000000;
            --text-color: #ffffff;
            --input-bg: #3a3a3a;
            --input-border: #5a5a5a;
            --button-bg: #ffffff;
            --button-text: #000000;
            --link-color: #ffffff;
            --error-color: #ff4d4d;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .login-form__wrapper {
            background-color: var(--bg-color);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .page-heading {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .input-box {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            background-color: var(--input-bg);
            color: var(--text-color);
        }

        .primary-button {
            background-color: var(--button-bg);
            color: var(--button-text);
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .primary-button:hover {
            background-color: #555555;
        }

        .links {
            text-align: center;
            margin-top: 1rem;
            color: var(--link-color);
            text-decoration: none;
        }

        .links:hover {
            text-decoration: underline;
        }

        .error {
            color: var(--error-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: var(--button-bg);
            color: var(--button-text);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }

        .bottom-link {
            display: block;
            margin: 5px auto;
        }
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()">Toggle Theme</button>

    <div class="login-form__wrapper">
        <h2 class="page-heading">Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <div class="input-group">
                <label for="email">Email:</label>
                <input id="email" placeholder="Email" class="input-box" type="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input id="password" placeholder="Password" class="input-box" type="password" name="password" required>
            </div>
            <button type="submit" class="primary-button">Login</button>
        </form>
        <a href="./signup.php" class="links bottom-link">Create New Account</a>
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
            const theme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            document.cookie = `theme=${theme};path=/;max-age=31536000`; // 1 year
        }

        if (document.cookie.includes('theme=dark')) {
            document.body.classList.add('dark-theme');
        }
    </script>
</body>
</html>
