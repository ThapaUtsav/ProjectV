function switchMode(mode) {
    if (mode === 'light') {
        document.body.classList.remove('dark-mode');
        document.body.classList.add('light-mode');
    } else {
        document.body.classList.remove('light-mode');
        document.body.classList.add('dark-mode');
    }
    document.getElementById('theme-dropdown').classList.remove('show');
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

function toggleThemeDropdown() {
    const themeDropdown = document.getElementById('theme-dropdown');
    themeDropdown.classList.toggle('show');
}

function toggleSubmenu(submenuId) {
    const allSubmenus = document.querySelectorAll('.submenu');
    
    allSubmenus.forEach((submenu) => {
        if (submenu.id === submenuId) {
            submenu.classList.toggle('active');
        } else {
            submenu.classList.remove('active');
        }
    });
}

document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuButton = document.querySelector('.hamburger-menu');
    const themeLink = document.querySelector('.theme-link');
    const themeDropdown = document.getElementById('theme-dropdown');

    if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
        sidebar.classList.remove('active');
    }

    if (!themeLink.contains(event.target) && !themeDropdown.contains(event.target)) {
        themeDropdown.classList.remove('show');
    }
});
