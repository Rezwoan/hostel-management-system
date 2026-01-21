/* Admin Common JavaScript - Shared functionality across Admin views */

// Modal functions
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
