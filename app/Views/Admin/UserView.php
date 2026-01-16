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
                    
                    <!-- Live Search -->
                    <div class="search-box">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Search users...">
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
                                                    <button type="button" class="btn btn-sm toggle-status-btn <?php echo $user['status'] === 'ACTIVE' ? 'btn-success' : 'btn-danger'; ?>" 
                                                            data-id="<?php echo (int)$user['id']; ?>"
                                                            data-status="<?php echo htmlspecialchars($user['status']); ?>"
                                                            onclick="toggleUserStatus(this)">
                                                        <?php echo htmlspecialchars($user['status']); ?>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_users&action=view&id=<?php echo (int)$user['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_users&action=edit&id=<?php echo (int)$user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteOptions(<?php echo (int)$user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>', this)">Delete</button>
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
    
    <!-- Delete Options Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Delete User</h3>
            <p id="deleteUserName"></p>
            <p style="margin-bottom: 15px;">Choose delete option:</p>
            <div class="delete-options">
                <button type="button" class="btn btn-warning" id="softDeleteBtn" style="width: 100%; margin-bottom: 10px;">
                    <strong>Deactivate</strong><br>
                    <small>Set user status to INACTIVE (can be reactivated later)</small>
                </button>
                <button type="button" class="btn btn-danger" id="hardDeleteBtn" style="width: 100%;">
                    <strong>Permanently Delete</strong><br>
                    <small>Remove user and all related data from database</small>
                </button>
            </div>
            <div class="modal-actions" style="margin-top: 15px;">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            </div>
        </div>
    </div>
    
    <script>
        let pendingUserId = null;
        let pendingRow = null;
        
        function showDeleteOptions(id, userName, btn) {
            pendingUserId = id;
            pendingRow = btn.closest("tr");
            document.getElementById("deleteUserName").textContent = "User: " + userName;
            document.getElementById("deleteModal").classList.add("open");
        }
        
        function closeDeleteModal() {
            document.getElementById("deleteModal").classList.remove("open");
            pendingUserId = null;
            pendingRow = null;
        }
        
        document.getElementById("softDeleteBtn").addEventListener("click", function() {
            if (pendingUserId) doDelete(pendingUserId, "soft", pendingRow);
            closeDeleteModal();
        });
        
        document.getElementById("hardDeleteBtn").addEventListener("click", function() {
            if (confirm("Are you ABSOLUTELY sure? This will permanently delete the user and ALL their data!")) {
                if (pendingUserId) doDelete(pendingUserId, "hard", pendingRow);
                closeDeleteModal();
            }
        });
        
        function doDelete(id, type, row) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "app/Controllers/Api/delete_user.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        try {
                            let response = JSON.parse(this.responseText);
                            if (response.success) {
                                row.style.transition = "opacity 0.3s";
                                row.style.opacity = "0";
                                setTimeout(function() { row.remove(); }, 300);
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
            
            xhr.send("id=" + id + "&type=" + type);
        }
        }
        
        // Toggle user status via AJAX
        function toggleUserStatus(btn) {
            let id = btn.getAttribute("data-id");
            let currentStatus = btn.getAttribute("data-status");
            let newStatus = currentStatus === "ACTIVE" ? "INACTIVE" : "ACTIVE";
            
            btn.disabled = true;
            
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "app/Controllers/Api/update_user_status.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let response = JSON.parse(this.responseText);
                    btn.disabled = false;
                    
                    if (response.success) {
                        btn.setAttribute("data-status", newStatus);
                        btn.textContent = newStatus;
                        btn.className = "btn btn-sm toggle-status-btn " + (newStatus === "ACTIVE" ? "btn-success" : "btn-danger");
                    } else {
                        alert("Error: " + response.error);
                    }
                }
            };
            
            xhr.send("id=" + id + "&status=" + newStatus);
        }
        
        // Email validation on Add User form
        let emailTimer;
        let emailInput = document.querySelector(".validate-email");
        let emailFeedback = document.getElementById("emailFeedback");
        
        if (emailInput && emailFeedback) {
            emailInput.addEventListener("keyup", function() {
                clearTimeout(emailTimer);
                let email = this.value.trim();
                
                if (!email || !email.includes("@")) {
                    emailFeedback.textContent = "";
                    return;
                }
                
                emailTimer = setTimeout(function() {
                    let xhr = new XMLHttpRequest();
                    xhr.open("GET", "app/Controllers/Api/check_email.php?email=" + encodeURIComponent(email), true);
                    
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let response = JSON.parse(this.responseText);
                            if (response.success) {
                                if (response.exists) {
                                    emailFeedback.textContent = "✗ Email already exists";
                                    emailFeedback.style.color = "#dc3545";
                                } else {
                                    emailFeedback.textContent = "✓ Email available";
                                    emailFeedback.style.color = "#28a745";
                                }
                            }
                        }
                    };
                    
                    xhr.send();
                }, 500);
            });
        }
        
        // Table search
        document.getElementById("tableSearch")?.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#usersTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    </script>
</body>
</html>
