import './bootstrap';

// Mobile menu toggle (works on viewport < md)
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('menu-toggle');
    var menu = document.getElementById('mobile-menu');
    var header = document.getElementById('site-header');
    if (!toggle || !menu) return;

    function setOpen(open) {
        menu.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.querySelector('.menu-icon-open').classList.toggle('hidden', open);
        toggle.querySelector('.menu-icon-close').classList.toggle('hidden', !open);
    }

    toggle.addEventListener('click', function () {
        setOpen(menu.classList.contains('hidden'));
    });

    document.addEventListener('click', function (e) {
        if (!header.contains(e.target) && !menu.classList.contains('hidden')) {
            setOpen(false);
        }
    });

    menu.querySelectorAll('.mobile-nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            setOpen(false);
        });
    });
});
