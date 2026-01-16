<?php
// Admin Notice Management View
$page = 'admin_notices';
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
                    <!-- Add Notice Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_notices">Notices</a>
                        <span>/</span>
                        <span class="current">Create New Notice</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Create New Notice</h3>
                        <form action="index.php?page=admin_notices" method="POST">
                            <input type="hidden" name="form_action" value="create_notice">
                            
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" id="title" name="title" class="form-control" required placeholder="Notice title">
                            </div>
                            
                            <div class="form-group">
                                <label for="content">Content <span class="required">*</span></label>
                                <textarea id="content" name="content" class="form-control" rows="6" required placeholder="Notice content..."></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="target_audience">Target Audience <span class="required">*</span></label>
                                    <select id="target_audience" name="target_audience" class="form-control" required>
                                        <option value="ALL">All Users</option>
                                        <option value="STUDENTS">Students Only</option>
                                        <option value="MANAGERS">Managers Only</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="hostel_id">Specific Hostel (Optional)</label>
                                    <select id="hostel_id" name="hostel_id" class="form-control">
                                        <option value="">All Hostels</option>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>">
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select id="priority" name="priority" class="form-control">
                                        <option value="NORMAL">Normal</option>
                                        <option value="HIGH">High (Important)</option>
                                        <option value="LOW">Low</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="expires_at">Expires On (Optional)</label>
                                    <input type="date" id="expires_at" name="expires_at" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="DRAFT">Draft</option>
                                    <option value="PUBLISHED">Publish Now</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Notice</button>
                                <a href="index.php?page=admin_notices" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['notice'])): ?>
                    <!-- Edit Notice Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_notices">Notices</a>
                        <span>/</span>
                        <span class="current">Edit Notice</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Notice</h3>
                        <form action="index.php?page=admin_notices" method="POST">
                            <input type="hidden" name="form_action" value="update_notice">
                            <input type="hidden" name="id" value="<?php echo (int)$data['notice']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" id="title" name="title" class="form-control" required
                                       value="<?php echo htmlspecialchars($data['notice']['title'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="content">Content <span class="required">*</span></label>
                                <textarea id="content" name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($data['notice']['content'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="target_audience">Target Audience <span class="required">*</span></label>
                                    <select id="target_audience" name="target_audience" class="form-control" required>
                                        <option value="ALL" <?php echo ($data['notice']['target_audience'] ?? '') === 'ALL' ? 'selected' : ''; ?>>All Users</option>
                                        <option value="STUDENTS" <?php echo ($data['notice']['target_audience'] ?? '') === 'STUDENTS' ? 'selected' : ''; ?>>Students Only</option>
                                        <option value="MANAGERS" <?php echo ($data['notice']['target_audience'] ?? '') === 'MANAGERS' ? 'selected' : ''; ?>>Managers Only</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="hostel_id">Specific Hostel</label>
                                    <select id="hostel_id" name="hostel_id" class="form-control">
                                        <option value="">All Hostels</option>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>" <?php echo ($data['notice']['hostel_id'] ?? '') == $hostel['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select id="priority" name="priority" class="form-control">
                                        <option value="LOW" <?php echo ($data['notice']['priority'] ?? '') === 'LOW' ? 'selected' : ''; ?>>Low</option>
                                        <option value="NORMAL" <?php echo ($data['notice']['priority'] ?? '') === 'NORMAL' ? 'selected' : ''; ?>>Normal</option>
                                        <option value="HIGH" <?php echo ($data['notice']['priority'] ?? '') === 'HIGH' ? 'selected' : ''; ?>>High</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="expires_at">Expires On</label>
                                    <input type="date" id="expires_at" name="expires_at" class="form-control"
                                           value="<?php echo htmlspecialchars($data['notice']['expires_at'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="DRAFT" <?php echo ($data['notice']['status'] ?? '') === 'DRAFT' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="PUBLISHED" <?php echo ($data['notice']['status'] ?? '') === 'PUBLISHED' ? 'selected' : ''; ?>>Published</option>
                                    <option value="ARCHIVED" <?php echo ($data['notice']['status'] ?? '') === 'ARCHIVED' ? 'selected' : ''; ?>>Archived</option>
                                </select>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Notice</button>
                                <a href="index.php?page=admin_notices" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['notice'])): ?>
                    <!-- View Notice Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_notices">Notices</a>
                        <span>/</span>
                        <span class="current">View Notice</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3><?php echo htmlspecialchars($data['notice']['title'] ?? ''); ?></h3>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['notice']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'PUBLISHED') $statusClass = 'badge-success';
                                elseif ($status === 'ARCHIVED') $statusClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Priority</div>
                            <div class="detail-value">
                                <?php 
                                $priority = $data['notice']['priority'] ?? 'NORMAL';
                                $priorityClass = 'badge-info';
                                if ($priority === 'HIGH') $priorityClass = 'badge-danger';
                                elseif ($priority === 'LOW') $priorityClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $priorityClass; ?>">
                                    <?php echo htmlspecialchars($priority); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Target Audience</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['notice']['target_audience'] ?? 'All'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Hostel</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['notice']['hostel_name'] ?? 'All Hostels'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created By</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['notice']['created_by_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['notice']['created_at'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Expires On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['notice']['expires_at'] ?? 'Never'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Content</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($data['notice']['content'] ?? '')); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_notices&action=edit&id=<?php echo (int)$data['notice']['id']; ?>" class="btn btn-primary">Edit Notice</a>
                            <a href="index.php?page=admin_notices" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Notices List -->
                    <div class="page-header">
                        <h2>All Notices</h2>
                        <a href="index.php?page=admin_notices&action=add" class="btn btn-primary">Create New Notice</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_notices">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="DRAFT" <?php echo (isset($_GET['status']) && $_GET['status'] === 'DRAFT') ? 'selected' : ''; ?>>Draft</option>
                                <option value="PUBLISHED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'PUBLISHED') ? 'selected' : ''; ?>>Published</option>
                                <option value="ARCHIVED" <?php echo (isset($_GET['status']) && $_GET['status'] === 'ARCHIVED') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                            <select name="target_audience" class="form-control">
                                <option value="">All Audiences</option>
                                <option value="ALL" <?php echo (isset($_GET['target_audience']) && $_GET['target_audience'] === 'ALL') ? 'selected' : ''; ?>>All Users</option>
                                <option value="STUDENTS" <?php echo (isset($_GET['target_audience']) && $_GET['target_audience'] === 'STUDENTS') ? 'selected' : ''; ?>>Students</option>
                                <option value="MANAGERS" <?php echo (isset($_GET['target_audience']) && $_GET['target_audience'] === 'MANAGERS') ? 'selected' : ''; ?>>Managers</option>
                            </select>
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </form>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Target</th>
                                        <th>Priority</th>
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
                                                <td><?php echo htmlspecialchars(substr($notice['title'], 0, 40)); ?><?php echo strlen($notice['title']) > 40 ? '...' : ''; ?></td>
                                                <td><?php echo htmlspecialchars($notice['target_audience'] ?? 'All'); ?></td>
                                                <td>
                                                    <?php 
                                                    $priority = $notice['priority'] ?? 'NORMAL';
                                                    $priorityClass = 'badge-info';
                                                    if ($priority === 'HIGH') $priorityClass = 'badge-danger';
                                                    elseif ($priority === 'LOW') $priorityClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $priorityClass; ?>">
                                                        <?php echo htmlspecialchars($priority); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $notice['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'PUBLISHED') $statusClass = 'badge-success';
                                                    elseif ($status === 'ARCHIVED') $statusClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($notice['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_notices&action=view&id=<?php echo (int)$notice['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_notices&action=edit&id=<?php echo (int)$notice['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_notices" method="POST" style="display:inline;" onsubmit="return confirm('Delete this notice?');">
                                                            <input type="hidden" name="form_action" value="delete_notice">
                                                            <input type="hidden" name="id" value="<?php echo (int)$notice['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No notices found</td>
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
