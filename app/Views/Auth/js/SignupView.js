document.addEventListener('DOMContentLoaded', function() {
    var nameField = document.getElementById('full_name');
    if (nameField && !nameField.value) {
        nameField.focus();
    }
});
