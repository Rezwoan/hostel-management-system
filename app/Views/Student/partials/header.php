<?php
// Student Header with Top Navigation
$currentPage = $page ?? '';
$activeSection = 'dashboard';

if ($currentPage === 'student_dashboard') $activeSection = 'dashboard';
elseif ($currentPage === 'student_applications') $activeSection = 'applications';
elseif ($currentPage === 'student_room') $activeSection = 'room';
elseif ($currentPage === 'student_complaints') $activeSection = 'complaints';
elseif ($currentPage === 'student_notices') $activeSection = 'notices';
elseif ($currentPage === 'student_fees') $activeSection = 'fees';
elseif ($currentPage === 'student_profile') $activeSection = 'profile';
?>
<header class="top-header">
    <div class="top-header-brand">
        <a href="index.php?page=student_dashboard">HMS Student</a>
    </div>
    
    <nav class="top-nav">
        <a href="index.php?page=student_dashboard" class="top-nav-item <?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=student_applications" class="top-nav-item <?php echo $activeSection === 'applications' ? 'active' : ''; ?>">Applications</a>
        <a href="index.php?page=student_room" class="top-nav-item <?php echo $activeSection === 'room' ? 'active' : ''; ?>">My Room</a>
        <a href="index.php?page=student_complaints" class="top-nav-item <?php echo $activeSection === 'complaints' ? 'active' : ''; ?>">Complaints</a>
        <a href="index.php?page=student_notices" class="top-nav-item <?php echo $activeSection === 'notices' ? 'active' : ''; ?>">Notices</a>
        <a href="index.php?page=student_fees" class="top-nav-item <?php echo $activeSection === 'fees' ? 'active' : ''; ?>">Fees</a>
        <a href="index.php?page=student_profile" class="top-nav-item <?php echo $activeSection === 'profile' ? 'active' : ''; ?>">Profile</a>
    </nav>
    
    <div class="top-header-actions">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'Student'); ?></span>
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
        <a href="index.php?page=student_dashboard" class="<?php echo $activeSection === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="index.php?page=student_applications" class="<?php echo $activeSection === 'applications' ? 'active' : ''; ?>">Applications</a>
        <a href="index.php?page=student_room" class="<?php echo $activeSection === 'room' ? 'active' : ''; ?>">My Room</a>
        <a href="index.php?page=student_complaints" class="<?php echo $activeSection === 'complaints' ? 'active' : ''; ?>">Complaints</a>
        <a href="index.php?page=student_notices" class="<?php echo $activeSection === 'notices' ? 'active' : ''; ?>">Notices</a>
        <a href="index.php?page=student_fees" class="<?php echo $activeSection === 'fees' ? 'active' : ''; ?>">Fees</a>
        <a href="index.php?page=student_profile" class="<?php echo $activeSection === 'profile' ? 'active' : ''; ?>">Profile</a>
    </nav>
</div>

<script>
function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
}
</script>
