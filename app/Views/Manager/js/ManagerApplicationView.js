/* Manager Application View JavaScript */

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
        if (!confirm('Are you sure you want to APPROVE this application? You can allocate a room later.')) {
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
        '<a href="index.php?page=manager_applications" class="btn btn-secondary">Back to List</a>';
}

document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    }
});
