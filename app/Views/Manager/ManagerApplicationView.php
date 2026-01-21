<?php
// Manager Application View
$page = 'manager_applications';
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
                        <a href="index.php?page=manager_applications">Applications</a>
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
                        <?php if (!empty($data['application']['notes'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Notes</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['application']['notes'])); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($data['application']['reject_reason'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Rejection Reason</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['application']['reject_reason'])); ?></div>
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
                        <form action="index.php?page=manager_applications" method="POST" id="reviewForm">
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
                                <a href="index.php?page=manager_applications" class="btn btn-secondary">Back to List</a>
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
                                if (!confirm('Are you sure you want to APPROVE this application? You can allocate a room later.')) {
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
                                '<a href="index.php?page=manager_applications" class="btn btn-secondary">Back to List</a>';
                        }
                    </script>
                    <?php else: ?>
                    <div class="form-actions">
                        <a href="index.php?page=manager_applications" class="btn btn-secondary">Back to List</a>
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
                                                        <a href="index.php?page=manager_applications&action=view&id=<?php echo (int)$app['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <?php if ($canReview): ?>
                                                            <form method="POST" action="index.php?page=manager_applications" style="display:inline;" onsubmit="return confirm('Are you sure you want to approve this application?');">
                                                                <input type="hidden" name="form_action" value="update_application_status">
                                                                <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                                <input type="hidden" name="status" value="APPROVED">
                                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                            </form>
                                                        <?php elseif ($status === 'APPROVED'): ?>
                                                            <a href="index.php?page=manager_allocations&action=add&student_id=<?php echo (int)$app['student_user_id']; ?>&hostel_id=<?php echo (int)$app['hostel_id']; ?>&app_id=<?php echo (int)$app['id']; ?>" class="btn btn-sm btn-primary">Allocate</a>
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
