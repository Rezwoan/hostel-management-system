/**
 * Signup Page JavaScript
 * File: app/Views/Auth/js/signup_view.js
 * 
 * Contains view-specific JavaScript for the signup page.
 * Add any client-side enhancements here if needed.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first field when page loads
    const nameField = document.getElementById('full_name');
    if (nameField && !nameField.value) {
        nameField.focus();
    }
});
