<?php
// Manager Allocation View
$page = 'manager_allocations';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
    <link rel="stylesheet" href="app/Views/Manager/css/common.css">
    <link rel="stylesheet" href="app/Views/Manager/css/ManagerAllocationView.css">
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
                
                <?php if ($action === 'add'): ?>
                    <?php 
                    // Get URL parameters for auto-fill
                    $preSelectedStudentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
                    $preSelectedHostelId = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;
                    $preSelectedAppId = isset($_GET['app_id']) ? (int)$_GET['app_id'] : 0;
                    ?>
                    <!-- Create Allocation Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">Create New Allocation</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Allocate Seat to Student</h3>
                        <p class="form-hint" style="margin-bottom: 15px; color: #666;">
                            <strong>Note:</strong> Only students with <span class="badge badge-success">APPROVED</span> applications 
                            who don't have active allocations are shown.
                        </p>
                        
                        <form action="index.php?page=manager_allocations" method="POST" id="allocationForm">
                            <input type="hidden" name="form_action" value="create_allocation">
                            <input type="hidden" id="application_id" name="application_id" value="<?php echo $preSelectedAppId; ?>">
                            <input type="hidden" id="hostel_id_hidden" name="hostel_id" value="<?php echo $preSelectedHostelId; ?>">
                            
                            <!-- Student Selection Dropdown -->
                            <div class="form-group">
                                <label for="student_id">Student <span class="required">*</span></label>
                                <select id="student_id" name="student_user_id" class="form-control" required>
                                    <option value="">-- Select a Student --</option>
                                </select>
                                <span class="form-hint">Shows students with approved applications who don't have active allocations</span>
                            </div>
                            
                            <!-- Application Info (shown after student is selected) -->
                            <div id="applicationInfo" class="alert alert-info" style="display: none; margin-bottom: 15px;">
                                <strong>Application Details:</strong>
                                <div id="appInfoContent"></div>
                            </div>
                            
                            <!-- Cascading Dropdowns: Hostel → Floor → Room → Seat -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="required">*</span></label>
                                    <select id="hostel_id" name="hostel_id_display" class="form-control" required disabled>
                                        <option value="">Select a student first</option>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>">
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span class="form-hint">Auto-selected from approved application</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="floor_id">Floor <span class="required">*</span></label>
                                    <select id="floor_id" name="floor_id" class="form-control" required disabled>
                                        <option value="">Select Floor</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_id">Room <span class="required">*</span></label>
                                    <select id="room_id" name="room_id" class="form-control" required disabled>
                                        <option value="">Select Room</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="seat_id">Seat <span class="required">*</span></label>
                                    <select id="seat_id" name="seat_id" class="form-control" required disabled>
                                        <option value="">Select Seat</option>
                                    </select>
                                    <span class="form-hint" id="seatHint">Only available seats will be shown</span>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="required">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control">
                                    <span class="form-hint">Leave empty for ongoing allocation</span>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Create Allocation</button>
                                <a href="index.php?page=manager_allocations" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                    <script>
                        var preSelectedStudentId = <?php echo $preSelectedStudentId; ?>;
                        var preSelectedHostelId = <?php echo $preSelectedHostelId; ?>;
                        var preSelectedAppId = <?php echo $preSelectedAppId; ?>;
                    </script>
                    <script src="app/Views/Manager/js/ManagerAllocationView.js"></script>
                    
                <?php else: ?>
                    <!-- List Allocations -->
                    <div class="page-header">
                        <h2>Room Allocations</h2>
                        <a href="index.php?page=manager_allocations&action=add" class="btn btn-primary">+ New Allocation</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <input type="text" id="searchInput" placeholder="Search by student name..." class="form-control">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="ENDED">Ended</option>
                        </select>
                        <?php if (isset($data['hostels']) && count($data['hostels']) > 1): ?>
                        <select id="hostelFilter" class="form-control">
                            <option value="">All Hostels</option>
                            <?php foreach ($data['hostels'] as $hostel): ?>
                                <option value="<?php echo htmlspecialchars($hostel['name']); ?>">
                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>All Allocations</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Student ID</th>
                                        <th>Hostel</th>
                                        <th>Room</th>
                                        <th>Seat</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['allocations'])): ?>
                                        <?php foreach ($data['allocations'] as $alloc): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($alloc['student_name']); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['student_id']); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['hostel_name']); ?></td>
                                                <td>Floor <?php echo htmlspecialchars($alloc['floor_no']); ?> - <?php echo htmlspecialchars($alloc['room_no']); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['seat_label']); ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($alloc['start_date'])); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo $alloc['status'] === 'ACTIVE' ? 'success' : 'secondary'; ?>">
                                                        <?php echo htmlspecialchars($alloc['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($alloc['created_by_name'] ?? 'N/A'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="empty-state">No allocations found</td>
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
