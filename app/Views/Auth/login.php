<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$err = isset($_GET['err']) ? (string)$_GET['err'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($err === '1'): ?>
        <p style="color:red;">Invalid email or password.</p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login_post">
        <div>
            <label>Email</label><br>
            <input name="email" type="email" required>
        </div>
        <br>
        <div>
            <label>Password</label><br>
            <input name="password" type="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
