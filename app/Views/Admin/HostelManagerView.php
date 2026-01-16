<?php
// Admin Hostel Manager Management View
$page = 'admin_hostel_managers';
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
                    <!-- Assign Manager Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostel_managers">Hostel Managers</a>
                        <span>/</span>
                        <span class="current">Assign Manager</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Assign Manager to Hostel</h3>
                        <form action="index.php?page=admin_hostel_managers" method="POST">
                            <input type="hidden" name="form_action" value="assign_manager">
                            
                            <div class="form-group">
                                <label for="user_id">Manager <span class="required">*</span></label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <option value="">Select Manager</option>
                                    <?php if (!empty($data['managers'])): ?>
                                        <?php foreach ($data['managers'] as $manager): ?>
                                            <option value="<?php echo (int)$manager['id']; ?>">
                                                <?php echo htmlspecialchars($manager['name']); ?> (<?php echo htmlspecialchars($manager['email']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="form-hint">Only users with MANAGER role are listed</span>
                            </div>
                            
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
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Assign Manager</button>
                                <a href="index.php?page=admin_hostel_managers" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['assignment'])): ?>
                    <!-- Edit Assignment Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostel_managers">Hostel Managers</a>
                        <span>/</span>
                        <span class="current">Edit Assignment</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Manager Assignment</h3>
                        <form action="index.php?page=admin_hostel_managers" method="POST">
                            <input type="hidden" name="form_action" value="update_assignment">
                            <input type="hidden" name="id" value="<?php echo (int)$data['assignment']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="user_id">Manager <span class="required">*</span></label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <?php if (!empty($data['managers'])): ?>
                                        <?php foreach ($data['managers'] as $manager): ?>
                                            <option value="<?php echo (int)$manager['id']; ?>" <?php echo ($data['assignment']['user_id'] ?? 0) == $manager['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($manager['name']); ?> (<?php echo htmlspecialchars($manager['email']); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="hostel_id">Hostel <span class="required">*</span></label>
                                <select id="hostel_id" name="hostel_id" class="form-control" required>
                                    <?php if (!empty($data['hostels'])): ?>
                                        <?php foreach ($data['hostels'] as $hostel): ?>
                                            <option value="<?php echo (int)$hostel['id']; ?>" <?php echo ($data['assignment']['hostel_id'] ?? 0) == $hostel['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($hostel['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Assignment</button>
                                <a href="index.php?page=admin_hostel_managers" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['assignment'])): ?>
                    <!-- View Assignment Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_hostel_managers">Hostel Managers</a>
                        <span>/</span>
                        <span class="current">View Assignment</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Assignment Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['assignment']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Manager Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['assignment']['manager_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Manager Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['assignment']['manager_email'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['assignment']['hostel_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Assigned On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['assignment']['created_at'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_hostel_managers&action=edit&id=<?php echo (int)$data['assignment']['id']; ?>" class="btn btn-primary">Edit Assignment</a>
                            <a href="index.php?page=admin_hostel_managers" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Assignments List -->
                    <div class="page-header">
                        <h2>Hostel Manager Assignments</h2>
                        <a href="index.php?page=admin_hostel_managers&action=add" class="btn btn-primary">Assign Manager</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Manager</th>
                                        <th>Email</th>
                                        <th>Hostel</th>
                                        <th>Assigned On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['assignments'])): ?>
                                        <?php foreach ($data['assignments'] as $assignment): ?>
                                            <tr>
                                                <td><?php echo (int)$assignment['id']; ?></td>
                                                <td><?php echo htmlspecialchars($assignment['manager_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($assignment['manager_email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($assignment['hostel_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($assignment['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_hostel_managers&action=view&id=<?php echo (int)$assignment['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_hostel_managers&action=edit&id=<?php echo (int)$assignment['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_hostel_managers" method="POST" style="display:inline;" onsubmit="return confirm('Remove this assignment?');">
                                                            <input type="hidden" name="form_action" value="remove_assignment">
                                                            <input type="hidden" name="id" value="<?php echo (int)$assignment['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No manager assignments found</td>
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
