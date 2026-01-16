<?php
// Admin Dashboard View
$page = 'admin_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - HMS Admin</title>
    <?php include __DIR__ . '/partials/head-meta.php'; ?>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="app/Views/Admin/css/admin.css">
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="admin-layout">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="admin-main full-width">
            <div class="admin-content">
                <div class="page-header">
                    <h2>Dashboard</h2>
                    <span class="refresh-indicator">
                        <span class="dot"></span>
                        Last updated: <span id="stat-last-updated">--</span>
                    </span>
                </div>
                
                <!-- Key Metrics -->
                <div class="stats-grid stats-grid-4">
                    <div class="stat-card stat-card-success">
                        <h3>Occupancy</h3>
                        <div class="stat-value">
                            <span id="stat-occupied-seats"><?php echo (int)($data['stats']['occupied_seats'] ?? 0); ?></span> / 
                            <span id="stat-total-seats"><?php echo (int)($data['stats']['total_seats'] ?? 0); ?></span>
                        </div>
                        <div class="stat-label"><span id="stat-occupancy-rate"><?php echo ($data['stats']['occupancy_rate'] ?? 0); ?></span>% filled</div>
                    </div>
                    <div class="stat-card stat-card-info">
                        <h3>Available</h3>
                        <div class="stat-value" id="stat-available-seats"><?php echo (int)($data['stats']['available_seats'] ?? 0); ?></div>
                        <div class="stat-label">seats ready</div>
                    </div>
                    <div class="stat-card stat-card-warning">
                        <h3>Pending</h3>
                        <div class="stat-value">
                            <span id="stat-pending-applications"><?php echo (int)($data['stats']['pending_applications'] ?? 0); ?></span> / 
                            <span id="stat-open-complaints"><?php echo (int)($data['stats']['open_complaints'] ?? 0); ?></span>
                        </div>
                        <div class="stat-label">apps / complaints</div>
                    </div>
                    <div class="stat-card stat-card-danger">
                        <h3>Unpaid</h3>
                        <div class="stat-value" id="stat-pending-amount">$<?php echo number_format((float)($data['stats']['pending_amount'] ?? 0), 2); ?></div>
                        <div class="stat-label"><span id="stat-unpaid-invoices"><?php echo (int)($data['stats']['unpaid_invoices'] ?? 0); ?></span> invoices</div>
                    </div>
                </div>
                
                <!-- Two Column Layout -->
                <div class="dashboard-grid">
                    <!-- Left Column -->
                    <div class="dashboard-column">
                        <!-- Finance Summary -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>💰 Finance</h3>
                                <a href="index.php?page=admin_invoices" class="btn btn-sm btn-secondary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="finance-item">
                                    <span>Total Due</span>
                                    <strong id="stat-total-due">$<?php echo number_format((float)($data['stats']['total_due'] ?? 0), 2); ?></strong>
                                </div>
                                <div class="finance-item finance-success">
                                    <span>Collected</span>
                                    <strong id="stat-total-collected">$<?php echo number_format((float)($data['stats']['total_collected'] ?? 0), 2); ?></strong>
                                </div>
                                <div class="finance-item finance-primary">
                                    <span>Today</span>
                                    <strong id="stat-today-collection">$<?php echo number_format((float)($data['stats']['today_collection'] ?? 0), 2); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>🏠 Infrastructure</h3>
                            </div>
                            <div class="card-body">
                                <div class="finance-item">
                                    <span>Students</span>
                                    <strong id="stat-total-students"><?php echo (int)($data['stats']['total_students'] ?? 0); ?></strong>
                                </div>
                                <div class="finance-item">
                                    <span>Hostels</span>
                                    <strong id="stat-total-hostels"><?php echo (int)($data['stats']['total_hostels'] ?? 0); ?></strong>
                                </div>
                                <div class="finance-item">
                                    <span>Rooms</span>
                                    <strong id="stat-total-rooms"><?php echo (int)($data['stats']['total_rooms'] ?? 0); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="dashboard-column">
                        <!-- Recent Activity -->
                        <div class="table-card">
                            <div class="table-card-header">
                                <h3>📋 Recent Activity</h3>
                                <a href="index.php?page=admin_audit_logs" class="btn btn-sm btn-secondary">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-compact">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['recent_logs'])): ?>
                                            <?php foreach (array_slice($data['recent_logs'], 0, 6) as $log): ?>
                                                <tr>
                                                    <td><?php echo date('H:i', strtotime($log['created_at'] ?? '')); ?></td>
                                                    <td><?php echo htmlspecialchars($log['user_name'] ?? 'System'); ?></td>
                                                    <td><?php echo htmlspecialchars($log['action'] ?? ''); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="empty-state">No recent activity</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
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
        
        refreshDashboard();
        setInterval(refreshDashboard, 10000);
    </script>
</body>
</html>
