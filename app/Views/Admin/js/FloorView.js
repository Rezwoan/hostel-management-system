/* Floor View JavaScript - Add floor page functionality */

document.addEventListener('DOMContentLoaded', function() {
    const hostelSelect = document.getElementById('hostel_id');
    const floorNoInput = document.getElementById('floor_no');
    const labelInput = document.getElementById('label');
    
    // Only add listener on the add floor page
    if (hostelSelect && floorNoInput && labelInput && window.currentAction === 'add') {
        hostelSelect.addEventListener('change', function() {
            const hostelId = this.value;
            
            if (hostelId) {
                // Show loading state
                floorNoInput.value = '';
                labelInput.value = 'Loading...';
                floorNoInput.disabled = true;
                labelInput.disabled = true;
                
                // Fetch next floor number from API
                fetch('app/Controllers/Api/get_next_floor_number.php?hostel_id=' + hostelId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            floorNoInput.value = data.next_floor_no;
                            labelInput.value = data.suggested_label;
                        } else {
                            console.error('Error:', data.error);
                            floorNoInput.value = 0;
                            labelInput.value = 'Floor 0';
                        }
                        floorNoInput.disabled = false;
                        labelInput.disabled = false;
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        floorNoInput.value = 0;
                        labelInput.value = 'Floor 0';
                        floorNoInput.disabled = false;
                        labelInput.disabled = false;
                    });
            } else {
                // Clear fields if no hostel selected
                floorNoInput.value = '';
                labelInput.value = '';
            }
        });
    }
});
