/* Manager Common JavaScript - Shared functionality */

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

// Initialize confirm button listener
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById("confirmBtn");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
    }
});

// Escape HTML utility
function escapeHtml(text) {
    if (!text) return '';
    let div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
