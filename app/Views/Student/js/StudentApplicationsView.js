/* Student Applications View JavaScript */

let formSubmitted = false;

function handleFormSubmit(form) {
    if (formSubmitted) {
        alert('Form is already being submitted. Please wait.');
        return false;
    }
    
    formSubmitted = true;
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    
    return true;
}
