<?php
// Manager Header with Top Navigation
$currentPage = $page ?? '';
$activeSection = 'dashboard';

$hostelPages = ['manager_hostels'];
$applicationPages = ['manager_applications', 'manager_allocations'];
$supportPages = ['manager_complaints', 'manager_notices'];
$studentPages = ['manager_students'];
$feePages = ['manager_fees'];

if (in_array($currentPage, $hostelPages)) $activeSection = 'hostels';
elseif (in_array($currentPage, $applicationPages)) $activeSection = 'applications';
elseif (in_array($currentPage, $supportPages)) $activeSection = 'support';
elseif (in_array($currentPage, $studentPages)) $activeSection = 'students';
elseif (in_array($currentPage, $feePages)) $activeSection = 'fees';
elseif ($currentPage === 'manager_dashboard') $activeSection = 'dashboard';
?>
<header class="top-header">
    <div class="top-header-brand">
        <a href="index.php?page=manager_dashboard">HMS Manager</a>
    </div>
    
    <nav class="top-nav">
        <a href="index.php?page=manager_dashboard" class="top-nav-item <?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=manager_hostels" class="top-nav-item <?php echo $activeSection === 'hostels' ? 'active' : ''; ?>">Hostels</a>
        <a href="index.php?page=manager_applications" class="top-nav-item <?php echo $activeSection === 'applications' ? 'active' : ''; ?>">Applications</a>
        <a href="index.php?page=manager_students" class="top-nav-item <?php echo $activeSection === 'students' ? 'active' : ''; ?>">Students</a>
        <a href="index.php?page=manager_complaints" class="top-nav-item <?php echo $activeSection === 'support' ? 'active' : ''; ?>">Support</a>
        <a href="index.php?page=manager_fees" class="top-nav-item <?php echo $activeSection === 'fees' ? 'active' : ''; ?>">Fees</a>
    </nav>
    
    <div class="top-header-actions">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Manager'); ?></span>
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
        <a href="index.php?page=manager_dashboard" class="<?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=manager_hostels" class="<?php echo $activeSection === 'hostels' ? 'active' : ''; ?>">Hostels</a>
        
        <!-- Applications Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'applications' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Applications <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'applications' ? 'open' : ''; ?>">
                <a href="index.php?page=manager_applications">Applications</a>
                <a href="index.php?page=manager_allocations">Allocations</a>
                <a href="index.php?page=manager_allocations&action=add">+ New Allocation</a>
            </div>
        </div>
        
        <a href="index.php?page=manager_students" class="<?php echo $activeSection === 'students' ? 'active' : ''; ?>">Students</a>
        
        <!-- Support Section -->
        <div class="mobile-nav-group">
            <button class="mobile-nav-toggle <?php echo $activeSection === 'support' ? 'active' : ''; ?>" onclick="toggleMobileSubmenu(this)">
                Support <span class="toggle-icon">▼</span>
            </button>
            <div class="mobile-submenu <?php echo $activeSection === 'support' ? 'open' : ''; ?>">
                <a href="index.php?page=manager_complaints">Complaints</a>
                <a href="index.php?page=manager_notices">Notices</a>
                <a href="index.php?page=manager_notices&action=add">+ New Notice</a>
            </div>
        </div>
        
        <a href="index.php?page=manager_fees" class="<?php echo $activeSection === 'fees' ? 'active' : ''; ?>">Fees</a>
    </nav>
</div>

<script src="app/Views/Manager/js/header.js"></script>
