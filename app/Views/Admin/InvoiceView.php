<?php
// Admin Invoice Management View
$page = 'admin_invoices';
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
                
                <?php if ($action === 'add'): ?>
                    <!-- Generate Invoice Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_invoices">Invoices</a>
                        <span>/</span>
                        <span class="current">Generate New Invoice</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Generate Invoice</h3>
                        <form action="index.php?page=admin_invoices" method="POST">
                            <input type="hidden" name="form_action" value="create_invoice">
                            
                            <div class="form-group">
                                <label for="student_user_id">Student <span class="required">*</span></label>
                                <select id="student_user_id" name="student_user_id" class="form-control" required>
                                    <option value="">Select Student</option>
                                    <?php if (!empty($data['students'])): ?>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <option value="<?php echo (int)$student['id']; ?>" 
                                                    data-hostel-id="<?php echo (int)$student['hostel_id']; ?>">
                                                <?php echo htmlspecialchars($student['name']); ?> 
                                                (<?php echo htmlspecialchars($student['student_id'] ?? $student['email']); ?>)
                                                - <?php echo htmlspecialchars($student['hostel_name']); ?>, Room <?php echo htmlspecialchars($student['room_number']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No students with active allocations</option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-hint">Only students with active room allocations are shown</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="hostel_id">Hostel <span class="required">*</span></label>
                                <select id="hostel_id" name="hostel_id" class="form-control" required>
                                    <option value="">Select Hostel</option>
                                    <?php if (!empty($data['hostels'])): ?>
                                        <?php foreach ($data['hostels'] as $hostel): ?>
                                            <option value="<?php echo (int)$hostel['id']; ?>">
                                                <?php echo htmlspecialchars($hostel['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="period_id">Fee Period <span class="required">*</span></label>
                                <select id="period_id" name="period_id" class="form-control" required>
                                    <option value="">Select Fee Period</option>
                                    <?php if (!empty($data['periods'])): ?>
                                        <?php foreach ($data['periods'] as $period): ?>
                                            <option value="<?php echo (int)$period['id']; ?>">
                                                <?php echo htmlspecialchars($period['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="amount_due">Amount Due <span class="required">*</span></label>
                                <input type="number" id="amount_due" name="amount_due" class="form-control" required step="0.01" min="0">
                                <small class="form-hint">Amount is automatically calculated based on the student's room type</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Generate Invoice</button>
                                <a href="index.php?page=admin_invoices" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                    <script>
                        // Auto-select hostel and fetch amount due based on student's allocation
                        document.getElementById('student_user_id').addEventListener('change', function() {
                            let selectedOption = this.options[this.selectedIndex];
                            let hostelId = selectedOption.dataset.hostelId;
                            let studentUserId = this.value;
                            
                            if (hostelId) {
                                document.getElementById('hostel_id').value = hostelId;
                            }
                            
                            // Fetch the room fee for this student
                            if (studentUserId) {
                                fetch(`app/Controllers/Api/get_student_room_fee.php?student_user_id=${studentUserId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success && data.data.default_fee) {
                                            document.getElementById('amount_due').value = data.data.default_fee.toFixed(2);
                                        } else {
                                            console.error('Failed to fetch room fee:', data.error || 'Unknown error');
                                            document.getElementById('amount_due').value = '';
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching room fee:', error);
                                        document.getElementById('amount_due').value = '';
                                    });
                            } else {
                                document.getElementById('amount_due').value = '';
                            }
                        });
                    </script>
                    
                <?php elseif ($action === 'edit' && isset($data['invoice'])): ?>
                    <!-- Edit Invoice Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_invoices">Invoices</a>
                        <span>/</span>
                        <span class="current">Edit Invoice #<?php echo (int)$data['invoice']['id']; ?></span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Edit Invoice</h3>
                        <form action="index.php?page=admin_invoices" method="POST">
                            <input type="hidden" name="form_action" value="update_invoice">
                            <input type="hidden" name="id" value="<?php echo (int)$data['invoice']['id']; ?>">
                            
                            <div class="form-group">
                                <label for="student_user_id">Student <span class="required">*</span></label>
                                <select id="student_user_id" name="student_user_id" class="form-control" required>
                                    <option value="">Select Student</option>
                                    <?php if (!empty($data['students'])): ?>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <option value="<?php echo (int)$student['id']; ?>" <?php echo ($data['invoice']['student_user_id'] == $student['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($student['name']); ?> 
                                                (<?php echo htmlspecialchars($student['email']); ?>)
                                                <?php if (!empty($student['student_id'])): ?>
                                                    - ID: <?php echo htmlspecialchars($student['student_id']); ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hostel_id">Hostel <span class="required">*</span></label>
                                    <select id="hostel_id" name="hostel_id" class="form-control" required>
                                        <option value="">Select Hostel</option>
                                        <?php if (!empty($data['hostels'])): ?>
                                            <?php foreach ($data['hostels'] as $hostel): ?>
                                                <option value="<?php echo (int)$hostel['id']; ?>" <?php echo ($data['invoice']['hostel_id'] == $hostel['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($hostel['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="period_id">Fee Period <span class="required">*</span></label>
                                    <select id="period_id" name="period_id" class="form-control" required>
                                        <option value="">Select Period</option>
                                        <?php if (!empty($data['periods'])): ?>
                                            <?php foreach ($data['periods'] as $period): ?>
                                                <option value="<?php echo (int)$period['id']; ?>" <?php echo ($data['invoice']['period_id'] == $period['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($period['name']); ?>
                                                    (<?php echo htmlspecialchars($period['start_date'] ?? ''); ?> - <?php echo htmlspecialchars($period['end_date'] ?? ''); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount_due">Amount Due <span class="required">*</span></label>
                                    <input type="number" id="amount_due" name="amount_due" class="form-control" required step="0.01" min="0" value="<?php echo htmlspecialchars($data['invoice']['amount_due'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status <span class="required">*</span></label>
                                    <select id="status" name="status" class="form-control" required>
                                        <option value="DUE" <?php echo ($data['invoice']['status'] ?? '') === 'DUE' ? 'selected' : ''; ?>>Due</option>
                                        <option value="PARTIAL" <?php echo ($data['invoice']['status'] ?? '') === 'PARTIAL' ? 'selected' : ''; ?>>Partial</option>
                                        <option value="PAID" <?php echo ($data['invoice']['status'] ?? '') === 'PAID' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="WAIVED" <?php echo ($data['invoice']['status'] ?? '') === 'WAIVED' ? 'selected' : ''; ?>>Waived</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Payment Summary (if any payments exist) -->
                            <?php 
                            $paidAmount = (float)($data['invoice']['paid_amount'] ?? 0);
                            if ($paidAmount > 0): 
                            ?>
                            <div class="alert alert-info" style="margin-bottom: 15px;">
                                <strong>Payment Info:</strong> 
                                This invoice has received $<?php echo number_format($paidAmount, 2); ?> in payments.
                                Balance: $<?php echo number_format((float)$data['invoice']['amount_due'] - $paidAmount, 2); ?>
                                <br><small>Note: Changing the amount due will affect the balance calculation.</small>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Invoice</button>
                                <a href="index.php?page=admin_invoices&action=view&id=<?php echo (int)$data['invoice']['id']; ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['invoice'])): ?>
                    <!-- View Invoice Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=admin_invoices">Invoices</a>
                        <span>/</span>
                        <span class="current">View Invoice</span>
                    </div>
                    
                    <div class="detail-card" style="margin-bottom: 20px;">
                        <h3>Invoice #<?php echo (int)$data['invoice']['id']; ?></h3>
                        <div class="detail-row">
                            <div class="detail-label">Invoice Number</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['invoice_no'] ?? 'INV-' . $data['invoice']['id']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['student_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['student_id_number'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Fee Period</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['period_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Amount Due</div>
                            <div class="detail-value"><strong>$<?php echo number_format((float)($data['invoice']['amount_due'] ?? 0), 2); ?></strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Paid Amount</div>
                            <div class="detail-value">$<?php echo number_format((float)($data['invoice']['paid_amount'] ?? 0), 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Balance</div>
                            <div class="detail-value">$<?php echo number_format((float)($data['invoice']['amount_due'] ?? 0) - (float)($data['invoice']['paid_amount'] ?? 0), 2); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Generated At</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['generated_at'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <?php 
                                $status = $data['invoice']['status'] ?? '';
                                $statusClass = 'badge-warning';
                                if ($status === 'PAID') $statusClass = 'badge-success';
                                elseif ($status === 'OVERDUE') $statusClass = 'badge-danger';
                                elseif ($status === 'CANCELLED') $statusClass = 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Description</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['invoice']['description'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=admin_invoices&action=edit&id=<?php echo (int)$data['invoice']['id']; ?>" class="btn btn-primary">Edit Invoice</a>
                            <a href="index.php?page=admin_payments&action=add&invoice_id=<?php echo (int)$data['invoice']['id']; ?>" class="btn btn-success">Record Payment</a>
                            <a href="index.php?page=admin_invoices" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                    <!-- Payments for this Invoice -->
                    <div class="table-card">
                        <div class="table-card-header">
                            <h3>Payment History</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['payments'])): ?>
                                        <?php foreach ($data['payments'] as $payment): ?>
                                            <tr>
                                                <td><?php echo (int)$payment['id']; ?></td>
                                                <td>$<?php echo number_format((float)($payment['amount_paid'] ?? 0), 2); ?></td>
                                                <td><?php echo htmlspecialchars($payment['method'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['paid_at'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['reference_no'] ?? 'N/A'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="empty-state">No payments recorded</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Invoices List -->
                    <div class="page-header">
                        <h2>All Invoices</h2>
                        <a href="index.php?page=admin_invoices&action=add" class="btn btn-primary">Generate New Invoice</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="invoiceSearch" class="form-control" placeholder="Search student..." data-table-search="invoicesTable">
                            <select id="statusFilter" class="form-control" data-filter-table="invoicesTable" data-filter-column="5">
                                <option value="">All Status</option>
                                <option value="PENDING">Pending</option>
                                <option value="PAID">Paid</option>
                                <option value="OVERDUE">Overdue</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                            <select id="periodFilter" class="form-control" data-filter-table="invoicesTable" data-filter-column="3">
                                <option value="">All Periods</option>
                                <?php if (!empty($data['fee_periods'])): ?>
                                    <?php foreach ($data['fee_periods'] as $period): ?>
                                        <option value="<?php echo htmlspecialchars($period['name']); ?>">
                                            <?php echo htmlspecialchars($period['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="stats-grid" style="margin-bottom: 20px;">
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['total_amount'] ?? 0), 2); ?></div>
                            <div class="stat-label">Total Amount</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['paid_amount'] ?? 0), 2); ?></div>
                            <div class="stat-label">Collected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">$<?php echo number_format((float)($data['stats']['pending_amount'] ?? 0), 2); ?></div>
                            <div class="stat-label">Pending</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value"><?php echo (int)($data['stats']['overdue_count'] ?? 0); ?></div>
                            <div class="stat-label">Overdue</div>
                        </div>
                    </div>
                    
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Student</th>
                                        <th>Period</th>
                                        <th>Amount Due</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Generated</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['invoices'])): ?>
                                        <?php foreach ($data['invoices'] as $invoice): ?>
                                            <?php 
                                            $amountDue = (float)($invoice['amount_due'] ?? 0);
                                            $paidAmount = (float)($invoice['paid_amount'] ?? 0);
                                            $balance = $amountDue - $paidAmount;
                                            ?>
                                            <tr data-id="<?php echo (int)$invoice['id']; ?>">
                                                <td><?php echo htmlspecialchars('INV-' . $invoice['id']); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($invoice['student_name'] ?? ''); ?><br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($invoice['student_id_number'] ?? 'No ID'); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($invoice['period_name'] ?? 'N/A'); ?></td>
                                                <td>$<?php echo number_format($amountDue, 2); ?></td>
                                                <td class="text-success">$<?php echo number_format($paidAmount, 2); ?></td>
                                                <td class="<?php echo $balance > 0 ? 'text-danger' : 'text-success'; ?>">$<?php echo number_format($balance, 2); ?></td>
                                                <td><?php echo htmlspecialchars($invoice['generated_at'] ?? ''); ?></td>
                                                <td>
                                                    <?php 
                                                    $status = $invoice['status'] ?? '';
                                                    $statusClass = 'badge-warning';
                                                    if ($status === 'PAID') $statusClass = 'badge-success';
                                                    elseif ($status === 'OVERDUE') $statusClass = 'badge-danger';
                                                    elseif ($status === 'PARTIAL') $statusClass = 'badge-info';
                                                    elseif ($status === 'CANCELLED' || $status === 'WAIVED') $statusClass = 'badge-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                                                </td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=admin_invoices&action=view&id=<?php echo (int)$invoice['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <a href="index.php?page=admin_invoices&action=edit&id=<?php echo (int)$invoice['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                        <?php if ($status !== 'PAID'): ?>
                                                            <a href="index.php?page=admin_payments&action=add&invoice_id=<?php echo (int)$invoice['id']; ?>" class="btn btn-sm btn-success">Pay</a>
                                                        <?php endif; ?>
                                                        <form method="POST" action="index.php?page=admin_invoices" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                            <input type="hidden" name="form_action" value="delete_invoice">
                                                            <input type="hidden" name="id" value="<?php echo (int)$invoice['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="empty-state">No invoices found</td>
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
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <h3 id="confirmTitle">Confirm Action</h3>
            <p id="confirmMessage">Are you sure?</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
    
    <script>
        let pendingAction = null;
        let pendingRow = null;
        
        function showConfirm(title, message, callback) {
            document.getElementById("confirmTitle").textContent = title;
            document.getElementById("confirmMessage").textContent = message;
            document.getElementById("confirmModal").classList.add("open");
            pendingAction = callback;
        }
        
        function closeModal() {
            document.getElementById("confirmModal").classList.remove("open");
            pendingAction = null;
            pendingRow = null;
        }
        
        document.getElementById("confirmBtn").addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
        
        // Table search
        document.getElementById("tableSearch")?.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#invoicesTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    </script>
</body>
</html>