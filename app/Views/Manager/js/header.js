/* Manager Header JavaScript - Mobile menu functionality */

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('open');
}

function toggleMobileSubmenu(button) {
    const submenu = button.nextElementSibling;
    submenu.classList.toggle('open');
    const icon = button.querySelector('.toggle-icon');
    icon.textContent = submenu.classList.contains('open') ? '▲' : '▼';
}
