<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hostel Management System</title>
    <?php 
    $pageTitle = 'Login';
    $pageDescription = 'Sign in to access your Hostel Management System dashboard. Manage room allocations, payments, and more.';
    include __DIR__ . '/../partials/meta.php'; 
    ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Auth/css/LoginView.css">
</head>
<body>

    <div class="auth-page">
        <div class="auth-container">
            
            <!-- Logo -->
            <div class="auth-logo">
                <div class="logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <h1>Hostel Management System</h1>
                <p>Student Accommodation Portal</p>
            </div>

            <!-- Login Card -->
            <div class="auth-card">
                <div class="card-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to access your dashboard</p>
                </div>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control"
                            placeholder="Enter your email"
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="form-options">
                        <label class="form-check">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Remember me for 30 days</span>
                        </label>
                        <a href="index.php?page=forgot_password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Sign In
                    </button>
                </form>

                <div class="auth-divider">
                    <span>New to Hostel System?</span>
                </div>

                <p class="auth-link">
                    Don't have an account? <a href="index.php?page=signup">Create Student Account</a>
                </p>
            </div>

            <!-- Footer -->
            <p class="page-footer">
                &copy; <?php echo date('Y'); ?> Hostel Management System. All rights reserved.
            </p>

        </div>
    </div>

    <script src="app/Views/Auth/js/LoginView.js"></script>
</body>
</html>