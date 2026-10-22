<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // TASK ADD KARNA
    if ($action === 'add') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);

        if (!empty($title)) {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $title, $description]);
        }
        header("Location: dashboard.php");
        exit();
    }

    // TASK DELETE KARNA
    if ($action === 'delete' && isset($_POST['id'])) {
        $task_id = $_POST['id'];
        // Check karo ke task usi user ka hai
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        
        header("Location: dashboard.php");
        exit();
    }

    // TASK UPDATE KARNA (Edit page se aayega)
    if ($action === 'update' && isset($_POST['id'])) {
        $task_id = $_POST['id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $description, $status, $task_id, $user_id]);
        
        header("Location: dashboard.php");
        exit();
    }
}
?>