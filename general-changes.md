# Projects Collection Updates

This pass makes several content changes to the `projects` collection:
consolidating four robot entries into one, renaming three projects,
removing two, and enriching the content of several others using the
existing dossier and external sources.

Do not modify any taxonomy structure. Do not modify the projects page
template (`/projects` index) or the project show template. Templates
are not changing — only entry data.

For removed entries, also handle related cross-references (e.g.
`related_projects` fields on other entries that may point at them).

For renamed entries, **preserve the existing slug** for routes — do
not rename the markdown file or the URL. Only update the displayed
title and adjust frontmatter so the slug stays stable.

---

## CHANGE 1 — Consolidate four robot entries into one

Combine these four entries into a single new entry:

- `first-raspberry-pi-robot.md` (2015)
- `moc-mobile-operations-center.md` (2015) — keep, this is NOT a robot
- `peon-scorpion-robot.md` (2015)
- `power-meowerzer.md` (2015)
- `zeus-the-robot-truck.md` (2015)

**Wait — re-read carefully:** the consolidation covers the four ROBOTS:
First Raspberry Pi Robot, Peon (the scorpion), Power Meowerzer, and
Zeus. The MOC (Mobile Operations Center) is NOT a robot — it's a
portable field computer — and stays as its own entry.

So the four to consolidate are: `first-raspberry-pi-robot`,
`peon-scorpion-robot`, `power-meowerzer`, and `zeus-the-robot-truck`.

### Create the consolidated entry

Create a new entry at `content/collections/projects/raspberry-pi-robots.md`
with this data:

- **Slug:** `raspberry-pi-robots`
- **Title:** "Raspberry Pi Robots"
- **Tagline:** "A small fleet of tinkering projects: a scorpion named Peon (a.k.a. ScorPeon), a cat enrichment bot, a robot truck, and the koala-headed Raspberry Pi robot that started it all."
- **Date:** 2015-09-01 (use September 2015, the active period for all four)
- **Status:** completed
- **Summary:** "A cluster of 2015 robotics projects exploring motor control, IoT sensors, and physical computing. None individually impressive — together, a sketchbook in hardware."
- **Project types (taxonomy):** iot-robotics, fabrication
- **Context (taxonomy):** personal
- **Hero image:** Use `first-raspberry-pi-robot.jpg` (the koala-headed robot) as the hero image — it photographs the most charmingly and best represents the "starter project" framing.
- **Gallery:** combine the existing gallery images from all four entries into one gallery: `peon-under-construction.jpg`, `peon-dark.jpg`, `power-meowerzer-wide.jpg`, `power-meowerzer-tall.jpg`, `zeus-front.jpg`, `zeus-close.jpg`. (The first-pi-robot image is the hero so doesn't need to repeat in the gallery.)
- **Featured:** false (these were not previously featured)

### Body content

Write the body as four short sections, one per robot, in chronological order if known (otherwise this order is fine):

```markdown
## First Raspberry Pi Robot

A Sparkfun Redbot kit with a RaspiRobot controller board and a koala-head portable speaker. Built at my mother's request. The first one.

The demo video's soundtrack is an original electronic track I composed in high school with FastTracker II. A friend said the music alone could have been a career.

[Blog post](https://zaskoda.com/2015/06/16/my-first-raspberry-pi-robot/) · [Video](https://www.youtube.com/watch?v=IJkcvx0B7tc)

## Peon (a.k.a. ScorPeon)

An upcycled scorpion-form robot on Raspberry Pi. Motor control, IoT sensors, and physical computing in a six-legged shell.

[Hackaday](https://hackaday.io/project/9356-peon-scorpion-robot-on-rpi) · [Video](https://www.youtube.com/watch?v=4Set6z566-s)

## Power Meowerzer

An enrichment bot for cats. Documented on Flickr.

[Flickr](https://www.flickr.com/photos/zaskoda/22439518477)

## Zeus the Robot Truck

A robot truck. The name says most of it.

[Video](https://www.youtube.com/watch?v=odrHETIbTYc)
```

### Then delete

After the consolidated entry is created and rendering correctly, delete these four files:

- `content/collections/projects/first-raspberry-pi-robot.md`
- `content/collections/projects/peon-scorpion-robot.md`
- `content/collections/projects/power-meowerzer.md`
- `content/collections/projects/zeus-the-robot-truck.md`

### Cross-references

Search the rest of the projects collection and the work collection for any `related_projects` field referencing any of these four slugs. If found, update each reference to point at `raspberry-pi-robots` instead. If a parent entry referenced multiple of the four, deduplicate so it only references `raspberry-pi-robots` once.

---

## CHANGE 2 — Rename "Chromodepth Haunted House" to "Circus of Fear"

The project's actual name was Circus of Fear. Update the existing entry at `content/collections/projects/chromodepth-haunted-house.md`:

- **Title:** "Circus of Fear"
- **Tagline:** "A haunted house where flat paint becomes 3D — chromodepth glasses, blacklight, and perceptual trickery."
- **DO NOT rename the file or change the slug.** The URL stays as `/projects/chromodepth-haunted-house` to preserve any existing links.

Update the body content to incorporate the actual project name and a bit more context from the dossier:

```markdown
Circus of Fear was a collaborative haunted house installation built by the Phoenix Asylum makerspace community. The central effect used chromodepth glasses — a perceptual technology that uses the brain's reading of color wavelengths to suggest depth — combined with blacklight-reactive paint, so flat painted surfaces appeared to shift and warp in three dimensions. Disorienting and immersive by design.

Circus of Fear was awarded a grant to perform at Apogaea and ran for several years after. I helped build the sets and performed as a character in the haunt itself.

The project sits at the intersection of several threads I keep returning to: perceptual experience design, physical installation, collaborative making, and live performance.
```

Period: 2011–2013 (already correct in the entry; keep).

---

## CHANGE 3 — Rename "World's First Steampunk Snowboard" to "Steampunk Snowboard"

Update `content/collections/projects/worlds-first-steampunk-snowboard.md`:

- **Title:** "Steampunk Snowboard"
- **Tagline:** "Built in one evening with Nino. Demoed at Arapahoe Basin the next day. Make Magazine called it the world's first."
- **DO NOT rename the file or change the slug.** URL stays as `/projects/worlds-first-steampunk-snowboard`.

Update the body content to include the Make Magazine pickup and the IncredibleDiary feature:

```markdown
A custom steampunk-aesthetic snowboard, built in a single evening with my friend Nino. Ridden at Arapahoe Basin the following day. As far as we know, the first of its kind.

The board was picked up by Make Magazine and a few other places at the time — Make ran a write-up in their Fun & Games section. IncredibleDiary called it "the world's first."
```

Add two new links to the project's `links` replicator:

- Label: "Make Magazine" | URL: https://makezine.com/article/home/fun-games/steampunk-snowboard/ | icon: external
- Label: "IncredibleDiary" | URL: https://incrediblediary.com/steampunk-snowboard-is-worlds-first-2.html | icon: external

Keep the existing Blog Post and Flickr links.

If the project has an `awards` replicator, add an entry there too (since Make Magazine pickup is real external recognition):

- Title: "Featured in Make Magazine"
- Year: 2009 (use 2009 as a reasonable approximation if the exact pickup date is unknown)
- Source: Make Magazine
- Source URL: https://makezine.com/article/home/fun-games/steampunk-snowboard/
- Recognition type: feature (or whatever default the awards field uses)

---

## CHANGE 4 — Expand DallasSnowboarders.com to reflect the full community

The existing entry frames DallasSnowboarders.com primarily as a website. It was more than that — it was a real organized community with in-person meetups, planned trips, and shared adventures. Update the entry at `content/collections/projects/dallassnowboarders.md`:

- **Title:** "Dallas Snowboarders"  (drop the ".com" — the site was a piece of it, not the whole thing)
- **Tagline:** "A real community of snowboarders who lived nowhere near snow — organized meetups, planned trips, and a digital home to share the adventures."

Update the body content:

```markdown
Dallas Snowboarders was a community I organized for snowboarding enthusiasts in the Dallas area. We met up in person regularly, planned group trips to Colorado and New Mexico, and shared the adventures online through a website and forum.

The digital side ran on PHPBB plus WordPress with custom photo album, calendar, and video mods. The community side ran on getting people together in person, even when there was no snow nearby. The site hit 15,000 page loads and 29 registered users in the first two weeks; the in-person meetups were the actual point.

An answer to the question: how do you build community around an activity that doesn't happen where the people live?
```

Keep the existing links (Launch Post, the four Winter Park / Wolf Creek / Crested Butte trip videos). Those videos document the in-person side of what the community actually was, so they're the right links to keep.

DO NOT rename the file. URL stays as `/projects/dallassnowboarders`.

---

## CHANGE 5 — Enrich ICTD Mesh Network as a measurement study

The existing entry frames this as a deployment project. It was actually a measurement study — there was a paper. Update `content/collections/projects/ictd-mesh-network.md`:

- **Title:** "ICTD Mesh Network Study"
- **Tagline:** "A measurement study of an open-mesh network's ability to self-heal as nodes dropped and recovered."

Update the body content:

```markdown
Graduate research at CU Boulder's ATLAS Institute. We deployed open-mesh networking hardware in an under-resourced community where traditional last-mile Internet infrastructure didn't reach, then ran a measurement study on the network's self-healing behavior — what happened when nodes dropped out and came back, how the mesh re-routed, and how connectivity held up under various failure modes.

We wrote it up as a paper. The work sits at the intersection of practical deployment (real users, real terrain) and academic measurement (instrumentation, methodology, quantitative results).
```

Period stays 2013–2014. DO NOT rename the file. URL stays as `/projects/ictd-mesh-network`.

If there's a link to the paper, add it as a project link. If not, leave a TODO comment in the file noting that a paper link could be added once located.

---

## CHANGE 6 — Enrich Tracker Music: Zaskoda with Shobek context

The existing entry mentions "Zaskoda of Shobek" only as an instrument name buried in technical details. Shobek was actually a music group — Scott was part of it, and there were multiple songs released. Update `content/collections/projects/tracker-music-zaskoda.md`:

- **Title:** "Tracker Music: Shobek" (correct the framing — Shobek was the group)
- **Tagline:** "Mid-90s electronic compositions on FastTracker II, released under the artist collective Shobek. One track survives in the demoscene's Modland archive."

Update the body content:

```markdown
In the mid-90s I was part of a music group called Shobek. We released a variety of electronic tracks composed on FastTracker II, the dominant module tracker of that era. Most of those songs are archived in places I can't easily reach anymore.

What survives publicly: "Festable in the pit." in the Modland demoscene archive — 8 channels, 125 BPM, with instrument 14 named "Zaskoda of Shobek" embedded in the file. A track from this era also soundtracked my first Raspberry Pi robot video in 2015 — a friend said the music alone could have been a career.

(I'd like to add direct audio downloads here eventually, once I can dig up the rest of the archive.)
```

DO NOT rename the file. URL stays as `/projects/tracker-music-zaskoda`.

Existing links (Modland Archive, Play Online) stay. Existing "Archived on Modland (1995)" award stays.

---

## CHANGE 7 — Remove "Take-Two CMS"

Delete the entry at `content/collections/projects/take-two-cms.md`.

The work it represented is already covered in the Gathering of Developers (GodGames) work entry, which mentions "the CMS that ran Take-Two's primary websites for more than a decade" as an achievement. Removing the standalone project entry doesn't lose the credit — it just stops surfacing as a thin project page with no media.

Search the projects collection and work collection for any `related_projects` references to `take-two-cms` and remove them.

---

## CHANGE 8 — Remove "Custom Blogging Platform"

Delete the entry at `content/collections/projects/custom-blogging-platform.md`.

The "blogging before the word existed" framing is already woven through the homepage bio, the Thinker & Writer pillar, and the Talibah project entry. The standalone Custom Blogging Platform entry doesn't add anything that isn't already covered.

Search the projects collection and work collection for any `related_projects` references to `custom-blogging-platform` and remove them.

---

## Verification

After deploy:

- [ ] `/projects` shows a new "Raspberry Pi Robots" card replacing the four previous individual robot cards
- [ ] The MOC: Mobile Operations Center entry is still present and unchanged (it's a portable field computer, not a robot)
- [ ] Chromodepth Haunted House now reads as "Circus of Fear" everywhere, but URLs to `/projects/chromodepth-haunted-house` still resolve
- [ ] World's First Steampunk Snowboard now reads as "Steampunk Snowboard" with the Make Magazine and IncredibleDiary links present; URL still resolves at `/projects/worlds-first-steampunk-snowboard`
- [ ] Dallas Snowboarders entry reframes the project as a community (not just a website) with the in-person organizing made primary
- [ ] ICTD Mesh Network now appears as "ICTD Mesh Network Study" with measurement study framing
- [ ] Tracker Music entry renamed to "Tracker Music: Shobek" with the group context surfaced
- [ ] Take-Two CMS and Custom Blogging Platform are gone from `/projects`
- [ ] No `related_projects` references point at deleted slugs (`take-two-cms`, `custom-blogging-platform`, or any of the four old robot slugs)
- [ ] The projects index taxonomy counts are accurate after consolidation/removal (the IoT & Robotics count drops by 3, Software/Web App count drops by 2, etc.)
- [ ] All renamed entries keep their original URLs/slugs intact

## Note for Scott (not for Composer)

The Make Magazine award addition (Step 3) is contingent on the project's awards replicator existing on the steampunk snowboard entry. If it doesn't, that award addition is skipped. Worth checking whether to surface the Make recognition on `/recognition` separately as a follow-up.