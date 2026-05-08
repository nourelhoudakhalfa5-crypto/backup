document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('menu-toggle');
    const navbarInner = document.getElementById('navbar-inner');
    const dropdown = document.getElementById('dropdown');
    const line1 = document.getElementById('line1');
    const line2 = document.getElementById('line2');

    let isMenuOpen = false;

    if (menuBtn && dropdown) {
        menuBtn.addEventListener('click', () => {
            isMenuOpen = !isMenuOpen;

            if (isMenuOpen) {
                if(navbarInner) {
                    navbarInner.classList.remove('bg-white');
                    navbarInner.classList.add('bg-black');
                }
                if(line1) {
                    line1.classList.remove('bg-black', 'rotate-0', 'translate-y-0');
                    line1.classList.add('bg-white', 'rotate-45', 'translate-y-2');
                }
                if(line2) {
                    line2.classList.remove('bg-black', 'rotate-0', 'translate-y-0');
                    line2.classList.add('bg-white', '-rotate-45', '-translate-y-2');
                }
                dropdown.classList.remove('hidden', 'dropdown-exit');
                dropdown.classList.add('dropdown-enter');
            } else {
                closeMenu();
            }
        });
    }

    function closeMenu() {
        if(navbarInner) {
            navbarInner.classList.remove('bg-black');
            navbarInner.classList.add('bg-white');
        }
        if(line1) {
            line1.classList.remove('bg-white', 'rotate-45', 'translate-y-2');
            line1.classList.add('bg-black', 'rotate-0', 'translate-y-0');
        }
        if(line2) {
            line2.classList.remove('bg-white', '-rotate-45', '-translate-y-2');
            line2.classList.add('bg-black', 'rotate-0', 'translate-y-0');
        }
        if(dropdown) {
            dropdown.classList.remove('dropdown-enter');
            dropdown.classList.add('dropdown-exit');
            setTimeout(() => {
                if (!isMenuOpen) {
                    dropdown.classList.add('hidden');
                }
            }, 300);
        }
    }

    document.addEventListener('click', (e) => {
        const navbar = document.getElementById('navbar');
        if (navbar && !navbar.contains(e.target) && isMenuOpen) {
            isMenuOpen = false;
            closeMenu();
        }
    });

    // Dark mode toggle
    const toggle = document.querySelector("[data-rdocc-theme-toggle]");
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('rdocc-theme');
    if (savedTheme === 'light') {
        document.body.classList.add('rdocc-theme-light');
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            const isLight = document.body.classList.toggle('rdocc-theme-light');
            localStorage.setItem('rdocc-theme', isLight ? 'light' : 'dark');
        });
    }
});
