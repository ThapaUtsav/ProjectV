// JavaScript for Mode Switching, Sidebar Toggle, and Submenu Toggle

// Function to toggle light and dark mode
function switchMode(mode) {
    if (mode === 'light') {
        document.body.classList.remove('dark-mode');
        document.body.classList.add('light-mode');
    } else {
        document.body.classList.remove('light-mode');
        document.body.classList.add('dark-mode');
    }
    // Close the theme dropdown after a selection
    document.getElementById('theme-dropdown').classList.remove('show');
}

// Function to toggle sidebar visibility
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');  // Toggle 'active' to show or hide sidebar
}

// Function to toggle theme dropdown visibility
function toggleThemeDropdown() {
    const themeDropdown = document.getElementById('theme-dropdown');
    themeDropdown.classList.toggle('show');
}

// Function to toggle submenu visibility and close others
function toggleSubmenu(submenuId) {
    const allSubmenus = document.querySelectorAll('.submenu');
    
    allSubmenus.forEach((submenu) => {
        if (submenu.id === submenuId) {
            submenu.classList.toggle('active');  // Show or hide the clicked submenu
        } else {
            submenu.classList.remove('active');  // Hide other submenus
        }
    });
}

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuButton = document.querySelector('.hamburger-menu');
    const themeLink = document.querySelector('.theme-link');
    const themeDropdown = document.getElementById('theme-dropdown');

    // Close sidebar if clicked outside the sidebar or menu button
    if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
        sidebar.classList.remove('active');
    }

    // Close theme dropdown if clicked outside the dropdown
    if (!themeLink.contains(event.target) && !themeDropdown.contains(event.target)) {
        themeDropdown.classList.remove('show');
    }
});
