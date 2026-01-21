<?php
// Admin Seat Management View
$page = 'admin_seats';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Admin</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
    <script src="public/assets/js/table-filter.js" defer></script>
</head>
<body>
    <script>window.currentAction = '<?php echo $action; ?>';</script>
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
                    <!-- Add Seat Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_seats">Seats</a>
                        <span>/</span>
                        <span class="current">Add New Seat</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Seat</h3>
                        <form action="index.php?page=admin_seats" method="POST">
                            <input type="hidden" name="form_action" value="create_seat">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="required">*</span></label>
                                    <select id="hostel_id" name="hostel_id" class="form-control" required>
                                        <option value="">Select Hostel</option>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>">
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="floor_id">Floor <span class="required">*</span></label>
                                    <select id="floor_id" name="floor_id" class="form-control" required disabled>
                                        <option value="">Select Hostel First</option>
                                    </select>
                                    <span class="form-hint">Select a hostel to load floors</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="room_id">Room <span class="required">*</span></label>
                                <select id="room_id" name="room_id" class="form-control" required disabled>
                                    <option value="">Select Floor First</option>
                                </select>
                                <span class="form-hint">Only rooms with available capacity are shown</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="seat_label">Seat Label <span class="required">*</span></label>
                                    <input type="text" id="seat_label" name="seat_label" class="form-control" required placeholder="e.g., A, B, C">
                                    <span class="form-hint">Auto-generated when room is selected. You can modify if needed.</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="ACTIVE" selected>Active</option>
                                        <option value="INACTIVE">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div id="capacity-info" class="alert alert-info" style="display:none; margin-bottom: 15px;">
                                <strong>Room Capacity:</strong> <span id="capacity-text"></span>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Seat</button>
                                <a href="index.php?page=admin_seats" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['seat'])): ?>
                    <!-- Edit Seat Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_seats">Seats</a>
                        <span>/</span>
                        <span class="current">Edit Seat</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Seat</h3>
                        <form action="index.php?page=admin_seats" method="POST">
                            <input type="hidden" name="form_action" value="update_seat">
                            <input type="hidden" name="id" value="<?php echo (int)$data['seat']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="room_id">Room <span class="required">*</span></label>
                                <select id="room_id" name="room_id" class="form-control" required>
                                    <?php if (!empty($data['rooms'])): ?>
                                        <?php foreach ($data['rooms'] as $room): ?>
                                            <option value="<?php echo (int)$room['id']; ?>" <?php echo ($data['seat']['room_id'] ?? 0) == $room['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($room['room_no']); ?> - <?php echo htmlspecialchars($room['hostel_name'] ?? ''); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="seat_no">Seat Number <span class="required">*</span></label>
                                    <input type="text" id="seat_no" name="seat_no" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['seat']['seat_no'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="VACANT" <?php echo ($data['seat']['status'] ?? '') === 'VACANT' ? 'selected' : ''; ?>>Vacant</option>
                                        <option value="OCCUPIED" <?php echo ($data['seat']['status'] ?? '') === 'OCCUPIED' ? 'selected' : ''; ?>>Occupied</option>
                                        <option value="RESERVED" <?php echo ($data['seat']['status'] ?? '') === 'RESERVED' ? 'selected' : ''; ?>>Reserved</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Seat</button>
                                <a href="index.php?page=admin_seats" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['seat'])): ?>
                    <!-- View Seat Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_seats">Seats</a>
                        <span>/</span>
                        <span class="current">View Seat</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Seat Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['seat']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Seat Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['seat']['seat_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['seat']['room_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['seat']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $statusClass = 'badge-warning';
                                if (($data['seat']['status'] ?? '') === 'VACANT') $statusClass = 'badge-success';
                                elseif (($data['seat']['status'] ?? '') === 'OCCUPIED') $statusClass = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($data['seat']['status'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($data['seat']['occupant_name'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Current Occupant</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['seat']['occupant_name']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_seats&action=edit&id=<?php echo (int)$data['seat']['id']; ?>" class="btn btn-primary">Edit Seat</a>
                            <a href="index.php?page=admin_seats" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Seats List -->
                    <div class="page-header">
                        <h2>All Seats</h2>
                        <a href="index.php?page=admin_seats&action=add" class="btn btn-primary">Add New Seat</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="seatSearch" class="form-control" placeholder="Search seats..." data-table-search="seatsTable">
                            <select id="hostelFilter" class="form-control" data-filter-table="seatsTable" data-filter-column="3">
                                <option value="">All Hostels</option>
                                <?php if (!empty($data['hostels'])): ?>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo htmlspecialchars($hostel['name']); ?>">
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <select id="statusFilter" class="form-control" data-filter-table="seatsTable" data-filter-column="4">
                                <option value="">All Status</option>
                                <option value="VACANT">Vacant</option>
                                <option value="OCCUPIED">Occupied</option>
                                <option value="RESERVED">Reserved</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="seatsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Seat No</th>
                                        <th>Room</th>
                                        <th>Hostel</th>
                                        <th>Status</th>
                                        <th>Occupant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['seats'])): ?>
                                        <?php foreach ($data['seats'] as $seat): ?>
                                            <tr>
                                                <td><?php echo (int)$seat['id']; ?></td>
                                                <td><?php echo htmlspecialchars($seat['seat_label'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($seat['room_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($seat['hostel_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $actualStatus = $seat['actual_status'] ?? 'VACANT';
                                                    $statusClass = 'badge-success';
                                                    if ($actualStatus === 'OCCUPIED') {
                                                        $statusClass = 'badge-danger';
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($actualStatus); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($seat['occupant_name'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_seats&action=view&id=<?php echo (int)$seat['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_seats&action=edit&id=<?php echo (int)$seat['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_seats" method="POST" style="display:inline;" onsubmit="return confirm('Delete this seat?');">
                                                            <input type="hidden" name="form_action" value="delete_seat">
                                                            <input type="hidden" name="id" value="<?php echo (int)$seat['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No seats found</td>
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
    
    <script src="app/Views/Admin/js/SeatView.js"></script>
</body>
</html>
