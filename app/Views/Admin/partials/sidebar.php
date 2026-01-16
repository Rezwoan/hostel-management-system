<?php
// Admin Contextual Sidebar
// Shows sub-navigation based on the active section
$currentPage = $page ?? '';

// Determine which section we're in
$userPages = ['admin_users', 'admin_students', 'admin_managers', 'admin_admins'];
$hostelPages = ['admin_hostels', 'admin_hostel_managers', 'admin_floors', 'admin_room_types', 'admin_rooms', 'admin_seats'];
$allocationPages = ['admin_applications', 'admin_allocations'];
$financePages = ['admin_fee_periods', 'admin_invoices', 'admin_payments'];
$supportPages = ['admin_complaint_categories', 'admin_complaints', 'admin_notices'];
$systemPages = ['admin_audit_logs'];

$showSidebar = false;
$sidebarTitle = '';
$sidebarLinks = [];

if (in_array($currentPage, $userPages)) {
    $showSidebar = true;
    $sidebarTitle = 'User Management';
    $sidebarLinks = [
        ['page' => 'admin_users', 'label' => 'All Users', 'icon' => '👥'],
        ['page' => 'admin_students', 'label' => 'Students', 'icon' => '🎓'],
        ['page' => 'admin_managers', 'label' => 'Managers', 'icon' => '👔'],
        ['page' => 'admin_admins', 'label' => 'Admins', 'icon' => '🔑'],
        ['page' => 'admin_users&action=add', 'label' => '+ Add User', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $hostelPages)) {
    $showSidebar = true;
    $sidebarTitle = 'Hostel Management';
    $sidebarLinks = [
        ['page' => 'admin_hostels', 'label' => 'Hostels', 'icon' => '🏢'],
        ['page' => 'admin_hostel_managers', 'label' => 'Managers', 'icon' => '👔'],
        ['page' => 'admin_floors', 'label' => 'Floors', 'icon' => '🏗️'],
        ['page' => 'admin_room_types', 'label' => 'Room Types', 'icon' => '🏷️'],
        ['page' => 'admin_rooms', 'label' => 'Rooms', 'icon' => '🚪'],
        ['page' => 'admin_seats', 'label' => 'Seats', 'icon' => '🛏️'],
    ];
} elseif (in_array($currentPage, $allocationPages)) {
    $showSidebar = true;
    $sidebarTitle = 'Allocation';
    $sidebarLinks = [
        ['page' => 'admin_applications', 'label' => 'Applications', 'icon' => '📝'],
        ['page' => 'admin_allocations', 'label' => 'Allocations', 'icon' => '✅'],
        ['page' => 'admin_allocations&action=add', 'label' => '+ New Allocation', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $financePages)) {
    $showSidebar = true;
    $sidebarTitle = 'Finance';
    $sidebarLinks = [
        ['page' => 'admin_fee_periods', 'label' => 'Fee Periods', 'icon' => '📅'],
        ['page' => 'admin_invoices', 'label' => 'Invoices', 'icon' => '📄'],
        ['page' => 'admin_payments', 'label' => 'Payments', 'icon' => '💰'],
        ['page' => 'admin_payments&action=add', 'label' => '+ Record Payment', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $supportPages)) {
    $showSidebar = true;
    $sidebarTitle = 'Support';
    $sidebarLinks = [
        ['page' => 'admin_complaints', 'label' => 'Complaints', 'icon' => '📢'],
        ['page' => 'admin_complaint_categories', 'label' => 'Categories', 'icon' => '🏷️'],
        ['page' => 'admin_notices', 'label' => 'Notices', 'icon' => '📌'],
        ['page' => 'admin_notices&action=add', 'label' => '+ New Notice', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $systemPages)) {
    $showSidebar = true;
    $sidebarTitle = 'System';
    $sidebarLinks = [
        ['page' => 'admin_audit_logs', 'label' => 'Audit Logs', 'icon' => '📋'],
    ];
}

if ($showSidebar): ?>
<aside class="context-sidebar">
    <div class="context-sidebar-header">
        <h3><?php echo $sidebarTitle; ?></h3>
    </div>
    <nav class="context-sidebar-nav">
        <?php foreach ($sidebarLinks as $link): 
            $isActive = strpos($link['page'], '&') !== false 
                ? false 
                : ($currentPage === $link['page']);
        ?>
            <a href="index.php?page=<?php echo $link['page']; ?>" 
               class="context-nav-link <?php echo $isActive ? 'active' : ''; ?> <?php echo strpos($link['page'], 'action=add') !== false ? 'add-link' : ''; ?>">
                <?php if ($link['icon']): ?><span class="nav-icon"><?php echo $link['icon']; ?></span><?php endif; ?>
                <?php echo $link['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
<?php endif; ?>
