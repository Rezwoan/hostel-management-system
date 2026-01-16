<?php
// Admin Dashboard View
$page = 'admin_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Admin</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
                <div class="header-actions">
                    <div class="user-info">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    </div>
                </div>
            </header>
            
            <div class="admin-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['total_users'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Students</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['total_students'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Hostels</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['total_hostels'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Rooms</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['total_rooms'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Active Allocations</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['active_allocations'] ?? 0); ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Pending Applications</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['pending_applications'] ?? 0); ?></div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="table-card" style="margin-bottom: 30px;">
                    <div class="table-card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="quick-actions">
                            <a href="index.php?page=admin_users&action=add" class="btn btn-primary">Add User</a>
                            <a href="index.php?page=admin_hostels&action=add" class="btn btn-primary">Add Hostel</a>
                            <a href="index.php?page=admin_applications" class="btn btn-secondary">View Applications</a>
                            <a href="index.php?page=admin_complaints" class="btn btn-secondary">View Complaints</a>
                            <a href="index.php?page=admin_notices&action=add" class="btn btn-success">Create Notice</a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>Recent Activity</h3>
                        <a href="index.php?page=admin_audit_logs" class="btn btn-sm btn-secondary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Entity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['recent_logs'])): ?>
                                    <?php foreach ($data['recent_logs'] as $log): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($log['created_at'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($log['user_name'] ?? 'System'); ?></td>
                                            <td><?php echo htmlspecialchars($log['action'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($log['entity_type'] ?? ''); ?> #<?php echo (int)($log['entity_id'] ?? 0); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="empty-state">No recent activity</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
