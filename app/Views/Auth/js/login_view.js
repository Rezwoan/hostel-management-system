/**
 * Login Page JavaScript
 * File: app/Views/Auth/js/login_view.js
 * 
 * Contains view-specific JavaScript for the login page.
 * Add any client-side enhancements here if needed.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on email field when page loads
    const emailField = document.getElementById('email');
    if (emailField && !emailField.value) {
        emailField.focus();
    }
});
