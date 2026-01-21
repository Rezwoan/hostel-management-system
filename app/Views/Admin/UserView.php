<?php
// Admin User Management View
$page = 'admin_users';
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
                    <!-- Add User Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_users">Users</a>
                        <span>/</span>
                        <span class="current">Add New User</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New User</h3>
                        <form action="index.php?page=admin_users" method="POST">
                            <input type="hidden" name="form_action" value="create_user">
                            
                            <div class="form-group">
                                <label for="name">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-control validate-email" required>
                                <span class="form-hint" id="emailFeedback"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password <span class="required">*</span></label>
                                <input type="password" id="password" name="password" class="form-control" required minlength="6">
                                <span class="form-hint">Minimum 6 characters</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="role_id">Role <span class="required">*</span></label>
                                    <select id="role_id" name="role_id" class="form-control" required>
                                        <?php foreach ($data['roles'] ?? [] as $role): ?>
                                            <option value="<?php echo (int)$role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="ACTIVE">Active</option>
                                        <option value="INACTIVE">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create User</button>
                                <a href="index.php?page=admin_users" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['user'])): ?>
                    <!-- Edit User Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_users">Users</a>
                        <span>/</span>
                        <span class="current">Edit User</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit User</h3>
                        <form action="index.php?page=admin_users" method="POST">
                            <input type="hidden" name="form_action" value="update_user">
                            <input type="hidden" name="id" value="<?php echo (int)$data['user']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="name">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required 
                                       value="<?php echo htmlspecialchars($data['user']['name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" required
                                       value="<?php echo htmlspecialchars($data['user']['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                       value="<?php echo htmlspecialchars($data['user']['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE" <?php echo ($data['user']['status'] ?? '') === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo ($data['user']['status'] ?? '') === 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="index.php?page=admin_users" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        
                        <hr style="margin: 30px 0;">
                        
                        <h3>Change Password</h3>
                        <form action="index.php?page=admin_users" method="POST">
                            <input type="hidden" name="form_action" value="update_user_password">
                            <input type="hidden" name="id" value="<?php echo (int)$data['user']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="new_password">New Password <span class="required">*</span></label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required minlength="6">
                                <span class="form-hint">Minimum 6 characters</span>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning">Change Password</button>
                            </div>
                        </form>
                        
                        <hr style="margin: 30px 0;">
                        
                        <h3>Change Role</h3>
                        <form action="index.php?page=admin_users" method="POST">
                            <input type="hidden" name="form_action" value="change_user_role">
                            <input type="hidden" name="user_id" value="<?php echo (int)$data['user']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="new_role_id">New Role <span class="required">*</span></label>
                                <select id="new_role_id" name="new_role_id" class="form-control" required>
                                    <?php foreach ($data['roles'] ?? [] as $role): ?>
                                        <option value="<?php echo (int)$role['id']; ?>" 
                                                <?php echo ($data['user']['role_id'] ?? 0) == $role['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-warning">Change Role</button>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['user'])): ?>
                    <!-- View User Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_users">Users</a>
                        <span>/</span>
                        <span class="current">View User</span>
                    </div>
                    
                    <div class="detail-card">
                        <div style="display: flex; gap: 30px; align-items: flex-start;">
                            <div style="flex: 1;">
                                <h3>User Details</h3>
                                <div class="detail-row">
                                    <div class="detail-label">ID</div>
                                    <div class="detail-value"><?php echo (int)$data['user']['id']; ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Name</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['user']['name'] ?? ''); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['user']['email'] ?? ''); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Phone</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['user']['phone'] ?? 'N/A'); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Role</div>
                                    <div class="detail-value">
                                        <span class="badge badge-info"><?php echo htmlspecialchars($data['user']['roles'] ?? 'No Role Assigned'); ?></span>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Status</div>
                                    <div class="detail-value">
                                        <span class="badge <?php echo ($data['user']['status'] ?? '') === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo htmlspecialchars($data['user']['status'] ?? ''); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Created</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['user']['created_at'] ?? ''); ?></div>
                                </div>
                            </div>
                            
                            <?php if (!empty($data['user']['profile_picture'])): ?>
                            <div style="flex-shrink: 0;">
                                <div style="text-align: center;">
                                    <?php 
                                    $imagePath = $data['user']['profile_picture'];
                                    // If path doesn't start with public/, add it
                                    if (strpos($imagePath, 'public/') !== 0) {
                                        $imagePath = 'public/' . $imagePath;
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                         alt="Profile Picture" 
                                         style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; border: 3px solid #ddd;">
                                    <p style="margin-top: 10px; font-size: 12px; color: #666;">Profile Picture</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($data['user']['student_id'])): ?>
                        <hr style="margin: 20px 0;">
                        <h4 style="margin-bottom: 15px;">Student Profile</h4>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['user']['student_id'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['user']['department'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Session Year</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['user']['session_year'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['user']['dob'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['user']['address'] ?? 'N/A'); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_users&action=edit&id=<?php echo (int)$data['user']['id']; ?>" class="btn btn-primary">Edit User</a>
                            <a href="index.php?page=admin_users" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Users List -->
                    <div class="page-header">
                        <h2>All Users</h2>
                        <a href="index.php?page=admin_users&action=add" class="btn btn-primary">Add New User</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="userSearch" class="form-control" placeholder="Search users..." data-table-search="usersTable">
                            <select id="roleFilter" class="form-control" data-filter-table="usersTable" data-filter-column="3">
                                <option value="">All Roles</option>
                                <option value="ADMIN">Admin</option>
                                <option value="MANAGER">Manager</option>
                                <option value="STUDENT">Student</option>
                            </select>
                            <select id="statusFilter" class="form-control" data-filter-table="usersTable" data-filter-column="4">
                                <option value="">All Status</option>
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['users'])): ?>
                                        <?php foreach ($data['users'] as $user): ?>
                                            <tr data-id="<?php echo (int)$user['id']; ?>">
                                                <td><?php echo (int)$user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['roles'] ?? 'No Role'); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $user['status'] === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo htmlspecialchars($user['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_users&action=view&id=<?php echo (int)$user['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_users&action=edit&id=<?php echo (int)$user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form method="POST" action="index.php?page=admin_users" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                            <input type="hidden" name="form_action" value="delete_user">
                                                            <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No users found</td>
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
        // No AJAX functionality - all actions use traditional form submissions
    </script>
</body>
</html>
