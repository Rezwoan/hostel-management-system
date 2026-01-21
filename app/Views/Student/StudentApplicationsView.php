<?php
// Student Applications View
$page = 'student_applications';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <?php include __DIR__ . '/partials/head-meta.php'; ?>
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
                
                <div class="page-header">
                    <h2>Room Applications</h2>
                    <button onclick="document.getElementById('applicationForm').scrollIntoView({behavior: 'smooth'})" class="btn btn-primary">+ New Application</button>
                </div>
                
                <!-- Applications List -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>My Applications</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Hostel</th>
                                    <th>Room Type</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['applications'])): ?>
                                    <?php foreach ($data['applications'] as $app): ?>
                                        <tr>
                                            <td><?php echo (int)$app['id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($app['created_at'] ?? '')); ?></td>
                                            <td><?php echo htmlspecialchars($app['hostel_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($app['room_type_name'] ?? ''); ?></td>
                                            <td>
                                                <?php 
                                                $status = $app['status'] ?? '';
                                                $statusClass = 'badge-secondary';
                                                $statusText = $status;
                                                $statusTitle = '';
                                                
                                                if ($status === 'APPROVED') {
                                                    $statusClass = 'badge-success';
                                                    $statusTitle = 'Your application has been approved';
                                                } elseif ($status === 'REJECTED') {
                                                    $statusClass = 'badge-danger';
                                                    $statusTitle = 'Your application was rejected';
                                                } elseif ($status === 'SUBMITTED') {
                                                    $statusClass = 'badge-warning';
                                                    $statusText = 'PENDING';
                                                    $statusTitle = 'Your application is under review';
                                                } elseif ($status === 'CANCELLED') {
                                                    $statusClass = 'badge-secondary';
                                                    $statusTitle = 'You cancelled this application';
                                                } elseif ($status === 'DRAFT') {
                                                    $statusClass = 'badge-secondary';
                                                    $statusTitle = 'Application not yet submitted';
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>" title="<?php echo htmlspecialchars($statusTitle); ?>">
                                                    <?php echo htmlspecialchars($statusText); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($app['notes'] ?? '', 0, 50)) . (strlen($app['notes'] ?? '') > 50 ? '...' : ''); ?></td>
                                            <td>
                                                <?php if ($app['status'] === 'SUBMITTED'): ?>
                                                    <form method="POST" action="index.php?page=student_applications" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this application?');">
                                                        <input type="hidden" name="form_action" value="cancel_application">
                                                        <input type="hidden" name="id" value="<?php echo (int)$app['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                                    </form>
                                                <?php elseif ($app['status'] === 'REJECTED' && !empty($app['reject_reason'])): ?>
                                                    <button onclick="alert('Reject Reason: <?php echo addslashes(htmlspecialchars($app['reject_reason'])); ?>')" class="btn btn-sm btn-secondary">View Reason</button>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="empty-state">No applications yet. Submit one below!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Application Form -->
                <div class="form-card" id="applicationForm">
                    <h3>Submit New Application</h3>
                    <?php 
                    $hasPending = false;
                    foreach ($data['applications'] as $app) {
                        if (in_array($app['status'], ['SUBMITTED', 'APPROVED'])) {
                            $hasPending = true;
                            break;
                        }
                    }
                    ?>
                    <?php if ($hasPending): ?>
                        <div class="alert alert-warning">
                            You already have a pending or approved application. Please wait for it to be processed or cancel it before submitting a new one.
                        </div>
                    <?php else: ?>
                        <form action="index.php?page=student_applications" method="POST" id="applicationForm" onsubmit="return handleFormSubmit(this)">
                            <input type="hidden" name="form_action" value="create_application">
                            <input type="hidden" name="submit_token" value="<?php echo htmlspecialchars($data['formToken']); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="required">*</span></label>
                                    <select name="hostel_id" id="hostel_id" class="form-control" required>
                                        <option value="">-- Select Hostel --</option>
                                        <?php foreach ($data['hostels'] as $hostel): ?>
                                            <option value="<?php echo (int)$hostel['id']; ?>">
                                                <?php echo htmlspecialchars($hostel['name']); ?> (<?php echo htmlspecialchars($hostel['code']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="room_type_id">Preferred Room Type <span class="required">*</span></label>
                                    <select name="room_type_id" id="room_type_id" class="form-control" required>
                                        <option value="">-- Select Room Type --</option>
                                        <?php foreach ($data['roomTypes'] as $roomType): ?>
                                            <option value="<?php echo (int)$roomType['id']; ?>">
                                                <?php echo htmlspecialchars($roomType['name']); ?> - $<?php echo number_format($roomType['default_fee'], 2); ?>/month
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes / Special Requests</label>
                                <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Any special requirements or preferences..."></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Submit Application</button>
                            </div>
                        </form>
                        
                        <script>
                        let formSubmitted = false;
                        
                        function handleFormSubmit(form) {
                            if (formSubmitted) {
                                alert('Form is already being submitted. Please wait.');
                                return false;
                            }
                            
                            formSubmitted = true;
                            const submitBtn = document.getElementById('submitBtn');
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Submitting...';
                            
                            return true;
                        }
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
