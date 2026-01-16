<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Hostel Management System</title>
    <?php 
    $pageTitle = 'Student Registration';
    $pageDescription = 'Register for a student account to apply for hostel accommodation. Quick and easy signup process.';
    include __DIR__ . '/../partials/meta.php'; 
    ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Auth/css/SignupView.css">
</head>
<body>

    <div class="auth-page">
        <div class="auth-container wide">
            
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

            <!-- Signup Card -->
            <div class="auth-card">
                <div class="card-header">
                    <h2>Student Registration</h2>
                    <p>Create your account to apply for hostel accommodation</p>
                </div>

                <?php if (!empty($success_msg)): ?>
                    <div class="alert alert-success">
                        <strong>Registration Successful!</strong> <?php echo htmlspecialchars($success_msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-error">
                        <strong>Error:</strong> <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    
                    <!-- Section 1: Account Details -->
                    <div class="form-section">
                        <h4>Account Details</h4>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="full_name">Full Name <span class="required">*</span></label>
                                <input 
                                    type="text" 
                                    id="full_name" 
                                    name="full_name" 
                                    class="form-control"
                                    placeholder="Enter your full name"
                                    required
                                    value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                                >
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-control"
                                    placeholder="your.email@example.com"
                                    required
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control"
                                    placeholder="Minimum 8 characters"
                                    required
                                    minlength="8"
                                >
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-control"
                                    placeholder="e.g. 01712345678"
                                    required
                                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Academic Details -->
                    <div class="form-section">
                        <h4>Academic Information</h4>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="student_id">Student ID <span class="required">*</span></label>
                                <input 
                                    type="text" 
                                    id="student_id" 
                                    name="student_id" 
                                    class="form-control"
                                    placeholder="XX-XXXXX-X"
                                    required
                                    maxlength="10"
                                    value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>"
                                >
                                <p class="form-hint">Format: 23-12345-1</p>
                            </div>

                            <div class="form-group">
                                <label for="department">Department <span class="required">*</span></label>
                                <select id="department" name="department" class="form-control" required>
                                    <option value="">-- Select Department --</option>
                                    <option value="CSE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'CSE') ? 'selected' : ''; ?>>CSE - Computer Science</option>
                                    <option value="EEE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'EEE') ? 'selected' : ''; ?>>EEE - Electrical Engineering</option>
                                    <option value="IPE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'IPE') ? 'selected' : ''; ?>>IPE - Industrial Engineering</option>
                                    <option value="BBA" <?php echo (isset($_POST['department']) && $_POST['department'] === 'BBA') ? 'selected' : ''; ?>>BBA - Business Administration</option>
                                    <option value="Architecture" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Architecture') ? 'selected' : ''; ?>>Architecture</option>
                                    <option value="English" <?php echo (isset($_POST['department']) && $_POST['department'] === 'English') ? 'selected' : ''; ?>>English</option>
                                    <option value="Law" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Law') ? 'selected' : ''; ?>>Law</option>
                                    <option value="Economics" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Economics') ? 'selected' : ''; ?>>Economics</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="session">Academic Session <span class="required">*</span></label>
                            <input 
                                type="text" 
                                id="session" 
                                name="session" 
                                class="form-control"
                                placeholder="e.g. 2025-2026"
                                required
                                value="<?php echo isset($_POST['session']) ? htmlspecialchars($_POST['session']) : ''; ?>"
                            >
                        </div>
                    </div>

                    <!-- Section 3: Personal Details -->
                    <div class="form-section">
                        <h4>Personal Information</h4>
                        
                        <div class="form-group">
                            <label for="dob">Date of Birth <span class="required">*</span></label>
                            <input 
                                type="date" 
                                id="dob" 
                                name="dob" 
                                class="form-control"
                                required
                                value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"
                            >
                        </div>

                        <div class="form-group">
                            <label for="address">Home Address <span class="required">*</span></label>
                            <textarea 
                                id="address" 
                                name="address" 
                                class="form-control"
                                rows="3" 
                                placeholder="Enter your complete home address"
                                required
                            ><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Create Account
                    </button>
                </form>

                <div class="auth-divider">
                    <span>Already have an account?</span>
                </div>

                <p class="auth-link">
                    <a href="index.php?page=login">Sign in here</a>
                </p>
            </div>

            <!-- Footer -->
            <p class="page-footer">
                &copy; <?php echo date('Y'); ?> Hostel Management System. All rights reserved.
            </p>

        </div>
    </div>

    <script src="app/Views/Auth/js/SignupView.js"></script>
</body>
</html>