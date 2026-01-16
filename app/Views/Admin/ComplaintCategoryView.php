<?php
// Admin Complaint Category Management View
$page = 'admin_complaint_categories';
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
                    <!-- Add Category Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_complaint_categories">Complaint Categories</a>
                        <span>/</span>
                        <span class="current">Add New Category</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Complaint Category</h3>
                        <form action="index.php?page=admin_complaint_categories" method="POST">
                            <input type="hidden" name="form_action" value="create_category">
                            
                            <div class="form-group">
                                <label for="name">Category Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g., Maintenance, Cleanliness, Security">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe what types of complaints fall under this category"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Category</button>
                                <a href="index.php?page=admin_complaint_categories" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['category'])): ?>
                    <!-- Edit Category Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_complaint_categories">Complaint Categories</a>
                        <span>/</span>
                        <span class="current">Edit Category</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Complaint Category</h3>
                        <form action="index.php?page=admin_complaint_categories" method="POST">
                            <input type="hidden" name="form_action" value="update_category">
                            <input type="hidden" name="id" value="<?php echo (int)$data['category']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="name">Category Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       value="<?php echo htmlspecialchars($data['category']['name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($data['category']['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="ACTIVE" <?php echo ($data['category']['status'] ?? '') === 'ACTIVE' ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo ($data['category']['status'] ?? '') === 'INACTIVE' ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Category</button>
                                <a href="index.php?page=admin_complaint_categories" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- Categories List -->
                    <div class="page-header">
                        <h2>All Complaint Categories</h2>
                        <a href="index.php?page=admin_complaint_categories&action=add" class="btn btn-primary">Add New Category</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['categories'])): ?>
                                        <?php foreach ($data['categories'] as $category): ?>
                                            <tr>
                                                <td><?php echo (int)$category['id']; ?></td>
                                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_complaint_categories&action=edit&id=<?php echo (int)$category['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_complaint_categories" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                                                            <input type="hidden" name="form_action" value="delete_category">
                                                            <input type="hidden" name="id" value="<?php echo (int)$category['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="empty-state">No complaint categories found</td>
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
