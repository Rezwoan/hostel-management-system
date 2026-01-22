/* Seat View JavaScript - Cascading dropdowns and auto-population */

document.addEventListener('DOMContentLoaded', function() {
    // ADD PAGE LOGIC
    if (window.currentAction === 'add') {
        const hostelSelect = document.getElementById('hostel_id');
        const floorSelect = document.getElementById('floor_id');
        const roomSelect = document.getElementById('room_id');
        const seatLabelInput = document.getElementById('seat_label');
        const capacityInfo = document.getElementById('capacity-info');
        const capacityText = document.getElementById('capacity-text');
        
        if (hostelSelect && floorSelect && roomSelect && seatLabelInput) {
            
            // When hostel is selected, load its floors
            hostelSelect.addEventListener('change', function() {
                const hostelId = this.value;
                
                // Reset dependent fields
                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                floorSelect.disabled = true;
                roomSelect.innerHTML = '<option value="">Select Floor First</option>';
                roomSelect.disabled = true;
                seatLabelInput.value = '';
                if (capacityInfo) capacityInfo.style.display = 'none';
                
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
            
            // When floor is selected, load rooms with available capacity
            floorSelect.addEventListener('change', function() {
                const floorId = this.value;
                
                // Reset dependent fields
                roomSelect.innerHTML = '<option value="">Select Room</option>';
                roomSelect.disabled = true;
                seatLabelInput.value = '';
                if (capacityInfo) capacityInfo.style.display = 'none';
                
                if (floorId) {
                    // Fetch rooms with available capacity for selected floor
                    fetch('app/Controllers/Api/get_rooms_with_capacity.php?floor_id=' + floorId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.rooms.length > 0) {
                                roomSelect.innerHTML = '<option value="">Select Room</option>';
                                data.rooms.forEach(room => {
                                    const option = document.createElement('option');
                                    option.value = room.id;
                                    option.setAttribute('data-capacity', room.capacity);
                                    option.setAttribute('data-seat-count', room.seat_count);
                                    option.setAttribute('data-available', room.available_seats);
                                    option.textContent = room.room_no + ' (Available: ' + room.available_seats + '/' + room.capacity + ')';
                                    roomSelect.appendChild(option);
                                });
                                roomSelect.disabled = false;
                            } else {
                                roomSelect.innerHTML = '<option value="">No rooms available</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading rooms:', error);
                            roomSelect.innerHTML = '<option value="">Error loading rooms</option>';
                        });
                } else {
                    roomSelect.innerHTML = '<option value="">Select Floor First</option>';
                }
            });
            
            // When room is selected, get next seat label and show capacity info
            roomSelect.addEventListener('change', function() {
                const roomId = this.value;
                const selectedOption = this.options[this.selectedIndex];
                
                seatLabelInput.value = '';
                if (capacityInfo) capacityInfo.style.display = 'none';
                
                if (roomId) {
                    // Show capacity info
                    const capacity = selectedOption.getAttribute('data-capacity');
                    const seatCount = selectedOption.getAttribute('data-seat-count');
                    const available = selectedOption.getAttribute('data-available');
                    
                    if (capacityInfo && capacityText) {
                        capacityText.textContent = `Room capacity: ${capacity}, Current seats: ${seatCount}, Available slots: ${available}`;
                        capacityInfo.style.display = 'block';
                    }
                    
                    // Fetch next seat label
                    seatLabelInput.value = 'Loading...';
                    seatLabelInput.disabled = true;
                    
                    fetch('app/Controllers/Api/get_next_seat_label.php?room_id=' + roomId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                seatLabelInput.value = data.next_seat_label;
                            } else {
                                console.error('Error:', data.error);
                                seatLabelInput.value = 'A';
                            }
                            seatLabelInput.disabled = false;
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            seatLabelInput.value = 'A';
                            seatLabelInput.disabled = false;
                        });
                }
            });
        }
    }
});
