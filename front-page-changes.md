# Homepage Updates

Several content and information architecture updates to the homepage.
This pass touches the homepage template, the Thinker & Writer pillar
content, the Cyberpunk Cowboy bio text, the Selected Work cards, and
the footer.

No image changes in this pass. No styling changes beyond what is needed
to make the new section render consistently with existing sections.

---

## STEP 1 — Add a "Recognition" section to the homepage

Insert a new section between "Things I've Built" and "From the Blog" on
the homepage. Use the EXACT same section header pattern as the other
homepage sections (heading + right-aligned "view all" link via the
shared `section-header.blade.php` partial).

- Section heading: "Recognition"
- Action link: "View all recognition →" pointing to `/recognition`
- Background surface: alternate with the surrounding sections per the
  existing alternating-surface rule. The current homepage alternates
  ink/slate/ink/slate/ink. Inserting a new section means the surface
  pattern needs to extend: Selected Work, Things I've Built,
  Recognition, From the Blog. Use whichever surface (`ink` or `slate`)
  continues the alternation so adjacent sections never share the same
  background.

### Section contents

Render three "highlight" cards in a `grid grid-cols-1 md:grid-cols-3
gap-6` layout. Each card mirrors the visual style of the pillar cards
in the Cyberpunk Cowboy section (same card class, same padding, same
border treatment) — these are not full award cards from the
`/recognition` page, they are compact teasers.

**Card 1 — Game Credit**
- Small label above the card title (uppercase, `font-ui`, copper):
  "Game Credit"
- Card title: "Dead Man's Hand"
- Body text: "A AAA western-themed FPS published by Atari on the Unreal
  Engine. Programmer credit for enemy AI, UI, mini-games, animation
  scripting, and environmental effects."
- Bottom-of-card link: "→ Credits" pointing to `/recognition#credits`

**Card 2 — Grant**
- Small label: "Grant"
- Card title: "Polygon Studios Grant"
- Body text: "$5,000 USD to bring Orbiter 8 to the Polygon network.
  Awarded March 2022."
- Bottom-of-card link: "→ Awards & Grants" pointing to
  `/recognition#awards`

**Card 3 — Talk**
- Small label: "Talk"
- Card title: "SXSW Interactive 2001"
- Body text: "Presented alongside Microsoft on applying game design
  principles to web development. Part of the SXSW Interactive track,
  representing Gathering of Developers."
- Bottom-of-card link: "→ Talks" pointing to `/recognition#talks`

The cards are static content rendered directly in the homepage section
partial. Do not query the `talks`, `credits`, or `projects` collections
to populate these — they are curated highlights, hardcoded into the
section template (in a new partial:
`resources/views/partials/home/recognition.blade.php`).

Include this new partial from the homepage template
(`resources/views/home.blade.php`) between the existing "Things I've
Built" and "From the Blog" sections.

## STEP 2 — Update the Thinker & Writer pillar

In the Cyberpunk Cowboy section's three pillars
(`resources/views/partials/home/pillars.blade.php` or wherever pillars
live), the "Thinker & Writer" pillar currently has links to Blog and
About. Add a third link.

Update the bottom of the pillar:

```
[→ Blog] [→ About] [→ Recognition]
```

The "→ Recognition" link points to `/recognition`. Style identically
to the existing two links.

No change to the pillar's body text.

## STEP 3 — Add "Recognition" to the footer nav

In the footer partial (`resources/views/partials/footer.blade.php` or
similar), the footer nav currently lists: Work, Projects, Blog, About,
Contact. Add "Recognition" between "Projects" and "Blog" so the order
matches the main top nav:

```
Work | Projects | Recognition | Blog | About | Contact
```

Use the same link styling as the other footer nav items.

## STEP 4 — Promote the standout metric on each Selected Work card

In the Selected Work section
(`resources/views/partials/home/featured-work.blade.php` or wherever
work cards are rendered on the homepage), each work card currently has
the structure:

```
Company Name
Role
Date range
Summary paragraph
Standout achievement (one line, styled with metric-highlight class)
See full history →
```

The standout achievement is the most impressive content on the card and
should be more prominent visually. Update the card structure so the
metric appears BEFORE the summary paragraph, like a pull-quote, and is
visually distinct:

```
Company Name
Role
Date range
[METRIC, prominent — copper, bold, larger than body text]
Summary paragraph
See full history →
```

Specifically:

- Move the achievement line to render BEFORE the summary
- Increase the achievement text size by one notch (e.g. `text-base`
  becomes `text-lg`)
- Keep the existing `metric-highlight` copper color treatment
- Use `font-semibold` or `font-bold` weight on the achievement
- Add a small amount of vertical spacing (`mb-3`) below the achievement
  before the summary begins

The data shown is unchanged — only the position and visual weight
change. The summary text itself is unchanged.

Apply to all three Selected Work cards on the homepage. Do NOT touch
the work cards rendered on `/work` — those are governed by a different
template and should stay as they are.

## STEP 5 — Rewrite the Cyberpunk Cowboy bio

In the Cyberpunk Cowboy section (the identity block partial), the bio
currently reads:

> I'm Scott Dudley, Koda to most of the Internet. I've been building
> things online since the 90s: games, communities, robots, blockchain
> experiments, and this website. I grew up in rural Texas, spent
> decades writing software in Seattle, and these days I'm doing it
> from Mexico.

Replace with:

> I'm Scott Dudley, Koda to most of the Internet. I started publishing
> things online in 1996, before the term "blogging" existed. Since
> then: shipped AAA games, ran a 700,000-member community, founded a
> Web3 studio, built robots, and made a 12-year overland van. I grew
> up in rural Texas, spent decades writing software in Seattle, and
> these days I'm doing it from Mexico.

This swaps the vague "since the 90s" for the specific 1996, and
replaces the generic "games, communities, robots, blockchain
experiments, and this website" with concrete, verifiable
accomplishments that match other content on the site.

Do NOT modify the "Cyberpunk Cowboy" heading or any other text in this
section.

## STEP 6 — Mobile responsive guard

The "Things I've Built" section currently renders 6 project cards in a
3-column grid at `lg:` breakpoint. If at some point the featured
project count changes, the grid must continue to look balanced on all
breakpoints.

Confirm the current grid behavior in the featured-projects partial:

- At `lg:` and up: 3 columns
- At `sm:` to `md:`: 2 columns
- Below `sm:`: 1 column

Six entries works cleanly at all three breakpoints (3×2, 2×3, 6×1).
This section's content is NOT changing in this pass, but document a
constraint in a comment at the top of the partial:

```blade
{{-- Featured projects: keep the count divisible by 2 AND 3 (6, 12, etc.) to avoid orphan cards at 2-col and 3-col breakpoints. --}}
```

This is the only "change" to the featured projects in this pass: a
comment for future-Scott or future-Composer to read before reshuffling
featured_order on any project entries.

---

## Verification

After deploy:

- [ ] A "Recognition" section appears on the homepage between "Things
      I've Built" and "From the Blog"
- [ ] The Recognition section shows exactly 3 cards: Dead Man's Hand
      (Game Credit), Polygon Studios Grant (Grant), SXSW Interactive
      2001 (Talk)
- [ ] Each Recognition card's bottom link points to the correct
      /recognition anchor
- [ ] Surface alternation continues correctly through the new section —
      no two adjacent sections share the same background
- [ ] The Thinker & Writer pillar now shows three links: Blog, About,
      Recognition
- [ ] The footer nav now lists six items in order: Work, Projects,
      Recognition, Blog, About, Contact
- [ ] Each Selected Work card on the homepage now shows the standout
      metric above the summary, in larger and bolder text
- [ ] The Cyberpunk Cowboy bio text matches the new version exactly
- [ ] A comment is present at the top of the featured-projects partial
      noting the count divisibility rule
- [ ] No image changes; no styling changes beyond what's needed for the
      new section and metric promotion
- [ ] /work, /projects, /recognition, /blog, /about, and /contact pages
      are unchanged