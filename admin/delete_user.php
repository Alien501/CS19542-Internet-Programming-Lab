<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    
    // Delete user from the database
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(['id' => $userId])) {
        header("Location: admin.php");
        exit();
    } else {
        die('Error deleting user');
    }
}
?>
