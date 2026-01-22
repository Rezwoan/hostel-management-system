<?php
// Student Room View
$page = 'student_room';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
    <link rel="stylesheet" href="app/Views/Student/css/StudentRoomView.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>My Room</h2>
                </div>
                
                <?php if ($data['allocation']): ?>
                    <!-- Current Allocation -->
                    <div class="detail-card">
                        <h3>Current Allocation</h3>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Floor</div>
                            <div class="detail-value">Floor <?php echo htmlspecialchars($data['allocation']['floor_no'] ?? ''); ?> <?php echo !empty($data['allocation']['label']) ? '(' . htmlspecialchars($data['allocation']['label']) . ')' : ''; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['room_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Seat</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['seat_label'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['room_type_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Allocated Since</div>
                            <div class="detail-value"><?php echo date('F d, Y', strtotime($data['allocation']['start_date'] ?? '')); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value"><span class="badge badge-success">ACTIVE</span></div>
                        </div>
                    </div>
                    
                    <!-- Roommates -->
                    <?php if (!empty($data['roommates'])): ?>
                    <div class="table-card" style="margin-top: 20px;">
                        <div class="table-card-header">
                            <h3>Roommates</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Department</th>
                                        <th>Seat</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['roommates'] as $roommate): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($roommate['name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($roommate['student_id'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($roommate['department'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($roommate['seat_label'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($roommate['email'] ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- No Allocation -->
                    <div class="empty-state-card">
                        <div class="empty-state-icon">🏠</div>
                        <h3>No Room Assigned</h3>
                        <p>You don't have a room allocation yet.</p>
                        <a href="index.php?page=student_applications" class="btn btn-primary">Apply for a Room</a>
                    </div>
                <?php endif; ?>
                
                <!-- Allocation History -->
                <?php if (!empty($data['history'])): ?>
                <div class="table-card" style="margin-top: 20px;">
                    <div class="table-card-header">
                        <h3>Allocation History</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hostel</th>
                                    <th>Room</th>
                                    <th>Seat</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['history'] as $hist): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($hist['hostel_name'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($hist['room_no'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($hist['seat_label'] ?? ''); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($hist['start_date'] ?? '')); ?></td>
                                        <td><?php echo $hist['end_date'] ? date('M d, Y', strtotime($hist['end_date'])) : 'Present'; ?></td>
                                        <td>
                                            <?php if ($hist['status'] === 'ACTIVE'): ?>
                                                <span class="badge badge-success">ACTIVE</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">ENDED</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
