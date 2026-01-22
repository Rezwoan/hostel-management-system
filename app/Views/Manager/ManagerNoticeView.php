<?php
// Manager Notice View
$page = 'manager_notices';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
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
                
                <?php if ($action === 'add' || $action === 'edit'): ?>
                    <!-- Create/Edit Notice Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_notices">Notices</a>
                        <span>/</span>
                        <span class="current"><?php echo $action === 'add' ? 'Create Notice' : 'Edit Notice'; ?></span>
                    </div>
                    
                    <div class="form-card">
                        <h3><?php echo $action === 'add' ? 'Create Hostel Notice' : 'Edit Notice'; ?></h3>
                        <form action="index.php?page=manager_notices" method="POST">
                            <input type="hidden" name="form_action" value="<?php echo $action === 'add' ? 'create_notice' : 'update_notice'; ?>">
                            <?php if ($action === 'edit'): ?>
                                <input type="hidden" name="id" value="<?php echo (int)$data['notice']['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="hostel_id">Hostel *</label>
                                <select name="hostel_id" id="hostel_id" required class="form-control" <?php echo $action === 'edit' ? 'disabled' : ''; ?>>
                                    <option value="">-- Select Hostel --</option>
                                    <?php foreach ($data['hostels'] as $hostel): ?>
                                        <option value="<?php echo (int)$hostel['id']; ?>" 
                                            <?php echo ($action === 'edit' && $data['notice']['hostel_id'] == $hostel['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($action === 'edit'): ?>
                                    <input type="hidden" name="hostel_id" value="<?php echo (int)$data['notice']['hostel_id']; ?>">
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="title">Title *</label>
                                <input type="text" name="title" id="title" required class="form-control" 
                                    value="<?php echo $action === 'edit' ? htmlspecialchars($data['notice']['title']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="body">Body *</label>
                                <textarea name="body" id="body" required class="form-control" rows="6"><?php echo $action === 'edit' ? htmlspecialchars($data['notice']['body']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select name="status" id="status" required class="form-control">
                                    <option value="PUBLISHED" <?php echo ($action === 'edit' && $data['notice']['status'] === 'PUBLISHED') ? 'selected' : ''; ?>>PUBLISHED</option>
                                    <option value="ARCHIVED" <?php echo ($action === 'edit' && $data['notice']['status'] === 'ARCHIVED') ? 'selected' : ''; ?>>ARCHIVED</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="publish_at">Publish At (Optional)</label>
                                <input type="datetime-local" name="publish_at" id="publish_at" class="form-control"
                                    value="<?php echo $action === 'edit' && $data['notice']['publish_at'] ? date('Y-m-d\TH:i', strtotime($data['notice']['publish_at'])) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="expire_at">Expire At (Optional)</label>
                                <input type="datetime-local" name="expire_at" id="expire_at" class="form-control"
                                    value="<?php echo $action === 'edit' && $data['notice']['expire_at'] ? date('Y-m-d\TH:i', strtotime($data['notice']['expire_at'])) : ''; ?>">
                            </div>
                            
                            <button type="submit" class="btn btn-success"><?php echo $action === 'add' ? 'Create Notice' : 'Update Notice'; ?></button>
                            <a href="index.php?page=manager_notices" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                    
                <?php else: ?>
                    <!-- List Notices -->
                    <div class="page-header">
                        <h2>Hostel Notices</h2>
                        <a href="index.php?page=manager_notices&action=add" class="btn btn-primary">+ New Notice</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <input type="text" id="searchInput" placeholder="Search by title..." class="form-control">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="PUBLISHED">Published</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                        <?php if (isset($data['hostels']) && count($data['hostels']) > 1): ?>
                        <select id="hostelFilter" class="form-control">
                            <option value="">All Hostels</option>
                            <?php foreach ($data['hostels'] as $hostel): ?>
                                <option value="<?php echo htmlspecialchars($hostel['name']); ?>">
                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>All Notices</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Hostel</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['notices'])): ?>
                                        <?php foreach ($data['notices'] as $notice): ?>
                                            <tr>
                                                <td><?php echo (int)$notice['id']; ?></td>
                                                <td><?php echo htmlspecialchars($notice['hostel_name']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($notice['title'], 0, 50)); ?><?php echo strlen($notice['title']) > 50 ? '...' : ''; ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo $notice['status'] === 'PUBLISHED' ? 'success' : 'secondary'; ?>">
                                                        <?php echo htmlspecialchars($notice['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d', strtotime($notice['created_at'])); ?></td>
                                                <td>
                                                    <a href="index.php?page=manager_notices&action=edit&id=<?php echo (int)$notice['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="index.php?page=manager_notices" method="POST" style="display:inline;" onsubmit="return confirm('Delete this notice?');">
                                                        <input type="hidden" name="form_action" value="delete_notice">
                                                        <input type="hidden" name="id" value="<?php echo (int)$notice['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No notices found</td>
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
