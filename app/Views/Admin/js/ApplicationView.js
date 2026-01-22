/* Application View JavaScript */

let pendingAction = null;

// Modal functions
function showConfirm(title, message, callback, btnText, btnClass) {
    document.getElementById("confirmTitle").textContent = title;
    document.getElementById("confirmMessage").textContent = message;
    let confirmBtn = document.getElementById("confirmBtn");
    confirmBtn.textContent = btnText || "Confirm";
    confirmBtn.className = "btn " + (btnClass || "btn-success");
    document.getElementById("confirmModal").style.display = "flex";
    pendingAction = callback;
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
    pendingAction = null;
}

// Reject Modal functions
function openRejectModal(appId) {
    document.getElementById('rejectAppId').value = appId;
    document.getElementById('rejectReasonInput').value = '';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectReasonInput').value = '';
    document.getElementById('rejectAppId').value = '';
}

// Review form functions (for view page)
function submitReview(status) {
    console.log('submitReview called with status:', status);
    
    const statusField = document.getElementById("reviewStatus");
    if (!statusField) {
        alert('Error: Status field not found');
        return;
    }
    
    statusField.value = status;
    console.log('Status field set to:', statusField.value);
    
    if (status === 'REJECTED') {
        let reason = document.getElementById("reject_reason").value.trim();
        if (!reason) {
            alert("Please provide a rejection reason.");
            return;
        }
    }
    
    if (status === 'APPROVED') {
        if (!confirm('Are you sure you want to APPROVE this application? You can revert this later if needed.')) {
            return;
        }
    }
    
    const form = document.getElementById("reviewForm");
    if (!form) {
        alert('Error: Form not found');
        return;
    }
    
    console.log('Submitting form...');
    form.submit();
}

function showRejectForm() {
    document.getElementById("rejectReasonGroup").style.display = "block";
    // Replace reject button with confirm reject button
    let actions = document.querySelector(".form-actions");
    actions.innerHTML = '<button type="button" class="btn btn-danger" onclick="submitReview(\'REJECTED\')">Confirm Rejection</button>' +
        '<button type="button" class="btn btn-secondary" onclick="cancelReject()">Cancel</button>';
}

function cancelReject() {
    document.getElementById("rejectReasonGroup").style.display = "none";
    document.getElementById("reject_reason").value = "";
    let actions = document.querySelector(".form-actions");
    actions.innerHTML = '<button type="button" class="btn btn-success" onclick="submitReview(\'APPROVED\')">Approve</button>' +
        '<button type="button" class="btn btn-danger" onclick="showRejectForm()">Reject</button>' +
        '<a href="index.php?page=admin_applications" class="btn btn-secondary">Back to List</a>';
}

// Initialize
document.addEventListener("DOMContentLoaded", function() {
    const confirmBtn = document.getElementById("confirmBtn");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
    }
    
    // Close reject modal when clicking outside
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    }
    
    // Table search
    const tableSearch = document.getElementById("tableSearch");
    if (tableSearch) {
        tableSearch.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#applicationsTable tbody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }
});
