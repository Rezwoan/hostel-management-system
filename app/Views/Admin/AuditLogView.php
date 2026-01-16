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
                    
                    <!-- Filter Bar - Client-side instant filtering -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="logSearch" class="form-control" placeholder="Search logs..." data-table-search="auditLogsTable">
                            <select id="actionFilter" class="form-control" data-filter-table="auditLogsTable" data-filter-column="2">
                                <option value="">All Actions</option>
                                <option value="CREATE">Create</option>
                                <option value="UPDATE">Update</option>
                                <option value="DELETE">Delete</option>
                                <option value="LOGIN">Login</option>
                                <option value="LOGOUT">Logout</option>
                                <option value="RECORD_PAYMENT">Record Payment</option>
                                <option value="APPROVE">Approve</option>
                                <option value="REJECT">Reject</option>
                            </select>
                            <select id="entityFilter" class="form-control" data-filter-table="auditLogsTable" data-filter-column="3">
                                <option value="">All Entities</option>
                                <option value="users">Users</option>
                                <option value="hostels">Hostels</option>
                                <option value="rooms">Rooms</option>
                                <option value="allocations">Allocations</option>
                                <option value="student_invoices">Invoices</option>
                                <option value="payments">Payments</option>
                                <option value="complaints">Complaints</option>
                                <option value="room_applications">Applications</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="auditLogsTable">
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
                                                    if (in_array($actionType, ['CREATE', 'APPROVE', 'RECORD_PAYMENT'])) $actionClass = 'badge-success';
                                                    elseif ($actionType === 'DELETE') $actionClass = 'badge-danger';
                                                    elseif (in_array($actionType, ['UPDATE', 'REJECT'])) $actionClass = 'badge-warning';
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
                                                <td>
                                                    <?php 
                                                    $details = $log['meta_json'] ?? '';
                                                    if (!empty($details)) {
                                                        // Try to format JSON nicely
                                                        $decoded = json_decode($details, true);
                                                        if ($decoded) {
                                                            // Show key details in a readable format
                                                            $displayParts = [];
                                                            foreach ($decoded as $key => $value) {
                                                                if (is_array($value)) continue;
                                                                $displayParts[] = str_replace('_', ' ', $key) . ': ' . $value;
                                                            }
                                                            $details = implode(', ', array_slice($displayParts, 0, 2));
                                                            if (count($displayParts) > 2) $details .= '...';
                                                        } else {
                                                            $details = substr($details, 0, 40) . (strlen($details) > 40 ? '...' : '');
                                                        }
                                                        echo htmlspecialchars($details);
                                                    } else {
                                                        echo '<span class="text-muted">-</span>';
                                                    }
                                                    ?>
                                                </td>
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
