<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Hostel Management System</title>
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
                <p>Password Recovery</p>
            </div>

            <!-- Forgot Password Card -->
            <div class="auth-card">
                
                <?php if ($step === 'verify'): ?>
                    <!-- Step 1: Verify Identity -->
                    <div class="card-header">
                        <h2>Forgot Password?</h2>
                        <p>Verify your identity to reset your password</p>
                    </div>

                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error_msg); ?>
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
                                placeholder="Enter your registered email"
                                required
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="student_id">Student ID</label>
                            <input 
                                type="text" 
                                id="student_id" 
                                name="student_id" 
                                class="form-control"
                                placeholder="Enter your Student ID (e.g., STU-2026-0001)"
                                required
                                value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input 
                                type="date" 
                                id="dob" 
                                name="dob" 
                                class="form-control"
                                required
                                value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"
                            >
                        </div>

                        <button type="submit" name="verify_identity" class="btn btn-primary btn-block btn-lg">
                            Verify Identity
                        </button>
                    </form>

                <?php else: ?>
                    <!-- Step 2: Reset Password -->
                    <div class="card-header">
                        <h2>Reset Password</h2>
                        <p>Enter your new password</p>
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
                            <label for="new_password">New Password</label>
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                class="form-control"
                                placeholder="Enter new password (min 6 characters)"
                                required
                                minlength="6"
                            >
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-control"
                                placeholder="Confirm your new password"
                                required
                                minlength="6"
                            >
                        </div>

                        <button type="submit" name="reset_password" class="btn btn-primary btn-block btn-lg">
                            Reset Password
                        </button>
                    </form>

                <?php endif; ?>

                <div class="auth-divider">
                    <span>Remember your password?</span>
                </div>

                <p class="auth-link">
                    <a href="index.php?page=login">Back to Login</a>
                </p>
            </div>

            <!-- Footer -->
            <p class="page-footer">
                &copy; <?php echo date('Y'); ?> Hostel Management System. All rights reserved.
            </p>

        </div>
    </div>

</body>
</html>
