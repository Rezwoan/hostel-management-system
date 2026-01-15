<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Hostel System</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (!empty($error_msg)): ?>
        <p style="color: red;">
            <?php echo htmlspecialchars($error_msg); ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <br>
    <a href="index.php?page=signup">Create a Student Account</a>

</body>
</html>