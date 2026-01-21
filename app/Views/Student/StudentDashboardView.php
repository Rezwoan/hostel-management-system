<?php
// Student Dashboard View
$page = 'student_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
                </div>
                
                <!-- Key Metrics -->
                <div class="stats-grid stats-grid-4">
                    <div class="stat-card stat-card-info">
                        <h3>Application</h3>
                        <div class="stat-value">
                            <?php 
                            $appStatus = $data['application']['status'] ?? 'NONE';
                            $statusClass = 'badge-secondary';
                            if ($appStatus === 'APPROVED') $statusClass = 'badge-success';
                            elseif ($appStatus === 'REJECTED') $statusClass = 'badge-danger';
                            elseif ($appStatus === 'SUBMITTED') $statusClass = 'badge-warning';
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo $appStatus === 'NONE' ? 'Not Applied' : htmlspecialchars($appStatus); ?>
                            </span>
                        </div>
                        <div class="stat-label">Current status</div>
                    </div>
                    
                    <div class="stat-card stat-card-success">
                        <h3>Room</h3>
                        <div class="stat-value">
                            <?php if ($data['allocation']): ?>
                                <?php echo htmlspecialchars($data['allocation']['room_no'] ?? 'N/A'); ?>
                            <?php else: ?>
                                <span class="badge badge-secondary">Not Assigned</span>
                            <?php endif; ?>
                        </div>
                        <div class="stat-label">
                            <?php if ($data['allocation']): ?>
                                <?php echo htmlspecialchars($data['allocation']['hostel_code'] ?? ''); ?> - Seat <?php echo htmlspecialchars($data['allocation']['seat_label'] ?? ''); ?>
                            <?php else: ?>
                                No allocation
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="stat-card stat-card-warning">
                        <h3>Pending Fees</h3>
                        <div class="stat-value">$<?php echo number_format((float)($data['stats']['total_due'] ?? 0), 2); ?></div>
                        <div class="stat-label"><?php echo (int)($data['stats']['pending_invoices'] ?? 0); ?> invoice(s)</div>
                    </div>
                    
                    <div class="stat-card stat-card-danger">
                        <h3>Complaints</h3>
                        <div class="stat-value"><?php echo (int)($data['stats']['open_complaints'] ?? 0); ?></div>
                        <div class="stat-label">Open</div>
                    </div>
                </div>
                
                <!-- Two Column Layout -->
                <div class="dashboard-grid">
                    <!-- Left Column -->
                    <div class="dashboard-column">
                        <!-- Quick Actions -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>⚡ Quick Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="action-buttons">
                                    <a href="index.php?page=student_applications" class="btn btn-primary btn-block">📝 Apply for Room</a>
                                    <a href="index.php?page=student_complaints&action=add" class="btn btn-secondary btn-block">📢 File Complaint</a>
                                    <a href="index.php?page=student_fees" class="btn btn-secondary btn-block">💰 View Fees</a>
                                    <a href="index.php?page=student_profile" class="btn btn-secondary btn-block">👤 Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Room Details -->
                        <?php if ($data['allocation']): ?>
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>🏠 My Room</h3>
                                <a href="index.php?page=student_room" class="btn btn-sm btn-secondary">View Details</a>
                            </div>
                            <div class="card-body">
                                <div class="finance-item">
                                    <span>Hostel</span>
                                    <strong><?php echo htmlspecialchars($data['allocation']['hostel_name'] ?? ''); ?></strong>
                                </div>
                                <div class="finance-item">
                                    <span>Room Number</span>
                                    <strong><?php echo htmlspecialchars($data['allocation']['room_no'] ?? ''); ?></strong>
                                </div>
                                <div class="finance-item">
                                    <span>Seat</span>
                                    <strong><?php echo htmlspecialchars($data['allocation']['seat_label'] ?? ''); ?></strong>
                                </div>
                                <div class="finance-item">
                                    <span>Since</span>
                                    <strong><?php echo date('M d, Y', strtotime($data['allocation']['start_date'] ?? '')); ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="dashboard-column">
                        <!-- Recent Notices -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>📌 Recent Notices</h3>
                                <a href="index.php?page=student_notices" class="btn btn-sm btn-secondary">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-compact">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Title</th>
                                            <th>Scope</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['notices'])): ?>
                                            <?php foreach ($data['notices'] as $notice): ?>
                                                <tr>
                                                    <td><?php echo date('M d', strtotime($notice['created_at'] ?? '')); ?></td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($notice['title'] ?? ''); ?></strong>
                                                        <?php if (strlen($notice['body'] ?? '') > 60): ?>
                                                            <br><small><?php echo htmlspecialchars(substr($notice['body'], 0, 60)) . '...'; ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($notice['scope'] === 'GLOBAL'): ?>
                                                            <span class="badge badge-info">Global</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary"><?php echo htmlspecialchars($notice['hostel_name'] ?? 'Hostel'); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="empty-state">No recent notices</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <style>
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .btn-block {
        width: 100%;
        text-align: center;
    }
    </style>
</body>
</html>