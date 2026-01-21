/* Payment View JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    // Payment form functionality (Add Payment page)
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
    
    if (invoiceSelect && amountInput && submitBtn) {
        function updatePaymentAmount() {
            const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
            const balance = parseFloat(selectedOption.dataset.balance) || 0;
            const due = parseFloat(selectedOption.dataset.due) || 0;
            const paid = parseFloat(selectedOption.dataset.paid) || 0;
            
            currentBalance = balance;
            
            if (invoiceSelect.value === '') {
                if (invoiceSummary) invoiceSummary.style.display = 'none';
                amountInput.value = '';
                amountInput.readOnly = true;
                amountInput.max = '';
                if (amountHint) amountHint.textContent = 'Select an invoice first';
                submitBtn.disabled = true;
                return;
            }
            
            // Show invoice summary
            if (invoiceSummary) {
                invoiceSummary.style.display = 'block';
                if (summaryDue) summaryDue.textContent = due.toFixed(2);
                if (summaryPaid) summaryPaid.textContent = paid.toFixed(2);
                if (summaryBalance) summaryBalance.textContent = balance.toFixed(2);
            }
            
            if (balance <= 0) {
                amountInput.value = '';
                amountInput.readOnly = true;
                if (amountHint) amountHint.textContent = 'This invoice is already fully paid';
                submitBtn.disabled = true;
                return;
            }
            
            submitBtn.disabled = false;
            
            if (paymentTypeFull && paymentTypeFull.checked) {
                // Full payment - auto-fill with balance
                amountInput.value = balance.toFixed(2);
                amountInput.readOnly = true;
                if (amountHint) amountHint.textContent = 'Full payment will clear the remaining balance';
            } else {
                // Partial payment - allow manual entry
                amountInput.readOnly = false;
                amountInput.max = balance;
                amountInput.value = '';
                if (amountHint) amountHint.textContent = 'Enter amount (max: $' + balance.toFixed(2) + ')';
            }
        }
        
        invoiceSelect.addEventListener('change', updatePaymentAmount);
        
        if (paymentTypeFull) {
            paymentTypeFull.addEventListener('change', updatePaymentAmount);
        }
        if (paymentTypePartial) {
            paymentTypePartial.addEventListener('change', updatePaymentAmount);
        }
    }
    
    // Table search
    const tableSearch = document.getElementById("tableSearch");
    if (tableSearch) {
        tableSearch.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#paymentsTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }
});
