/* zasKoda.com — scroll effects and nav behavior (vanilla JS) */

function onReady(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
}

onReady(() => {
    initMobileNav();
    initTransparentNav();
    initHeroParallax();
    initFooterReveal();
    initEntryFilters('[data-project-card]');
    initEntryFilters('[data-work-card]');
    initPostLightbox();
});

/*
 * Home page nav: transparent over the hero, solid once the user scrolls.
 * The nav renders with the transparent classes server-side; this swaps them
 * for the solid set past a small scroll threshold (and back).
 */
function initTransparentNav() {
    const nav = document.querySelector('[data-nav-transparent]');
    if (!nav) return;

    const clear = ['bg-transparent', 'border-transparent'];
    const solid = ['bg-ink/90', 'backdrop-blur-sm', 'border-card-border'];
    let isSolid = false;

    function update() {
        const shouldBeSolid = window.scrollY > 24;
        if (shouldBeSolid === isSolid) return;
        isSolid = shouldBeSolid;
        nav.classList.remove(...(shouldBeSolid ? clear : solid));
        nav.classList.add(...(shouldBeSolid ? solid : clear));
        // .nav--solid drives the link/icon colors (dark over the photo, normal when solid)
        nav.classList.toggle('nav--solid', shouldBeSolid);
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
}

/*
 * Hero parallax (old-site mechanism): pan the photo's background-position down
 * at 1/3 scroll speed, so it appears to scroll slower than the page. The 35%
 * base keeps the boat framed at rest on wide viewports.
 */
function initHeroParallax() {
    const bg = document.querySelector('[data-hero-bg]');
    if (!bg) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    let ticking = false;

    function update() {
        bg.style.backgroundPosition = `center calc(35% + ${window.scrollY / 3}px)`;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });

    update();
}

/*
 * Lightbox for blog post images.
 * Targets anchors in .post-body that wrap an image and link to a local asset
 * image file. GLightbox (and its CSS) is lazy-loaded only when such links
 * exist, so non-post pages don't pay for it.
 */
function initPostLightbox() {
    const links = [...document.querySelectorAll('.post-body a[href^="/assets/"]')]
        .filter((a) => /\.(png|jpe?g|gif|webp)$/i.test(a.getAttribute('href')) && a.querySelector('img'));
    if (!links.length) return;

    links.forEach((a) => {
        a.classList.add('post-lightbox');
        const caption = a.getAttribute('title') || a.querySelector('img').getAttribute('alt');
        if (caption) a.setAttribute('data-glightbox', `title: ${caption}`);
    });

    Promise.all([
        import('glightbox'),
        import('glightbox/dist/css/glightbox.min.css'),
    ]).then(([{ default: GLightbox }]) => {
        GLightbox({ selector: '.post-lightbox', loop: false });
    });
}

/* Mobile hamburger toggle */
function initMobileNav() {
    const button = document.querySelector('[data-nav-toggle]');
    const menu = document.querySelector('[data-nav-menu]');
    if (!button || !menu) return;

    button.addEventListener('click', () => {
        const isOpen = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', String(!isOpen));
    });
}

/*
 * Entry list filters (projects and work pages).
 * OR logic within a row, AND across rows.
 */
function initEntryFilters(cardSelector) {
    const cards = document.querySelectorAll(cardSelector);
    if (!cards.length) return;

    const groups = document.querySelectorAll('[data-filter-group]');
    if (!groups.length) return;

    const selected = {};
    groups.forEach((group) => {
        selected[group.dataset.filterGroup] = new Set();
    });

    const emptyState = document.querySelector('[data-filter-empty]');

    const accentForGroup = {
        types: 'copper',
        contexts: 'circuit',
        type: 'copper',
        focus: 'circuit',
        industry: 'copper',
    };

    function cardValues(card, groupName) {
        const raw = card.dataset[groupName] || '';
        if (!raw) return [];
        return raw.split(' ').filter(Boolean);
    }

    function applyFilters() {
        let visibleCount = 0;

        cards.forEach((card) => {
            let show = true;

            groups.forEach((group) => {
                const groupName = group.dataset.filterGroup;
                const active = selected[groupName];
                if (active.size === 0) return;

                const values = cardValues(card, groupName);
                const match = values.some((v) => active.has(v));
                if (!match) show = false;
            });

            card.classList.toggle('hidden', !show);
            if (show) visibleCount++;
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function styleButtons(group, groupName) {
        const accent = accentForGroup[groupName] || 'copper';
        const allButton = group.querySelector('[data-filter-all]');
        const active = selected[groupName];

        allButton.setAttribute('aria-pressed', String(active.size === 0));
        allButton.classList.toggle('bg-cowboy-700', active.size === 0);

        group.querySelectorAll('[data-filter]').forEach((btn) => {
            const isActive = active.has(btn.dataset.filter);
            btn.setAttribute('aria-pressed', String(isActive));
            btn.classList.toggle('bg-cowboy-700', isActive);
            btn.classList.toggle('border-copper-600', isActive && accent === 'copper');
            btn.classList.toggle('text-copper-400', isActive && accent === 'copper');
            btn.classList.toggle('border-circuit-500', isActive && accent === 'circuit');
            btn.classList.toggle('text-circuit-400', isActive && accent === 'circuit');
        });
    }

    groups.forEach((group) => {
        const groupName = group.dataset.filterGroup;

        group.querySelector('[data-filter-all]').addEventListener('click', () => {
            selected[groupName].clear();
            styleButtons(group, groupName);
            applyFilters();
        });

        group.querySelectorAll('[data-filter]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const value = btn.dataset.filter;
                if (selected[groupName].has(value)) {
                    selected[groupName].delete(value);
                } else {
                    selected[groupName].add(value);
                }
                styleButtons(group, groupName);
                applyFilters();
            });
        });

        styleButtons(group, groupName);
    });

    applyFilters();
}

/*
 * Effect 2 — Seattle footer reveal.
 * bg-fixed pins the skyline to the viewport; JS shifts background-position as the
 * footer scrolls into view (blind-reveal). Touch devices fall back to scroll attachment.
 */
function initFooterReveal() {
    const footer = document.querySelector('[data-footer]');
    if (!footer) return;

    const isTouch = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
    if (isTouch) {
        footer.classList.remove('bg-fixed');
        footer.style.backgroundAttachment = 'scroll';
    }

    let ticking = false;

    function update() {
        const rect = footer.getBoundingClientRect();
        const viewH = window.innerHeight;

        if (rect.bottom > 0 && rect.top < viewH) {
            // 0 = footer entering viewport, 1 = footer fills viewport (fully revealed)
            const reveal = Math.min(1, Math.max(0, (viewH - rect.top) / viewH));
            const pos = 15 + (1 - reveal) * 20;
            footer.style.backgroundPosition = `center ${pos}%`;
        }

        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });

    update();
}
