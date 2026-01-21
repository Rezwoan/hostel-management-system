<?php
// Manager Fee Period Management View (Read-Only)
$page = 'manager_fee_periods';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
    <?php include __DIR__ . '/../Admin/partials/head-meta.php'; ?>
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
                
                <?php if ($action === 'view' && isset($data['period'])): ?>
                    <!-- View Fee Period Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_fee_periods">Fee Periods</a>
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
                            <a href="index.php?page=manager_fee_periods" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Fee Periods List -->
                    <div class="page-header">
                        <h2>All Fee Periods</h2>
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
                                                        <a href="index.php?page=manager_fee_periods&action=view&id=<?php echo (int)$period['id']; ?>" class="btn btn-sm btn-secondary">View</a>
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
