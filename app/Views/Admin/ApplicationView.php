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
                            console.log('submitReview called with status:', status);
                            
                            const statusField = document.getElementById("reviewStatus");
                            if (!statusField) {
                                alert('Error: Status field not found');
                                return;
                            }
                            
                            statusField.value = status;
                            console.log('Status field set to:', statusField.value);
                            
                            if (status === 'REJECTED') {
                                let reason = document.getElementById("reject_reason").value.trim();
                                if (!reason) {
                                    alert("Please provide a rejection reason.");
                                    return;
                                }
                            }
                            
                            if (status === 'APPROVED') {
                                if (!confirm('Are you sure you want to APPROVE this application? You can revert this later if needed.')) {
                                    return;
                                }
                            }
                            
                            const form = document.getElementById("reviewForm");
                            if (!form) {
                                alert('Error: Form not found');
                                return;
                            }
                            
                            console.log('Submitting form...');
                            form.submit();
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
                    <?php elseif (in_array($appStatus, ['APPROVED', 'REJECTED'])): ?>
                    <!-- Revert option for already reviewed applications -->
                    <div class="alert alert-info" style="margin-bottom: 15px;">
                        <strong>Note:</strong> This application has already been <?php echo strtolower($appStatus); ?>. 
                        If this was done by mistake, you can revert it back to SUBMITTED status for review.
                    </div>
                    <div class="form-card">
                        <h3>Revert Application Status</h3>
                        <form action="index.php?page=admin_applications" method="POST" onsubmit="return confirm('Are you sure you want to revert this application back to SUBMITTED status? This will allow you to review it again.')">
                            <input type="hidden" name="form_action" value="revert_application">
                            <input type="hidden" name="id" value="<?php echo (int)$data['application']['id']; ?>">
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning">Revert to SUBMITTED</button>
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
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="appSearch" class="form-control" placeholder="Search by name, email..." data-table-search="applicationsTable">
                            <select id="statusFilter" class="form-control" data-filter-table="applicationsTable" data-filter-column="5">
                                <option value="">All Status</option>
                                <option value="DRAFT">Draft</option>
                                <option value="SUBMITTED">Submitted</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                            <select id="hostelFilter" class="form-control" data-filter-table="applicationsTable" data-filter-column="2">
                                <option value="">All Hostels</option>
                                <?php if (!empty($data['hostels'])): ?>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo htmlspecialchars($hostel['name']); ?>">
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
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
                                            <tr data-id="<?php echo (int)$app['id']; ?>" data-student-id="<?php echo (int)$app['student_user_id']; ?>" data-hostel-id="<?php echo (int)$app['hostel_id']; ?>">
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
                                                            <form method="POST" action="index.php?page=admin_applications" style="display:inline;" onsubmit="return confirm('Are you sure you want to approve this application?');">
                                                                <input type="hidden" name="form_action" value="update_application_status">
                                                                <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                                <input type="hidden" name="status" value="APPROVED">
                                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                            </form>
                                                            <form method="POST" action="index.php?page=admin_applications" style="display:inline;" onsubmit="return confirm('Are you sure you want to reject this application?');">
                                                                <input type="hidden" name="form_action" value="update_application_status">
                                                                <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                                <input type="hidden" name="status" value="REJECTED">
                                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                            </form>
                                                        <?php elseif ($status === 'APPROVED'): ?>
                                                            <a href="index.php?page=admin_allocations&action=add&student_id=<?php echo (int)$app['student_user_id']; ?>&hostel_id=<?php echo (int)$app['hostel_id']; ?>&app_id=<?php echo (int)$app['id']; ?>" class="btn btn-sm btn-primary">Allocate</a>
                                                        <?php endif; ?>
                                                        <form method="POST" action="index.php?page=admin_applications" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                                            <input type="hidden" name="form_action" value="delete_application">
                                                            <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
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
                <button type="button" class="btn btn-success" id="confirmBtn">Confirm</button>
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
        
        function showConfirm(title, message, callback, btnText, btnClass) {
            document.getElementById("confirmTitle").textContent = title;
            document.getElementById("confirmMessage").textContent = message;
            let confirmBtn = document.getElementById("confirmBtn");
            confirmBtn.textContent = btnText || "Confirm";
            confirmBtn.className = "btn " + (btnClass || "btn-success");
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