<?php
// Admin Room Type Management View
$page = 'admin_room_types';
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
                    <!-- Add Room Type Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_room_types">Room Types</a>
                        <span>/</span>
                        <span class="current">Add New Room Type</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Room Type</h3>
                        <form action="index.php?page=admin_room_types" method="POST">
                            <input type="hidden" name="form_action" value="create_room_type">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Type Name <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required placeholder="e.g., Single, Double, Triple">
                                </div>
                                
                                <div class="form-group">
                                    <label for="default_capacity">Default Capacity <span class="required">*</span></label>
                                    <input type="number" id="default_capacity" name="default_capacity" class="form-control" required min="1" max="10">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="default_fee">Default Fee <span class="required">*</span></label>
                                    <input type="number" id="default_fee" name="default_fee" class="form-control" required step="0.01" min="0">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe the room type features"></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Room Type</button>
                                <a href="index.php?page=admin_room_types" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['room_type'])): ?>
                    <!-- Edit Room Type Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_room_types">Room Types</a>
                        <span>/</span>
                        <span class="current">Edit Room Type</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Room Type</h3>
                        <form action="index.php?page=admin_room_types" method="POST">
                            <input type="hidden" name="form_action" value="update_room_type">
                            <input type="hidden" name="id" value="<?php echo (int)$data['room_type']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Type Name <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['room_type']['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="default_capacity">Default Capacity <span class="required">*</span></label>
                                    <input type="number" id="default_capacity" name="default_capacity" class="form-control" required min="1" max="10"
                                           value="<?php echo (int)($data['room_type']['default_capacity'] ?? 1); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="default_fee">Default Fee <span class="required">*</span></label>
                                    <input type="number" id="default_fee" name="default_fee" class="form-control" required step="0.01" min="0"
                                           value="<?php echo number_format((float)($data['room_type']['default_fee'] ?? 0), 2, '.', ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($data['room_type']['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Room Type</button>
                                <a href="index.php?page=admin_room_types" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['room_type'])): ?>
                    <!-- View Room Type Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_room_types">Room Types</a>
                        <span>/</span>
                        <span class="current">View Room Type</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Room Type Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['room_type']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room_type']['name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Capacity</div>
                            <div class="detail-value"><?php echo (int)($data['room_type']['default_capacity'] ?? 0); ?> person(s)</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Default Fee</div>
                            <div class="detail-value">$<?php echo number_format((float)($data['room_type']['default_fee'] ?? 0), 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['room_type']['description'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_room_types&action=edit&id=<?php echo (int)$data['room_type']['id']; ?>" class="btn btn-primary">Edit Room Type</a>
                            <a href="index.php?page=admin_room_types" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Room Types List -->
                    <div class="page-header">
                        <h2>All Room Types</h2>
                        <a href="index.php?page=admin_room_types&action=add" class="btn btn-primary">Add New Room Type</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Capacity</th>
                                        <th>Default Fee</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['room_types'])): ?>
                                        <?php foreach ($data['room_types'] as $roomType): ?>
                                            <tr>
                                                <td><?php echo (int)$roomType['id']; ?></td>
                                                <td><?php echo htmlspecialchars($roomType['name']); ?></td>
                                                <td><?php echo (int)($roomType['default_capacity'] ?? 0); ?></td>
                                                <td>$<?php echo number_format((float)($roomType['default_fee'] ?? 0), 2); ?></td>
                                                <td><?php echo htmlspecialchars(substr($roomType['description'] ?? '', 0, 50)); ?><?php echo strlen($roomType['description'] ?? '') > 50 ? '...' : ''; ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_room_types&action=view&id=<?php echo (int)$roomType['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_room_types&action=edit&id=<?php echo (int)$roomType['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_room_types" method="POST" style="display:inline;" onsubmit="return confirm('Delete this room type?');">
                                                            <input type="hidden" name="form_action" value="delete_room_type">
                                                            <input type="hidden" name="id" value="<?php echo (int)$roomType['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No room types found</td>
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
