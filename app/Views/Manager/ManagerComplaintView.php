<?php
// Manager Complaint View
$page = 'manager_complaints';
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
        
        <main class="admin-main">
            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'view' && isset($data['complaint'])): ?>
                    <!-- View Complaint -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_complaints">Complaints</a>
                        <span>/</span>
                        <span class="current">View Complaint</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Complaint Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Complaint ID</div>
                            <div class="detail-value"><?php echo (int)$data['complaint']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['student_name']); ?> (<?php echo htmlspecialchars($data['complaint']['student_id']); ?>)</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Category</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['category_name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Subject</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['subject']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['complaint']['description'])); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['complaint']['status'];
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
                            <div class="detail-label">Created</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['complaint']['created_at']); ?></div>
                        </div>
                    </div>
                    
                    <!-- Messages -->
                    <div class="table-card" style="margin-bottom: 20px;">
                        <div class="table-card-header">
                            <h3>Conversation</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['messages'])): ?>
                                <?php foreach ($data['messages'] as $msg): ?>
                                    <div class="message-item <?php echo $msg['sender_type'] === 'staff' ? 'message-staff' : 'message-student'; ?>" style="margin-bottom: 15px; padding: 10px; border-left: 3px solid <?php echo $msg['sender_type'] === 'staff' ? '#007bff' : '#28a745'; ?>; background: #f8f9fa;">
                                        <div style="font-weight: bold; margin-bottom: 5px;">
                                            <?php echo htmlspecialchars($msg['sender_name']); ?>
                                            <span style="font-size: 0.85em; color: #666;">- <?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></span>
                                        </div>
                                        <div><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="empty-state">No messages yet</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Add Response -->
                    <div class="form-card" style="margin-bottom: 20px;">
                        <h3>Add Response</h3>
                        <form action="index.php?page=manager_complaints" method="POST">
                            <input type="hidden" name="form_action" value="add_response">
                            <input type="hidden" name="complaint_id" value="<?php echo (int)$data['complaint']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="message">Your Response *</label>
                                <textarea name="message" id="message" required class="form-control" rows="4"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Send Response</button>
                        </form>
                    </div>
                    
                    <!-- Update Status -->
                    <div class="form-card">
                        <h3>Update Status</h3>
                        <form action="index.php?page=manager_complaints" method="POST">
                            <input type="hidden" name="form_action" value="update_complaint_status">
                            <input type="hidden" name="id" value="<?php echo (int)$data['complaint']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select name="status" id="status" required class="form-control">
                                    <option value="OPEN" <?php echo $data['complaint']['status'] === 'OPEN' ? 'selected' : ''; ?>>OPEN</option>
                                    <option value="IN_PROGRESS" <?php echo $data['complaint']['status'] === 'IN_PROGRESS' ? 'selected' : ''; ?>>IN_PROGRESS</option>
                                    <option value="RESOLVED" <?php echo $data['complaint']['status'] === 'RESOLVED' ? 'selected' : ''; ?>>RESOLVED</option>
                                    <option value="CLOSED" <?php echo $data['complaint']['status'] === 'CLOSED' ? 'selected' : ''; ?>>CLOSED</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Update Status</button>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- List Complaints -->
                    <div class="page-header">
                        <h2>Complaints</h2>
                        <span class="badge badge-warning"><?php echo (int)($data['open_count'] ?? 0); ?> Open/In Progress</span>
                    </div>
                    
                    <!-- Stats Grid -->
                    <?php if (isset($data['stats'])): ?>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Open</div>
                            <div class="stat-value"><?php echo (int)$data['stats']['open']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">In Progress</div>
                            <div class="stat-value"><?php echo (int)$data['stats']['in_progress']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Resolved</div>
                            <div class="stat-value"><?php echo (int)$data['stats']['resolved']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Total</div>
                            <div class="stat-value"><?php echo (int)$data['stats']['total']; ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <input type="text" id="searchInput" placeholder="Search by student, subject..." class="form-control">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="OPEN">Open</option>
                            <option value="IN_PROGRESS">In Progress</option>
                            <option value="RESOLVED">Resolved</option>
                            <option value="CLOSED">Closed</option>
                        </select>
                        <?php if (isset($data['categories']) && count($data['categories']) > 0): ?>
                        <select id="categoryFilter" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($data['categories'] as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['name']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>All Complaints</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Category</th>
                                        <th>Subject</th>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['complaints'])): ?>
                                        <?php foreach ($data['complaints'] as $complaint): ?>
                                            <tr>
                                                <td><?php echo (int)$complaint['id']; ?></td>
                                                <td><?php echo htmlspecialchars($complaint['student_name']); ?></td>
                                                <td><?php echo htmlspecialchars($complaint['category_name']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 50)); ?><?php echo strlen($complaint['subject']) > 50 ? '...' : ''; ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($complaint['created_at'])); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $complaint['status'];
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                                    elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                                    elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="index.php?page=manager_complaints&action=view&id=<?php echo (int)$complaint['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No complaints found</td>
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
