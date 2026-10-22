<?php
session_start();
require_once 'config/db.php';

if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$user_id = $_SESSION['user_id'];

$search = $_GET['search'] ?? '';
$filter_status = $_GET['status'] ?? '';

$query = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];
if (!empty($search)) { $query .= " AND title LIKE ?"; $params[] = "%$search%"; }
if (!empty($filter_status)) { $query .= " AND status = ?"; $params[] = $filter_status; }
$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query); $stmt->execute($params); $tasks = $stmt->fetchAll();

// Statistics
$stats_stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY status");
$stats_stmt->execute([$user_id]);
$stats_data = $stats_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$total = array_sum($stats_data);
$completed = $stats_data['Completed'] ?? 0;
$pending = $stats_data['Pending'] ?? 0;
$in_progress = $stats_data['In Progress'] ?? 0;

include 'includes/header.php';
?>

<h4 class="mb-4 fw-bold">Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?> </h4>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-white border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Total Tasks</h6>
                <h2 class="mb-0 text-dark fw-bold"><?= $total ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-white border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Pending</h6>
                <h2 class="mb-0 text-secondary fw-bold"><?= $pending ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-white border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">In Progress</h6>
                <h2 class="mb-0 text-warning fw-bold"><?= $in_progress ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-white border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Completed</h6>
                <h2 class="mb-0 text-success fw-bold"><?= $completed ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Task Form -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="mb-4 fw-bold">Create New Task</h5>
                <form action="task_action.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Task Title</label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" required placeholder="e.g. Finish project">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Add details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">+ Add Task</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Task List -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Recent Tasks</h5>
                </div>

                <!-- Search Form -->
                <form method="GET" action="dashboard.php" class="row g-2 mb-4 bg-light p-3 rounded-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control border-0" placeholder="🔍 Search tasks..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select border-0">
                            <option value="">All Statuses</option>
                            <option value="Pending" <?= $filter_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="In Progress" <?= $filter_status == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="Completed" <?= $filter_status == 'Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary w-100">Filter</button>
                    </div>
                </form>
                
                <?php if(count($tasks) === 0): ?>
                    <div class="text-center py-5">
                        <h1 class="text-muted mb-3">📭</h1>
                        <p class="text-muted">No tasks found. Create one to get started!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="text-muted small text-uppercase">
                                <tr>
                                    <th>Task Details</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tasks as $task): ?>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($task['title']) ?></h6>
                                        <?php if($task['description']): ?>
                                            <small class="text-muted"><?= htmlspecialchars($task['description']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?= $task['status'] === 'Completed' ? 'success' : ($task['status'] === 'In Progress' ? 'warning text-dark' : 'secondary') ?> px-3 py-2">
                                            <?= $task['status'] ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-light text-primary">Edit</a>
                                        <form action="task_action.php" method="POST" class="d-inline" onsubmit="return confirm('Delete this task?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-light text-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>