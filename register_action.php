<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Password ko secure (hash) karo
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check karo ke email pehle se toh nahi hai
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email is already registered!";
            header("Location: register.php");
            exit();
        }

        // Naya user insert karo
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $hashed_password]);

        // Success message aur login page pe bhej do
        $_SESSION['success'] = "Account created successfully! Please login.";
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        die("Registration failed: " . $e->getMessage());
    }
}
?>