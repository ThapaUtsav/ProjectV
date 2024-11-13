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
            document.getElementById('theme-dropdown').classList.remove('show');
        }

        // Function to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Function to toggle theme dropdown
        function toggleThemeDropdown() {
            const themeDropdown = document.getElementById('theme-dropdown');
            themeDropdown.classList.toggle('show');
        }

        // Function to toggle submenu visibility and hide others
        function toggleSubmenu(submenuId) {
            const allSubmenus = document.querySelectorAll('.submenu');
            
            allSubmenus.forEach((submenu) => {
                if (submenu.id === submenuId) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                } else {
                    submenu.style.display = 'none'; // Close all other submenus
                }
            });
        }

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.querySelector('.hamburger-menu');
            const themeLink = document.querySelector('.theme-link');
            const themeDropdown = document.getElementById('theme-dropdown');

            if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                sidebar.classList.remove('show');
            }

            if (!themeLink.contains(event.target) && !themeDropdown.contains(event.target)) {
                themeDropdown.classList.remove('show');
            }
        });
   