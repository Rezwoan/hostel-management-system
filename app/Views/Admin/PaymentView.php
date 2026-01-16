<?php
// Admin Payment Management View
$page = 'admin_payments';
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
                    <!-- Record Payment Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_payments">Payments</a>
                        <span>/</span>
                        <span class="current">Record New Payment</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Record Payment</h3>
                        <form action="index.php?page=admin_payments" method="POST">
                            <input type="hidden" name="form_action" value="create_payment">
                            
                            <div class="form-group">
                                <label for="invoice_id">Invoice <span class="required">*</span></label>
                                <select id="invoice_id" name="invoice_id" class="form-control" required>
                                    <option value="">Select Invoice</option>
                                    <?php if (!empty($data['invoices'])): ?>
                                        <?php foreach ($data['invoices'] as $invoice): ?>
                                            <option value="<?php echo (int)$invoice['id']; ?>" <?php echo (isset($_GET['invoice_id']) && $_GET['invoice_id'] == $invoice['id']) ? 'selected' : ''; ?>>
                                                INV-<?php echo $invoice['id']; ?> - 
                                                <?php echo htmlspecialchars($invoice['student_name']); ?>
                                                <?php if (!empty($invoice['student_id_number'])): ?>
                                                    (<?php echo htmlspecialchars($invoice['student_id_number']); ?>)
                                                <?php endif; ?>
                                                - $<?php echo number_format((float)($invoice['amount_due'] ?? 0), 2); ?>
                                                [<?php echo htmlspecialchars($invoice['status'] ?? ''); ?>]
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="form-hint">Search by student name or ID in the dropdown</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount">Amount <span class="required">*</span></label>
                                    <input type="number" id="amount" name="amount" class="form-control" required step="0.01" min="0.01">
                                </div>
                                
                                <div class="form-group">
                                    <label for="payment_date">Payment Date <span class="required">*</span></label>
                                    <input type="date" id="payment_date" name="payment_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method <span class="required">*</span></label>
                                    <select id="payment_method" name="payment_method" class="form-control" required>
                                        <option value="">Select Method</option>
                                        <option value="CASH">Cash</option>
                                        <option value="BANK_TRANSFER">Bank Transfer</option>
                                        <option value="CARD">Card</option>
                                        <option value="CHEQUE">Cheque</option>
                                        <option value="ONLINE">Online Payment</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reference_no">Reference Number</label>
                                    <input type="text" id="reference_no" name="reference_no" class="form-control" placeholder="Transaction/Receipt No.">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Record Payment</button>
                                <a href="index.php?page=admin_payments" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['payment'])): ?>
                    <!-- View Payment Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_payments">Payments</a>
                        <span>/</span>
                        <span class="current">View Payment</span>
                    </div>
                    
                    <div class="detail-card">
                        <h3>Payment Details</h3>
                        <div class="detail-row">
                            <div class="detail-label">Payment ID</div>
                            <div class="detail-value"><?php echo (int)$data['payment']['id']; ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Invoice</div>
                            <div class="detail-value">
                                <a href="index.php?page=admin_invoices&action=view&id=<?php echo (int)$data['payment']['invoice_id']; ?>">
                                    <?php echo htmlspecialchars($data['payment']['invoice_no'] ?? 'INV-' . $data['payment']['invoice_id']); ?>
                                </a>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['student_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount</div>
                            <div class="detail-value"><strong>$<?php echo number_format((float)($data['payment']['amount_paid'] ?? 0), 2); ?></strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Payment Date</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['paid_at'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Payment Method</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['method'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Reference No.</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['reference_no'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Remarks</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['remarks'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Recorded By</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['recorded_by_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Recorded On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['created_at'] ?? ''); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_payments" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Payments List -->
                    <div class="page-header">
                        <h2>All Payments</h2>
                        <a href="index.php?page=admin_payments&action=add" class="btn btn-primary">Record New Payment</a>
                    </div>
                    
                    <!-- Filter Bar -->
                    <div class="filter-bar">
                        <form action="index.php" method="GET" class="filter-form">
                            <input type="hidden" name="page" value="admin_payments">
                            <select name="payment_method" class="form-control">
                                <option value="">All Methods</option>
                                <option value="CASH" <?php echo (isset($_GET['payment_method']) && $_GET['payment_method'] === 'CASH') ? 'selected' : ''; ?>>Cash</option>
                                <option value="BANK_TRANSFER" <?php echo (isset($_GET['payment_method']) && $_GET['payment_method'] === 'BANK_TRANSFER') ? 'selected' : ''; ?>>Bank Transfer</option>
                                <option value="CARD" <?php echo (isset($_GET['payment_method']) && $_GET['payment_method'] === 'CARD') ? 'selected' : ''; ?>>Card</option>
                                <option value="CHEQUE" <?php echo (isset($_GET['payment_method']) && $_GET['payment_method'] === 'CHEQUE') ? 'selected' : ''; ?>>Cheque</option>
                                <option value="ONLINE" <?php echo (isset($_GET['payment_method']) && $_GET['payment_method'] === 'ONLINE') ? 'selected' : ''; ?>>Online</option>
                            </select>
                            <input type="date" name="from_date" class="form-control" value="<?php echo htmlspecialchars($_GET['from_date'] ?? ''); ?>" placeholder="From Date">
                            <input type="date" name="to_date" class="form-control" value="<?php echo htmlspecialchars($_GET['to_date'] ?? ''); ?>" placeholder="To Date">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </form>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['total_collected'] ?? 0), 2); ?></div>
                            <div class="stat-label">Total Collected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['total_payments'] ?? 0); ?></div>
                            <div class="stat-label">Total Payments</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['today_collected'] ?? 0), 2); ?></div>
                            <div class="stat-label">Today</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['this_month'] ?? 0), 2); ?></div>
                            <div class="stat-label">This Month</div>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Invoice</th>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['payments'])): ?>
                                        <?php foreach ($data['payments'] as $payment): ?>
                                            <tr>
                                                <td><?php echo (int)$payment['id']; ?></td>
                                                <td>
                                                    <a href="index.php?page=admin_invoices&action=view&id=<?php echo (int)$payment['invoice_id']; ?>">
                                                        <?php echo htmlspecialchars($payment['invoice_no'] ?? 'INV-' . $payment['invoice_id']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($payment['student_name'] ?? ''); ?></td>
                                                <td>$<?php echo number_format((float)($payment['amount_paid'] ?? 0), 2); ?></td>
                                                <td><?php echo htmlspecialchars($payment['method'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['paid_at'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['reference_no'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_payments&action=view&id=<?php echo (int)$payment['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="empty-state">No payments found</td>
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
