<?php
// Admin Application Management View
$page = 'admin_applications';
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
            </header>
            
            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'view' && isset($data['application'])): ?>
                    <!-- View Application Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_applications">Applications</a>
                        <span>/</span>
                        <span class="current">View Application</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Application Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Application ID</div>
                            <div class="detail-value"><?php echo (int)$data['application']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['student_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['student_email'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel Preference</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['hostel_name'] ?? 'Any'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Type Preference</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['room_type_name'] ?? 'Any'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Application Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['created_at'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['application']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'APPROVED') $statusClass = 'badge-success';
                                elseif ($status === 'REJECTED') $statusClass = 'badge-danger';
                                elseif ($status === 'PENDING') $statusClass = 'badge-warning';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($data['application']['remarks'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Remarks</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['application']['remarks']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Action Forms -->
                    <?php if (($data['application']['status'] ?? '') === 'PENDING'): ?>
                    <div class="form-card">
                        <h3>Process Application</h3>
                        <form action="index.php?page=admin_applications" method="POST">
                            <input type="hidden" name="id" value="<?php echo (int)$data['application']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control" rows="3" placeholder="Add any remarks about this application"></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" name="form_action" value="approve_application" class="btn btn-success">Approve</button>
                                <button type="submit" name="form_action" value="reject_application" class="btn btn-danger">Reject</button>
                                <a href="index.php?page=admin_applications" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="form-actions">
                        <a href="index.php?page=admin_applications" class="btn btn-secondary">Back to List</a>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Applications List -->
                    <div class="page-header">
                        <h2>All Applications</h2>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_applications">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="PENDING" <?php echo (isset($_GET['status']) && $_GET['status'] === 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                                <option value="APPROVED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'APPROVED') ? 'selected' : ''; ?>>Approved</option>
                                <option value="REJECTED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'REJECTED') ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <select name="hostel_id" class="form-control">
                                <option value="">All Hostels</option>
                                <?php if (!empty($data['hostels'])): ?>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo (int)$hostel['id']; ?>" <?php echo (isset($_GET['hostel_id']) && $_GET['hostel_id'] == $hostel['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </form>
                    </div>
                    
                    <!-- Stats Summary -->
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['pending'] ?? 0); ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['approved'] ?? 0); ?></div>
                            <div class="stat-label">Approved</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['rejected'] ?? 0); ?></div>
                            <div class="stat-label">Rejected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['total'] ?? 0); ?></div>
                            <div class="stat-label">Total</div>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Hostel Pref.</th>
                                        <th>Room Type</th>
                                        <th>Applied On</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['applications'])): ?>
                                        <?php foreach ($data['applications'] as $app): ?>
                                            <tr>
                                                <td><?php echo (int)$app['id']; ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($app['student_name'] ?? ''); ?><br>
                                                    <small><?php echo htmlspecialchars($app['student_email'] ?? ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($app['hostel_name'] ?? 'Any'); ?></td>
                                                <td><?php echo htmlspecialchars($app['room_type_name'] ?? 'Any'); ?></td>
                                                <td><?php echo htmlspecialchars($app['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $app['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'APPROVED') $statusClass = 'badge-success';
                                                    elseif ($status === 'REJECTED') $statusClass = 'badge-danger';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_applications&action=view&id=<?php echo (int)$app['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <?php if ($status === 'PENDING'): ?>
                                                            <form action="index.php?page=admin_applications" method="POST" style="display:inline;">
                                                                <input type="hidden" name="form_action" value="approve_application">
                                                                <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No applications found</td>
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
