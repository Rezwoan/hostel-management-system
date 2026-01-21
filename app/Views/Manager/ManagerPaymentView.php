<?php
// Manager Payment Management View
$page = 'manager_payments';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Manager</title>
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
                    <!-- Record Payment Form -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_payments">Payments</a>
                        <span>/</span>
                        <span class="current">Record New Payment</span>
                    </div>
                    
                    <div class="form-card">
                        <h3>Record Payment</h3>
                        <form action="index.php?page=manager_payments" method="POST" id="paymentForm">
                            <input type="hidden" name="form_action" value="record_payment">
                            
                            <div class="form-group">
                                <label for="invoice_id">Invoice <span class="required">*</span></label>
                                <select id="invoice_id" name="invoice_id" class="form-control" required>
                                    <option value="" data-due="0" data-paid="0">Select Invoice</option>
                                    <?php if (!empty($data['invoices'])): ?>
                                        <?php foreach ($data['invoices'] as $invoice): 
                                            $amountDue = (float)($invoice['amount_due'] ?? 0);
                                            $paidAmount = (float)($invoice['paid_amount'] ?? 0);
                                            $balance = $amountDue - $paidAmount;
                                            if ($invoice['status'] === 'PAID') continue; // Skip paid invoices
                                        ?>
                                            <option value="<?php echo (int)$invoice['id']; ?>" 
                                                    data-due="<?php echo $amountDue; ?>"
                                                    data-paid="<?php echo $paidAmount; ?>"
                                                    data-balance="<?php echo $balance; ?>"
                                                    <?php echo (isset($_GET['invoice_id']) && $_GET['invoice_id'] == $invoice['id']) ? 'selected' : ''; ?>>
                                                INV-<?php echo $invoice['id']; ?> - 
                                                <?php echo htmlspecialchars($invoice['student_name']); ?>
                                                <?php if (!empty($invoice['student_id_number'])): ?>
                                                    (<?php echo htmlspecialchars($invoice['student_id_number']); ?>)
                                                <?php endif; ?>
                                                - Due: $<?php echo number_format($amountDue, 2); ?>
                                                | Balance: $<?php echo number_format($balance, 2); ?>
                                                [<?php echo htmlspecialchars($invoice['status'] ?? ''); ?>]
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <span class="form-hint">Only unpaid and partially paid invoices are shown</span>
                            </div>
                            
                            <!-- Invoice Summary (shown when invoice selected) -->
                            <div id="invoiceSummary" class="alert alert-info" style="display: none; margin-bottom: 15px;">
                                <strong>Invoice Summary:</strong><br>
                                Total Due: $<span id="summaryDue">0.00</span> | 
                                Already Paid: $<span id="summaryPaid">0.00</span> | 
                                <strong>Balance: $<span id="summaryBalance">0.00</span></strong>
                            </div>
                            
                            <div class="form-group">
                                <label>Payment Type <span class="required">*</span></label>
                                <div class="radio-group" style="display: flex; gap: 20px; margin-top: 5px;">
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="radio" name="payment_type" value="full" id="paymentTypeFull" checked>
                                        <span>Full Payment (Pay Remaining Balance)</span>
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="radio" name="payment_type" value="partial" id="paymentTypePartial">
                                        <span>Partial Payment</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="amount_paid">Amount <span class="required">*</span></label>
                                    <input type="number" id="amount_paid" name="amount_paid" class="form-control" required step="0.01" min="0.01" readonly>
                                    <span class="form-hint" id="amountHint">Select an invoice first</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="payment_date">Payment Date</label>
                                    <input type="date" id="payment_date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="method">Payment Method <span class="required">*</span></label>
                                    <select id="method" name="method" class="form-control" required>
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
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Record Payment</button>
                                <a href="index.php?page=manager_payments" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const invoiceSelect = document.getElementById('invoice_id');
                            const amountInput = document.getElementById('amount_paid');
                            const amountHint = document.getElementById('amountHint');
                            const paymentTypeFull = document.getElementById('paymentTypeFull');
                            const paymentTypePartial = document.getElementById('paymentTypePartial');
                            const submitBtn = document.getElementById('submitBtn');
                            const invoiceSummary = document.getElementById('invoiceSummary');
                            const summaryDue = document.getElementById('summaryDue');
                            const summaryPaid = document.getElementById('summaryPaid');
                            const summaryBalance = document.getElementById('summaryBalance');
                            
                            let currentBalance = 0;
                            
                            function updatePaymentAmount() {
                                const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
                                const balance = parseFloat(selectedOption.dataset.balance) || 0;
                                const due = parseFloat(selectedOption.dataset.due) || 0;
                                const paid = parseFloat(selectedOption.dataset.paid) || 0;
                                
                                currentBalance = balance;
                                
                                if (invoiceSelect.value === '') {
                                    invoiceSummary.style.display = 'none';
                                    amountInput.value = '';
                                    amountInput.readOnly = true;
                                    amountInput.max = '';
                                    amountHint.textContent = 'Select an invoice first';
                                    submitBtn.disabled = true;
                                    return;
                                }
                                
                                // Show invoice summary
                                invoiceSummary.style.display = 'block';
                                summaryDue.textContent = due.toFixed(2);
                                summaryPaid.textContent = paid.toFixed(2);
                                summaryBalance.textContent = balance.toFixed(2);
                                
                                if (balance <= 0) {
                                    amountInput.value = '';
                                    amountInput.readOnly = true;
                                    amountHint.textContent = 'This invoice is already fully paid';
                                    submitBtn.disabled = true;
                                    return;
                                }
                                
                                submitBtn.disabled = false;
                                
                                if (paymentTypeFull.checked) {
                                    // Full payment - auto-fill with balance
                                    amountInput.value = balance.toFixed(2);
                                    amountInput.readOnly = true;
                                    amountHint.textContent = 'Full payment will clear the remaining balance';
                                } else {
                                    // Partial payment - allow manual entry
                                    amountInput.readOnly = false;
                                    amountInput.max = balance;
                                    amountInput.value = '';
                                    amountHint.textContent = 'Enter amount (max: $' + balance.toFixed(2) + ')';
                                }
                            }
                            
                            // Validate partial payment amount
                            amountInput.addEventListener('input', function() {
                                if (paymentTypePartial.checked && currentBalance > 0) {
                                    const enteredAmount = parseFloat(this.value) || 0;
                                    if (enteredAmount > currentBalance) {
                                        this.value = currentBalance.toFixed(2);
                                        alert('Amount cannot exceed the remaining balance of $' + currentBalance.toFixed(2));
                                    }
                                    if (enteredAmount <= 0) {
                                        submitBtn.disabled = true;
                                    } else {
                                        submitBtn.disabled = false;
                                    }
                                }
                            });
                            
                            // Event listeners
                            invoiceSelect.addEventListener('change', updatePaymentAmount);
                            paymentTypeFull.addEventListener('change', updatePaymentAmount);
                            paymentTypePartial.addEventListener('change', updatePaymentAmount);
                            
                            // Form validation
                            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                                const amount = parseFloat(amountInput.value) || 0;
                                if (amount <= 0) {
                                    e.preventDefault();
                                    alert('Please enter a valid payment amount');
                                    return false;
                                }
                                if (amount > currentBalance) {
                                    e.preventDefault();
                                    alert('Payment amount cannot exceed the remaining balance of $' + currentBalance.toFixed(2));
                                    return false;
                                }
                            });
                            
                            // Initialize if invoice is pre-selected (from Pay button on invoice)
                            if (invoiceSelect.value !== '') {
                                updatePaymentAmount();
                            }
                        });
                        </script>
                    </div>
                    
                <?php elseif ($action === 'view' && isset($data['payment'])): ?>
                    <!-- View Payment Details -->
                    <div class="breadcrumb">
                        <a href="index.php?page=manager_payments">Payments</a>
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
                                <a href="index.php?page=manager_fees&action=view&id=<?php echo (int)$data['payment']['invoice_id']; ?>">
                                    INV-<?php echo (int)$data['payment']['invoice_id']; ?>
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
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['recorder_name'] ?? ''); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Recorded On</div>
                            <div class="detail-value"><?php echo htmlspecialchars($data['payment']['paid_at'] ?? ''); ?></div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php?page=manager_payments" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <!-- Payments List -->
                    <div class="page-header">
                        <h2>All Payments</h2>
                        <a href="index.php?page=manager_payments&action=add" class="btn btn-primary">Record New Payment</a>
                    </div>
                    
                    <!-- Filter Bar - Client-Side (Instant, No Page Reload) -->
                    <div class="filter-bar">
                        <div class="filter-form">
                            <input type="text" id="paymentSearch" class="form-control" placeholder="Search payments..." data-table-search="paymentsTable">
                            <select id="methodFilter" class="form-control" data-filter-table="paymentsTable" data-filter-column="4">
                                <option value="">All Methods</option>
                                <option value="CASH">Cash</option>
                                <option value="BANK_TRANSFER">Bank Transfer</option>
                                <option value="CARD">Card</option>
                                <option value="CHEQUE">Cheque</option>
                                <option value="ONLINE">Online</option>
                            </select>
                        </div>
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
                            <table class="table" id="paymentsTable">
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
                                            <tr data-id="<?php echo (int)$payment['id']; ?>">
                                                <td><?php echo (int)$payment['id']; ?></td>
                                                <td>
                                                    <a href="index.php?page=manager_fees&action=view&id=<?php echo (int)$payment['invoice_id']; ?>">
                                                        INV-<?php echo (int)$payment['invoice_id']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($payment['student_name'] ?? ''); ?></td>
                                                <td>$<?php echo number_format((float)($payment['amount_paid'] ?? 0), 2); ?></td>
                                                <td><?php echo htmlspecialchars($payment['method'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['paid_at'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($payment['reference_no'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="action-btns">
                                                        <a href="index.php?page=manager_payments&action=view&id=<?php echo (int)$payment['id']; ?>" class="btn btn-sm btn-secondary">View</a>
                                                        <form method="POST" action="index.php?page=manager_payments" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                            <input type="hidden" name="form_action" value="delete_payment">
                                                            <input type="hidden" name="id" value="<?php echo (int)$payment['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
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
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay" style="display:none;">
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
            document.getElementById("confirmModal").style.display = "flex";
            pendingAction = callback;
        }
        
        function closeModal() {
            document.getElementById("confirmModal").style.display = "none";
            pendingAction = null;
            pendingRow = null;
        }
        
        document.getElementById("confirmBtn").addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
        
        // Table search
        document.getElementById("paymentSearch")?.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#paymentsTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    </script>
</body>
</html>
