<?php
// Manager Student View
$page = 'manager_students';
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
                <?php if ($action === 'view' && isset($data['student'])): ?>
                    <!-- View Student Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_students">Students</a>
                        <span>/</span>
                        <span class="current">Student Details</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Student Information</h3>
                        <div style="display: flex; gap: 30px;">
                            <div style="flex: 1;">
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['student_id']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['email']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['phone'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['department']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Session</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['session_year']); ?></div>
                        </div>
                            </div>
                            
                            <?php if (!empty($data['student']['profile_picture'])): ?>
                            <div style="flex-shrink: 0;">
                                <div style="text-align: center;">
                                    <?php 
                                    $imagePath = $data['student']['profile_picture'];
                                    // If path doesn't start with public/, add it
                                    if (strpos($imagePath, 'public/') !== 0) {
                                        $imagePath = 'public/' . $imagePath;
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="Profile Picture" 
                                         style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; border: 3px solid #ddd;">
                                    <p style="margin-top: 10px; font-size: 12px; color: #666;">Profile Picture</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($data['allocation']): ?>
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Current Allocation</h3>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['hostel_name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room</div>
                            <div class="detail-value">Floor <?php echo htmlspecialchars($data['allocation']['floor_no']); ?> - Room <?php echo htmlspecialchars($data['allocation']['room_no']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Seat</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['seat_label']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['room_type_name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value"><?php echo date('Y-m-d', strtotime($data['allocation']['start_date'])); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['invoices'])): ?>
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Fee History</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Amount Due</th>
                                        <th>Paid</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['invoices'] as $invoice): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($invoice['period_name']); ?></td>
                                            <td>$<?php echo number_format($invoice['amount_due'], 2); ?></td>
                                            <td>$<?php echo number_format($invoice['paid_amount'], 2); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $invoice['status'] === 'PAID' ? 'success' : 'warning'; ?>">
                                                    <?php echo htmlspecialchars($invoice['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- List Students -->
                    <div class="page-header">
                        <h2>Students</h2>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <input type="text" id="searchInput" placeholder="Search by name, email, or student ID..." class="form-control">
                    </div>
                    
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>All Students</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Department</th>
                                        <th>Hostel</th>
                                        <th>Room</th>
                                        <th>Seat</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['students'])): ?>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                                <td><?php echo htmlspecialchars($student['hostel_name']); ?></td>
                                                <td><?php echo htmlspecialchars($student['room_no']); ?></td>
                                                <td><?php echo htmlspecialchars($student['seat_label']); ?></td>
                                                <td>
                                                    <a href="index.php?page=manager_students&action=view&id=<?php echo (int)$student['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No students found</td>
                                        </tr>
                                    <?php endif; ?>
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
