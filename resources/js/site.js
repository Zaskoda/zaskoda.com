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
    initLightbox();
});

/*
 * Home page nav: mostly transparent over the hero, solid once the user scrolls.
 * The nav renders with the subtle white tint server-side; this swaps it
 * for the solid set past a small scroll threshold (and back).
 */
function initTransparentNav() {
    const nav = document.querySelector('[data-nav-transparent]');
    if (!nav) return;

    const clear = ['bg-white/20', 'border-transparent'];
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
 * Hero parallax: pan the photo at 1/3 scroll speed so it appears to scroll
 * slower than the page. Uses object-position on an <img> (same math as the old
 * background-position approach) to preserve the center 35% crop at rest. Updates
 * are clamped to the hero height and skipped once the hero leaves the viewport.
 */
function initHeroParallax() {
    const hero = document.querySelector('[data-hero]');
    const img = document.querySelector('[data-hero-img]');
    if (!hero || !img) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const basePosition = 35;
    let isVisible = true;
    let ticking = false;

    const observer = new IntersectionObserver(
        ([entry]) => { isVisible = entry.isIntersecting; },
        { threshold: 0 },
    );
    observer.observe(hero);

    function update() {
        ticking = false;
        if (!isVisible) return;

        const offset = Math.min(window.scrollY, hero.offsetHeight) / 3;
        img.style.objectPosition = `center calc(${basePosition}% + ${offset}px)`;
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', update, { passive: true });

    update();
}

/*
 * Lightbox for content images — blog posts (.post-body) and project pages
 * ([data-lightbox-region]). Blog images arrive pre-wrapped in <a href="full">
 * links from the WordPress import; project images are bare <img> tags, so we
 * wrap each in a link to its own full-size source. Either way the anchor
 * becomes a GLightbox trigger. Only local /assets/ images qualify, so dead or
 * external embeds are left alone. GLightbox is lazy-loaded only when triggers
 * exist, so other pages don't pay for it.
 */
function initLightbox() {
    const regions = document.querySelectorAll('.post-body, [data-lightbox-region]');
    if (!regions.length) return;

    const isAssetImage = (url) => !!url && /^\/assets\/.*\.(png|jpe?g|gif|webp)(\?|#|$)/i.test(url);

    const items = [];
    regions.forEach((region) => {
        region.querySelectorAll('img').forEach((img) => {
            const link = img.closest('a');
            let trigger;

            if (link) {
                // Already wrapped (blog) — lightbox only links to local asset images.
                if (!isAssetImage(link.getAttribute('href'))) return;
                trigger = link;
            } else {
                // Bare image (project) — wrap it in a link to its own full-size source.
                if (!isAssetImage(img.getAttribute('src'))) return;
                trigger = document.createElement('a');
                trigger.href = img.getAttribute('src');
                trigger.className = 'lightbox-wrap';
                img.replaceWith(trigger);
                trigger.appendChild(img);
            }

            trigger.classList.add('lightbox-item');
            const caption = trigger.getAttribute('title') || img.getAttribute('alt');
            if (caption) trigger.setAttribute('data-glightbox', `title: ${caption}`);
            items.push(trigger);
        });
    });

    if (!items.length) return;

    Promise.all([
        import('glightbox'),
        import('glightbox/dist/css/glightbox.min.css'),
    ]).then(([{ default: GLightbox }]) => {
        GLightbox({ selector: '.lightbox-item', loop: false });
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
 * Updates run only while the footer is on screen; reduced-motion keeps center 25%.
 */
function initFooterReveal() {
    const footer = document.querySelector('[data-footer]');
    if (!footer) return;

    const isTouch = window.matchMedia('(hover: none) and (pointer: coarse)').matches;
    if (isTouch) {
        footer.classList.remove('bg-fixed');
        footer.style.backgroundAttachment = 'scroll';
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    let isVisible = false;
    let ticking = false;

    const observer = new IntersectionObserver(
        ([entry]) => {
            isVisible = entry.isIntersecting;
            if (isVisible) update();
        },
        { threshold: 0 },
    );
    observer.observe(footer);

    function update() {
        ticking = false;
        if (!isVisible) return;

        const rect = footer.getBoundingClientRect();
        const viewH = window.innerHeight;

        if (rect.bottom > 0 && rect.top < viewH) {
            // 0 = footer entering viewport, 1 = footer fills viewport (fully revealed)
            const reveal = Math.min(1, Math.max(0, (viewH - rect.top) / viewH));
            const pos = 15 + (1 - reveal) * 20;
            footer.style.backgroundPosition = `center ${pos}%`;
        }
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', update, { passive: true });

    update();
}
