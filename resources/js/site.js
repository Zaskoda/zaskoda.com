/* zasKoda.com — scroll effects and nav behavior (vanilla JS) */

function onReady(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
}

onReady(() => {
    initMobileNav();
    initHeroParallax();
    initFooterReveal();
    initProjectFilters();
});

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
 * Effect 1 — Hero parallax.
 * The layer (photo + reflection) translates DOWN at 0.6x scroll, so the image
 * appears to scroll at ~40% of page speed. The ~1.15 overscan lives on the photo
 * elements themselves; the section's static bottom fade guarantees no seam.
 */
function initHeroParallax() {
    const layer = document.querySelector('[data-hero-parallax]');
    const play = document.querySelector('[data-hero-play]');
    if (!layer) return;

    let ticking = false;

    function update() {
        const y = window.scrollY;
        const offset = y * 0.6;

        layer.style.transform = `translate3d(0, ${offset}px, 0)`;

        if (play) {
            const playScale = Math.max(0, 1 - y * 0.003);
            const playOpacity = Math.max(0, 1 - y * 0.005);
            play.style.transform = `scale(${playScale})`;
            play.style.opacity = playOpacity;
            play.style.pointerEvents = playOpacity <= 0 ? 'none' : '';
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

/*
 * Project grid filters.
 * OR logic within a row, AND across rows.
 */
function initProjectFilters() {
    const groups = document.querySelectorAll('[data-filter-group]');
    const cards = document.querySelectorAll('[data-project-card]');
    if (!groups.length || !cards.length) return;

    const selected = { types: new Set(), contexts: new Set() };

    function applyFilters() {
        cards.forEach((card) => {
            const cardTypes = (card.dataset.types || '').split(' ').filter(Boolean);
            const cardContexts = (card.dataset.contexts || '').split(' ').filter(Boolean);

            const typeMatch = selected.types.size === 0
                || cardTypes.some((t) => selected.types.has(t));
            const contextMatch = selected.contexts.size === 0
                || cardContexts.some((c) => selected.contexts.has(c));

            card.classList.toggle('hidden', !(typeMatch && contextMatch));
        });
    }

    function styleButtons(group, groupName) {
        const allButton = group.querySelector('[data-filter-all]');
        const active = selected[groupName];
        allButton.setAttribute('aria-pressed', String(active.size === 0));
        allButton.classList.toggle('bg-cowboy-700', active.size === 0);
        group.querySelectorAll('[data-filter]').forEach((btn) => {
            const isActive = active.has(btn.dataset.filter);
            btn.setAttribute('aria-pressed', String(isActive));
            btn.classList.toggle('bg-cowboy-700', isActive);
            btn.classList.toggle('border-copper-600', isActive && groupName === 'types');
            btn.classList.toggle('text-copper-400', isActive && groupName === 'types');
            btn.classList.toggle('border-circuit-500', isActive && groupName === 'contexts');
            btn.classList.toggle('text-circuit-400', isActive && groupName === 'contexts');
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
