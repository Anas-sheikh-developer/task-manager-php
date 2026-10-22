<?php
require_once 'config/db.php';
include 'includes/header.php';
?>
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h3 class="text-center fw-bold mb-1">Welcome Back</h3>
                <p class="text-center text-muted mb-4">Login to manage your tasks</p>
                
                <form action="login_action.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg fs-6" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg fs-6" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2">Login</button>
                </form>
                
                <p class="text-center mt-4 mb-0 text-muted">
                    New here? <a href="register.php" class="text-dark fw-bold text-decoration-none">Create account</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>