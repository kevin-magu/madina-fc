document.addEventListener('DOMContentLoaded', function () {
    const mobileMenu = document.getElementById('mobile-menu');
    const navbar = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.navbar a');

    if (!mobileMenu || !navbar) return;

    // Toggle mobile menu
    mobileMenu.addEventListener('click', function (e) {
        e.stopPropagation();
        this.classList.toggle('active');
        navbar.classList.toggle('active');
        document.body.style.overflow = navbar.classList.contains('active') ? 'hidden' : '';
    });

    // Utility: set and listen to active link
    function setActiveLink(targetLink) {
        navLinks.forEach(link => link.classList.remove('active'));
        targetLink.classList.add('active');

        // Close mobile menu if open
        mobileMenu.classList.remove('active');
        navbar.classList.remove('active');
        document.body.style.overflow = '';

        // 🔊 Listen/Respond to active link change
        console.log('Active link set to:', targetLink.href);
        // You can trigger a custom event here if needed:
        // document.dispatchEvent(new CustomEvent('navlink:active', { detail: targetLink.href }));
    }

    // Highlight active link on page load
    const currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
    navLinks.forEach(link => {
        const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/+$/, '') || '/';
        if (linkPath === currentPath || (currentPath === '/' && linkPath === '/madina-fc')) {
            setActiveLink(link);
        }

        // Also listen to click events
        link.addEventListener('click', function (e) {
            setActiveLink(this);
        });
    });

    // Auto-close menu on outside click
    document.addEventListener('click', function (e) {
        if (
            navbar.classList.contains('active') &&
            !navbar.contains(e.target) &&
            !mobileMenu.contains(e.target)
        ) {
            mobileMenu.classList.remove('active');
            navbar.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Auto-reset menu on resize
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768 && navbar.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            navbar.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
