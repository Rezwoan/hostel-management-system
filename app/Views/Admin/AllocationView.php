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
                    <?php 
                    // Get URL parameters for auto-fill
                    $preSelectedStudentId = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
                    $preSelectedHostelId = isset($_GET['hostel_id']) ? (int)$_GET['hostel_id'] : 0;
                    $preSelectedAppId = isset($_GET['app_id']) ? (int)$_GET['app_id'] : 0;
                    ?>
                    <!-- Create Allocation Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_allocations">Allocations</a>
                        <span>/</span>
                        <span class="current">Create New Allocation</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Allocate Seat to Student</h3>
                        <p class="form-hint" style="margin-bottom: 15px; color: #666;">
                            <strong>Note:</strong> Only students with <span class="badge badge-success">APPROVED</span> applications 
                            who don't have active allocations are shown.
                        </p>
                        
                        <form action="index.php?page=admin_allocations" method="POST" id="allocationForm">
                            <input type="hidden" name="form_action" value="create_allocation">
                            <input type="hidden" id="application_id" name="application_id" value="<?php echo $preSelectedAppId; ?>">
                            
                            <!-- Student Selection Dropdown -->
                            <div class="form-group">
                                <label for="student_id">Student <span class="required">*</span></label>
                                <select id="student_id" name="student_id" class="form-control" required>
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
                                    <select id="hostel_id" name="hostel_id" class="form-control" required disabled>
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
                                <a href="index.php?page=admin_allocations" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                    <style>
                        .alert-info {
                            background: #e7f3ff;
                            border: 1px solid #b3d7ff;
                            padding: 12px;
                            border-radius: 4px;
                        }
                    </style>
                    
                    <script>
                        // =============================================
                        // STUDENT DROPDOWN WITH AUTO-FILL
                        // =============================================
                        let studentsData = []; // Store all approved students
                        
                        // Pre-selected values from URL (set by PHP)
                        const preSelectedStudentId = <?php echo $preSelectedStudentId; ?>;
                        const preSelectedHostelId = <?php echo $preSelectedHostelId; ?>;
                        const preSelectedAppId = <?php echo $preSelectedAppId; ?>;
                        
                        // Load all approved students on page load
                        function loadApprovedStudents() {
                            let studentSelect = document.getElementById("student_id");
                            studentSelect.innerHTML = '<option value="">Loading students...</option>';
                            
                            let xhr = new XMLHttpRequest();
                            xhr.open("GET", "app/Controllers/Api/get_approved_students.php", true);
                            
                            xhr.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    let response = JSON.parse(this.responseText);
                                    studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
                                    
                                    if (response.success && response.data.length > 0) {
                                        studentsData = response.data;
                                        response.data.forEach(function(student) {
                                            let option = document.createElement("option");
                                            option.value = student.id;
                                            option.textContent = student.name + " (" + student.email + ")";
                                            // Store additional data as data attributes
                                            option.dataset.applicationId = student.application_id;
                                            option.dataset.hostelId = student.hostel_id;
                                            option.dataset.hostelName = student.hostel_name;
                                            option.dataset.roomTypeName = student.room_type_name;
                                            studentSelect.appendChild(option);
                                        });
                                        
                                        // If pre-selected from URL, auto-select
                                        if (preSelectedStudentId > 0) {
                                            studentSelect.value = preSelectedStudentId;
                                            onStudentChange();
                                        }
                                    } else {
                                        studentSelect.innerHTML = '<option value="">No eligible students (need APPROVED application)</option>';
                                    }
                                }
                            };
                            
                            xhr.send();
                        }
                        
                        // When student is selected from dropdown
                        function onStudentChange() {
                            let studentSelect = document.getElementById("student_id");
                            let selectedOption = studentSelect.options[studentSelect.selectedIndex];
                            
                            if (!studentSelect.value) {
                                // Reset form
                                document.getElementById("application_id").value = "";
                                document.getElementById("applicationInfo").style.display = "none";
                                document.getElementById("hostel_id").value = "";
                                resetDependentDropdowns();
                                document.getElementById("submitBtn").disabled = true;
                                return;
                            }
                            
                            // Set hidden application ID
                            document.getElementById("application_id").value = selectedOption.dataset.applicationId;
                            
                            // Show application info
                            document.getElementById("applicationInfo").style.display = "block";
                            document.getElementById("appInfoContent").innerHTML = 
                                'Hostel: <strong>' + escapeHtml(selectedOption.dataset.hostelName) + '</strong> | ' +
                                'Room Type: <strong>' + escapeHtml(selectedOption.dataset.roomTypeName) + '</strong>';
                            
                            // Auto-select hostel from student's application
                            let hostelSelect = document.getElementById("hostel_id");
                            hostelSelect.value = selectedOption.dataset.hostelId;
                            
                            // Enable submit button
                            document.getElementById("submitBtn").disabled = false;
                            
                            // Load floors for this hostel
                            loadFloors();
                        }
                        
                        function resetDependentDropdowns() {
                            document.getElementById("floor_id").innerHTML = '<option value="">Select Floor</option>';
                            document.getElementById("floor_id").disabled = true;
                            document.getElementById("room_id").innerHTML = '<option value="">Select Room</option>';
                            document.getElementById("room_id").disabled = true;
                            document.getElementById("seat_id").innerHTML = '<option value="">Select Seat</option>';
                            document.getElementById("seat_id").disabled = true;
                        }
                        
                        function escapeHtml(text) {
                            if (!text) return '';
                            let div = document.createElement('div');
                            div.textContent = text;
                            return div.innerHTML;
                        }
                        
                        // =============================================
                        // CASCADING DROPDOWNS AJAX
                        // =============================================
                        
                        // When Hostel changes, load Floors
                        function loadFloors() {
                            let hostelId = document.getElementById("hostel_id").value;
                            let floorSelect = document.getElementById("floor_id");
                            let roomSelect = document.getElementById("room_id");
                            let seatSelect = document.getElementById("seat_id");
                            
                            // Reset dependent dropdowns
                            floorSelect.innerHTML = '<option value="">Loading floors...</option>';
                            floorSelect.disabled = true;
                            roomSelect.innerHTML = '<option value="">Select Room</option>';
                            roomSelect.disabled = true;
                            seatSelect.innerHTML = '<option value="">Select Seat</option>';
                            seatSelect.disabled = true;
                            
                            if (!hostelId) {
                                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                                return;
                            }
                            
                            let xhr = new XMLHttpRequest();
                            xhr.open("GET", "app/Controllers/Api/get_floors.php?hostel_id=" + hostelId, true);
                            
                            xhr.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    let response = JSON.parse(this.responseText);
                                    floorSelect.innerHTML = '<option value="">Select Floor</option>';
                                    if (response.success && response.data.length > 0) {
                                        response.data.forEach(function(floor) {
                                            let option = document.createElement("option");
                                            option.value = floor.id;
                                            option.textContent = "Floor " + floor.floor_number + (floor.name ? " - " + floor.name : "");
                                            floorSelect.appendChild(option);
                                        });
                                        floorSelect.disabled = false;
                                    } else {
                                        floorSelect.innerHTML = '<option value="">No floors available</option>';
                                    }
                                }
                            };
                            
                            xhr.send();
                        }
                        
                        // When Floor changes, load Rooms
                        function loadRooms() {
                            let floorId = document.getElementById("floor_id").value;
                            let roomSelect = document.getElementById("room_id");
                            let seatSelect = document.getElementById("seat_id");
                            
                            // Reset dependent dropdowns
                            roomSelect.innerHTML = '<option value="">Loading rooms...</option>';
                            roomSelect.disabled = true;
                            seatSelect.innerHTML = '<option value="">Select Seat</option>';
                            seatSelect.disabled = true;
                            
                            if (!floorId) {
                                roomSelect.innerHTML = '<option value="">Select Room</option>';
                                return;
                            }
                            
                            let xhr = new XMLHttpRequest();
                            xhr.open("GET", "app/Controllers/Api/get_rooms.php?floor_id=" + floorId, true);
                            
                            xhr.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    let response = JSON.parse(this.responseText);
                                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                                    if (response.success && response.data.length > 0) {
                                        response.data.forEach(function(room) {
                                            let option = document.createElement("option");
                                            option.value = room.id;
                                            option.textContent = "Room " + room.room_number + (room.room_type ? " (" + room.room_type + ")" : "");
                                            roomSelect.appendChild(option);
                                        });
                                        roomSelect.disabled = false;
                                    } else {
                                        roomSelect.innerHTML = '<option value="">No rooms available</option>';
                                    }
                                }
                            };
                            
                            xhr.send();
                        }
                        
                        // When Room changes, load Seats
                        function loadSeats() {
                            let roomId = document.getElementById("room_id").value;
                            let seatSelect = document.getElementById("seat_id");
                            
                            seatSelect.innerHTML = '<option value="">Loading seats...</option>';
                            seatSelect.disabled = true;
                            
                            if (!roomId) {
                                seatSelect.innerHTML = '<option value="">Select Seat</option>';
                                return;
                            }
                            
                            let xhr = new XMLHttpRequest();
                            xhr.open("GET", "app/Controllers/Api/get_seats.php?room_id=" + roomId + "&available_only=1", true);
                            
                            xhr.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    let response = JSON.parse(this.responseText);
                                    seatSelect.innerHTML = '<option value="">Select Seat</option>';
                                    if (response.success && response.data.length > 0) {
                                        response.data.forEach(function(seat) {
                                            let option = document.createElement("option");
                                            option.value = seat.id;
                                            option.textContent = seat.seat_label + " - Available";
                                            seatSelect.appendChild(option);
                                        });
                                        seatSelect.disabled = false;
                                    } else {
                                        seatSelect.innerHTML = '<option value="">No available seats in this room</option>';
                                    }
                                }
                            };
                            
                            xhr.send();
                        }
                        
                        // Add event listeners
                        document.getElementById("student_id").addEventListener("change", onStudentChange);
                        document.getElementById("hostel_id").addEventListener("change", loadFloors);
                        document.getElementById("floor_id").addEventListener("change", loadRooms);
                        document.getElementById("room_id").addEventListener("change", loadSeats);
                        
                        // Load students on page load
                        document.addEventListener("DOMContentLoaded", loadApprovedStudents);
                    </script>
                    
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
                                    <option value="ENDED" <?php echo ($data['allocation']['status'] ?? '') === 'ENDED' ? 'selected' : ''; ?>>Ended</option>
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
                            <div class="detail-label">Student Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['student_email'] ?? ''); ?></div>
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
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['seat_label'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['start_date'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">End Date</div>
                            <div class="detail-value"><?php echo !empty($data['allocation']['end_date']) ? htmlspecialchars($data['allocation']['end_date']) : '(Ongoing)'; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['allocation']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'ACTIVE') $statusClass = 'badge-success';
                                elseif ($status === 'ENDED') $statusClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created By</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['allocation']['created_by_name'] ?? ''); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <?php if (($data['allocation']['status'] ?? '') === 'ACTIVE'): ?>
                                <button type="button" class="btn btn-warning" onclick="endAllocationView(<?php echo (int)$data['allocation']['id']; ?>)">End Allocation</button>
                            <?php endif; ?>
                            <a href="index.php?page=admin_allocations" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                    <!-- Custom Confirmation Modal for View page -->
                    <div id="confirmModal" class="modal-overlay">
                        <div class="modal-box">
                            <h3 id="confirmTitle">Confirm Action</h3>
                            <p id="confirmMessage">Are you sure?</p>
                            <div class="modal-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
                            </div>
                        </div>
                    </div>
                    
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
                        
                        function endAllocationView(id) {
                            showConfirm("End Allocation", "Are you sure you want to end this allocation?", function() {
                                let xhr = new XMLHttpRequest();
                                xhr.open("POST", "app/Controllers/Api/end_allocation.php", true);
                                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                
                                xhr.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        let response = JSON.parse(this.responseText);
                                        if (response.success) {
                                            window.location.href = "index.php?page=admin_allocations&msg=allocation_ended";
                                        } else {
                                            alert("Error: " + response.error);
                                        }
                                    }
                                };
                                
                                xhr.send("id=" + id);
                            });
                        }
                    </script>
                    
                <?php else: ?>
                    <!-- Allocations List -->
                    <div class="page-header">
                        <h2>All Allocations</h2>
                        <a href="index.php?page=admin_allocations&action=add" class="btn btn-primary">Create New Allocation</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="allocSearch" class="form-control" placeholder="Search by student name..." data-table-search="allocationsTable">
                            <select id="statusFilter" class="form-control" data-filter-table="allocationsTable" data-filter-column="6">
                                <option value="">All Status</option>
                                <option value="ACTIVE">Active</option>
                                <option value="ENDED">Ended</option>
                            </select>
                            <select id="hostelFilter" class="form-control" data-filter-table="allocationsTable" data-filter-column="2">
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
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="allocationsTable">
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
                                            <tr data-id="<?php echo (int)$alloc['id']; ?>">
                                                <td><?php echo (int)$alloc['id']; ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($alloc['student_name'] ?? ''); ?><br>
                                                    <small><?php echo htmlspecialchars($alloc['student_email'] ?? ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($alloc['hostel_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['room_no'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($alloc['seat_label'] ?? ''); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($alloc['start_date'] ?? ''); ?><br>
                                                    <?php if (!empty($alloc['end_date'])): ?>
                                                        to <?php echo htmlspecialchars($alloc['end_date']); ?>
                                                    <?php else: ?>
                                                        <small>(ongoing)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $alloc['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'ACTIVE') $statusClass = 'badge-success';
                                                    elseif ($status === 'ENDED') $statusClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_allocations&action=view&id=<?php echo (int)$alloc['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <?php if ($status === 'ACTIVE'): ?>
                                                            <button type="button" class="btn btn-sm btn-danger" onclick="endAllocation(<?php echo (int)$alloc['id']; ?>, this)">End</button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteAllocation(<?php echo (int)$alloc['id']; ?>, this)">Delete</button>
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
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="confirmTitle">Confirm Action</h3>
            <p id="confirmMessage">Are you sure?</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>
    
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
        
        // End allocation via AJAX
        function endAllocation(id, btn) {
            let rowToUpdate = btn.closest("tr");
            
            showConfirm("End Allocation", "Are you sure you want to end this allocation? The seat will become available again.", function() {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "app/Controllers/Api/end_allocation.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            try {
                                let response = JSON.parse(this.responseText);
                                if (response.success) {
                                    // Update the row status badge and remove the End button
                                    let statusCell = rowToUpdate.querySelector("td:nth-child(7)");
                                    if (statusCell) {
                                        statusCell.innerHTML = '<span class="badge badge-secondary">ENDED</span>';
                                    }
                                    // Remove the End button
                                    let endBtn = rowToUpdate.querySelector("button[onclick*='endAllocation']");
                                    if (endBtn) endBtn.remove();
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
        
        // Delete allocation via AJAX
        function deleteAllocation(id, btn) {
            let rowToDelete = btn.closest("tr");
            
            showConfirm("Delete Allocation", "Are you sure you want to permanently delete this allocation record?", function() {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "app/Controllers/Api/delete_allocation.php", true);
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
    </script>
</body>
</html>
