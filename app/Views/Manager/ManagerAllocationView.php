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
                
                <?php if ($action === 'add'): ?>
                    <!-- Create Allocation Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">Create Allocation</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Create Room Allocation</h3>
                        <form action="index.php?page=manager_allocations" method="POST" id="allocationForm">
                            <input type="hidden" name="form_action" value="create_allocation">
                            
                            <div class="form-group">
                                <label for="hostel_id">Hostel *</label>
                                <select name="hostel_id" id="hostel_id" required class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Select Hostel --</option>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo (int)$hostel['id']; ?>" <?php echo (isset($data['selected_hostel_id']) && $data['selected_hostel_id'] == $hostel['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <?php if (isset($data['selected_hostel_id'])): ?>
                            <div class="form-group">
                                <label for="student_user_id">Student (Approved, No Current Allocation) *</label>
                                <select name="student_user_id" id="student_user_id" required class="form-control">
                                    <option value="">-- Select Student --</option>
                                    <?php foreach ($data['approved_students'] as $student): ?>
                                        <option value="<?php echo (int)$student['id']; ?>">
                                            <?php echo htmlspecialchars($student['name']); ?> 
                                            (<?php echo htmlspecialchars($student['student_id']); ?>) - 
                                            <?php echo htmlspecialchars($student['department']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="seat_id">Seat *</label>
                                <select name="seat_id" id="seat_id" required class="form-control">
                                    <option value="">-- Select Seat --</option>
                                    <?php foreach ($data['available_seats'] as $seat): ?>
                                        <option value="<?php echo (int)$seat['id']; ?>">
                                            Floor <?php echo htmlspecialchars($seat['floor_no']); ?> - 
                                            Room <?php echo htmlspecialchars($seat['room_no']); ?> - 
                                            Seat <?php echo htmlspecialchars($seat['seat_label']); ?> 
                                            (<?php echo htmlspecialchars($seat['room_type_name']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <input type="hidden" name="hostel_id" value="<?php echo (int)$data['selected_hostel_id']; ?>">
                            
                            <button type="submit" class="btn btn-success">Create Allocation</button>
                            <?php endif; ?>
                            <a href="index.php?page=manager_allocations" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                    
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
