<?php
// Admin Floor Management View
$page = 'admin_floors';
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
                    <!-- Add Floor Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_floors">Floors</a>
                        <span>/</span>
                        <span class="current">Add New Floor</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Floor</h3>
                        <form action="index.php?page=admin_floors" method="POST">
                            <input type="hidden" name="form_action" value="create_floor">
                            
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
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="floor_no">Floor Number <span class="required">*</span></label>
                                    <input type="number" id="floor_no" name="floor_no" class="form-control" required min="0">
                                    <span class="form-hint">Use 0 for Ground Floor</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="label">Floor Label</label>
                                    <input type="text" id="label" name="label" class="form-control" placeholder="e.g., Ground Floor, First Floor">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Floor</button>
                                <a href="index.php?page=admin_floors" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['floor'])): ?>
                    <!-- Edit Floor Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_floors">Floors</a>
                        <span>/</span>
                        <span class="current">Edit Floor</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Floor</h3>
                        <form action="index.php?page=admin_floors" method="POST">
                            <input type="hidden" name="form_action" value="update_floor">
                            <input type="hidden" name="id" value="<?php echo (int)$data['floor']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="hostel_id">Hostel <span class="required">*</span></label>
                                <select id="hostel_id" name="hostel_id" class="form-control" required>
                                    <?php if (!empty($data['hostels'])): ?>
                                        <?php foreach ($data['hostels'] as $hostel): ?>
                                            <option value="<?php echo (int)$hostel['id']; ?>" <?php echo ($data['floor']['hostel_id'] ?? 0) == $hostel['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($hostel['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="floor_no">Floor Number <span class="required">*</span></label>
                                    <input type="number" id="floor_no" name="floor_no" class="form-control" required min="0"
                                           value="<?php echo (int)($data['floor']['floor_no'] ?? 0); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="label">Floor Label</label>
                                    <input type="text" id="label" name="label" class="form-control"
                                           value="<?php echo htmlspecialchars($data['floor']['label'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Floor</button>
                                <a href="index.php?page=admin_floors" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['floor'])): ?>
                    <!-- View Floor Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_floors">Floors</a>
                        <span>/</span>
                        <span class="current">View Floor</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Floor Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['floor']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['floor']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Floor Number</div>
                            <div class="detail-value"><?php echo (int)$data['floor']['floor_no']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Label</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['floor']['label'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_floors&action=edit&id=<?php echo (int)$data['floor']['id']; ?>" class="btn btn-primary">Edit Floor</a>
                            <a href="index.php?page=admin_floors" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                    <!-- Rooms on this Floor -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Rooms on this Floor</h3>
                            <a href="index.php?page=admin_rooms&action=add" class="btn btn-sm btn-primary">Add Room</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
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
                                                <td><?php echo htmlspecialchars($room['room_no']); ?></td>
                                                <td><?php echo htmlspecialchars($room['room_type_name'] ?? ''); ?></td>
                                                <td><?php echo (int)$room['capacity']; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $room['status'] === 'AVAILABLE' ? 'badge-success' : 'badge-warning'; ?>">
                                                        <?php echo htmlspecialchars($room['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="index.php?page=admin_rooms&action=edit&id=<?php echo (int)$room['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">No rooms found on this floor</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Floors List -->
                    <div class="page-header">
                        <h2>All Floors</h2>
                        <a href="index.php?page=admin_floors&action=add" class="btn btn-primary">Add New Floor</a>
                    </div>
                    
                    <!-- Filter by Hostel -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_floors">
                            <select name="hostel_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Hostels</option>
                                <?php if (!empty($data['hostels'])): ?>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo (int)$hostel['id']; ?>" <?php echo (isset($_GET['hostel_id']) && $_GET['hostel_id'] == $hostel['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hostel</th>
                                        <th>Floor No</th>
                                        <th>Label</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['floors'])): ?>
                                        <?php foreach ($data['floors'] as $floor): ?>
                                            <tr>
                                                <td><?php echo (int)$floor['id']; ?></td>
                                                <td><?php echo htmlspecialchars($floor['hostel_name'] ?? ''); ?></td>
                                                <td><?php echo (int)$floor['floor_no']; ?></td>
                                                <td><?php echo htmlspecialchars($floor['label'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_floors&action=view&id=<?php echo (int)$floor['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_floors&action=edit&id=<?php echo (int)$floor['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_floors" method="POST" style="display:inline;" onsubmit="return confirm('Delete this floor?');">
                                                            <input type="hidden" name="form_action" value="delete_floor">
                                                            <input type="hidden" name="id" value="<?php echo (int)$floor['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">No floors found</td>
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
