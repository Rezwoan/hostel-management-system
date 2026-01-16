document.addEventListener('DOMContentLoaded', function() {
    var emailField = document.getElementById('email');
    if (emailField && !emailField.value) {
        emailField.focus();
    }
});
