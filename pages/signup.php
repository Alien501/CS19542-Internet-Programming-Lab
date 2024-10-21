<?php
session_start();

include '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $sql = "SELECT * FROM students WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $existing_student = $stmt->fetch();

        if ($existing_student) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO students (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $hashed_password
            ]);

            $success = "Account created successfully. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --input-bg: #f0f0f0;
            --input-border: #cccccc;
            --button-bg: #000000;
            --button-text: #ffffff;
            --link-color: #000000;
            --error-color: #ff0000;
            --success-color: #00ff00;
        }

        .dark-theme {
            --bg-color: #000000;
            --text-color: #ffffff;
            --input-bg: #333333;
            --input-border: #666666;
            --button-bg: #ffffff;
            --button-text: #000000;
            --link-color: #ffffff;
            --error-color: #ff4444;
            --success-color: #44ff44;
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

        .input-box {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--input-border);
            border-radius: 4px;
            background-color: var(--input-bg);
            color: var(--text-color);
            margin-bottom: 1rem;
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
            opacity: 0.8;
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

        .success {
            color: var(--success-color);
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
    </style>
</head>
<body>
    <button class="theme-toggle" onclick="toggleTheme()">Toggle Theme</button>

    <div class="login-form__wrapper">
        <h2 class="page-heading">Sign Up</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="signup.php" class="login-form">
            <input class="input-box" type="text" name="name" placeholder="Name" required>
            <input class="input-box" type="email" name="email" placeholder="Email" required>
            <input class="input-box" type="password" name="password" placeholder="Password" required>
            <input class="input-box" type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button class="primary-button" type="submit">Sign Up</button>
        </form>

        <a href="./login.php" class="links">Already have an account? Login</a>
    </div>

    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
        }
    </script>
</body>
</html>
