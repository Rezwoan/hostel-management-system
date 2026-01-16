<?php
// Admin Sidebar Partial
// Include this in all admin views
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2>HMS Admin</h2>
        <p>Hostel Management System</p>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <a href="index.php?page=admin_dashboard" class="nav-link <?php echo ($page ?? '') === 'admin_dashboard' ? 'active' : ''; ?>">Dashboard</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">User Management</div>
            <a href="index.php?page=admin_users" class="nav-link <?php echo ($page ?? '') === 'admin_users' ? 'active' : ''; ?>">Users</a>
            <a href="index.php?page=admin_students" class="nav-link <?php echo ($page ?? '') === 'admin_students' ? 'active' : ''; ?>">Students</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Hostel Management</div>
            <a href="index.php?page=admin_hostels" class="nav-link <?php echo ($page ?? '') === 'admin_hostels' ? 'active' : ''; ?>">Hostels</a>
            <a href="index.php?page=admin_hostel_managers" class="nav-link <?php echo ($page ?? '') === 'admin_hostel_managers' ? 'active' : ''; ?>">Hostel Managers</a>
            <a href="index.php?page=admin_floors" class="nav-link <?php echo ($page ?? '') === 'admin_floors' ? 'active' : ''; ?>">Floors</a>
            <a href="index.php?page=admin_room_types" class="nav-link <?php echo ($page ?? '') === 'admin_room_types' ? 'active' : ''; ?>">Room Types</a>
            <a href="index.php?page=admin_rooms" class="nav-link <?php echo ($page ?? '') === 'admin_rooms' ? 'active' : ''; ?>">Rooms</a>
            <a href="index.php?page=admin_seats" class="nav-link <?php echo ($page ?? '') === 'admin_seats' ? 'active' : ''; ?>">Seats</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Applications & Allocations</div>
            <a href="index.php?page=admin_applications" class="nav-link <?php echo ($page ?? '') === 'admin_applications' ? 'active' : ''; ?>">Applications</a>
            <a href="index.php?page=admin_allocations" class="nav-link <?php echo ($page ?? '') === 'admin_allocations' ? 'active' : ''; ?>">Allocations</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Finance</div>
            <a href="index.php?page=admin_fee_periods" class="nav-link <?php echo ($page ?? '') === 'admin_fee_periods' ? 'active' : ''; ?>">Fee Periods</a>
            <a href="index.php?page=admin_invoices" class="nav-link <?php echo ($page ?? '') === 'admin_invoices' ? 'active' : ''; ?>">Invoices</a>
            <a href="index.php?page=admin_payments" class="nav-link <?php echo ($page ?? '') === 'admin_payments' ? 'active' : ''; ?>">Payments</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Support</div>
            <a href="index.php?page=admin_complaint_categories" class="nav-link <?php echo ($page ?? '') === 'admin_complaint_categories' ? 'active' : ''; ?>">Complaint Categories</a>
            <a href="index.php?page=admin_complaints" class="nav-link <?php echo ($page ?? '') === 'admin_complaints' ? 'active' : ''; ?>">Complaints</a>
            <a href="index.php?page=admin_notices" class="nav-link <?php echo ($page ?? '') === 'admin_notices' ? 'active' : ''; ?>">Notices</a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">System</div>
            <a href="index.php?page=admin_audit_logs" class="nav-link <?php echo ($page ?? '') === 'admin_audit_logs' ? 'active' : ''; ?>">Audit Logs</a>
            <a href="index.php?page=logout" class="nav-link">Logout</a>
        </div>
    </nav>
</aside>
