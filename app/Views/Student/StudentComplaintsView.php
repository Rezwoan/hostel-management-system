<?php
// Student Complaints View
$page = 'student_complaints';
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
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'view' && $data['complaint']): ?>
                    <!-- View Complaint Detail -->
                    <div class="breadcrumb">
                        <a href="index.php?page=student_complaints">Complaints</a>
                        <span>/</span>
                        <span class="current">Complaint #<?php echo (int)$data['complaint']['id']; ?></span>
                    </div>
                    
                    <div class="detail-card">
                        <h3><?php echo htmlspecialchars($data['complaint']['subject'] ?? ''); ?></h3>
                        <div class="detail-row">
                            <div class="detail-label">Complaint ID</div>
                            <div class="detail-value"><?php echo (int)$data['complaint']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['category_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['complaint']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Submitted</div>
                            <div class="detail-value"><?php echo date('F d, Y h:i A', strtotime($data['complaint']['created_at'] ?? '')); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['complaint']['description'] ?? '')); ?></div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="index.php?page=student_complaints" class="btn btn-secondary">Back to List</a>
                    </div>
                    
                <?php elseif ($action === 'add'): ?>
                    <!-- Create Complaint Form -->
                    <div class="page-header">
                        <h2>File a Complaint</h2>
                    </div>
                    
                    <div class="form-card">
                        <form action="index.php?page=student_complaints" method="POST">
                            <input type="hidden" name="form_action" value="create_complaint">
                            
                            <div class="form-group">
                                <label for="category_id">Category <span class="required">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($data['categories'] as $cat): ?>
                                        <option value="<?php echo (int)$cat['id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject <span class="required">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" maxlength="200" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea name="description" id="description" rows="6" class="form-control" required></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Submit Complaint</button>
                                <a href="index.php?page=student_complaints" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- List Complaints -->
                    <div class="page-header">
                        <h2>My Complaints</h2>
                        <a href="index.php?page=student_complaints&action=add" class="btn btn-primary">+ File New Complaint</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['complaints'])): ?>
                                        <?php foreach ($data['complaints'] as $comp): ?>
                                            <tr>
                                                <td><?php echo (int)$comp['id']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($comp['created_at'] ?? '')); ?></td>
                                                <td><?php echo htmlspecialchars($comp['category_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($comp['subject'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $comp['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                                    elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                                    elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="index.php?page=student_complaints&action=view&id=<?php echo (int)$comp['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No complaints yet.</td>
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
    
    <style>
    .complaint-thread {
        padding: 20px;
    }
    .message-item {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .message-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    .message-time {
        color: #6c757d;
        font-size: 0.9em;
    }
    .message-body {
        line-height: 1.6;
    }
    .breadcrumb {
        margin-bottom: 20px;
        font-size: 14px;
    }
    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb span {
        margin: 0 8px;
        color: #6c757d;
    }
    </style>
</body>
</html>
