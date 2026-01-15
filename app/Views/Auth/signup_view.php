<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
</head>
<body>

    <h2>Student Registration</h2>

    <?php if (!empty($success_msg)): ?>
        <p style="color: green; font-weight: bold;">
            <?php echo $success_msg; ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <p style="color: red; font-weight: bold;">
            <?php echo htmlspecialchars($error_msg); ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">
        <h3>Login Details</h3>
        <label>Full Name:</label><br>
        <input type="text" name="full_name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone"><br><br>

        <h3>Student Details</h3>
        <label>Student ID (Roll No):</label><br>
        <input type="text" name="student_id" required><br><br>

        <label>Department:</label><br>
        <input type="text" name="department"><br><br>

        <label>Session:</label><br>
        <input type="text" name="session"><br><br>

        <button type="submit">Register</button>
    </form>

    <br>
    <a href="login.php">Back to Login</a>

</body>
</html>