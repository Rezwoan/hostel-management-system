<?php
// Admin Hostel Management View
$page = 'admin_hostels';
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
                <div class="header-actions">
                    <div class="user-info">
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    </div>
                </div>
            </header>
            
            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($action === 'add'): ?>
                    <!-- Add Hostel Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostels">Hostels</a>
                        <span>/</span>
                        <span class="current">Add New Hostel</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Hostel</h3>
                        <form action="index.php?page=admin_hostels" method="POST">
                            <input type="hidden" name="form_action" value="create_hostel">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Hostel Name <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="code">Hostel Code <span class="required">*</span></label>
                                    <input type="text" id="code" name="code" class="form-control" required>
                                    <span class="form-hint">Unique identifier (e.g., H001)</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Hostel</button>
                                <a href="index.php?page=admin_hostels" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['hostel'])): ?>
                    <!-- Edit Hostel Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostels">Hostels</a>
                        <span>/</span>
                        <span class="current">Edit Hostel</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Hostel</h3>
                        <form action="index.php?page=admin_hostels" method="POST">
                            <input type="hidden" name="form_action" value="update_hostel">
                            <input type="hidden" name="id" value="<?php echo (int)$data['hostel']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Hostel Name <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['hostel']['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="code">Hostel Code <span class="required">*</span></label>
                                    <input type="text" id="code" name="code" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['hostel']['code'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($data['hostel']['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE" <?php echo ($data['hostel']['status'] ?? '') === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo ($data['hostel']['status'] ?? '') === 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Hostel</button>
                                <a href="index.php?page=admin_hostels" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['hostel'])): ?>
                    <!-- View Hostel Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostels">Hostels</a>
                        <span>/</span>
                        <span class="current">View Hostel</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Hostel Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['hostel']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Code</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['code'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['hostel']['address'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge <?php echo ($data['hostel']['status'] ?? '') === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo htmlspecialchars($data['hostel']['status'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_hostels&action=edit&id=<?php echo (int)$data['hostel']['id']; ?>" class="btn btn-primary">Edit Hostel</a>
                            <a href="index.php?page=admin_hostels" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                    <!-- Floors in this Hostel -->
                    <div class="table-card" style="margin-bottom: 20px;">
                        <div class="table-card-header">
                            <h3>Floors</h3>
                            <a href="index.php?page=admin_floors&action=add" class="btn btn-sm btn-primary">Add Floor</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Floor No</th>
                                        <th>Label</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['floors'])): ?>
                                        <?php foreach ($data['floors'] as $floor): ?>
                                            <tr>
                                                <td><?php echo (int)$floor['floor_no']; ?></td>
                                                <td><?php echo htmlspecialchars($floor['label'] ?? ''); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_floors&action=edit&id=<?php echo (int)$floor['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="empty-state">No floors found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Rooms in this Hostel -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Rooms</h3>
                            <a href="index.php?page=admin_rooms&action=add" class="btn btn-sm btn-primary">Add Room</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Floor</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['rooms'])): ?>
                                        <?php foreach ($data['rooms'] as $room): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($room['room_no']); ?></td>
                                                <td><?php echo (int)($room['floor_no'] ?? 0); ?></td>
                                                <td><?php echo htmlspecialchars($room['room_type_name'] ?? ''); ?></td>
                                                <td><?php echo (int)$room['capacity']; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $room['status'] === 'AVAILABLE' ? 'badge-success' : 'badge-warning'; ?>">
                                                        <?php echo htmlspecialchars($room['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">No rooms found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Hostels List -->
                    <div class="page-header">
                        <h2>All Hostels</h2>
                        <a href="index.php?page=admin_hostels&action=add" class="btn btn-primary">Add New Hostel</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['hostels'])): ?>
                                        <?php foreach ($data['hostels'] as $hostel): ?>
                                            <tr>
                                                <td><?php echo (int)$hostel['id']; ?></td>
                                                <td><?php echo htmlspecialchars($hostel['name']); ?></td>
                                                <td><?php echo htmlspecialchars($hostel['code']); ?></td>
                                                <td><?php echo htmlspecialchars($hostel['address'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $hostel['status'] === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo htmlspecialchars($hostel['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_hostels&action=view&id=<?php echo (int)$hostel['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_hostels&action=edit&id=<?php echo (int)$hostel['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_hostels" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this hostel?');">
                                                            <input type="hidden" name="form_action" value="delete_hostel">
                                                            <input type="hidden" name="id" value="<?php echo (int)$hostel['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No hostels found</td>
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
