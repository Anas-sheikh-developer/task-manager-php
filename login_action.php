<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // User ko email se dhoondo
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Agar user mil gaya aur password match ho gaya
        if ($user && password_verify($password, $user['password'])) {
            // Session variables set karo
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header("Location: dashboard.php"); // Dashboard pe bhej do
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password!";
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Login failed: " . $e->getMessage());
    }
}
?>