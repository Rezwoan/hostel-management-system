/* Allocation View JavaScript - Student dropdown, cascading selects, and modal functionality */

let studentsData = []; // Store all approved students

// Escape HTML utility
function escapeHtml(text) {
    if (!text) return '';
    let div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load all approved students on page load
function loadApprovedStudents() {
    let studentSelect = document.getElementById("student_id");
    if (!studentSelect) return;
    
    studentSelect.innerHTML = '<option value="">Loading students...</option>';
    
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "app/Controllers/Api/get_approved_students.php", true);
    
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);
            studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
            
            if (response.success && response.data.length > 0) {
                studentsData = response.data;
                response.data.forEach(function(student) {
                    let option = document.createElement("option");
                    option.value = student.id;
                    option.textContent = student.name + " (" + student.email + ")";
                    // Store additional data as data attributes
                    option.dataset.applicationId = student.application_id;
                    option.dataset.hostelId = student.hostel_id;
                    option.dataset.hostelName = student.hostel_name;
                    option.dataset.roomTypeName = student.room_type_name;
                    studentSelect.appendChild(option);
                });
                
                // If pre-selected from URL, auto-select
                if (typeof preSelectedStudentId !== 'undefined' && preSelectedStudentId > 0) {
                    studentSelect.value = preSelectedStudentId;
                    onStudentChange();
                }
            } else {
                studentSelect.innerHTML = '<option value="">No eligible students (need APPROVED application)</option>';
            }
        }
    };
    
    xhr.send();
}

// When student is selected from dropdown
function onStudentChange() {
    let studentSelect = document.getElementById("student_id");
    let selectedOption = studentSelect.options[studentSelect.selectedIndex];
    
    if (!studentSelect.value) {
        // Reset form
        document.getElementById("application_id").value = "";
        document.getElementById("hostel_id_hidden").value = "";
        document.getElementById("applicationInfo").style.display = "none";
        document.getElementById("hostel_id").value = "";
        resetDependentDropdowns();
        document.getElementById("submitBtn").disabled = true;
        return;
    }
    
    // Set hidden application ID and hostel ID
    document.getElementById("application_id").value = selectedOption.dataset.applicationId;
    document.getElementById("hostel_id_hidden").value = selectedOption.dataset.hostelId;
    
    // Show application info
    document.getElementById("applicationInfo").style.display = "block";
    document.getElementById("appInfoContent").innerHTML = 
        'Hostel: <strong>' + escapeHtml(selectedOption.dataset.hostelName) + '</strong> | ' +
        'Room Type: <strong>' + escapeHtml(selectedOption.dataset.roomTypeName) + '</strong>';
    
    // Auto-select hostel from student's application (for display)
    let hostelSelect = document.getElementById("hostel_id");
    hostelSelect.value = selectedOption.dataset.hostelId;
    
    // Enable submit button
    document.getElementById("submitBtn").disabled = false;
    
    // Load floors for this hostel
    loadFloors();
}

function resetDependentDropdowns() {
    document.getElementById("floor_id").innerHTML = '<option value="">Select Floor</option>';
    document.getElementById("floor_id").disabled = true;
    document.getElementById("room_id").innerHTML = '<option value="">Select Room</option>';
    document.getElementById("room_id").disabled = true;
    document.getElementById("seat_id").innerHTML = '<option value="">Select Seat</option>';
    document.getElementById("seat_id").disabled = true;
}

// When Hostel changes, load Floors
function loadFloors() {
    let hostelId = document.getElementById("hostel_id").value;
    let floorSelect = document.getElementById("floor_id");
    let roomSelect = document.getElementById("room_id");
    let seatSelect = document.getElementById("seat_id");
    
    // Reset dependent dropdowns
    floorSelect.innerHTML = '<option value="">Loading floors...</option>';
    floorSelect.disabled = true;
    roomSelect.innerHTML = '<option value="">Select Room</option>';
    roomSelect.disabled = true;
    seatSelect.innerHTML = '<option value="">Select Seat</option>';
    seatSelect.disabled = true;
    
    if (!hostelId) {
        floorSelect.innerHTML = '<option value="">Select Floor</option>';
        return;
    }
    
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "app/Controllers/Api/get_floors.php?hostel_id=" + hostelId, true);
    
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            if (response.success && response.data.length > 0) {
                response.data.forEach(function(floor) {
                    let option = document.createElement("option");
                    option.value = floor.id;
                    option.textContent = "Floor " + floor.floor_number + (floor.name ? " - " + floor.name : "");
                    floorSelect.appendChild(option);
                });
                floorSelect.disabled = false;
            } else {
                floorSelect.innerHTML = '<option value="">No floors available</option>';
            }
        }
    };
    
    xhr.send();
}

// When Floor changes, load Rooms
function loadRooms() {
    let floorId = document.getElementById("floor_id").value;
    let roomSelect = document.getElementById("room_id");
    let seatSelect = document.getElementById("seat_id");
    
    // Reset dependent dropdowns
    roomSelect.innerHTML = '<option value="">Loading rooms...</option>';
    roomSelect.disabled = true;
    seatSelect.innerHTML = '<option value="">Select Seat</option>';
    seatSelect.disabled = true;
    
    if (!floorId) {
        roomSelect.innerHTML = '<option value="">Select Room</option>';
        return;
    }
    
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "app/Controllers/Api/get_rooms.php?floor_id=" + floorId, true);
    
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);
            roomSelect.innerHTML = '<option value="">Select Room</option>';
            if (response.success && response.data.length > 0) {
                response.data.forEach(function(room) {
                    let option = document.createElement("option");
                    option.value = room.id;
                    option.textContent = "Room " + room.room_number + (room.room_type ? " (" + room.room_type + ")" : "");
                    roomSelect.appendChild(option);
                });
                roomSelect.disabled = false;
            } else {
                roomSelect.innerHTML = '<option value="">No rooms available</option>';
            }
        }
    };
    
    xhr.send();
}

// When Room changes, load Seats
function loadSeats() {
    let roomId = document.getElementById("room_id").value;
    let seatSelect = document.getElementById("seat_id");
    
    seatSelect.innerHTML = '<option value="">Loading seats...</option>';
    seatSelect.disabled = true;
    
    if (!roomId) {
        seatSelect.innerHTML = '<option value="">Select Seat</option>';
        return;
    }
    
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "app/Controllers/Api/get_seats.php?room_id=" + roomId + "&available_only=1", true);
    
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);
            seatSelect.innerHTML = '<option value="">Select Seat</option>';
            if (response.success && response.data.length > 0) {
                response.data.forEach(function(seat) {
                    let option = document.createElement("option");
                    option.value = seat.id;
                    option.textContent = seat.seat_label + " - Available";
                    seatSelect.appendChild(option);
                });
                seatSelect.disabled = false;
            } else {
                seatSelect.innerHTML = '<option value="">No available seats in this room</option>';
            }
        }
    };
    
    xhr.send();
}

// Modal functions
let pendingAction = null;

function showConfirm(title, message, callback) {
    document.getElementById("confirmTitle").textContent = title;
    document.getElementById("confirmMessage").textContent = message;
    document.getElementById("confirmModal").classList.add("open");
    pendingAction = callback;
}

function closeModal() {
    document.getElementById("confirmModal").classList.remove("open");
    pendingAction = null;
}

// End allocation from view page
function endAllocationView(id) {
    showConfirm("End Allocation", "Are you sure you want to end this allocation?", function() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "app/Controllers/Api/end_allocation.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let response = JSON.parse(this.responseText);
                if (response.success) {
                    window.location.href = "index.php?page=admin_allocations&msg=allocation_ended";
                } else {
                    alert("Error: " + response.error);
                }
            }
        };
        
        xhr.send("id=" + id);
    });
}

// Initialize
document.addEventListener("DOMContentLoaded", function() {
    // Set up event listeners if elements exist
    const studentSelect = document.getElementById("student_id");
    const hostelSelect = document.getElementById("hostel_id");
    const floorSelect = document.getElementById("floor_id");
    const roomSelect = document.getElementById("room_id");
    const confirmBtn = document.getElementById("confirmBtn");
    
    if (studentSelect) {
        studentSelect.addEventListener("change", onStudentChange);
        loadApprovedStudents();
    }
    
    if (hostelSelect) {
        hostelSelect.addEventListener("change", loadFloors);
    }
    
    if (floorSelect) {
        floorSelect.addEventListener("change", loadRooms);
    }
    
    if (roomSelect) {
        roomSelect.addEventListener("change", loadSeats);
    }
    
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function() {
            if (pendingAction) pendingAction();
            closeModal();
        });
    }
});
