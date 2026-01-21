<?php
// Student Notices View
$page = 'student_notices';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
    <link rel="stylesheet" href="app/Views/Student/css/StudentNoticesView.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>Notices & Announcements</h2>
                </div>
                
                <?php if (!empty($data['notices'])): ?>
                    <?php foreach ($data['notices'] as $notice): ?>
                        <div class="notice-card">
                            <div class="notice-header">
                                <div>
                                    <h3><?php echo htmlspecialchars($notice['title'] ?? ''); ?></h3>
                                    <div class="notice-meta">
                                        <?php if ($notice['scope'] === 'GLOBAL'): ?>
                                            <span class="badge badge-info">Global</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?php echo htmlspecialchars($notice['hostel_name'] ?? 'Hostel'); ?></span>
                                        <?php endif; ?>
                                        <span class="text-muted">• Posted on <?php echo date('F d, Y', strtotime($notice['created_at'] ?? '')); ?></span>
                                        <?php if (!empty($notice['created_by_name'])): ?>
                                            <span class="text-muted">by <?php echo htmlspecialchars($notice['created_by_name']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="notice-body">
                                <?php echo nl2br(htmlspecialchars($notice['body'] ?? '')); ?>
                            </div>
                            <?php if (!empty($notice['expire_at'])): ?>
                                <div class="notice-footer">
                                    Expires on <?php echo date('F d, Y', strtotime($notice['expire_at'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state-card">
                        <div class="empty-state-icon">📌</div>
                        <h3>No Notices</h3>
                        <p>There are no published notices at this time.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
