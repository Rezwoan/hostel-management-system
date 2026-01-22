/* Complaint View JavaScript */

// Simple table search filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById("tableSearch");
    if (searchInput) {
        searchInput.addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            let rows = document.querySelectorAll("#complaintsTableBody tr");
            
            rows.forEach(function(row) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }
});
