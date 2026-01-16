<?php
// Admin Student Profile Management View
$page = 'admin_students';
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
                
                <?php if ($action === 'view' && isset($data['student'])): ?>
                    <!-- View Student Profile Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_students">Students</a>
                        <span>/</span>
                        <span class="current">View Student</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Student Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">User ID</div>
                            <div class="detail-value"><?php echo (int)$data['student']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['email'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['phone'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['student_id'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Department</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['department'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Session Year</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['session_year'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['dob'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Address</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['address'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge <?php echo ($data['student']['status'] ?? '') === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo htmlspecialchars($data['student']['status'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Registered</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['student']['created_at'] ?? ''); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_students&action=edit&id=<?php echo (int)$data['student']['id']; ?>" class="btn btn-primary">Edit Student</a>
                            <a href="index.php?page=admin_students" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['student'])): ?>
                    <!-- Edit Student Profile Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_students">Students</a>
                        <span>/</span>
                        <span class="current">Edit Student</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Student Profile</h3>
                        <form action="index.php?page=admin_students" method="POST">
                            <input type="hidden" name="form_action" value="update_student">
                            <input type="hidden" name="user_id" value="<?php echo (int)$data['student']['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="student_id">Student ID <span class="required">*</span></label>
                                    <input type="text" id="student_id" name="student_id" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['student']['student_id'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input type="text" id="department" name="department" class="form-control"
                                           value="<?php echo htmlspecialchars($data['student']['department'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="session_year">Session Year</label>
                                    <input type="text" id="session_year" name="session_year" class="form-control"
                                           value="<?php echo htmlspecialchars($data['student']['session_year'] ?? ''); ?>" placeholder="e.g., 2024-2025">
                                </div>
                                
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" id="dob" name="dob" class="form-control"
                                           value="<?php echo htmlspecialchars($data['student']['dob'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($data['student']['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Student</button>
                                <a href="index.php?page=admin_students" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- Student Profiles List -->
                    <div class="page-header">
                        <h2>All Students</h2>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_students">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or student ID..."
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Session</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['students'])): ?>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <tr>
                                                <td><?php echo (int)$student['id']; ?></td>
                                                <td><?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($student['name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($student['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($student['department'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($student['session_year'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_students&action=view&id=<?php echo (int)$student['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_students&action=edit&id=<?php echo (int)$student['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No students found</td>
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
