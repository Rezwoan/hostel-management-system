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
    <?php include __DIR__ . '/partials/head-meta.php'; ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
    <script src="public/assets/js/table-filter.js" defer></script>
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
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['complaint']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                elseif ($status === 'OPEN') $statusClass = 'badge-warning';
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
                    </div>
                    
                    <!-- Update Status Form -->
                    <?php if (($data['complaint']['status'] ?? '') !== 'CLOSED'): ?>
                    <div class="form-card">
                        <h3>Update Complaint Status</h3>
                        <form action="index.php?page=admin_complaints" method="POST">
                            <input type="hidden" name="form_action" value="update_complaint_status">
                            <input type="hidden" name="id" value="<?php echo (int)$data['complaint']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="OPEN" <?php echo ($data['complaint']['status'] ?? '') === 'OPEN' ? 'selected' : ''; ?>>Open</option>
                                    <option value="IN_PROGRESS" <?php echo ($data['complaint']['status'] ?? '') === 'IN_PROGRESS' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="RESOLVED" <?php echo ($data['complaint']['status'] ?? '') === 'RESOLVED' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="CLOSED" <?php echo ($data['complaint']['status'] ?? '') === 'CLOSED' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Status</button>
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
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="complaintSearch" class="form-control" placeholder="Search complaints..." data-table-search="complaintsTable">
                            <select id="statusFilter" class="form-control" data-filter-table="complaintsTable" data-filter-column="4">
                                <option value="">All Status</option>
                                <option value="OPEN">Open</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="RESOLVED">Resolved</option>
                                <option value="CLOSED">Closed</option>
                            </select>
                            <select id="categoryFilter" class="form-control" data-filter-table="complaintsTable" data-filter-column="2">
                                <option value="">All Categories</option>
                                <?php if (!empty($data['categories'])): ?>
                                    <?php foreach ($data['categories'] as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['name']); ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Stats Summary -->
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['open'] ?? 0); ?></div>
                            <div class="stat-label">Open</div>
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
                            <table class="table" id="complaintsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Student</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="complaintsTableBody">
                                    <?php if (!empty($data['complaints'])): ?>
                                        <?php foreach ($data['complaints'] as $complaint): ?>
                                            <tr data-id="<?php echo (int)$complaint['id']; ?>">
                                                <td><?php echo (int)$complaint['id']; ?></td>
                                                <td><?php echo htmlspecialchars(substr($complaint['subject'], 0, 30)); ?><?php echo strlen($complaint['subject']) > 30 ? '...' : ''; ?></td>
                                                <td><?php echo htmlspecialchars($complaint['category_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($complaint['student_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $complaint['status'] ?? 'OPEN';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'RESOLVED') $statusClass = 'badge-success';
                                                    elseif ($status === 'CLOSED') $statusClass = 'badge-secondary';
                                                    elseif ($status === 'IN_PROGRESS') $statusClass = 'badge-info';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                                                </td>
                                                <td><?php echo htmlspecialchars($complaint['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_complaints&action=view&id=<?php echo (int)$complaint['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                    <form method="POST" action="index.php?page=admin_complaints" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                                                        <input type="hidden" name="form_action" value="delete_complaint">
                                                        <input type="hidden" name="id" value="<?php echo (int)$complaint['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
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
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="confirmTitle">Confirm Action</h3>
            <p id="confirmMessage">Are you sure?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-danger" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
    
    <script>
        let pendingAction = null;
        let pendingRow = null;
        
        function showConfirm(title, message, callback) {
            document.getElementById("confirmTitle").textContent = title;
            document.getElementById("confirmMessage").textContent = message;
            document.getElementById("confirmModal").classList.add("open");
            pendingAction = callback;
        }
        
        function closeModal() {
            document.getElementById("confirmModal").classList.remove("open");
            pendingAction = null;
            pendingRow = null;
        }
        
        document.getElementById("confirmBtn").addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
        
        // Simple table search filter
        document.getElementById("tableSearch")?.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#complaintsTableBody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    </script>
</body>
</html>
