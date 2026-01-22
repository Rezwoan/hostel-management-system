<?php
// Student Fees View
$page = 'student_fees';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Student</title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>Fee Status</h2>
                </div>
                
                <!-- Summary Cards -->
                <?php 
                $totalDue = 0;
                $totalPaid = 0;
                $pendingCount = 0;
                foreach ($data['invoices'] as $inv) {
                    $totalDue += (float)$inv['amount_due'];
                    $totalPaid += (float)$inv['paid_amount'];
                    if ($inv['status'] !== 'PAID' && $inv['status'] !== 'WAIVED') {
                        $pendingCount++;
                    }
                }
                $totalBalance = $totalDue - $totalPaid;
                ?>
                <div class="stats-grid stats-grid-3" style="margin-bottom: 30px;">
                    <div class="stat-card stat-card-info">
                        <h3>Total Amount</h3>
                        <div class="stat-value">$<?php echo number_format($totalDue, 2); ?></div>
                        <div class="stat-label">All invoices</div>
                    </div>
                    <div class="stat-card stat-card-success">
                        <h3>Total Paid</h3>
                        <div class="stat-value">$<?php echo number_format($totalPaid, 2); ?></div>
                        <div class="stat-label">Payments made</div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <h3>Balance Due</h3>
                        <div class="stat-value">$<?php echo number_format($totalBalance, 2); ?></div>
                        <div class="stat-label"><?php echo $pendingCount; ?> pending invoice(s)</div>
                    </div>
                </div>
                
                <!-- Invoices Table -->
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>📄 Invoices</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Period</th>
                                    <th>Hostel</th>
                                    <th>Amount Due</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Generated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['invoices'])): ?>
                                    <?php foreach ($data['invoices'] as $inv): ?>
                                        <?php 
                                        $amountDue = (float)$inv['amount_due'];
                                        $paidAmount = (float)$inv['paid_amount'];
                                        $balance = $amountDue - $paidAmount;
                                        ?>
                                        <tr>
                                            <td><?php echo (int)$inv['id']; ?></td>
                                            <td><?php echo htmlspecialchars($inv['period_name'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($inv['hostel_name'] ?? ''); ?></td>
                                            <td>$<?php echo number_format($amountDue, 2); ?></td>
                                            <td>$<?php echo number_format($paidAmount, 2); ?></td>
                                            <td>$<?php echo number_format($balance, 2); ?></td>
                                            <td>
                                                <?php 
                                                $status = $inv['status'] ?? '';
                                                $statusClass = 'badge-warning';
                                                if ($status === 'PAID') $statusClass = 'badge-success';
                                                elseif ($status === 'WAIVED') $statusClass = 'badge-info';
                                                elseif ($status === 'PARTIAL') $statusClass = 'badge-warning';
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo htmlspecialchars($status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($inv['generated_at'] ?? '')); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="empty-state">No invoices generated yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Payments Table -->
                <div class="table-card" style="margin-top: 30px;">
                    <div class="table-card-header">
                        <h3>💰 Payment History</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Period</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Recorded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['payments'])): ?>
                                    <?php foreach ($data['payments'] as $pay): ?>
                                        <tr>
                                            <td><?php echo (int)$pay['id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($pay['paid_at'] ?? '')); ?></td>
                                            <td><?php echo htmlspecialchars($pay['period_name'] ?? ''); ?></td>
                                            <td>$<?php echo number_format((float)$pay['amount_paid'], 2); ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo htmlspecialchars($pay['method'] ?? ''); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($pay['reference_no'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($pay['recorded_by_name'] ?? 'System'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="empty-state">No payments made yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
