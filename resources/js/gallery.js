export function initGallery() {
    const root = document.getElementById('gallery');
    const lightbox = document.getElementById('lightbox');
    if (!root || !lightbox) return;

    const items = [...root.querySelectorAll('[data-gallery-item]')];
    const filters = [...document.querySelectorAll('[data-filter]')];
    const img = document.getElementById('lightbox-img');
    const counter = document.getElementById('lightbox-count');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');

    let visible = items;
    let index = 0;
    let lastFocus = null;
    let touchX = null;

    const isOpen = () => !lightbox.classList.contains('hidden');

    // Filter kategori (tanpa muat ulang halaman)
    function applyFilter(slug, updateUrl = true) {
        items.forEach((el) => {
            el.classList.toggle('hidden', slug !== 'all' && el.dataset.category !== slug);
        });
        visible = items.filter((el) => !el.classList.contains('hidden'));

        filters.forEach((btn) => {
            const active = btn.dataset.filter === slug;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', String(active));
        });

        if (updateUrl) {
            const url = new URL(window.location.href);
            if (slug === 'all') {
                url.searchParams.delete('kategori');
            } else {
                url.searchParams.set('kategori', slug);
            }
            history.replaceState(null, '', url);
        }
    }

    filters.forEach((btn) => btn.addEventListener('click', () => applyFilter(btn.dataset.filter)));

    const initial = new URLSearchParams(window.location.search).get('kategori');
    if (initial && filters.some((btn) => btn.dataset.filter === initial)) {
        applyFilter(initial, false);
    }

    // Lightbox
    function show(i) {
        index = (i + visible.length) % visible.length;
        const el = visible[index];

        img.src = el.dataset.full;
        img.alt = el.dataset.alt || '';
        counter.textContent = `${index + 1} / ${visible.length}`;

        const many = visible.length > 1;
        prevBtn.classList.toggle('hidden', !many);
        nextBtn.classList.toggle('hidden', !many);

        if (many) {
            new Image().src = visible[(index + 1) % visible.length].dataset.full;
        }
    }

    function open(i) {
        lastFocus = document.activeElement;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        show(i);
        closeBtn.focus();
    }

    function close() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        img.src = '';
        lastFocus?.focus();
    }

    items.forEach((el) => el.addEventListener('click', () => open(visible.indexOf(el))));
    closeBtn.addEventListener('click', close);
    prevBtn.addEventListener('click', () => show(index - 1));
    nextBtn.addEventListener('click', () => show(index + 1));
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) close();
    });

    document.addEventListener('keydown', (e) => {
        if (!isOpen()) return;

        if (e.key === 'Escape') {
            close();
        } else if (e.key === 'ArrowLeft') {
            show(index - 1);
        } else if (e.key === 'ArrowRight') {
            show(index + 1);
        } else if (e.key === 'Tab') {
            const focusable = [closeBtn, prevBtn, nextBtn].filter((b) => !b.classList.contains('hidden'));
            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    // Geser di layar sentuh
    lightbox.addEventListener('touchstart', (e) => {
        touchX = e.changedTouches[0].clientX;
    }, { passive: true });

    lightbox.addEventListener('touchend', (e) => {
        if (touchX === null) return;
        const dx = e.changedTouches[0].clientX - touchX;
        touchX = null;
        if (Math.abs(dx) > 50) show(dx > 0 ? index - 1 : index + 1);
    }, { passive: true });
}
