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
                                    <select id="floor_id" name="floor_id" class="form-control" required disabled>
                                        <option value="">Select Hostel First</option>
                                    </select>
                                    <span class="form-hint">Select a hostel to load available floors</span>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="room_no">Room Number <span class="required">*</span></label>
                                    <input type="text" id="room_no" name="room_no" class="form-control" required placeholder="e.g., 101, A-101">
                                    <span class="form-hint">Auto-generated when floor is selected. You can modify if needed.</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="room_type_id">Room Type <span class="required">*</span></label>
                                    <select id="room_type_id" name="room_type_id" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <?php if (!empty($data['room_types'])): ?>
                                            <?php foreach ($data['room_types'] as $type): ?>
                                                <option value="<?php echo (int)$type['id']; ?>" data-capacity="<?php echo (int)$type['default_capacity']; ?>">
                                                    <?php echo htmlspecialchars($type['name']); ?> (Capacity: <?php echo (int)$type['default_capacity']; ?>)
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
                                    <span class="form-hint">Auto-filled from room type. You can adjust if needed.</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="ACTIVE" selected>Active</option>
                                        <option value="INACTIVE">Inactive</option>
                                        <option value="MAINTENANCE">Maintenance</option>
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
                                    <label for="edit_hostel_id">Hostel <span class="required">*</span></label>
                                    <select id="edit_hostel_id" name="hostel_id" class="form-control" required>
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
                                    <label for="edit_floor_id">Floor <span class="required">*</span></label>
                                    <select id="edit_floor_id" name="floor_id" class="form-control" required data-current-floor="<?php echo (int)($data['room']['floor_id'] ?? 0); ?>">
                                        <?php if (!empty($data['floors'])): ?>
                                            <?php foreach ($data['floors'] as $floor): ?>
                                                <option value="<?php echo (int)$floor['id']; ?>" data-hostel-id="<?php echo (int)$floor['hostel_id']; ?>" <?php echo ($data['room']['floor_id'] ?? 0) == $floor['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($floor['label'] ?? 'Floor ' . $floor['floor_no']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_room_no">Room Number <span class="required">*</span></label>
                                    <input type="text" id="edit_room_no" name="room_no" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['room']['room_no'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_room_type_id">Room Type <span class="required">*</span></label>
                                    <select id="edit_room_type_id" name="room_type_id" class="form-control" required>
                                        <?php if (!empty($data['room_types'])): ?>
                                            <?php foreach ($data['room_types'] as $type): ?>
                                                <option value="<?php echo (int)$type['id']; ?>" data-capacity="<?php echo (int)$type['default_capacity']; ?>" <?php echo ($data['room']['room_type_id'] ?? 0) == $type['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($type['name']); ?> (Capacity: <?php echo (int)$type['default_capacity']; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span class="form-hint">Changing room type will update capacity</span>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="edit_capacity">Capacity <span class="required">*</span></label>
                                    <input type="number" id="edit_capacity" name="capacity" class="form-control" required min="1" max="10"
                                           value="<?php echo (int)($data['room']['capacity'] ?? 1); ?>">
                                    <span class="form-hint">Auto-filled from room type. Adjust if needed.</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="edit_status">Status <span class="required">*</span></label>
                                    <select id="edit_status" name="status" class="form-control" required>
                                        <option value="ACTIVE" <?php echo ($data['room']['status'] ?? '') === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                        <option value="INACTIVE" <?php echo ($data['room']['status'] ?? '') === 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="MAINTENANCE" <?php echo ($data['room']['status'] ?? '') === 'MAINTENANCE' ? 'selected' : ''; ?>>Maintenance</option>
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
    
    <script>
    // Room Management: Cascading dropdowns and auto-population
    document.addEventListener('DOMContentLoaded', function() {
        const currentAction = '<?php echo $action; ?>';
        
        // ADD PAGE LOGIC
        if (currentAction === 'add') {
            const hostelSelect = document.getElementById('hostel_id');
            const floorSelect = document.getElementById('floor_id');
            const roomNoInput = document.getElementById('room_no');
            const roomTypeSelect = document.getElementById('room_type_id');
            const capacityInput = document.getElementById('capacity');
            
            if (hostelSelect && floorSelect && roomNoInput && roomTypeSelect && capacityInput) {
                
                // When hostel is selected, load its floors
                hostelSelect.addEventListener('change', function() {
                    const hostelId = this.value;
                    
                    // Reset dependent fields
                    floorSelect.innerHTML = '<option value="">Select Floor</option>';
                    floorSelect.disabled = true;
                    roomNoInput.value = '';
                    
                    if (hostelId) {
                        // Fetch floors for selected hostel
                        fetch('app/Controllers/Api/get_floors.php?hostel_id=' + hostelId)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.data.length > 0) {
                                    floorSelect.innerHTML = '<option value="">Select Floor</option>';
                                    data.data.forEach(floor => {
                                        const option = document.createElement('option');
                                        option.value = floor.id;
                                        option.textContent = (floor.label || 'Floor ' + floor.floor_no);
                                        floorSelect.appendChild(option);
                                    });
                                    floorSelect.disabled = false;
                                } else {
                                    floorSelect.innerHTML = '<option value="">No floors available</option>';
                                }
                            })
                            .catch(error => {
                                console.error('Error loading floors:', error);
                                floorSelect.innerHTML = '<option value="">Error loading floors</option>';
                            });
                    } else {
                        floorSelect.innerHTML = '<option value="">Select Hostel First</option>';
                    }
                });
                
                // When floor is selected, generate room number
                floorSelect.addEventListener('change', function() {
                    const floorId = this.value;
                    
                    if (floorId) {
                        roomNoInput.value = 'Loading...';
                        roomNoInput.disabled = true;
                        
                        // Fetch next room number for selected floor
                        fetch('app/Controllers/Api/get_next_room_number.php?floor_id=' + floorId)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    roomNoInput.value = data.next_room_no;
                                } else {
                                    console.error('Error:', data.error);
                                    roomNoInput.value = '101';
                                }
                                roomNoInput.disabled = false;
                            })
                            .catch(error => {
                                console.error('Fetch error:', error);
                                roomNoInput.value = '101';
                                roomNoInput.disabled = false;
                            });
                    } else {
                        roomNoInput.value = '';
                    }
                });
                
                // When room type is selected, populate capacity
                roomTypeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const capacity = selectedOption.getAttribute('data-capacity');
                    
                    if (capacity) {
                        capacityInput.value = capacity;
                    } else {
                        capacityInput.value = '';
                    }
                });
            }
        }
        
        // EDIT PAGE LOGIC
        if (currentAction === 'edit') {
            const editHostelSelect = document.getElementById('edit_hostel_id');
            const editFloorSelect = document.getElementById('edit_floor_id');
            const editRoomTypeSelect = document.getElementById('edit_room_type_id');
            const editCapacityInput = document.getElementById('edit_capacity');
            
            if (editHostelSelect && editFloorSelect && editRoomTypeSelect && editCapacityInput) {
                
                // Store all floor options with their hostel IDs
                const allFloorOptions = Array.from(editFloorSelect.options).filter(opt => opt.value !== '');
                const currentFloorId = editFloorSelect.getAttribute('data-current-floor');
                
                // Function to filter floors by hostel
                function filterFloorsByHostel(hostelId) {
                    const currentValue = editFloorSelect.value;
                    editFloorSelect.innerHTML = '<option value="">Select Floor</option>';
                    
                    const filteredFloors = allFloorOptions.filter(opt => 
                        opt.getAttribute('data-hostel-id') == hostelId
                    );
                    
                    if (filteredFloors.length > 0) {
                        filteredFloors.forEach(opt => {
                            editFloorSelect.appendChild(opt.cloneNode(true));
                        });
                        
                        // Restore previous selection if it belongs to current hostel
                        if (currentValue && filteredFloors.some(opt => opt.value == currentValue)) {
                            editFloorSelect.value = currentValue;
                        }
                    } else {
                        editFloorSelect.innerHTML = '<option value="">No floors available for this hostel</option>';
                    }
                }
                
                // When hostel is changed in edit mode, filter floors
                editHostelSelect.addEventListener('change', function() {
                    const hostelId = this.value;
                    if (hostelId) {
                        filterFloorsByHostel(hostelId);
                    } else {
                        editFloorSelect.innerHTML = '<option value="">Select Hostel First</option>';
                    }
                });
                
                // When room type is changed, update capacity
                editRoomTypeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const capacity = selectedOption.getAttribute('data-capacity');
                    
                    if (capacity) {
                        // Show confirmation before changing capacity
                        if (editCapacityInput.value && editCapacityInput.value != capacity) {
                            if (confirm('Changing room type will update capacity to ' + capacity + '. Continue?')) {
                                editCapacityInput.value = capacity;
                            } else {
                                // Revert to previous selection
                                const previousValue = editRoomTypeSelect.getAttribute('data-previous-value');
                                if (previousValue) {
                                    editRoomTypeSelect.value = previousValue;
                                }
                                return;
                            }
                        } else {
                            editCapacityInput.value = capacity;
                        }
                        editRoomTypeSelect.setAttribute('data-previous-value', this.value);
                    }
                });
                
                // Store initial value for room type
                editRoomTypeSelect.setAttribute('data-previous-value', editRoomTypeSelect.value);
            }
        }
    });
    </script>
</body>
</html>
