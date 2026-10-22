<?php
// Database credentials (XAMPP mein by default root aur khali password hota hai)
$host = 'localhost';
$dbname = 'task_manager_db';
$username = 'root';
$password = '';

try {
    // PDO use karke secure connection bana rahe hain
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Error handling set karo
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>