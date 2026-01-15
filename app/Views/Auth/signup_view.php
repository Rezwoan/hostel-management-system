<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
</head>
<body>

    <h2>Student Registration</h2>

    <?php if (!empty($success_msg)): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 15px;">
            <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
            <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        
        <h3>1. Login Details</h3>
        <label>Full Name: *</label><br>
        <input type="text" name="full_name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"><br><br>

        <label>Email: *</label><br>
        <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br><br>

        <label>Password: *</label><br>
        <input type="password" name="password" required><br><br>

        <label>Phone: *</label><br>
        <input type="text" name="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"><br><br>

        <h3>2. Academic Details</h3>
        <label>Student ID (Format: XX-XXXXX-X): *</label><br>
        <input type="text" name="student_id" placeholder="23-12345-1" required value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>"><br><br>

        <label>Department: *</label><br>
        <select name="department" required>
            <option value="">-- Select Department --</option>
            <option value="CSE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'CSE') ? 'selected' : ''; ?>>CSE (Computer Science & Engineering)</option>
            <option value="EEE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'EEE') ? 'selected' : ''; ?>>EEE (Electrical & Electronic Engineering)</option>
            <option value="IPE" <?php echo (isset($_POST['department']) && $_POST['department'] === 'IPE') ? 'selected' : ''; ?>>IPE (Industrial & Production Engineering)</option>
            <option value="BBA" <?php echo (isset($_POST['department']) && $_POST['department'] === 'BBA') ? 'selected' : ''; ?>>BBA (Bachelor of Business Administration)</option>
            <option value="Architecture" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Architecture') ? 'selected' : ''; ?>>Architecture</option>
            <option value="English" <?php echo (isset($_POST['department']) && $_POST['department'] === 'English') ? 'selected' : ''; ?>>English</option>
            <option value="Law" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Law') ? 'selected' : ''; ?>>Law</option>
            <option value="Economics" <?php echo (isset($_POST['department']) && $_POST['department'] === 'Economics') ? 'selected' : ''; ?>>Economics</option>
        </select><br><br>

        <label>Session (e.g. 2025-2026): *</label><br>
        <input type="text" name="session" required value="<?php echo isset($_POST['session']) ? htmlspecialchars($_POST['session']) : ''; ?>"><br><br>

        <h3>3. Personal Details</h3>
        <label>Date of Birth: *</label><br>
        <input type="date" name="dob" required value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>"><br><br>

        <label>Home Address: *</label><br>
        <textarea name="address" rows="3" cols="30" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea><br><br>

        <button type="submit">Register</button>
    </form>

    <br>
    <a href="index.php?page=login">Back to Login</a>

</body>
</html>