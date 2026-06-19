# Homepage Refinements — Consolidated Updates

This pass integrates several text and pattern refinements to the homepage
that came out of recent review. It supersedes the bio rewrite and pillar
content in the previous HOMEPAGE-UPDATES pass where they overlap. The
Recognition section, Selected Work metric promotion, and footer changes
from that previous pass STAND — if they have already been implemented,
do not re-do them. Only apply what's specified below.

No image changes in this pass. No styling changes beyond minor tweaks
called out explicitly. No changes to /work, /projects, /recognition,
/blog, /about, or /contact pages — homepage only.

---

## STEP 1 — Rewrite the Cyberpunk Cowboy bio

In the identity block partial on the homepage, replace the current bio
text with this version:

> I'm Scott Dudley — Koda for short. I've been publishing things online
> since the early 90s, back when "online" mostly meant email and BBSes.
> Since then: indie and AAA games, blogs before they were called blogs,
> online communities of every shape and size, robots and tinkering
> projects, three companies (a pre-dot-com-bubble web dev firm, a social
> media marketing studio, and a Web3 game studio), a 12-year overland
> van build, and the occasional turn as a fire spinner. Grew up in
> rural Texas. Decade in Seattle. Currently splitting time between
> Seattle and Mexico.

This replaces whatever bio is currently in place. The "Cyberpunk Cowboy"
heading above it is unchanged. The personal photo above the heading is
unchanged.

If a previous version of the bio is wrapped in a `<p>` or similar block,
preserve that wrapper but replace its text contents. Do not change the
font, sizing, or color of the bio text.

## STEP 2 — Tighten the three pillar bodies

In the three pillars partial (Engineer & Maker, Community Builder,
Thinker & Writer), replace the body text of each pillar with the
versions below. The headings, icons, and link styles are unchanged.

### Engineer & Maker

Replace the current body text with:

> Thirty years of shipping things on the Internet. Indie and AAA games,
> distributed systems, web platforms, blockchain experiments, and
> physical builds. I like the parts where code meets the world.

Bottom of the pillar continues to show: `→ Work  → Projects`

### Community Builder

Replace the current body text with:

> Online communities of every shape and size, for thirty years. From
> early BBSes to a 700,000-member platform at Gaia.com to a Burning Man
> regional org I helped lead through its biggest year. Communities are
> built; they don't happen.

Bottom of the pillar continues to show: `→ Work`

### Thinker & Writer

Replace the current body text with:

> I write about building things, leading people, and the future of
> decentralized systems. Blogging since 1996, paid author at
> CryptoSlate, presented at SXSW Interactive.

Bottom of the pillar continues to show: `→ Blog  → About  → Recognition`

(The Recognition link was added in the previous homepage pass. If it's
not present yet, add it — see the previous pass for context.)

## STEP 3 — Unify the Recognition section card pattern

The Recognition section on the homepage currently has three different
card types (Game Credit / Grant / Talk). Unify them visually so they
follow one consistent card pattern.

Each card now uses the SAME structure:

1. A small icon at the top (different per card, see below)
2. Card title (the recognition itself)
3. Body text (2-3 lines)
4. ONE primary action link at the bottom

### Card 1 — Dead Man's Hand
- Icon: a small "controller" or "gamepad" SVG (game-related visual)
- Title: "Dead Man's Hand"
- Body: "A AAA western FPS published by Atari on the Unreal Engine.
  Programmer credit for enemy AI, UI, mini-games, animation scripting,
  and environmental effects."
- Action link: "View all credits →" pointing to `/recognition#credits`

### Card 2 — Polygon Studios Grant
- Icon: a small "award" or "ribbon" SVG (achievement-related visual)
- Title: "Polygon Studios Grant"
- Body: "$5,000 USD awarded to bring Orbiter 8 to the Polygon network.
  March 2022."
- Action link: "View all awards →" pointing to `/recognition#awards`

### Card 3 — SXSW Interactive 2001
- Icon: a small "microphone" or "speaker" SVG (talk-related visual)
- Title: "SXSW Interactive 2001"
- Body: "Presented alongside Microsoft on applying game design
  principles to web development. Twenty-five years ago at SXSW
  Interactive, representing Gathering of Developers."
- Action link: "View all talks →" pointing to `/recognition#talks`

### Pattern guidance

- All three cards use IDENTICAL structure, padding, and visual weight
- All three icons render in the same size and same color (copper)
- All three action links use identical styling — pick one verb pattern
  ("View all...") and use it consistently
- Remove the small uppercase "Game Credit / Grant / Talk" labels that
  the previous pass placed above each card title — the icon alone is
  enough type signaling now. The card title carries the content.
- The "View all recognition →" link at the top of the section
  (right-aligned next to the section heading via the section-header
  partial) is unchanged

Heroicons (which Statamic projects often have available) has suitable
SVGs: `cube-transparent` or `command-line` for Game Credit, `trophy` or
`star` for Grant, `microphone` for Talk. Pick one matching set. If
heroicons aren't already in the project, inline simple SVGs from a free
source like heroicons.com or Lucide rather than adding a library.

## STEP 4 — Add a small "Made in Seattle" tagline below the hero

In the hero section, add a small subdued line of text below the play
button — but still inside the hero container, positioned near the
bottom-center of the hero. The text reads:

> Made in ❤︎ Seattle, Washington

(Use the actual heavy heart character `❤︎` or an inline SVG heart, not
the word "heart.")

### Styling

- Position: absolute, bottom of hero, horizontally centered (or
  right-aligned beneath the existing right-third hero text block if
  that fits the composition better)
- Color: `cowboy-300` (muted)
- Size: small (`text-sm` or smaller)
- Font: `font-ui`
- The heart character is in copper (`text-copper-500`)
- Spacing from the bottom of the hero: enough that it doesn't crowd
  the bottom edge but still reads as part of the hero, not the section
  below

This is a small grace note. It should not compete with "Hello Internet"
or the play button — it's a footer-to-the-hero element.

## STEP 5 — Soften the hero play button copy

The play button currently links to a Flickr photo page rather than a
video, with the tooltip "I made this boat on a trip to Alaska — watch
it sail." The word "watch" implies motion the link doesn't deliver.

Change the tooltip and any visible button text to:

> I made this boat on a trip to Alaska. See more from the trip.

Or, equivalently:

> Made this boat on a trip to Alaska. See more →

The link destination is unchanged (it still points to the existing
Flickr URL). The play-button visual treatment is unchanged. Only the
copy on the tooltip/hover/aria-label is updated.

## STEP 6 — Confirm and harden the featured-projects mobile guard

The "Things I've Built" partial should have a comment at the top
documenting the divisibility-by-2-and-3 rule (so 6, 12, etc. card
counts don't produce orphan rows at any breakpoint).

If the comment is already present from a previous pass, leave it. If
not, add:

```blade
{{-- Featured projects: keep the count divisible by 2 AND 3 (6, 12, etc.) to avoid orphan cards at the 2-column and 3-column breakpoints. --}}
```

No actual change to the project count or grid markup.

---

## Verification

After deploy:

- [ ] The Cyberpunk Cowboy bio matches the new text exactly, including
      em-dash, "Koda for short," BBS reference, the three-companies
      parenthetical, and the closing geography line
- [ ] All three pillar body texts are updated to match the new versions
- [ ] The Thinker & Writer pillar still shows three links: Blog, About,
      Recognition
- [ ] The Recognition section has three uniformly-styled cards with
      icons at the top and "View all..." links at the bottom
- [ ] The small uppercase "Game Credit / Grant / Talk" labels above
      card titles are gone
- [ ] A "Made in ❤ Seattle, Washington" tagline appears at the bottom
      of the hero, subtly styled
- [ ] The play button's tooltip/hover text references seeing more from
      the trip rather than watching the boat sail
- [ ] The featured-projects partial has a divisibility comment
- [ ] No other content changed on the homepage
- [ ] /work, /projects, /recognition, /blog, /about, and /contact pages
      are unchanged