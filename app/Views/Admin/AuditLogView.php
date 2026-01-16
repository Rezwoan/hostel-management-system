<?php
// Admin Audit Log View
$page = 'admin_audit_logs';
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
                
                <?php if ($action === 'view' && isset($data['log'])): ?>
                    <!-- View Log Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_audit_logs">Audit Logs</a>
                        <span>/</span>
                        <span class="current">View Log Entry</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Audit Log Entry #<?php echo (int)$data['log']['id']; ?></h3>
                        <div class="detail-row">
                            <div class="detail-label">User</div>
                            <div class="detail-value">
                                <?php echo htmlspecialchars($data['log']['user_name'] ?? 'System'); ?>
                                <?php if (!empty($data['log']['user_email'])): ?>
                                    (<?php echo htmlspecialchars($data['log']['user_email']); ?>)
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Action</div>
                            <div class="detail-value">
                                <span class="badge badge-info"><?php echo htmlspecialchars($data['log']['action'] ?? ''); ?></span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Entity Type</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['log']['entity_type'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Entity ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['log']['entity_id'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Timestamp</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['log']['created_at'] ?? ''); ?></div>
                        </div>
                        <?php if (!empty($data['log']['meta_json'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Additional Data</div>
                            <div class="detail-value"><pre style="background:#f4f6f9;padding:10px;border-radius:4px;overflow:auto;max-height:200px;"><?php echo htmlspecialchars($data['log']['meta_json']); ?></pre></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php 
                        // Show Undo button for reversible actions
                        $canUndo = false;
                        $undoAction = '';
                        $undoParams = [];
                        
                        if ($data['log']['action'] === 'ROLE_CHANGE' && !empty($data['log']['meta_json'])) {
                            $meta = json_decode($data['log']['meta_json'], true);
                            if ($meta && isset($meta['old_role_id']) && $meta['old_role_id'] !== null) {
                                $canUndo = true;
                                $undoAction = 'undo_role_change';
                                $undoParams = [
                                    'user_id' => $data['log']['entity_id'],
                                    'old_role_id' => $meta['old_role_id']
                                ];
                            }
                        }
                        ?>
                        
                        <div class="form-actions">
                            <?php if ($canUndo): ?>
                            <form action="index.php?page=admin_audit_logs" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to undo this action?');">
                                <input type="hidden" name="form_action" value="<?php echo $undoAction; ?>">
                                <input type="hidden" name="log_id" value="<?php echo (int)$data['log']['id']; ?>">
                                <?php foreach ($undoParams as $key => $value): ?>
                                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                                <?php endforeach; ?>
                                <button type="submit" class="btn btn-warning">Undo This Action</button>
                            </form>
                            <?php endif; ?>
                            <a href="index.php?page=admin_audit_logs" class="btn btn-secondary">Back to Logs</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Audit Logs List -->
                    <div class="page-header">
                        <h2>System Audit Logs</h2>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_audit_logs">
                            <select name="action" class="form-control">
                                <option value="">All Actions</option>
                                <option value="CREATE" <?php echo (isset($_GET['action']) && $_GET['action'] === 'CREATE') ? 'selected' : ''; ?>>Create</option>
                                <option value="UPDATE" <?php echo (isset($_GET['action']) && $_GET['action'] === 'UPDATE') ? 'selected' : ''; ?>>Update</option>
                                <option value="DELETE" <?php echo (isset($_GET['action']) && $_GET['action'] === 'DELETE') ? 'selected' : ''; ?>>Delete</option>
                                <option value="LOGIN" <?php echo (isset($_GET['action']) && $_GET['action'] === 'LOGIN') ? 'selected' : ''; ?>>Login</option>
                                <option value="LOGOUT" <?php echo (isset($_GET['action']) && $_GET['action'] === 'LOGOUT') ? 'selected' : ''; ?>>Logout</option>
                            </select>
                            <select name="entity_type" class="form-control">
                                <option value="">All Entities</option>
                                <option value="user" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'user') ? 'selected' : ''; ?>>User</option>
                                <option value="hostel" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'hostel') ? 'selected' : ''; ?>>Hostel</option>
                                <option value="room" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'room') ? 'selected' : ''; ?>>Room</option>
                                <option value="allocation" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'allocation') ? 'selected' : ''; ?>>Allocation</option>
                                <option value="invoice" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'invoice') ? 'selected' : ''; ?>>Invoice</option>
                                <option value="payment" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'payment') ? 'selected' : ''; ?>>Payment</option>
                                <option value="complaint" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] === 'complaint') ? 'selected' : ''; ?>>Complaint</option>
                            </select>
                            <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($_GET['from_date'] ?? ''); ?>" placeholder="From">
                            <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($_GET['to_date'] ?? ''); ?>" placeholder="To">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </form>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Entity</th>
                                        <th>Details</th>
                                        <th>Timestamp</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['logs'])): ?>
                                        <?php foreach ($data['logs'] as $log): ?>
                                            <tr>
                                                <td><?php echo (int)$log['id']; ?></td>
                                                <td><?php echo htmlspecialchars($log['actor_name'] ?? 'System'); ?></td>
                                                <td>
                                                    <?php 
                                                    $actionType = $log['action'] ?? '';
                                                    $actionClass = 'badge-info';
                                                    if ($actionType === 'CREATE') $actionClass = 'badge-success';
                                                    elseif ($actionType === 'DELETE') $actionClass = 'badge-danger';
                                                    elseif ($actionType === 'UPDATE') $actionClass = 'badge-warning';
                                                    ?>
                                                    <span class="badge <?php echo $actionClass; ?>">
                                                        <?php echo htmlspecialchars($actionType); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($log['entity_type'] ?? ''); ?>
                                                    <?php if (!empty($log['entity_id'])): ?>
                                                        #<?php echo (int)$log['entity_id']; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars(substr($log['meta_json'] ?? '', 0, 40)); ?><?php echo strlen($log['meta_json'] ?? '') > 40 ? '...' : ''; ?></td>
                                                <td><?php echo htmlspecialchars($log['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_audit_logs&action=view&id=<?php echo (int)$log['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="empty-state">No audit logs found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (!empty($data['pagination'])): ?>
                    <div class="pagination">
                        <?php if ($data['pagination']['current_page'] > 1): ?>
                            <a href="index.php?page=admin_audit_logs&p=<?php echo $data['pagination']['current_page'] - 1; ?>" class="btn btn-sm btn-secondary">Previous</a>
                        <?php endif; ?>
                        <span class="pagination-info">Page <?php echo $data['pagination']['current_page']; ?> of <?php echo $data['pagination']['total_pages']; ?></span>
                        <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                            <a href="index.php?page=admin_audit_logs&p=<?php echo $data['pagination']['current_page'] + 1; ?>" class="btn btn-sm btn-secondary">Next</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
