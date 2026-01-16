<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Hostel Management System</title>
    <?php 
    $pageTitle = 'Student Dashboard';
    $pageDescription = 'Student portal for hostel management. View your room allocation, file complaints, and manage your hostel stay.';
    include __DIR__ . '/../partials/meta.php'; 
    ?>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
    
    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>

    <hr>

    <h3>Quick Actions</h3>
    <ul>
        <li><a href="index.php?page=profile">My Profile</a></li>
        <li><a href="index.php?page=allocations">My Room Allocation</a></li>
        <li><a href="index.php?page=complaints">File a Complaint</a></li>
    </ul>

    <br><br>
    
    <a href="index.php?page=logout" style="color: red;">Logout</a>

</body>
</html>