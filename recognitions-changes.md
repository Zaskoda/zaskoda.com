# Recognition Page — Add Calendar Feature and Archives Subsection

This pass adds one new award entry (Burning Man calendar feature on
Temple of Moon), verifies an existing entry's amount, and introduces a
new "Archives & Preservation" subsection within the Awards & Grants
section to surface a different type of external recognition: independent
archives preserving Scott's work.

Do not modify any content not listed below. Templates and data only. No
styling changes beyond what's needed to render the new subsection.

Background structure: awards are stored as a replicator on project
entries (see `AWARDS-DATA-REPORT.md`). The `/recognition` page template
at `resources/views/pages/recognition.blade.php` queries projects and
work, flattens their awards arrays, and renders them sorted by year.

---

## STEP 1 — Add a new "Recognition Type" field to the award replicator

We need a way to distinguish standard awards/grants from archival/preservation
recognitions, so they render in separate subsections.

In `resources/blueprints/collections/projects/project.yaml`, extend the
existing `award` set inside the `awards` replicator. Add ONE new optional
field, placed BEFORE `sub_items` (so the order becomes: title, year,
amount, source, source_url, recognition_type, sub_items):

```yaml
- handle: recognition_type
  field:
    type: select
    display: 'Recognition Type'
    instructions: 'What kind of recognition is this? Defaults to standard award if unset.'
    options:
      award: 'Award / Grant'
      feature: 'Feature / Curation'
      archive: 'Archive / Preservation'
    default: award
```

Do not change, rename, or reorder any existing fields. Do not modify the
top-level awards replicator structure beyond adding this one field.

## STEP 2 — Add the Burning Man calendar feature

Open `content/collections/projects/temple-of-moon.md`.

If the project has no `awards` field, add it. If it has existing awards,
add this new entry to the end of the list.

The new award block:

- `type: award`
- `enabled: true`
- `title:` "Featured in an official Burning Man calendar"
- `year:` "2012"
- `amount:` (omit)
- `source:` "Burning Man Project"
- `source_url:` "https://burningman.org"
- `recognition_type:` "feature"
- `sub_items:` (omit)

Note on year: the calendar that featured Temple of Moon was likely the
2012 calendar (released late 2011 after the 2011 burn). Use 2012. If
that proves incorrect upon Scott's review, the year is a one-line
edit.

## STEP 3 — Verify and correct the existing Apogaea 2011 Creative Grant

Open `content/collections/projects/temple-of-moon.md`.

If an award entry currently exists with the title "Apogaea 2011 Creative
Grant" (or similar) with amount "$1,000 USD":

- Keep the entry, but REMOVE the `amount` field entirely. The exact
  grant amount has not been verified. Leave a YAML comment on that
  block: `# Amount unverified — confirm with Scott before re-adding`
- Ensure `recognition_type:` "award" is set (or omit, since award is
  the default)

If no such entry exists yet, create it:

- `type: award`
- `enabled: true`
- `title:` "Apogaea 2011 Creative Grant"
- `year:` "2011"
- `amount:` (omit, with the comment noted above)
- `source:` "Apogaea, Inc."
- `source_url:` (omit)
- `recognition_type:` "award"

## STEP 4 — Add four new archive/preservation entries

These entries go onto the relevant project pages. Each uses
`recognition_type: archive`. Each project gets one new award block
appended to its existing `awards` array (creating the array if needed).

### 4a — On Orbiter 8 (`content/collections/projects/orbiter-8.md`)

Add a new award block, in addition to the existing consolidated
"Orbiter 8 — Hackathon Wins & Grants" entry:

- `type: award`
- `enabled: true`
- `title:` "GitHub Arctic Code Vault Contributor"
- `year:` "2020"
- `amount:` (omit)
- `source:` "GitHub"
- `source_url:` "https://archiveprogram.github.com/arctic-vault/"
- `recognition_type:` "archive"

### 4b — On Splat (`content/collections/projects/splat.md`)

Find the entry titled "Splat" (the 1997 game, not any other Splat
reference). Add:

- `type: award`
- `enabled: true`
- `title:` "Archived in the Internet Archive MS-DOS Games library"
- `year:` "2019"
- `amount:` (omit, or use the text "Still playable in-browser")
- `source:` "Internet Archive"
- `source_url:` "https://archive.org/details/softwarelibrary_msdos_games"
- `recognition_type:` "archive"

Note on year: 2019 is the Internet Archive's MS-DOS library launch
year for that collection. If the specific date Splat was added isn't
known, this is a reasonable approximate.

### 4c — On Bob and His Amazing Journey Home (`content/collections/projects/bob-and-his-amazing-journey-home.md`)

Add:

- `type: award`
- `enabled: true`
- `title:` "Archived on DOSGames.com — 10,000+ plays"
- `year:` "1995"
- `amount:` (omit)
- `source:` "DOSGames.com"
- `source_url:` "https://www.dosgames.com/"
- `recognition_type:` "archive"

And a second entry for the Information Society feature, since it's a
distinct cultural recognition:

- `type: award`
- `enabled: true`
- `title:` "Featured on an Information Society album"
- `year:` "1995"
- `amount:` (omit)
- `source:` "Information Society"
- `source_url:` (omit)
- `recognition_type:` "feature"

(Year for both is approximate — Bob's release year. If precise dates
become known, easy to adjust later.)

### 4d — On the Tracker Music project (`content/collections/projects/tracker-music-zaskoda.md` or similar — check `content/collections/projects/` for the entry titled "Tracker Music: Zaskoda")

Add:

- `type: award`
- `enabled: true`
- `title:` 'Archived on Modland'
- `year:` "1995"
- `amount:` (omit)
- `source:` "Modland"
- `source_url:` "https://modland.com/"
- `recognition_type:` "archive"

If the tracker music project entry doesn't exist yet in `projects/`,
skip step 4d and leave a TODO comment in this commit message noting
the missing project. Do NOT create the project entry as part of this
pass.

## STEP 5 — Update the recognition page template

In `resources/views/pages/recognition.blade.php`, update both the
flattening logic and the rendering to support the new
`recognition_type` field and the new subsection.

### Flattening logic

In the PHP block that builds the `$awards` collection, include
`recognition_type` in each pushed item (default to 'award' if absent):

```
'recognition_type' => $award['recognition_type'] ?? 'award',
```

After the existing sort by year, split the flattened collection into
two groups:

- `$standardAwards` — items where `recognition_type` is 'award' or
  'feature' (or any value other than 'archive')
- `$archiveAwards` — items where `recognition_type` is 'archive'

Both groups remain sorted by year descending within themselves.

### Rendering — Awards & Grants section

Render `$standardAwards` exactly as the current section renders them —
no markup changes. The Burning Man calendar feature, the Apogaea
Creative Grant, and the Information Society feature will naturally
appear here alongside existing awards, sorted by year.

### Rendering — new Archives & Preservation subsection

After the standard awards list ends, but BEFORE the page closes the
Awards & Grants section's anchor scope, add a new subsection. Use the
same heading styling pattern as the role-category subsections in the
Game Credits section (smaller heading than the main "Awards & Grants"
heading, but clearly a heading):

- Subsection heading: "Archives & Preservation"
- Short intro line below the heading: "Work that's been preserved by
  independent archives — long after the platforms that hosted it
  moved on."
- Render `$archiveAwards` using the SAME award card markup as the
  standard awards section. Reuse the existing markup, do not invent a
  new card style.

The subsection heading uses `font-display` styling, sized appropriately
between the main section heading and a typical card title. Match the
existing Game Credits subsection heading style (Programming /
Engineering, Technology, etc.) for visual consistency.

If `$archiveAwards` is empty, do NOT render the heading or intro line.

### Anchor nav at top of page

Do not change the top anchor nav. The "Awards & Grants" link should
continue to point at `#awards` and scroll to the parent section. The
Archives subsection lives within that section and doesn't need its
own anchor in the top nav.

## STEP 6 — Update the project show page

In `resources/views/projects/show.blade.php`, the compact awards list
on individual project pages should continue working unchanged. Confirm
that adding `recognition_type` to the data doesn't break that template
(it shouldn't — the template only reads `title`, `year`, and `amount`).

If sub-items rendering was added in the previous pass and that template
groups by anything related to type, leave it alone — archive entries on
individual project pages should render the same way as awards there.

---

## Verification

After deploy:

- [ ] `/recognition` Awards & Grants section now shows additional entries
      in chronological order, with the Burning Man calendar feature
      appearing in the 2012 timeframe and the Information Society
      feature appearing in 1995
- [ ] A new "Archives & Preservation" subsection appears below the
      standard awards
- [ ] The Archives subsection contains 4 entries (or 3 if tracker music
      project doesn't exist yet): Arctic Code Vault (Orbiter 8),
      Internet Archive (Splat), DOSGames.com (Bob), and Modland (tracker
      music, if applicable)
- [ ] Each archive entry uses the same card styling as the standard
      award cards
- [ ] Archive entries sort by year, with the oldest (1995 Bob and
      tracker music) at the bottom and Arctic Code Vault (2020) at the
      top
- [ ] No `amount` field is set on the Apogaea 2011 Creative Grant
      pending verification
- [ ] Project pages still render their awards lists without errors
- [ ] No other content or styling changed

## Note for Scott (not for Composer)

- The Apogaea 2011 Creative Grant amount needs verification. Removing
  the unverified $1,000 figure prevents misstating the public record;
  add it back once confirmed.
- Tracker music project entry may or may not exist in `projects/`. If
  step 4d is skipped, decide whether to create the project entry first
  (with its own page) before adding the Modland archive recognition.
- The Burning Man calendar feature year is best-guessed as 2012. If you
  remember which calendar year specifically (2011 calendar released
  late 2010, 2012 calendar released late 2011, etc.), correct in the
  CP after this pass lands.