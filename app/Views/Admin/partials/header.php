<?php
// Admin Header with Top Navigation
// Determine active section based on current page
$currentPage = $page ?? '';
$activeSection = 'dashboard';

$userPages = ['admin_users', 'admin_students'];
$hostelPages = ['admin_hostels', 'admin_hostel_managers', 'admin_floors', 'admin_room_types', 'admin_rooms', 'admin_seats'];
$allocationPages = ['admin_applications', 'admin_allocations'];
$financePages = ['admin_fee_periods', 'admin_invoices', 'admin_payments'];
$supportPages = ['admin_complaint_categories', 'admin_complaints', 'admin_notices'];
$systemPages = ['admin_audit_logs'];

if (in_array($currentPage, $userPages)) $activeSection = 'users';
elseif (in_array($currentPage, $hostelPages)) $activeSection = 'hostel';
elseif (in_array($currentPage, $allocationPages)) $activeSection = 'allocation';
elseif (in_array($currentPage, $financePages)) $activeSection = 'finance';
elseif (in_array($currentPage, $supportPages)) $activeSection = 'support';
elseif (in_array($currentPage, $systemPages)) $activeSection = 'system';
elseif ($currentPage === 'admin_dashboard') $activeSection = 'dashboard';
?>
<header class="top-header">
    <div class="top-header-brand">
        <a href="index.php?page=admin_dashboard">HMS Admin</a>
    </div>
    
    <nav class="top-nav">
        <a href="index.php?page=admin_dashboard" class="top-nav-item <?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=admin_users" class="top-nav-item <?php echo $activeSection === 'users' ? 'active' : ''; ?>">Users</a>
        <a href="index.php?page=admin_hostels" class="top-nav-item <?php echo $activeSection === 'hostel' ? 'active' : ''; ?>">Hostel</a>
        <a href="index.php?page=admin_applications" class="top-nav-item <?php echo $activeSection === 'allocation' ? 'active' : ''; ?>">Allocation</a>
        <a href="index.php?page=admin_invoices" class="top-nav-item <?php echo $activeSection === 'finance' ? 'active' : ''; ?>">Finance</a>
        <a href="index.php?page=admin_complaints" class="top-nav-item <?php echo $activeSection === 'support' ? 'active' : ''; ?>">Support</a>
        <a href="index.php?page=admin_audit_logs" class="top-nav-item <?php echo $activeSection === 'system' ? 'active' : ''; ?>">System</a>
    </nav>
    
    <div class="top-header-actions">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
        <a href="index.php?page=logout" class="btn btn-sm btn-outline">Logout</a>
    </div>
    
    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <span>Menu</span>
        <button onclick="toggleMobileMenu()">&times;</button>
    </div>
    <nav class="mobile-nav">
        <a href="index.php?page=admin_dashboard" class="<?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=admin_users" class="<?php echo $activeSection === 'users' ? 'active' : ''; ?>">Users</a>
        <a href="index.php?page=admin_hostels" class="<?php echo $activeSection === 'hostel' ? 'active' : ''; ?>">Hostel</a>
        <a href="index.php?page=admin_applications" class="<?php echo $activeSection === 'allocation' ? 'active' : ''; ?>">Allocation</a>
        <a href="index.php?page=admin_invoices" class="<?php echo $activeSection === 'finance' ? 'active' : ''; ?>">Finance</a>
        <a href="index.php?page=admin_complaints" class="<?php echo $activeSection === 'support' ? 'active' : ''; ?>">Support</a>
        <a href="index.php?page=admin_audit_logs" class="<?php echo $activeSection === 'system' ? 'active' : ''; ?>">System</a>
        <a href="index.php?page=logout" class="logout">Logout</a>
    </nav>
</div>
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>

<script>
function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.getElementById('mobileMenuOverlay').classList.toggle('open');
}
</script>
