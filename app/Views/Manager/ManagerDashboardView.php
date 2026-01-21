<?php
// Manager Dashboard View
$page = 'manager_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
    <?php include __DIR__ . '/../Admin/partials/head-meta.php'; ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>Manager Dashboard</h2>
                </div>
                
                <!-- My Hostels -->
                <?php if (!empty($data['hostels'])): ?>
                    <div class="table-card" style="margin-bottom: 20px;">
                        <div class="table-card-header">
                            <h3>🏢 My Hostels</h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($data['hostels'] as $hostel): ?>
                                <div class="finance-item">
                                    <span><strong><?php echo htmlspecialchars($hostel['name']); ?></strong> (<?php echo htmlspecialchars($hostel['code']); ?>)</span>
                                    <span class="badge badge-<?php echo $hostel['status'] === 'ACTIVE' ? 'success' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($hostel['status']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Key Metrics -->
                <div class="stats-grid stats-grid-4">
                    <div class="stat-card stat-card-success">
                        <h3>Occupancy</h3>
                        <div class="stat-value">
                            <?php echo (int)($data['stats']['occupied_seats'] ?? 0); ?> / 
                            <?php echo (int)($data['stats']['total_seats'] ?? 0); ?>
                        </div>
                        <div class="stat-label"><?php echo ($data['stats']['occupancy_rate'] ?? 0); ?>% filled</div>
                    </div>
                    <div class="stat-card stat-card-info">
                        <h3>Available</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['available_seats'] ?? 0); ?></div>
                        <div class="stat-label">seats ready</div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <h3>Pending</h3>
                        <div class="stat-value">
                            <?php echo (int)($data['stats']['pending_applications'] ?? 0); ?> / 
                            <?php echo (int)($data['stats']['open_complaints'] ?? 0); ?>
                        </div>
                        <div class="stat-label">apps / complaints</div>
                    </div>
                    <div class="stat-card stat-card-primary">
                        <h3>Students</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['total_students'] ?? 0); ?></div>
                        <div class="stat-label">currently residing</div>
                    </div>
                </div>
                
                <!-- Two Column Layout -->
                <div class="dashboard-grid">
                    <!-- Left Column -->
                    <div class="dashboard-column">
                        <!-- Pending Applications -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>📝 Pending Applications</h3>
                                <a href="index.php?page=manager_applications" class="btn btn-sm btn-secondary">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-compact">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Room Type</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['recent_applications'])): ?>
                                            <?php foreach ($data['recent_applications'] as $app): ?>
                                                <tr onclick="window.location='index.php?page=manager_applications&action=view&id=<?php echo (int)$app['id']; ?>'" style="cursor: pointer;">
                                                    <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($app['room_type_name']); ?></td>
                                                    <td><?php echo date('M j', strtotime($app['submitted_at'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="empty-state">No pending applications</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="dashboard-column">
                        <!-- Open Complaints -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>📢 Open Complaints</h3>
                                <a href="index.php?page=manager_complaints" class="btn btn-sm btn-secondary">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-compact">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['recent_complaints'])): ?>
                                            <?php foreach ($data['recent_complaints'] as $complaint): ?>
                                                <tr onclick="window.location='index.php?page=manager_complaints&action=view&id=<?php echo (int)$complaint['id']; ?>'" style="cursor: pointer;">
                                                    <td><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 30)); ?><?php echo strlen($complaint['subject']) > 30 ? '...' : ''; ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo $complaint['status'] === 'OPEN' ? 'warning' : 'info'; ?>">
                                                            <?php echo htmlspecialchars($complaint['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="empty-state">No open complaints</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="table-card" style="margin-top: 20px;">
                    <div class="table-card-header">
                        <h3>⚡ Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="index.php?page=manager_applications" class="btn btn-primary">Review Applications</a>
                            <a href="index.php?page=manager_allocations&action=add" class="btn btn-primary">Create Allocation</a>
                            <a href="index.php?page=manager_complaints" class="btn btn-primary">Handle Complaints</a>
                            <a href="index.php?page=manager_notices&action=add" class="btn btn-primary">Post Notice</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
