<?php
// Manager Fee View
$page = 'manager_fees';
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
        
        <main class="admin-main full-width">
            <div class="admin-content">
                <?php if ($action === 'view' && isset($data['invoice'])): ?>
                    <!-- View Invoice -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_fees">Fees</a>
                        <span>/</span>
                        <span class="current">Invoice Details</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Invoice Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Invoice ID</div>
                            <div class="detail-value"><?php echo (int)$data['invoice']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['student_name']); ?> (<?php echo htmlspecialchars($data['invoice']['student_id']); ?>)</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Period</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['period_name']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount Due</div>
                            <div class="detail-value">$<?php echo number_format($data['invoice']['amount_due'], 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Paid</div>
                            <div class="detail-value">$<?php echo number_format($data['invoice']['paid_amount'], 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Remaining</div>
                            <div class="detail-value">$<?php echo number_format($data['remaining_amount'], 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="badge badge-<?php echo $data['invoice']['status'] === 'PAID' ? 'success' : 'warning'; ?>">
                                    <?php echo htmlspecialchars($data['invoice']['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($data['payments'])): ?>
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Payment History</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                        <th>Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['payments'] as $payment): ?>
                                        <tr>
                                            <td><?php echo date('Y-m-d', strtotime($payment['paid_at'])); ?></td>
                                            <td>$<?php echo number_format($payment['amount_paid'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($payment['method']); ?></td>
                                            <td><?php echo htmlspecialchars($payment['reference_no'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($payment['recorded_by_name']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- List Invoices -->
                    <div class="page-header">
                        <h2>Fee Management</h2>
                    </div>
                    
                    <!-- Stats Grid -->
                    <?php if (isset($data['stats'])): ?>
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)$data['stats']['total_amount'], 2); ?></div>
                            <div class="stat-label">Total Amount</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)$data['stats']['paid_amount'], 2); ?></div>
                            <div class="stat-label">Collected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)$data['stats']['pending_amount'], 2); ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)$data['stats']['overdue_count']; ?></div>
                            <div class="stat-label">Overdue</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <input type="text" id="searchInput" placeholder="Search by student name..." class="form-control">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="PENDING">Pending</option>
                            <option value="PAID">Paid</option>
                            <option value="OVERDUE">Overdue</option>
                        </select>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Student Invoices</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Student ID</th>
                                        <th>Period</th>
                                        <th>Amount Due</th>
                                        <th>Paid</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['invoices'])): ?>
                                        <?php foreach ($data['invoices'] as $invoice): ?>
                                            <tr>
                                                <td><?php echo (int)$invoice['id']; ?></td>
                                                <td><?php echo htmlspecialchars($invoice['student_name']); ?></td>
                                                <td><?php echo htmlspecialchars($invoice['student_id']); ?></td>
                                                <td><?php echo htmlspecialchars($invoice['period_name']); ?></td>
                                                <td>$<?php echo number_format($invoice['amount_due'], 2); ?></td>
                                                <td>$<?php echo number_format($invoice['paid_amount'], 2); ?></td>
                                                <td>
                                                    <span class="badge badge-<?php echo $invoice['status'] === 'PAID' ? 'success' : 'warning'; ?>">
                                                        <?php echo htmlspecialchars($invoice['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="index.php?page=manager_fees&action=view&id=<?php echo (int)$invoice['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="empty-state">No invoices found</td>
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
