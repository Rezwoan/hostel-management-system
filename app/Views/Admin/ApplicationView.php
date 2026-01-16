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
                    <?php 
                    $appStatus = $data['application']['status'] ?? '';
                    $canReview = in_array($appStatus, ['DRAFT', 'SUBMITTED']);
                    ?>
                    <?php if ($canReview): ?>
                    <div class="form-card">
                        <h3>Process Application</h3>
                        <form action="index.php?page=admin_applications" method="POST" id="reviewForm">
                            <input type="hidden" name="form_action" value="review_application">
                            <input type="hidden" name="id" value="<?php echo (int)$data['application']['id']; ?>">
                            <input type="hidden" name="status" id="reviewStatus" value="">
                            
                            <div class="form-group" id="rejectReasonGroup" style="display: none;">
                                <label for="reject_reason">Rejection Reason <span class="required">*</span></label>
                                <textarea id="reject_reason" name="reject_reason" class="form-control" rows="3" placeholder="Explain why the application is being rejected"></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-success" onclick="submitReview('APPROVED')">Approve</button>
                                <button type="button" class="btn btn-danger" onclick="showRejectForm()">Reject</button>
                                <a href="index.php?page=admin_applications" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
                    </div>
                    
                    <script>
                        function submitReview(status) {
                            document.getElementById("reviewStatus").value = status;
                            if (status === 'REJECTED') {
                                let reason = document.getElementById("reject_reason").value.trim();
                                if (!reason) {
                                    alert("Please provide a rejection reason.");
                                    return;
                                }
                            }
                            document.getElementById("reviewForm").submit();
                        }
                        
                        function showRejectForm() {
                            document.getElementById("rejectReasonGroup").style.display = "block";
                            // Replace reject button with confirm reject button
                            let actions = document.querySelector(".form-actions");
                            actions.innerHTML = '<button type="button" class="btn btn-danger" onclick="submitReview(\'REJECTED\')">Confirm Rejection</button>' +
                                '<button type="button" class="btn btn-secondary" onclick="cancelReject()">Cancel</button>';
                        }
                        
                        function cancelReject() {
                            document.getElementById("rejectReasonGroup").style.display = "none";
                            document.getElementById("reject_reason").value = "";
                            let actions = document.querySelector(".form-actions");
                            actions.innerHTML = '<button type="button" class="btn btn-success" onclick="submitReview(\'APPROVED\')">Approve</button>' +
                                '<button type="button" class="btn btn-danger" onclick="showRejectForm()">Reject</button>' +
                                '<a href="index.php?page=admin_applications" class="btn btn-secondary">Back to List</a>';
                        }
                    </script>
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
                                <option value="DRAFT" <?php echo (isset($_GET['status']) && $_GET['status'] === 'DRAFT') ? 'selected' : ''; ?>>Draft</option>
                                <option value="SUBMITTED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'SUBMITTED') ? 'selected' : ''; ?>>Submitted</option>
                                <option value="APPROVED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'APPROVED') ? 'selected' : ''; ?>>Approved</option>
                                <option value="REJECTED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'REJECTED') ? 'selected' : ''; ?>>Rejected</option>
                                <option value="CANCELLED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'CANCELLED') ? 'selected' : ''; ?>>Cancelled</option>
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
                    
                    <!-- Live Search -->
                    <div class="search-box" style="margin-bottom: 15px;">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search applications...">
                    </div>
                    
                    <!-- Status Legend -->
                    <div class="status-legend" style="margin-bottom: 15px; font-size: 13px; color: #666;">
                        <strong>Status Guide:</strong>
                        <span class="badge badge-secondary">DRAFT</span> = Started, not yet submitted |
                        <span class="badge badge-warning">SUBMITTED</span> = Awaiting review |
                        <span class="badge badge-success">APPROVED</span> = Ready for allocation |
                        <span class="badge badge-danger">REJECTED</span> = Application denied
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="applicationsTable">
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
                                            <?php 
                                            $status = $app['status'] ?? '';
                                            $statusClass = 'badge-secondary';
                                            if ($status === 'SUBMITTED') $statusClass = 'badge-warning';
                                            elseif ($status === 'APPROVED') $statusClass = 'badge-success';
                                            elseif ($status === 'REJECTED') $statusClass = 'badge-danger';
                                            elseif ($status === 'CANCELLED') $statusClass = 'badge-secondary';
                                            $canReview = in_array($status, ['DRAFT', 'SUBMITTED']);
                                            ?>
                                            <tr data-id="<?php echo (int)$app['id']; ?>">
                                                <td><?php echo (int)$app['id']; ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($app['student_name'] ?? ''); ?><br>
                                                    <small><?php echo htmlspecialchars($app['student_email'] ?? ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($app['hostel_name'] ?? 'Any'); ?></td>
                                                <td><?php echo htmlspecialchars($app['room_type_name'] ?? 'Any'); ?></td>
                                                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($app['created_at'] ?? 'now'))); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $statusClass; ?>" id="status-<?php echo (int)$app['id']; ?>"><?php echo htmlspecialchars($status); ?></span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_applications&action=view&id=<?php echo (int)$app['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <?php if ($canReview): ?>
                                                            <button type="button" class="btn btn-sm btn-success" onclick="quickApprove(<?php echo (int)$app['id']; ?>, this)" title="Quick Approve">✓</button>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal(<?php echo (int)$app['id']; ?>)" title="Reject">✗</button>
                                                        <?php elseif ($status === 'APPROVED'): ?>
                                                            <a href="index.php?page=admin_allocations&action=add" class="btn btn-sm btn-primary" title="Create Allocation">Allocate</a>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteApplication(<?php echo (int)$app['id']; ?>, this)" title="Delete">🗑</button>
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
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="confirmTitle">Confirm Action</h3>
            <p id="confirmMessage">Are you sure?</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
    
    <!-- Reject Modal with Reason -->
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Reject Application</h3>
            <p>Please provide a reason for rejection:</p>
            <textarea id="rejectReasonInput" class="form-control" rows="3" placeholder="Enter rejection reason..."></textarea>
            <input type="hidden" id="rejectAppId" value="">
            <div class="modal-actions" style="margin-top: 15px;">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitReject()">Reject Application</button>
            </div>
        </div>
    </div>
    
    <style>
        .inline-status-select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
    </style>
    
    <script>
        let pendingAction = null;
        
        function showConfirm(title, message, callback) {
            document.getElementById("confirmTitle").textContent = title;
            document.getElementById("confirmMessage").textContent = message;
            document.getElementById("confirmModal").classList.add("open");
            pendingAction = callback;
        }
        
        function closeModal() {
            document.getElementById("confirmModal").classList.remove("open");
            pendingAction = null;
        }
        
        document.getElementById("confirmBtn").addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
        
        // Delete application via AJAX
        function deleteApplication(id, btn) {
            let rowToDelete = btn.closest("tr");
            
            showConfirm("Delete Application", "Are you sure you want to delete this application?", function() {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "app/Controllers/Api/delete_application.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            try {
                                let response = JSON.parse(this.responseText);
                                if (response.success) {
                                    rowToDelete.style.transition = "opacity 0.3s";
                                    rowToDelete.style.opacity = "0";
                                    setTimeout(function() { rowToDelete.remove(); }, 300);
                                } else {
                                    alert("Error: " + response.error);
                                }
                            } catch (e) {
                                alert("Server error: " + this.responseText);
                            }
                        } else {
                            alert("Request failed with status: " + this.status);
                        }
                    }
                };
                
                xhr.send("id=" + id);
            });
        }
        
        // Quick Approve via AJAX
        function quickApprove(id, btn) {
            let row = btn.closest("tr");
            
            showConfirm("Approve Application", "Are you sure you want to approve this application?", function() {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "app/Controllers/Api/update_application_status.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        try {
                            let response = JSON.parse(this.responseText);
                            if (response.success) {
                                // Update status badge
                                let statusBadge = document.getElementById("status-" + id);
                                statusBadge.className = "badge badge-success";
                                statusBadge.textContent = "APPROVED";
                                
                                // Replace action buttons
                                let actionsCell = row.querySelector(".action-btns");
                                actionsCell.innerHTML = 
                                    '<a href="index.php?page=admin_applications&action=view&id=' + id + '" class="btn btn-sm btn-secondary">View</a> ' +
                                    '<a href="index.php?page=admin_allocations&action=add" class="btn btn-sm btn-primary">Allocate</a> ' +
                                    '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteApplication(' + id + ', this)">🗑</button>';
                            } else {
                                alert("Error: " + response.error);
                            }
                        } catch (e) {
                            alert("Server error: " + this.responseText);
                        }
                    }
                };
                
                xhr.send("id=" + id + "&status=APPROVED");
            });
        }
        
        // Show Reject Modal
        function showRejectModal(id) {
            document.getElementById("rejectAppId").value = id;
            document.getElementById("rejectReasonInput").value = "";
            document.getElementById("rejectModal").classList.add("open");
        }
        
        function closeRejectModal() {
            document.getElementById("rejectModal").classList.remove("open");
        }
        
        // Submit Reject via AJAX
        function submitReject() {
            let id = document.getElementById("rejectAppId").value;
            let reason = document.getElementById("rejectReasonInput").value.trim();
            
            if (!reason) {
                alert("Please provide a rejection reason.");
                return;
            }
            
            let row = document.querySelector('tr[data-id="' + id + '"]');
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "app/Controllers/Api/update_application_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        let response = JSON.parse(this.responseText);
                        if (response.success) {
                            closeRejectModal();
                            
                            // Update status badge
                            let statusBadge = document.getElementById("status-" + id);
                            statusBadge.className = "badge badge-danger";
                            statusBadge.textContent = "REJECTED";
                            
                            // Replace action buttons
                            let actionsCell = row.querySelector(".action-btns");
                            actionsCell.innerHTML = 
                                '<a href="index.php?page=admin_applications&action=view&id=' + id + '" class="btn btn-sm btn-secondary">View</a> ' +
                                '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteApplication(' + id + ', this)">🗑</button>';
                        } else {
                            alert("Error: " + response.error);
                        }
                    } catch (e) {
                        alert("Server error: " + this.responseText);
                    }
                }
            };
            
            xhr.send("id=" + id + "&status=REJECTED&reject_reason=" + encodeURIComponent(reason));
        }
        
        // Table search
        document.getElementById("tableSearch")?.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#applicationsTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    </script>
</body>
</html>