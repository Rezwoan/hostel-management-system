<?php
// Admin Fee Period Management View
$page = 'admin_fee_periods';
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
                    <!-- Add Fee Period Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_fee_periods">Fee Periods</a>
                        <span>/</span>
                        <span class="current">Add New Fee Period</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Add New Fee Period</h3>
                        <form action="index.php?page=admin_fee_periods" method="POST">
                            <input type="hidden" name="form_action" value="create_fee_period">
                            
                            <div class="form-group">
                                <label for="name">Period Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g., Semester 1 2024, Annual 2024-25">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="required">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="required">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Create Fee Period</button>
                                <a href="index.php?page=admin_fee_periods" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'edit' && isset($data['period'])): ?>
                    <!-- Edit Fee Period Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_fee_periods">Fee Periods</a>
                        <span>/</span>
                        <span class="current">Edit Fee Period</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Fee Period</h3>
                        <form action="index.php?page=admin_fee_periods" method="POST">
                            <input type="hidden" name="form_action" value="update_fee_period">
                            <input type="hidden" name="id" value="<?php echo (int)$data['period']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="name">Period Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       value="<?php echo htmlspecialchars($data['period']['name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Start Date <span class="required">*</span></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['period']['start_date'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="end_date">End Date <span class="required">*</span></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required
                                           value="<?php echo htmlspecialchars($data['period']['end_date'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Fee Period</button>
                                <a href="index.php?page=admin_fee_periods" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['period'])): ?>
                    <!-- View Fee Period Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_fee_periods">Fee Periods</a>
                        <span>/</span>
                        <span class="current">View Fee Period</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Fee Period Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">ID</div>
                            <div class="detail-value"><?php echo (int)$data['period']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Name</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['period']['name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['period']['start_date'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">End Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['period']['end_date'] ?? ''); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_fee_periods&action=edit&id=<?php echo (int)$data['period']['id']; ?>" class="btn btn-primary">Edit Fee Period</a>
                            <a href="index.php?page=admin_fee_periods" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Fee Periods List -->
                    <div class="page-header">
                        <h2>All Fee Periods</h2>
                        <a href="index.php?page=admin_fee_periods&action=add" class="btn btn-primary">Add New Fee Period</a>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['periods'])): ?>
                                        <?php foreach ($data['periods'] as $period): ?>
                                            <tr>
                                                <td><?php echo (int)$period['id']; ?></td>
                                                <td><?php echo htmlspecialchars($period['name']); ?></td>
                                                <td><?php echo htmlspecialchars($period['start_date']); ?></td>
                                                <td><?php echo htmlspecialchars($period['end_date']); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_fee_periods&action=view&id=<?php echo (int)$period['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_fee_periods&action=edit&id=<?php echo (int)$period['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="index.php?page=admin_fee_periods" method="POST" style="display:inline;" onsubmit="return confirm('Delete this fee period?');">
                                                            <input type="hidden" name="form_action" value="delete_fee_period">
                                                            <input type="hidden" name="id" value="<?php echo (int)$period['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">No fee periods found</td>
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
