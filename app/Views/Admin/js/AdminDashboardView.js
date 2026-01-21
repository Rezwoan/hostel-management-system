/* Admin Dashboard JavaScript */

// Auto-refresh dashboard stats every 10 seconds
function refreshDashboard() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "app/Controllers/Api/get_dashboard_stats.php", true);
    
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let response = JSON.parse(this.responseText);
            if (response.success) {
                let stats = response.data;
                
                updateStat("stat-total-students", stats.total_students);
                updateStat("stat-total-hostels", stats.total_hostels);
                updateStat("stat-total-rooms", stats.total_rooms);
                updateStat("stat-total-seats", stats.total_seats);
                updateStat("stat-occupied-seats", stats.occupied_seats);
                updateStat("stat-available-seats", stats.available_seats);
                updateStat("stat-occupancy-rate", stats.occupancy_rate);
                updateStat("stat-pending-applications", stats.pending_applications);
                updateStat("stat-open-complaints", stats.open_complaints);
                updateStat("stat-unpaid-invoices", stats.unpaid_invoices);
                updateStat("stat-total-due", "$" + formatMoney(stats.total_due));
                updateStat("stat-total-collected", "$" + formatMoney(stats.total_collected));
                updateStat("stat-pending-amount", "$" + formatMoney(stats.total_due - stats.total_collected));
                updateStat("stat-today-collection", "$" + formatMoney(stats.today_collection));
                
                document.getElementById("stat-last-updated").textContent = stats.last_updated;
            }
        }
    };
    
    xhr.send();
}

function updateStat(elementId, newValue) {
    let element = document.getElementById(elementId);
    if (element && element.textContent != newValue) {
        element.classList.add("updating");
        element.textContent = newValue;
        setTimeout(function() { element.classList.remove("updating"); }, 500);
    }
}

function formatMoney(amount) {
    return parseFloat(amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

// Initialize dashboard refresh
document.addEventListener('DOMContentLoaded', function() {
    refreshDashboard();
    setInterval(refreshDashboard, 10000);
});
