/* Manager Allocation View JavaScript - Similar to Admin Allocation View */

let studentsData = [];

function escapeHtml(text) {
    if (!text) return '';
    let div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

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
                    option.dataset.applicationId = student.application_id;
                    option.dataset.hostelId = student.hostel_id;
                    option.dataset.hostelName = student.hostel_name;
                    option.dataset.roomTypeName = student.room_type_name;
                    studentSelect.appendChild(option);
                });
                
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

function onStudentChange() {
    let studentSelect = document.getElementById("student_id");
    let selectedOption = studentSelect.options[studentSelect.selectedIndex];
    
    if (!studentSelect.value) {
        document.getElementById("application_id").value = "";
        document.getElementById("hostel_id_hidden").value = "";
        document.getElementById("applicationInfo").style.display = "none";
        document.getElementById("hostel_id").value = "";
        resetDependentDropdowns();
        document.getElementById("submitBtn").disabled = true;
        return;
    }
    
    document.getElementById("application_id").value = selectedOption.dataset.applicationId;
    document.getElementById("hostel_id_hidden").value = selectedOption.dataset.hostelId;
    
    document.getElementById("applicationInfo").style.display = "block";
    document.getElementById("appInfoContent").innerHTML = 
        'Hostel: <strong>' + escapeHtml(selectedOption.dataset.hostelName) + '</strong> | ' +
        'Room Type: <strong>' + escapeHtml(selectedOption.dataset.roomTypeName) + '</strong>';
    
    let hostelSelect = document.getElementById("hostel_id");
    hostelSelect.value = selectedOption.dataset.hostelId;
    
    document.getElementById("submitBtn").disabled = false;
    
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

function loadFloors() {
    let hostelId = document.getElementById("hostel_id").value;
    let floorSelect = document.getElementById("floor_id");
    let roomSelect = document.getElementById("room_id");
    let seatSelect = document.getElementById("seat_id");
    
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

function loadRooms() {
    let floorId = document.getElementById("floor_id").value;
    let roomSelect = document.getElementById("room_id");
    let seatSelect = document.getElementById("seat_id");
    
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

document.addEventListener("DOMContentLoaded", function() {
    const studentSelect = document.getElementById("student_id");
    const hostelSelect = document.getElementById("hostel_id");
    const floorSelect = document.getElementById("floor_id");
    const roomSelect = document.getElementById("room_id");
    
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
});
