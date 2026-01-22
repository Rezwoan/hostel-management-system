<?php
// Admin Login Activity View
$page = 'admin_login_activity';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Admin</title>
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
                        <a href="index.php?page=admin_login_activity">Login Activity</a>
                        <span>/</span>
                        <span class="current">View Details</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Login Activity Entry #<?php echo (int)$data['log']['id']; ?></h3>
                        
                        <?php 
                        // Parse meta_json for detailed display
                        $metaData = [];
                        if (!empty($data['log']['meta_json'])) {
                            $metaData = json_decode($data['log']['meta_json'], true) ?: [];
                        }
                        ?>
                        
                        <div class="detail-row">
                            <div class="detail-label">User</div>
                            <div class="detail-value">
                                <?php echo htmlspecialchars($data['log']['user_name'] ?? 'Unknown'); ?>
                                <?php if (!empty($data['log']['user_email'])): ?>
                                    (<?php echo htmlspecialchars($data['log']['user_email']); ?>)
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Action</div>
                            <div class="detail-value">
                                <?php 
                                $actionType = $data['log']['action'] ?? '';
                                $actionClass = 'badge-info';
                                $actionLabel = $actionType;
                                switch ($actionType) {
                                    case 'LOGIN': $actionClass = 'badge-success'; $actionLabel = 'Login Success'; break;
                                    case 'LOGIN_FAILED': $actionClass = 'badge-danger'; $actionLabel = 'Login Failed'; break;
                                    case 'LOGOUT': $actionClass = 'badge-warning'; $actionLabel = 'Logout'; break;
                                    case 'SIGNUP': $actionClass = 'badge-info'; $actionLabel = 'New Signup'; break;
                                }
                                ?>
                                <span class="badge <?php echo $actionClass; ?>"><?php echo htmlspecialchars($actionLabel); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Timestamp</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['log']['created_at'] ?? ''); ?></div>
                        </div>
                        
                        <?php if (!empty($metaData['ip_address'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">IP Address</div>
                            <div class="detail-value"><code><?php echo htmlspecialchars($metaData['ip_address']); ?></code></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($metaData['email'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Email Used</div>
                            <div class="detail-value"><?php echo htmlspecialchars($metaData['email']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($metaData['success'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php if ($metaData['success']): ?>
                                    <span class="badge badge-success">Success</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Failed</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($metaData['user_agent'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Browser/Device</div>
                            <div class="detail-value"><small><?php echo htmlspecialchars($metaData['user_agent']); ?></small></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($metaData['remember_me'])): ?>
                        <div class="detail-row">
                            <div class="detail-label">Remember Me</div>
                            <div class="detail-value">
                                <span class="badge badge-info">Enabled</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_login_activity" class="btn btn-secondary">Back to Login Activity</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Login Activity List -->
                    <div class="page-header">
                        <h2>Login Activity</h2>
                        <p class="text-muted">Monitor user login, logout, and signup events</p>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="stats-grid stats-grid-4">
                        <?php
                        $stats = [
                            'logins' => 0,
                            'failed' => 0,
                            'logouts' => 0,
                            'signups' => 0
                        ];
                        if (!empty($data['logs'])) {
                            foreach ($data['logs'] as $log) {
                                switch ($log['action']) {
                                    case 'LOGIN': $stats['logins']++; break;
                                    case 'LOGIN_FAILED': $stats['failed']++; break;
                                    case 'LOGOUT': $stats['logouts']++; break;
                                    case 'SIGNUP': $stats['signups']++; break;
                                }
                            }
                        }
                        ?>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $stats['logins']; ?></div>
                            <div class="stat-label">Successful Logins</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" style="color: #dc3545;"><?php echo $stats['failed']; ?></div>
                            <div class="stat-label">Failed Logins</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo $stats['logouts']; ?></div>
                            <div class="stat-label">Logouts</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" style="color: #17a2b8;"><?php echo $stats['signups']; ?></div>
                            <div class="stat-label">New Signups</div>
                        </div>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="logSearch" class="form-control" placeholder="Search by user, email, or IP..." data-table-search="loginActivityTable">
                            <select id="actionFilter" class="form-control" data-filter-table="loginActivityTable" data-filter-column="2">
                                <option value="">All Activity Types</option>
                                <option value="LOGIN">Successful Login</option>
                                <option value="LOGIN_FAILED">Failed Login</option>
                                <option value="LOGOUT">Logout</option>
                                <option value="SIGNUP">Signup</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="loginActivityTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Activity</th>
                                        <th>IP Address</th>
                                        <th>Timestamp</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['logs'])): ?>
                                        <?php foreach ($data['logs'] as $log): ?>
                                            <?php
                                            $metaData = [];
                                            if (!empty($log['meta_json'])) {
                                                $metaData = json_decode($log['meta_json'], true) ?: [];
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo (int)$log['id']; ?></td>
                                                <td>
                                                    <?php 
                                                    $userName = $log['actor_name'] ?? 'Unknown';
                                                    $userEmail = $metaData['email'] ?? $log['actor_email'] ?? '';
                                                    echo htmlspecialchars($userName);
                                                    if (!empty($userEmail)) {
                                                        echo '<br><small class="text-muted">' . htmlspecialchars($userEmail) . '</small>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $actionType = $log['action'] ?? '';
                                                    $actionClass = 'badge-info';
                                                    $actionLabel = $actionType;
                                                    switch ($actionType) {
                                                        case 'LOGIN': $actionClass = 'badge-success'; $actionLabel = 'Login'; break;
                                                        case 'LOGIN_FAILED': $actionClass = 'badge-danger'; $actionLabel = 'Failed Login'; break;
                                                        case 'LOGOUT': $actionClass = 'badge-warning'; $actionLabel = 'Logout'; break;
                                                        case 'SIGNUP': $actionClass = 'badge-info'; $actionLabel = 'Signup'; break;
                                                    }
                                                    ?>
                                                    <span class="badge <?php echo $actionClass; ?>">
                                                        <?php echo htmlspecialchars($actionLabel); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($metaData['ip_address'])): ?>
                                                        <code><?php echo htmlspecialchars($metaData['ip_address']); ?></code>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($log['created_at'] ?? ''); ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_login_activity&action=view&id=<?php echo (int)$log['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="empty-state">No login activity recorded yet</td>
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
