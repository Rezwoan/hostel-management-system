/* Room View JavaScript - Cascading dropdowns and auto-population */

document.addEventListener('DOMContentLoaded', function() {
    // ADD PAGE LOGIC
    if (window.currentAction === 'add') {
        const hostelSelect = document.getElementById('hostel_id');
        const floorSelect = document.getElementById('floor_id');
        const roomNoInput = document.getElementById('room_no');
        const roomTypeSelect = document.getElementById('room_type_id');
        const capacityInput = document.getElementById('capacity');
        
        if (hostelSelect && floorSelect && roomNoInput && roomTypeSelect && capacityInput) {
            
            // When hostel is selected, load its floors
            hostelSelect.addEventListener('change', function() {
                const hostelId = this.value;
                
                // Reset dependent fields
                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                floorSelect.disabled = true;
                roomNoInput.value = '';
                
                if (hostelId) {
                    // Fetch floors for selected hostel
                    fetch('app/Controllers/Api/get_floors.php?hostel_id=' + hostelId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                                data.data.forEach(floor => {
                                    const option = document.createElement('option');
                                    option.value = floor.id;
                                    option.textContent = (floor.label || 'Floor ' + floor.floor_no);
                                    floorSelect.appendChild(option);
                                });
                                floorSelect.disabled = false;
                            } else {
                                floorSelect.innerHTML = '<option value="">No floors available</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading floors:', error);
                            floorSelect.innerHTML = '<option value="">Error loading floors</option>';
                        });
                } else {
                    floorSelect.innerHTML = '<option value="">Select Hostel First</option>';
                }
            });
            
            // When floor is selected, generate room number
            floorSelect.addEventListener('change', function() {
                const floorId = this.value;
                
                if (floorId) {
                    roomNoInput.value = 'Loading...';
                    roomNoInput.disabled = true;
                    
                    // Fetch next room number for selected floor
                    fetch('app/Controllers/Api/get_next_room_number.php?floor_id=' + floorId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                roomNoInput.value = data.next_room_no;
                            } else {
                                console.error('Error:', data.error);
                                roomNoInput.value = '101';
                            }
                            roomNoInput.disabled = false;
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            roomNoInput.value = '101';
                            roomNoInput.disabled = false;
                        });
                } else {
                    roomNoInput.value = '';
                }
            });
            
            // When room type is selected, populate capacity
            roomTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const capacity = selectedOption.getAttribute('data-capacity');
                
                if (capacity) {
                    capacityInput.value = capacity;
                } else {
                    capacityInput.value = '';
                }
            });
        }
    }
    
    // EDIT PAGE LOGIC
    if (window.currentAction === 'edit') {
        const editHostelSelect = document.getElementById('edit_hostel_id');
        const editFloorSelect = document.getElementById('edit_floor_id');
        const editRoomTypeSelect = document.getElementById('edit_room_type_id');
        const editCapacityInput = document.getElementById('edit_capacity');
        
        if (editHostelSelect && editFloorSelect && editRoomTypeSelect && editCapacityInput) {
            
            // Store all floor options with their hostel IDs
            const allFloorOptions = Array.from(editFloorSelect.options).filter(opt => opt.value !== '');
            const currentFloorId = editFloorSelect.getAttribute('data-current-floor');
            
            // Filter floors when hostel changes
            editHostelSelect.addEventListener('change', function() {
                const hostelId = this.value;
                
                editFloorSelect.innerHTML = '<option value="">Select Floor</option>';
                
                if (hostelId) {
                    // Fetch floors for selected hostel
                    fetch('app/Controllers/Api/get_floors.php?hostel_id=' + hostelId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.length > 0) {
                                data.data.forEach(floor => {
                                    const option = document.createElement('option');
                                    option.value = floor.id;
                                    option.textContent = (floor.label || 'Floor ' + floor.floor_no);
                                    if (floor.id == currentFloorId) {
                                        option.selected = true;
                                    }
                                    editFloorSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error loading floors:', error);
                        });
                }
            });
            
            // When room type is selected, populate capacity
            editRoomTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const capacity = selectedOption.getAttribute('data-capacity');
                
                if (capacity) {
                    editCapacityInput.value = capacity;
                }
            });
        }
    }
});
