<?php
// Simple demo view for the manager role
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <h1><?php echo $pageTitle; ?></h1>
    <p><?php echo $message; ?></p>
    <nav>
        <a href="/index.php?role=admin">Admin</a> |
        <a href="/index.php?role=manager">Manager</a> |
        <a href="/index.php?role=student">Student</a>
    </nav>
</body>
</html>
