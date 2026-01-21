<?php
// Manager Contextual Sidebar
$currentPage = $page ?? '';

$applicationPages = ['manager_applications', 'manager_allocations'];
$financePages = ['manager_fee_periods', 'manager_fees', 'manager_payments'];
$supportPages = ['manager_complaints', 'manager_notices'];

$showSidebar = false;
$sidebarTitle = '';
$sidebarLinks = [];

if (in_array($currentPage, $applicationPages)) {
    $showSidebar = true;
    $sidebarTitle = 'Room Management';
    $sidebarLinks = [
        ['page' => 'manager_applications', 'label' => 'Applications', 'icon' => '📝'],
        ['page' => 'manager_allocations', 'label' => 'Allocations', 'icon' => '✅'],
        ['page' => 'manager_allocations&action=add', 'label' => '+ New Allocation', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $financePages)) {
    $showSidebar = true;
    $sidebarTitle = 'Finance';
    $sidebarLinks = [
        ['page' => 'manager_fee_periods', 'label' => 'Fee Periods', 'icon' => '📅'],
        ['page' => 'manager_fees', 'label' => 'Invoices', 'icon' => '📄'],
        ['page' => 'manager_fees&action=add', 'label' => '+ Generate Invoice', 'icon' => ''],
        ['page' => 'manager_payments', 'label' => 'Payments', 'icon' => '💰'],
        ['page' => 'manager_payments&action=add', 'label' => '+ Record Payment', 'icon' => ''],
    ];
} elseif (in_array($currentPage, $supportPages)) {
    $showSidebar = true;
    $sidebarTitle = 'Support';
    $sidebarLinks = [
        ['page' => 'manager_complaints', 'label' => 'Complaints', 'icon' => '📢'],
        ['page' => 'manager_notices', 'label' => 'Notices', 'icon' => '📌'],
        ['page' => 'manager_notices&action=add', 'label' => '+ New Notice', 'icon' => ''],
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
