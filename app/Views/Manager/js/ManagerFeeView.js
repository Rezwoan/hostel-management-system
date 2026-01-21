/* Manager Fee View JavaScript */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-select hostel and fetch amount due based on student's allocation
    const studentSelect = document.getElementById('student_user_id');
    if (studentSelect) {
        studentSelect.addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let hostelId = selectedOption.dataset.hostelId;
            let studentUserId = this.value;
            
            if (hostelId) {
                document.getElementById('hostel_id').value = hostelId;
            }
            
            // Fetch the room fee for this student
            if (studentUserId) {
                fetch(`app/Controllers/Api/get_student_room_fee.php?student_user_id=${studentUserId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.default_fee) {
                            document.getElementById('amount_due').value = data.data.default_fee.toFixed(2);
                        } else {
                            console.error('Failed to fetch room fee:', data.error || 'Unknown error');
                            document.getElementById('amount_due').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching room fee:', error);
                        document.getElementById('amount_due').value = '';
                    });
            } else {
                document.getElementById('amount_due').value = '';
            }
        });
    }
});
