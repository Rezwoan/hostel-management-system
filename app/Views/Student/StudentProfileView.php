<?php
// Student Profile View
$page = 'student_profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <?php include __DIR__ . '/partials/head-meta.php'; ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <main class="admin-main full-width">
            <div class="admin-content">
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <div class="page-header">
                    <h2>My Profile</h2>
                    <?php if ($action === 'view'): ?>
                        <button onclick="document.getElementById('editForm').scrollIntoView({behavior: 'smooth'})" class="btn btn-primary">Edit Profile</button>
                    <?php endif; ?>
                </div>
                
                <?php if ($action === 'view'): ?>
                    <!-- View Profile -->
                    <div class="detail-card">
                        <h3>Personal Information</h3>
                        
                        <!-- Profile Picture Display -->
                        <div class="detail-row">
                            <div class="detail-label">Profile Picture</div>
                            <div class="detail-value">
                                <img src="public/<?php echo htmlspecialchars($data['profile']['profile_picture'] ?? 'uploads/profile_pictures/default.png'); ?>?v=<?php echo time(); ?>" 
                                     alt="Profile Picture" 
                                     style="width: 150px; height: 150px; border-radius: 8px; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['email'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['phone'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['student_id'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['department'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Session Year</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['profile']['session_year'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><?php echo $data['profile']['dob'] ? date('F d, Y', strtotime($data['profile']['dob'])) : 'Not set'; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['profile']['address'] ?? '')); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Account Status</div>
                            <div class="detail-value">
                                <span class="badge badge-success"><?php echo htmlspecialchars($data['profile']['status'] ?? ''); ?></span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Member Since</div>
                            <div class="detail-value"><?php echo date('F d, Y', strtotime($data['profile']['created_at'] ?? '')); ?></div>
                        </div>
                    </div>
                    
                    <!-- Edit Profile Form -->
                    <div class="form-card" style="margin-top: 30px;" id="editForm">
                        <h3>Edit Profile</h3>
                        <form action="index.php?page=student_profile" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_action" value="update_profile">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name <span class="required">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($data['profile']['name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($data['profile']['email'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($data['profile']['phone'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" name="dob" id="dob" class="form-control" value="<?php echo htmlspecialchars($data['profile']['dob'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input type="text" name="department" id="department" class="form-control" value="<?php echo htmlspecialchars($data['profile']['department'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="session_year">Session Year</label>
                                    <input type="text" name="session_year" id="session_year" class="form-control" value="<?php echo htmlspecialchars($data['profile']['session_year'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" rows="3" class="form-control"><?php echo htmlspecialchars($data['profile']['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Update Profile Picture Form -->
                    <div class="form-card" style="margin-top: 30px;">
                        <h3>Update Profile Picture</h3>
                        <form action="index.php?page=student_profile" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_action" value="update_profile_picture">
                            
                            <div class="form-group">
                                <label>Current Profile Picture</label>
                                <div style="margin: 10px 0;">
                                    <img src="public/<?php echo htmlspecialchars($data['profile']['profile_picture'] ?? 'uploads/profile_pictures/default.png'); ?>?v=<?php echo time(); ?>" 
                                         alt="Current Profile Picture" 
                                         style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; border: 2px solid #ddd;">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="profile_picture">New Profile Picture <span class="required">*</span></label>
                                <input 
                                    type="file" 
                                    id="profile_picture" 
                                    name="profile_picture" 
                                    class="form-control"
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    required
                                >
                                <small class="form-text">Allowed formats: JPG, PNG, WEBP. Max size: 2MB</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Picture</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Change Password Form -->
                    <div class="form-card" style="margin-top: 30px;">
                        <h3>Change Password</h3>
                        <form action="index.php?page=student_profile" method="POST">
                            <input type="hidden" name="form_action" value="change_password">
                            
                            <div class="form-group">
                                <label for="current_password">Current Password <span class="required">*</span></label>
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">New Password <span class="required">*</span></label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" minlength="8" required>
                                    <small class="form-text">Must be at least 8 characters</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="8" required>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
