<?php
// Admin Complaint Management View
$page = 'admin_complaints';
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
                
                <?php if ($action === 'view' && isset($data['complaint'])): ?>
                    <!-- View Complaint Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_complaints">Complaints</a>
                        <span>/</span>
                        <span class="current">View Complaint</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Complaint #<?php echo (int)$data['complaint']['id']; ?></h3>
                        <div class="detail-row">
                            <div class="detail-label">Subject</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['subject'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['category_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Submitted By</div>
                            <div class="detail-value">
                                <?php echo htmlspecialchars($data['complaint']['student_name'] ?? ''); ?><br>
                                <small><?php echo htmlspecialchars($data['complaint']['student_email'] ?? ''); ?></small>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel/Room</div>
                            <div class="detail-value">
                                <?php echo htmlspecialchars($data['complaint']['hostel_name'] ?? 'N/A'); ?>
                                <?php if (!empty($data['complaint']['room_no'])): ?>
                                    / Room <?php echo htmlspecialchars($data['complaint']['room_no']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Submitted On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['created_at'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Priority</div>
                            <div class="detail-value">
                                <?php 
                                $priority = $data['complaint']['priority'] ?? 'NORMAL';
                                $priorityClass = 'badge-info';
                                if ($priority === 'HIGH') $priorityClass = 'badge-danger';
                                elseif ($priority === 'LOW') $priorityClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $priorityClass; ?>">
                                    <?php echo htmlspecialchars($priority); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['complaint']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['complaint']['description'] ?? '')); ?></div>
                        </div>
                        <?php if (!empty($data['complaint']['resolution'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Resolution</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['complaint']['resolution'])); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Update Status Form -->
                    <?php if (($data['complaint']['status'] ?? '') !== 'CLOSED'): ?>
                    <div class="form-card">
                        <h3>Update Complaint</h3>
                        <form action="index.php?page=admin_complaints" method="POST">
                            <input type="hidden" name="form_action" value="update_complaint">
                            <input type="hidden" name="id" value="<?php echo (int)$data['complaint']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="PENDING" <?php echo ($data['complaint']['status'] ?? '') === 'PENDING' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="IN_PROGRESS" <?php echo ($data['complaint']['status'] ?? '') === 'IN_PROGRESS' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="RESOLVED" <?php echo ($data['complaint']['status'] ?? '') === 'RESOLVED' ? 'selected' : ''; ?>>Resolved</option>
                                        <option value="CLOSED" <?php echo ($data['complaint']['status'] ?? '') === 'CLOSED' ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select id="priority" name="priority" class="form-control">
                                        <option value="LOW" <?php echo ($data['complaint']['priority'] ?? '') === 'LOW' ? 'selected' : ''; ?>>Low</option>
                                        <option value="NORMAL" <?php echo ($data['complaint']['priority'] ?? '') === 'NORMAL' ? 'selected' : ''; ?>>Normal</option>
                                        <option value="HIGH" <?php echo ($data['complaint']['priority'] ?? '') === 'HIGH' ? 'selected' : ''; ?>>High</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="resolution">Resolution/Response</label>
                                <textarea id="resolution" name="resolution" class="form-control" rows="4"><?php echo htmlspecialchars($data['complaint']['resolution'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Complaint</button>
                                <a href="index.php?page=admin_complaints" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="form-actions">
                        <a href="index.php?page=admin_complaints" class="btn btn-secondary">Back to List</a>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Complaints List -->
                    <div class="page-header">
                        <h2>All Complaints</h2>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_complaints">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="PENDING" <?php echo (isset($_GET['status']) && $_GET['status'] === 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                                <option value="IN_PROGRESS" <?php echo (isset($_GET['status']) && $_GET['status'] === 'IN_PROGRESS') ? 'selected' : ''; ?>>In Progress</option>
                                <option value="RESOLVED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'RESOLVED') ? 'selected' : ''; ?>>Resolved</option>
                                <option value="CLOSED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'CLOSED') ? 'selected' : ''; ?>>Closed</option>
                            </select>
                            <select name="category_id" class="form-control">
                                <option value="">All Categories</option>
                                <?php if (!empty($data['categories'])): ?>
                                    <?php foreach ($data['categories'] as $cat): ?>
                                        <option value="<?php echo (int)$cat['id']; ?>" <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <select name="priority" class="form-control">
                                <option value="">All Priority</option>
                                <option value="HIGH" <?php echo (isset($_GET['priority']) && $_GET['priority'] === 'HIGH') ? 'selected' : ''; ?>>High</option>
                                <option value="NORMAL" <?php echo (isset($_GET['priority']) && $_GET['priority'] === 'NORMAL') ? 'selected' : ''; ?>>Normal</option>
                                <option value="LOW" <?php echo (isset($_GET['priority']) && $_GET['priority'] === 'LOW') ? 'selected' : ''; ?>>Low</option>
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
                            <div class="stat-value"><?php echo (int)($data['stats']['in_progress'] ?? 0); ?></div>
                            <div class="stat-label">In Progress</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['resolved'] ?? 0); ?></div>
                            <div class="stat-label">Resolved</div>
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
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Student</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['complaints'])): ?>
                                        <?php foreach ($data['complaints'] as $complaint): ?>
                                            <tr>
                                                <td><?php echo (int)$complaint['id']; ?></td>
                                                <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 30)); ?><?php echo strlen($complaint['subject']) > 30 ? '...' : ''; ?></td>
                                                <td><?php echo htmlspecialchars($complaint['category_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($complaint['student_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $priority = $complaint['priority'] ?? 'NORMAL';
                                                    $priorityClass = 'badge-info';
                                                    if ($priority === 'HIGH') $priorityClass = 'badge-danger';
                                                    elseif ($priority === 'LOW') $priorityClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $priorityClass; ?>">
                                                        <?php echo htmlspecialchars($priority); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $complaint['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                                    elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                                    elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($complaint['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_complaints&action=view&id=<?php echo (int)$complaint['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="empty-state">No complaints found</td>
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
