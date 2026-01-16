<?php
// Admin Header with Top Navigation
// Determine active section based on current page
$currentPage = $page ?? '';
$activeSection = 'dashboard';

$userPages = ['admin_users', 'admin_students', 'admin_managers', 'admin_admins'];
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
        
        <!-- Users Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'users' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Users <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'users' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_users">All Users</a>
                <a href="index.php?page=admin_students">Students</a>
                <a href="index.php?page=admin_managers">Managers</a>
                <a href="index.php?page=admin_admins">Admins</a>
                <a href="index.php?page=admin_users&action=add">+ Add User</a>
            </div>
        </div>
        
        <!-- Hostel Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'hostel' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Hostel <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'hostel' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_hostels">Hostels</a>
                <a href="index.php?page=admin_hostel_managers">Managers</a>
                <a href="index.php?page=admin_floors">Floors</a>
                <a href="index.php?page=admin_room_types">Room Types</a>
                <a href="index.php?page=admin_rooms">Rooms</a>
                <a href="index.php?page=admin_seats">Seats</a>
            </div>
        </div>
        
        <!-- Allocation Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'allocation' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Allocation <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'allocation' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_applications">Applications</a>
                <a href="index.php?page=admin_allocations">Allocations</a>
                <a href="index.php?page=admin_allocations&action=add">+ New Allocation</a>
            </div>
        </div>
        
        <!-- Finance Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'finance' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Finance <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'finance' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_fee_periods">Fee Periods</a>
                <a href="index.php?page=admin_invoices">Invoices</a>
                <a href="index.php?page=admin_payments">Payments</a>
                <a href="index.php?page=admin_payments&action=add">+ Record Payment</a>
            </div>
        </div>
        
        <!-- Support Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'support' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Support <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'support' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_complaints">Complaints</a>
                <a href="index.php?page=admin_complaint_categories">Categories</a>
                <a href="index.php?page=admin_notices">Notices</a>
                <a href="index.php?page=admin_notices&action=add">+ New Notice</a>
            </div>
        </div>
        
        <!-- System Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'system' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                System <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'system' ? 'open' : ''; ?>">
                <a href="index.php?page=admin_audit_logs">Audit Logs</a>
            </div>
        </div>
        
        <a href="index.php?page=logout" class="logout">Logout</a>
    </nav>
</div>
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()"></div>

<script>
function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.getElementById('mobileMenuOverlay').classList.toggle('open');
}

function toggleMobileSubmenu(btn) {
    const submenu = btn.nextElementSibling;
    const isOpen = submenu.classList.contains('open');
    
    // Close all other submenus (optional - for accordion behavior)
    // document.querySelectorAll('.mobile-submenu.open').forEach(s => s.classList.remove('open'));
    // document.querySelectorAll('.mobile-nav-toggle').forEach(b => b.classList.remove('expanded'));
    
    // Toggle current submenu
    submenu.classList.toggle('open');
    btn.classList.toggle('expanded');
}
</script>
