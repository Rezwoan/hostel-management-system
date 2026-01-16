<?php
// Admin Room Management View
$page = 'admin_rooms';
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
                    <!-- Add Room Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_rooms">Rooms</a>
                        <span>/</span>
                        <span class="current">Add New Room</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Room</h3>
                        <form action="index.php?page=admin_rooms" method="POST">
                            <input type="hidden" name="form_action" value="create_room">
                            
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
                                    <select id="floor_id" name="floor_id" class="form-control" required>
                                        <option value="">Select Floor</option>
                                        <?php if (!empty($data['floors'])): ?>
                                            <?php foreach ($data['floors'] as $floor): ?>
                                                <option value="<?php echo (int)$floor['id']; ?>">
                                                    <?php echo htmlspecialchars($floor['label'] ?? 'Floor ' . $floor['floor_no']); ?> (<?php echo htmlspecialchars($floor['hostel_name'] ?? ''); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_no">Room Number <span class="required">*</span></label>
                                    <input type="text" id="room_no" name="room_no" class="form-control" required placeholder="e.g., 101, A-101">
                                </div>
                                
                                <div class="form-group">
                                    <label for="room_type_id">Room Type <span class="required">*</span></label>
                                    <select id="room_type_id" name="room_type_id" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <?php if (!empty($data['room_types'])): ?>
                                            <?php foreach ($data['room_types'] as $type): ?>
                                                <option value="<?php echo (int)$type['id']; ?>">
                                                    <?php echo htmlspecialchars($type['name']); ?> (Capacity: <?php echo (int)$type['capacity']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="capacity">Capacity <span class="required">*</span></label>
                                    <input type="number" id="capacity" name="capacity" class="form-control" required min="1" max="10">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="AVAILABLE">Available</option>
                                        <option value="OCCUPIED">Occupied</option>
                                        <option value="MAINTENANCE">Under Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Room</button>
                                <a href="index.php?page=admin_rooms" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['room'])): ?>
                    <!-- Edit Room Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_rooms">Rooms</a>
                        <span>/</span>
                        <span class="current">Edit Room</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Room</h3>
                        <form action="index.php?page=admin_rooms" method="POST">
                            <input type="hidden" name="form_action" value="update_room">
                            <input type="hidden" name="id" value="<?php echo (int)$data['room']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="required">*</span></label>
                                    <select id="hostel_id" name="hostel_id" class="form-control" required>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>" <?php echo ($data['room']['hostel_id'] ?? 0) == $hostel['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="floor_id">Floor <span class="required">*</span></label>
                                    <select id="floor_id" name="floor_id" class="form-control" required>
                                        <?php if (!empty($data['floors'])): ?>
                                            <?php foreach ($data['floors'] as $floor): ?>
                                                <option value="<?php echo (int)$floor['id']; ?>" <?php echo ($data['room']['floor_id'] ?? 0) == $floor['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($floor['label'] ?? 'Floor ' . $floor['floor_no']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_no">Room Number <span class="required">*</span></label>
                                    <input type="text" id="room_no" name="room_no" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['room']['room_no'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="room_type_id">Room Type <span class="required">*</span></label>
                                    <select id="room_type_id" name="room_type_id" class="form-control" required>
                                        <?php if (!empty($data['room_types'])): ?>
                                            <?php foreach ($data['room_types'] as $type): ?>
                                                <option value="<?php echo (int)$type['id']; ?>" <?php echo ($data['room']['room_type_id'] ?? 0) == $type['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($type['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="capacity">Capacity <span class="required">*</span></label>
                                    <input type="number" id="capacity" name="capacity" class="form-control" required min="1" max="10"
                                           value="<?php echo (int)($data['room']['capacity'] ?? 1); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="AVAILABLE" <?php echo ($data['room']['status'] ?? '') === 'AVAILABLE' ? 'selected' : ''; ?>>Available</option>
                                        <option value="OCCUPIED" <?php echo ($data['room']['status'] ?? '') === 'OCCUPIED' ? 'selected' : ''; ?>>Occupied</option>
                                        <option value="MAINTENANCE" <?php echo ($data['room']['status'] ?? '') === 'MAINTENANCE' ? 'selected' : ''; ?>>Under Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Room</button>
                                <a href="index.php?page=admin_rooms" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['room'])): ?>
                    <!-- View Room Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_rooms">Rooms</a>
                        <span>/</span>
                        <span class="current">View Room</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Room Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['room']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room']['room_no'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Floor</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room']['floor_label'] ?? 'Floor ' . ($data['room']['floor_no'] ?? '')); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Room Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room']['room_type_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Capacity</div>
                            <div class="detail-value"><?php echo (int)$data['room']['capacity']; ?> person(s)</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $statusClass = 'badge-warning';
                                if (($data['room']['status'] ?? '') === 'AVAILABLE') $statusClass = 'badge-success';
                                elseif (($data['room']['status'] ?? '') === 'OCCUPIED') $statusClass = 'badge-info';
                                elseif (($data['room']['status'] ?? '') === 'MAINTENANCE') $statusClass = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($data['room']['status'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_rooms&action=edit&id=<?php echo (int)$data['room']['id']; ?>" class="btn btn-primary">Edit Room</a>
                            <a href="index.php?page=admin_rooms" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                    <!-- Seats in this Room -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Seats</h3>
                            <a href="index.php?page=admin_seats&action=add" class="btn btn-sm btn-primary">Add Seat</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Seat No</th>
                                        <th>Status</th>
                                        <th>Occupant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['seats'])): ?>
                                        <?php foreach ($data['seats'] as $seat): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($seat['seat_no']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo ($seat['status'] ?? '') === 'VACANT' ? 'badge-success' : 'badge-warning'; ?>">
                                                        <?php echo htmlspecialchars($seat['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($seat['occupant_name'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_seats&action=edit&id=<?php echo (int)$seat['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="empty-state">No seats found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Rooms List -->
                    <div class="page-header">
                        <h2>All Rooms</h2>
                        <a href="index.php?page=admin_rooms&action=add" class="btn btn-primary">Add New Room</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="roomSearch" class="form-control" placeholder="Search rooms..." data-table-search="roomsTable">
                            <select id="hostelFilter" class="form-control" data-filter-table="roomsTable" data-filter-column="2">
                                <option value="">All Hostels</option>
                                <?php if (!empty($data['hostels'])): ?>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo htmlspecialchars($hostel['name']); ?>">
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <select id="statusFilter" class="form-control" data-filter-table="roomsTable" data-filter-column="6">
                                <option value="">All Status</option>
                                <option value="ACTIVE">Active</option>
                                <option value="MAINTENANCE">Maintenance</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="roomsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Room No</th>
                                        <th>Hostel</th>
                                        <th>Floor</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['rooms'])): ?>
                                        <?php foreach ($data['rooms'] as $room): ?>
                                            <tr>
                                                <td><?php echo (int)$room['id']; ?></td>
                                                <td><?php echo htmlspecialchars($room['room_no']); ?></td>
                                                <td><?php echo htmlspecialchars($room['hostel_name'] ?? ''); ?></td>
                                                <td><?php echo (int)($room['floor_no'] ?? 0); ?></td>
                                                <td><?php echo htmlspecialchars($room['room_type_name'] ?? ''); ?></td>
                                                <td><?php echo (int)$room['capacity']; ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = 'badge-warning';
                                                    if ($room['status'] === 'AVAILABLE') $statusClass = 'badge-success';
                                                    elseif ($room['status'] === 'OCCUPIED') $statusClass = 'badge-info';
                                                    elseif ($room['status'] === 'MAINTENANCE') $statusClass = 'badge-danger';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($room['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_rooms&action=view&id=<?php echo (int)$room['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_rooms&action=edit&id=<?php echo (int)$room['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_rooms" method="POST" style="display:inline;" onsubmit="return confirm('Delete this room?');">
                                                            <input type="hidden" name="form_action" value="delete_room">
                                                            <input type="hidden" name="id" value="<?php echo (int)$room['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="empty-state">No rooms found</td>
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
