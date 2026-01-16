<?php
// Admin Allocation Management View
$page = 'admin_allocations';
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
                
                <?php if ($action === 'add'): ?>
                    <!-- Create Allocation Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">Create New Allocation</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Allocate Room to Student</h3>
                        <form action="index.php?page=admin_allocations" method="POST">
                            <input type="hidden" name="form_action" value="create_allocation">
                            
                            <div class="form-group">
                                <label for="student_id">Student <span class="required">*</span></label>
                                <select id="student_id" name="student_id" class="form-control" required>
                                    <option value="">Select Student</option>
                                    <?php if (!empty($data['students'])): ?>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <option value="<?php echo (int)$student['id']; ?>">
                                                <?php echo htmlspecialchars($student['name']); ?> (<?php echo htmlspecialchars($student['student_id_number'] ?? $student['email']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_id">Room <span class="required">*</span></label>
                                    <select id="room_id" name="room_id" class="form-control" required>
                                        <option value="">Select Room</option>
                                        <?php if (!empty($data['rooms'])): ?>
                                            <?php foreach ($data['rooms'] as $room): ?>
                                                <option value="<?php echo (int)$room['id']; ?>">
                                                    <?php echo htmlspecialchars($room['room_no']); ?> - <?php echo htmlspecialchars($room['hostel_name'] ?? ''); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="seat_id">Seat <span class="required">*</span></label>
                                    <select id="seat_id" name="seat_id" class="form-control" required>
                                        <option value="">Select Seat</option>
                                        <?php if (!empty($data['seats'])): ?>
                                            <?php foreach ($data['seats'] as $seat): ?>
                                                <option value="<?php echo (int)$seat['id']; ?>">
                                                    Seat <?php echo htmlspecialchars($seat['seat_no']); ?> - Room <?php echo htmlspecialchars($seat['room_no'] ?? ''); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="required">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="required">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Allocation</button>
                                <a href="index.php?page=admin_allocations" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['allocation'])): ?>
                    <!-- Edit Allocation Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">Edit Allocation</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Allocation</h3>
                        <form action="index.php?page=admin_allocations" method="POST">
                            <input type="hidden" name="form_action" value="update_allocation">
                            <input type="hidden" name="id" value="<?php echo (int)$data['allocation']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="required">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['allocation']['start_date'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="required">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['allocation']['end_date'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE" <?php echo ($data['allocation']['status'] ?? '') === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="COMPLETED" <?php echo ($data['allocation']['status'] ?? '') === 'COMPLETED' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="CANCELLED" <?php echo ($data['allocation']['status'] ?? '') === 'CANCELLED' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Allocation</button>
                                <a href="index.php?page=admin_allocations" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['allocation'])): ?>
                    <!-- View Allocation Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">View Allocation</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Allocation Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['allocation']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['student_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['student_id_number'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['room_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Seat</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['seat_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['start_date'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">End Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['end_date'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['allocation']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'ACTIVE') $statusClass = 'badge-success';
                                elseif ($status === 'COMPLETED') $statusClass = 'badge-info';
                                elseif ($status === 'CANCELLED') $statusClass = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_allocations&action=edit&id=<?php echo (int)$data['allocation']['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="index.php?page=admin_allocations" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Allocations List -->
                    <div class="page-header">
                        <h2>All Allocations</h2>
                        <a href="index.php?page=admin_allocations&action=add" class="btn btn-primary">Create New Allocation</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_allocations">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="ACTIVE" <?php echo (isset($_GET['status']) && $_GET['status'] === 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                                <option value="COMPLETED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'COMPLETED') ? 'selected' : ''; ?>>Completed</option>
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
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Hostel</th>
                                        <th>Room</th>
                                        <th>Seat</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['allocations'])): ?>
                                        <?php foreach ($data['allocations'] as $alloc): ?>
                                            <tr>
                                                <td><?php echo (int)$alloc['id']; ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($alloc['student_name'] ?? ''); ?><br>
                                                    <small><?php echo htmlspecialchars($alloc['student_id_number'] ?? ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($alloc['hostel_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['room_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['seat_no'] ?? ''); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($alloc['start_date'] ?? ''); ?><br>
                                                    to <?php echo htmlspecialchars($alloc['end_date'] ?? ''); ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $alloc['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'ACTIVE') $statusClass = 'badge-success';
                                                    elseif ($status === 'COMPLETED') $statusClass = 'badge-info';
                                                    elseif ($status === 'CANCELLED') $statusClass = 'badge-danger';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_allocations&action=view&id=<?php echo (int)$alloc['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_allocations&action=edit&id=<?php echo (int)$alloc['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <?php if ($status === 'ACTIVE'): ?>
                                                            <form action="index.php?page=admin_allocations" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this allocation?');">
                                                                <input type="hidden" name="form_action" value="cancel_allocation">
                                                                <input type="hidden" name="id" value="<?php echo (int)$alloc['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
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
