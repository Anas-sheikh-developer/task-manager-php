<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f4f7f6; 
            color: #333;
        }
        .card {
            border-radius: 12px;
        }
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        /* Soft shadow for cards */
        .shadow-sm {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/task-manager/dashboard.php">📝 TaskFlow</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto fw-medium">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link active" href="/task-manager/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/task-manager/logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/task-manager/index.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="/task-manager/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">