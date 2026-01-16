<?php
// Admin Admin List View
$page = 'admin_admins';
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
                
                <?php if ($action === 'view' && isset($data['admin'])): ?>
                    <!-- View Admin -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_admins">Admins</a>
                        <span>/</span>
                        <span class="current"><?php echo htmlspecialchars($data['admin']['name']); ?></span>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-header">
                            <h2><?php echo htmlspecialchars($data['admin']['name']); ?></h2>
                            <span class="badge <?php echo $data['admin']['status'] === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo htmlspecialchars($data['admin']['status']); ?>
                            </span>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>Email</label>
                                <span><?php echo htmlspecialchars($data['admin']['email']); ?></span>
                            </div>
                            <div class="detail-item">
                                <label>Role</label>
                                <span><?php echo htmlspecialchars($data['admin']['role_name']); ?></span>
                            </div>
                            <div class="detail-item">
                                <label>Created</label>
                                <span><?php echo htmlspecialchars(date('M d, Y', strtotime($data['admin']['created_at']))); ?></span>
                            </div>
                        </div>
                        <div class="detail-actions">
                            <a href="index.php?page=admin_users&action=edit&id=<?php echo (int)$data['admin']['id']; ?>" class="btn btn-primary">Edit User</a>
                            <a href="index.php?page=admin_admins" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Admins List -->
                    <div class="page-header">
                        <h2>All Admins</h2>
                        <a href="index.php?page=admin_users&action=add" class="btn btn-primary">+ Add New User</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="adminSearch" class="form-control" placeholder="Search admins..." data-table-search="adminsTable">
                            <select id="statusFilter" class="form-control" data-filter-table="adminsTable" data-filter-column="3">
                                <option value="">All Status</option>
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="adminsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['admins'])): ?>
                                        <?php foreach ($data['admins'] as $admin): ?>
                                            <tr>
                                                <td><?php echo (int)$admin['id']; ?></td>
                                                <td><?php echo htmlspecialchars($admin['name']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $admin['status'] === 'ACTIVE' ? 'badge-success' : 'badge-danger'; ?>">
                                                        <?php echo htmlspecialchars($admin['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars(date('M d, Y', strtotime($admin['created_at']))); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_admins&action=view&id=<?php echo (int)$admin['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_users&action=edit&id=<?php echo (int)$admin['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No admins found</td>
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
