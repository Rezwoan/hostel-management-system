/* Admin Header JavaScript - Mobile menu functionality */

function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.getElementById('mobileMenuOverlay').classList.toggle('open');
}

function toggleMobileSubmenu(btn) {
    const submenu = btn.nextElementSibling;
    const isOpen = submenu.classList.contains('open');
    
    // Toggle current submenu
    submenu.classList.toggle('open');
    btn.classList.toggle('expanded');
}
