document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('site-header');
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');

    // Header: transparan di atas hero, solid setelah di-scroll
    const onScroll = () => header?.classList.toggle('is-scrolled', window.scrollY > 40);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    // Menu mobile
    const closeMenu = () => {
        menu?.classList.add('hidden');
        toggle?.setAttribute('aria-expanded', 'false');
        header?.classList.remove('menu-open');
    };

    if (toggle && menu && header) {
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('hidden') === false;
            toggle.setAttribute('aria-expanded', String(open));
            header.classList.toggle('menu-open', open);
        });
        menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', closeMenu));
    }

    // Animasi saat scroll
    const items = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
        items.forEach((el) => observer.observe(el));
    } else {
        items.forEach((el) => el.classList.add('is-visible'));
    }

    // Galeri hero: ganti foto tiap 6 detik
    const slides = document.querySelectorAll('[data-hero-slide]');
    if (slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        let current = 0;
        setInterval(() => {
            slides[current].classList.replace('opacity-100', 'opacity-0');
            current = (current + 1) % slides.length;
            slides[current].classList.replace('opacity-0', 'opacity-100');
        }, 6000);
    }
});
