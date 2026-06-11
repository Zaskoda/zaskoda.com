#!/usr/bin/env python3
"""Generate project markdown entries from projects.md inventory."""

import os
import textwrap

PROJECTS_DIR = os.path.join(
    os.path.dirname(__file__), "..", "content", "collections", "projects"
)


def link(label, url, icon="external"):
    if not url.startswith("http"):
        url = "https://" + url.lstrip("/")
    return {
        "type": "link",
        "enabled": True,
        "label": label,
        "url": url,
        "icon": icon,
    }


def award(title, year, amount=None):
    a = {"type": "award", "enabled": True, "title": title, "year": str(year)}
    if amount:
        a["amount"] = amount
    return a


def yaml_list(items, indent=0):
    pad = " " * indent
    if not items:
        return ""
    return "\n".join(f"{pad}- {item}" for item in items)


def yaml_links(links):
    if not links:
        return ""
    lines = ["links:"]
    for l in links:
        lines.append("  -")
        lines.append("    type: link")
        lines.append("    enabled: true")
        lines.append(f"    label: '{l['label'].replace(chr(39), chr(39)+chr(39))}'")
        lines.append(f"    url: '{l['url']}'")
        lines.append(f"    icon: {l['icon']}")
    return "\n".join(lines)


def yaml_awards(awards):
    if not awards:
        return ""
    lines = ["awards:"]
    for a in awards:
        lines.append("  -")
        lines.append("    type: award")
        lines.append("    enabled: true")
        lines.append(f"    title: '{a['title'].replace(chr(39), chr(39)+chr(39))}'")
        lines.append(f"    year: '{a['year']}'")
        if a.get("amount"):
            lines.append(f"    amount: '{a['amount']}'")
    return "\n".join(lines)


def write_project(slug, data):
    fm = [
        "---",
        f"id: project-{slug}",
        "blueprint: project",
        f"title: '{data['title'].replace(chr(39), chr(39)+chr(39))}'",
        f"tagline: '{data['tagline'].replace(chr(39), chr(39)+chr(39))}'",
        f"date: '{data['date']}'",
    ]
    if data.get("date_end"):
        fm.append(f"date_end: '{data['date_end']}'")
    fm.append(f"status: {data['status']}")
    fm.append(f"summary: '{data['summary'].replace(chr(39), chr(39)+chr(39))}'")
    if data.get("hero_image"):
        fm.append(f"hero_image: {data['hero_image']}")
    if data.get("gallery"):
        fm.append("gallery:")
        for g in data["gallery"]:
            fm.append(f"  - {g}")
    if data.get("links"):
        fm.append(yaml_links(data["links"]))
    if data.get("awards"):
        fm.append(yaml_awards(data["awards"]))
    if data.get("tech_stack"):
        fm.append("tech_stack:")
        fm.extend(f"  - {t}" for t in data["tech_stack"])
    fm.append("project_type:")
    fm.extend(f"  - {t}" for t in data["project_type"])
    fm.append("context:")
    fm.extend(f"  - {c}" for c in data["context"])
    if data.get("featured"):
        fm.append("featured: true")
        fm.append(f"featured_order: {data['featured_order']}")
    if data.get("related_work"):
        fm.append("related_work:")
        fm.extend(f"  - {w}" for w in data["related_work"])
    fm.append("---")
    body = data.get("body", "").strip()
    content = "\n".join(fm) + ("\n\n" + body if body else "") + "\n"
    path = os.path.join(PROJECTS_DIR, f"{slug}.md")
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Wrote {slug}.md")


PROJECTS = [
    # --- Tier 1 existing (enriched) ---
    {
        "slug": "orbiter-8",
        "title": "Orbiter 8",
        "tagline": "A fully decentralized space trading MMO: all game logic lives in Solidity smart contracts. No central server.",
        "date": "2019-01-01",
        "status": "active",
        "summary": "Browser-based space MMO on the EVM: dynamically generated galaxy, ERC-721 ships and planets, ERC-20 currency with SushiSwap. Unstoppable-code ethos — client runs from hard drive, USB, or IPFS. Deployed across 10+ chains.",
        "hero_image": "projects/orbiter-8.jpg",
        "links": [
            link("GitHub", "https://github.com/Partavate-Studios/orbiter8-prototype", "github"),
            link("Live Demo", "https://partavate-studios.github.io/orbiter8-demo3-client/", "demo"),
            link("Tutorial Video", "https://www.youtube.com/watch?v=cL30sFMYi6A", "youtube"),
            link("Demo 3 Trailer", "https://www.youtube.com/watch?v=Cno77-YOcUM", "youtube"),
            link("BlockchainNW Talk", "https://www.youtube.com/watch?v=W2EVIiV3u3k", "youtube"),
            link("Vancouver Talk", "https://www.youtube.com/watch?v=Zojabsl4i20", "youtube"),
        ],
        "awards": [
            award("Moonriver Grants Hackathon: 1st place, gaming", "2022", "419 MOVR"),
            award("Bobabeam Bridging Hackathon: 1st place", "2022", "$3,500"),
            award("Polygon Studios grant", "2022", "$5,000"),
        ],
        "tech_stack": ["vue", "typescript", "solidity", "ethereum", "hardhat", "ethersjs", "docker"],
        "project_type": ["blockchain", "game", "software", "open-source"],
        "context": ["professional"],
        "featured": True,
        "featured_order": 1,
        "related_work": ["work-partavate-studios"],
        "body": """Orbiter 8 is a decentralized space trading MMO built entirely on the Ethereum Virtual Machine. All game logic runs in Solidity smart contracts: there is no central server. The game world is a dynamically generated directed network of stars that expands as players explore. Ships and planets are ERC-721 NFTs tradeable on OpenSea. The in-game currency, Galactic Credits, is an ERC-20 token swappable on SushiSwap.

The client is browser-based and deliberately lightweight: anyone can build an alternate client against the same ABI. The whole project is built around the "unstoppable code" ethos. The client can run from a local hard drive, a USB stick, or IPFS, and there are no fees beyond Ethereum gas, by design.

Deployed and tested on Ropsten, Rinkeby, Polygon, BNB, Arbitrum, Moonriver, Moonbase, Bobabase, and Harmony testnets. The code is archived in the GitHub Arctic Code Vault. Logo and branding by Walter "2" Costinak.""",
    },
    {
        "slug": "temple-of-moon",
        "title": "Temple of Moon",
        "tagline": "A memorial art installation: a bridge with spiral staircases forming an infinity symbol, built with no prior large-scale art experience.",
        "date": "2011-01-01",
        "status": "completed",
        "summary": "Built for Apogaea 2011 in memory of Moon, a Phoenix Asylum founding member. Designed with sketches and SketchUp; volunteers organized before the grant was submitted. Hollow center posts became 14-foot flaming torches at the burn.",
        "hero_image": "projects/temple-of-moon.jpg",
        "links": [
            link("Flickr Set", "https://www.flickr.com/photos/zaskoda/sets/72157625571250565/", "external"),
        ],
        "project_type": ["art-installation", "fabrication"],
        "context": ["nonprofit", "festival-burn"],
        "featured": True,
        "featured_order": 2,
        "body": """The Temple of Moon was built in 2011 for Apogaea, Colorado's regional Burning Man event, in memory of Moon — a founding member of the Phoenix Asylum makerspace — and her unborn child. Moon was lead artist on CANO's Circus of Fear. The temple was designed as a bridge with spiral staircases on each end, forming an infinity symbol when viewed from above. Each spiral had six steps, symbolic of the tattoos on Moon's face.

I had no prior large-scale art experience. I designed with poster board sketches, clay models, and SketchUp renderings, drew on carpentry skills from helping build my family's home in high school, and organized volunteers through Facebook before the art grant was even submitted.

At the burn, hollow center posts for the spiral stairs — intended for airflow — superheated when I forgot to drill air holes in the base, creating accidental 14-foot flaming torches. The Temple of Moon was later featured in an official Burning Man calendar.""",
    },
    {
        "slug": "polar-bear-van",
        "title": "Polar Bear Van",
        "tagline": "A 12-year overland camper van build: equal parts engineering project, art piece, and way of life.",
        "date": "2009-10-01",
        "date_end": "2021-09-01",
        "status": "archived",
        "summary": "1987 Ford E250 4x4 built over 12 years: lift, custom cabinetry, propane heat, electrical system, roof tent, rock sliders. 355 forum posts, ~70,000 views. Sold September 2021.",
        "hero_image": "projects/polar-bear-van-outside.jpg",
        "gallery": ["projects/polar-bear-van.jpg"],
        "links": [
            link("Build Thread", "https://sportsmobileforum.com/threads/polar-bear-1.736163/", "external"),
            link("Intro Post", "https://zaskoda.com/2009/10/28/introducing-polar-bear/", "external"),
        ],
        "project_type": ["vehicle-build", "fabrication"],
        "context": ["personal"],
        "featured": True,
        "featured_order": 4,
        "body": """Polar Bear was a 1987 Ford E250 4x4 van purchased in South Lake Tahoe in October 2009 and driven 1,500 miles home to Boulder. Over 12 years it became a full overland camper build: 6-8 inch lift, custom cabinetry and kitchen, propane heat, full electrical system with house battery, roof top tent, and rock sliders that once anchored a self-recovery from a ravine.

The build was documented in exhaustive detail on the Sportsmobile Forum: 355 posts and roughly 70,000 views. Polar Bear went to Burning Man multiple years, overlanded across Colorado and Washington, and served as basecamp for backpacking trips.

Sold in September 2021. "When I started working on her, I thought she was a piece of art I would one day finish. But now, I see that she was more of a performance art. She created and influenced a lifestyle, a way of living, that I will remember and cherish forever.""",
    },
    {
        "slug": "apogaea-volunteer-database",
        "title": "Apogaea Volunteer Database",
        "tagline": "Open-source volunteer management for 1,000+ volunteers at Colorado's regional Burning Man event.",
        "date": "2012-01-01",
        "status": "completed",
        "summary": "Python/Django system built from scratch for Apogaea Inc. Handles complex shift and role assignments across 1,000+ volunteers. Integrates ODK Collect for offline field data. Still in use.",
        "hero_image": "projects/voldb.jpg",
        "links": [
            link("GitHub", "https://github.com/Apogaea/voldb", "github"),
        ],
        "tech_stack": ["python", "django"],
        "project_type": ["software", "open-source", "community-platform"],
        "context": ["nonprofit", "professional"],
        "featured": True,
        "featured_order": 3,
        "body": """An open-source volunteer management system built for Apogaea Inc., the nonprofit that produces Colorado's regional Burning Man event. The system manages more than 1,000 volunteers across complex shift and role assignments, and integrates ODK Collect for offline field data gathering.

I designed and built the system from scratch in Python and Django. After my term as board president ended, I oversaw the system as Volunteer Database Lead. The project remains in active use and has earned 8 GitHub stars.""",
    },
    # --- Tier 1 new ---
    {
        "slug": "polydice",
        "title": "PolyDice",
        "tagline": "Award-winning NFT tabletop dice with fully on-chain randomization, built for EthDenver 2022.",
        "date": "2022-01-01",
        "status": "completed",
        "summary": "Tabletop dice-rolling dapp with Rix Studios. Each die is a customizable ERC-721 with on-chain randomization. EthDenver 2022 award winner. Hundreds given away at DygyCon 9.",
        "links": [
            link("GitHub", "https://github.com/Partavate-Studios/nft-tabletop-dice", "github"),
        ],
        "awards": [award("EthDenver 2022 award winner", "2022")],
        "project_type": ["blockchain", "software", "open-source"],
        "context": ["professional"],
        "related_work": ["work-partavate-studios"],
        "body": """PolyDice is a tabletop dice-rolling dapp built in partnership with Rix Studios for EthDenver 2022. Each die is a customizable ERC-721 token with fully on-chain randomization — no off-chain oracle required for the roll.

The project won an award at EthDenver 2022. Hundreds of free PolyDice were given away at DygyCon 9's virtual booth.""",
    },
    {
        "slug": "bob-and-his-amazing-journey-home",
        "title": "Bob and His Amazing Journey Home",
        "tagline": "A DOS puzzle game built in high school, published through Soft Disk, and still playable in a browser 30 years later.",
        "date": "1995-01-01",
        "status": "archived",
        "summary": "Sokoban-style DOS puzzle: push blocks, avoid lasers, collect keys. Self-published under Zaskoda Soft, distributed by Soft Disk. ~10,000 plays on DOSGames.com, still playable via DOSBox.",
        "links": [
            link("Play Online", "https://www.dosgames.com/game/bob-and-his-amazing-journey-home/", "demo"),
        ],
        "project_type": ["game", "software"],
        "context": ["personal"],
        "body": """Bob and His Amazing Journey Home is a Sokoban-style puzzle game: push blocks, avoid lasers, collect keys, reach the exit. Built in high school at 640x480 with 256 colors and PC speaker sound.

Self-published under the Zaskoda Soft label and distributed by Soft Disk — the legendary shareware house where id Software got their start. The game was featured on an Information Society album and has accumulated roughly 10,000 plays on DOSGames.com, rated 3.5/5, still playable in-browser via DOSBox.

Part of the Zaskoda Soft story: the label that started the whole identity.""",
    },
    {
        "slug": "gearbox-community-architecture",
        "title": "Gearbox Community Architecture",
        "tagline": "A forum jail that rehabilitated trolls instead of banning them, at one of the largest game developer communities of its era.",
        "date": "2004-01-01",
        "date_end": "2007-12-31",
        "status": "completed",
        "summary": "Community systems at Gearbox Software: The Ban Bin forum jail, The Illuminate reward council, Internal Combustion flame forum, Gearblogs developer diaries, and ARG puzzle campaigns.",
        "links": [
            link("Ban Bin Writeup", "https://zaskoda.com/2005/09/24/the-ban-bin-how-effective-is-a-forum-jail/", "external"),
            link("Forum Ideas", "https://zaskoda.com/2005/06/08/a-few-good-forum-ideas/", "external"),
        ],
        "project_type": ["community-platform", "software"],
        "context": ["professional"],
        "body": """A suite of community systems built at Gearbox Software during one of the largest game developer communities of its era.

**The Ban Bin** — a forum jail used instead of permanent bans, where violations were stated openly and could be disputed. Only two users were ever jailed twice; none more. Documented in the blog post "The Ban Bin: How Effective Is A Forum Jail?"

**The Illuminate** — a hidden reward forum functioning as an informal community council.

**Internal Combustion** — a members-only flame forum that turned complaints constructive.

**Gearblogs** — an early developer diary platform.

**ARG campaigns** — collaborative puzzle campaigns that unlocked game content.

Built on a custom PHP framework, later migrated to CakePHP. The moderation innovations anticipated rehabilitative systems adopted broadly years later.""",
    },
    # --- Tier 2 ---
    {
        "slug": "first-raspberry-pi-robot",
        "title": "First Raspberry Pi Robot",
        "tagline": "A Sparkfun Redbot kit, a RaspiRobot controller board, and a koala head portable speaker. Made at my mother's request.",
        "date": "2015-01-01",
        "status": "completed",
        "summary": "First Raspberry Pi robotics project: Sparkfun Redbot, RaspiRobot controller, koala-head speaker. Demo soundtrack is an original FastTracker II track from high school.",
        "links": [
            link("Blog Post", "https://zaskoda.com/2015/06/16/my-first-raspberry-pi-robot/", "external"),
            link("Video", "https://www.youtube.com/watch?v=IJkcvx0B7tc", "youtube"),
        ],
        "project_type": ["iot-robotics"],
        "context": ["personal"],
        "body": """Scott's first Raspberry Pi robotics project, built at his mother's request. Sparkfun Redbot kit, RaspiRobot controller board, and a koala head portable speaker.

The demo video soundtrack is an original electronic track composed in high school with FastTracker II — a friend said the music could have been a career on its own.""",
    },
    {
        "slug": "moc-mobile-operations-center",
        "title": "MOC: Mobile Operations Center",
        "tagline": "A portable field computing platform: Raspberry Pi, HDMI screen, WiFi, and power in one case.",
        "date": "2015-09-01",
        "status": "completed",
        "summary": "Self-contained field computing platform on Raspberry Pi for environments without traditional infrastructure. 26 followers on Hackaday.",
        "links": [
            link("Hackaday", "https://hackaday.io/project/9358-moc-mobile-operations-center", "hackaday"),
        ],
        "project_type": ["iot-robotics", "fabrication"],
        "context": ["personal"],
        "body": """A self-contained field computing platform built on Raspberry Pi: HDMI screen, WiFi, and power in one portable case — designed for environments without traditional infrastructure. Documented on Hackaday with 26 followers.""",
    },
    {
        "slug": "peon-scorpion-robot",
        "title": "Peon: Scorpion Robot",
        "tagline": "An upcycled scorpion-form robot on Raspberry Pi.",
        "date": "2015-09-01",
        "status": "completed",
        "summary": "Upcycled robotics project exploring motor control, IoT sensors, and physical computing in a scorpion form factor.",
        "links": [
            link("Hackaday", "https://hackaday.io/project/9356-peon-scorpion-robot-on-rpi", "hackaday"),
        ],
        "project_type": ["iot-robotics", "fabrication"],
        "context": ["personal"],
        "body": """An upcycled scorpion-form robot on Raspberry Pi, exploring motor control, IoT sensors, and physical computing.""",
    },
    {
        "slug": "zeus-the-robot-truck",
        "title": "Zeus the Robot Truck",
        "tagline": "A robot truck. The name says most of it.",
        "date": "2015-09-01",
        "status": "completed",
        "summary": "A robot truck project documented on video.",
        "links": [
            link("Video", "https://www.youtube.com/watch?v=odrHETIbTYc", "youtube"),
        ],
        "project_type": ["iot-robotics"],
        "context": ["personal"],
        "body": """A robot truck project. The name says most of it. Documented on video.""",
    },
    {
        "slug": "laraddress",
        "title": "laraddress: Federated Address Book",
        "tagline": "The first concrete step in leaving Facebook: a federated, user-owned address book.",
        "date": "2019-01-01",
        "status": "archived",
        "summary": "Laravel app for collecting contact info before leaving Facebook — a replacement for the centralized social graph with verified contact cards and selective visibility.",
        "links": [
            link("GitHub", "https://github.com/zaskoda/laraddress", "github"),
            link("Leaving Facebook", "https://zaskoda.com/2019/01/01/leaving-facebook-why/", "external"),
        ],
        "project_type": ["software", "open-source"],
        "context": ["personal"],
        "body": """A Laravel application for collecting contact info from Facebook friends before leaving the platform — and, more deeply, a replacement for the centralized social graph. Friends create verified contact cards with selective visibility by group. Planned federation meant two friends running their own instances would sync via API.

Built because the software Scott wanted didn't exist. Pairs naturally with the "Leaving Facebook: Why" blog post and the decentralist values section on /about.""",
    },
    {
        "slug": "dallasfestevil",
        "title": "DallasFestEvil.com",
        "tagline": "The entire web operation for a Dallas haunted house festival — an early playbook for guerrilla community marketing.",
        "date": "2004-01-01",
        "date_end": "2005-12-31",
        "status": "archived",
        "summary": "Dynamic festival site on a limited budget: daily page refreshes, custom contact system, R.I.P. All Access mailing list club, banner exchange, 14-album photo system. The festival succeeded.",
        "links": [
            link("Case Study", "https://zaskoda.com/2005/05/24/community-building-case-study-tips-from-dallasfestevil-com/", "external"),
        ],
        "project_type": ["community-platform", "software"],
        "context": ["professional"],
        "body": """Built on a very limited budget for a previously unsuccessful Dallas haunted house festival: a dynamic site with pages refreshed daily during the event, a custom contact system with strict response-time discipline, an exclusive mailing list club ("R.I.P. All Access") with scavenger hunts and sponsor prizes, a cross-promotion banner exchange, and a 14-album custom photo system that became the major traffic driver.

The festival succeeded. The case study reads like standard practice — a decade early.""",
    },
    {
        "slug": "dallassnowboarders",
        "title": "DallasSnowboarders.com",
        "tagline": "A community for snowboarders who live nowhere near snow.",
        "date": "2006-01-01",
        "status": "archived",
        "summary": "Virtual community for Dallas-area snowboarders: PHPBB forum plus WordPress with custom photo album, calendar, and video mods. 15,000 page loads and 29 users in two weeks.",
        "links": [
            link("Launch Post", "https://zaskoda.com/2006/11/01/a-new-virtual-community-dallassnowboarders-com/", "external"),
        ],
        "project_type": ["community-platform"],
        "context": ["personal"],
        "body": """Co-launched a virtual community for the small but growing group of Dallas-area snowboarding enthusiasts: PHPBB forum plus WordPress CMS with custom photo album, calendar, and video mods. 15,000 page loads and 29 registered users in the first two weeks, with regular in-person meetups.

An answer to the question: how do you build community around an activity that doesn't happen where the people live?""",
    },
    {
        "slug": "fantasy-origin",
        "title": "Fantasy Origin",
        "tagline": "A community for indie game developers that outlived its founder's ownership by a decade.",
        "date": "1999-01-01",
        "status": "archived",
        "summary": "Virtual community for independent game developers during the GodGames era. Survived more than a decade after ownership transferred. Predates most modern community platforms.",
        "project_type": ["community-platform"],
        "context": ["professional", "personal"],
        "body": """A virtual community for independent game developers, built during the GodGames era. It survived more than a decade — including after ownership transferred to someone else. Predates most modern community platforms.""",
    },
    {
        "slug": "chromodepth-haunted-house",
        "title": "Chromodepth Haunted House",
        "tagline": "A haunted house where flat paint becomes 3D — chromodepth glasses, blacklight, and perceptual trickery.",
        "date": "2011-01-01",
        "date_end": "2013-12-31",
        "status": "completed",
        "summary": "Phoenix Asylum installation using chromodepth glasses and blacklight paints to make flat surfaces shift in depth. Grant-funded for Apogaea; ran for years after.",
        "project_type": ["art-installation", "fabrication"],
        "context": ["nonprofit", "festival-burn"],
        "body": """A collaborative Phoenix Asylum installation using chromodepth glasses and blacklight-reactive paints to make flat surfaces shift in depth — a perceptual illusion that plays with how the brain reads color as distance.

Awarded a grant to perform at Apogaea and ran for years after. Scott helped build the sets and performed in the haunt.""",
    },
    {
        "slug": "ictd-mesh-network",
        "title": "ICTD Mesh Network",
        "tagline": "Built and benchmarked an open-mesh network for last-mile connectivity in an under-resourced community.",
        "date": "2013-01-01",
        "date_end": "2014-12-31",
        "status": "completed",
        "summary": "Graduate research at CU Boulder ATLAS Institute: deploying and benchmarking open-mesh networking hardware for last-mile Internet where infrastructure doesn't reach.",
        "project_type": ["research", "iot-robotics"],
        "context": ["academic"],
        "body": """Graduate research at CU Boulder's ATLAS Institute: deploying and benchmarking open-mesh networking hardware for last-mile Internet connectivity in an under-resourced community where traditional infrastructure doesn't reach.""",
    },
    {
        "slug": "revision-field-data-tools",
        "title": "Re:Vision Field Data Tools",
        "tagline": "Offline field data collection for a Denver nonprofit fighting the urban food desert problem.",
        "date": "2013-01-01",
        "status": "completed",
        "summary": "ICTD lab project for Re:Vision International. Deployed ODK Collect for offline community garden data, bilingual documentation, and trained local women on the platform.",
        "project_type": ["research", "software"],
        "context": ["academic", "nonprofit"],
        "body": """ICTD lab project for Re:Vision International, which works with minority women on urban food access. Deployed ODK Collect for offline community garden data gathering, composed bilingual English/Spanish documentation, and trained local women on the platform.""",
    },
    {
        "slug": "prospera-case-study",
        "title": "Prospera Case Study",
        "tagline": "A business case study for a Guadalajara nonprofit helping women become micro-entrepreneurs.",
        "date": "2013-01-01",
        "status": "completed",
        "summary": "Co-authored a social entrepreneurship case study and teaching note on Prospera's business model, focused on tools for exploring product potential with the US LOHAS market.",
        "project_type": ["research"],
        "context": ["academic", "nonprofit"],
        "body": """Co-authored a social entrepreneurship case study and teaching note on Prospera's business model, focused on tools for exploring product potential with the US LOHAS market.""",
    },
    {
        "slug": "digital-globe-ipad-app",
        "title": "Digital Globe iPad App",
        "tagline": "A networked Unity3D experience that teaches satellite imaging through play.",
        "date": "2013-01-01",
        "status": "completed",
        "summary": "Interactive iPad experience at Mondo Robot teaching satellite imaging: data on the satellite until a ground station passes, two scans combining into 3D. Networked for live presenter control.",
        "links": [
            link("Demo", "https://vimeo.com/84173757", "vimeo"),
        ],
        "project_type": ["game", "software"],
        "context": ["professional"],
        "body": """Built at Mondo Robot: an interactive iPad experience teaching Digital Globe's clients how satellite systems work — how data waits on the satellite until a ground station passes underneath, how two scans from different angles combine into a 3D scene. Networked so a live presenter controlled the pacing.

The design challenge was making complex technical concepts tangible.""",
    },
    {
        "slug": "worlds-first-steampunk-snowboard",
        "title": "World's First Steampunk Snowboard",
        "tagline": "Built in one evening, demoed at Arapahoe Basin the next day.",
        "date": "2009-01-01",
        "status": "completed",
        "summary": "Custom steampunk-aesthetic snowboard built in a single evening with Nino. Ridden at A-Basin the following day.",
        "links": [
            link("Blog Post", "https://zaskoda.com/2009/04/13/the-worlds-first-steampunk-snowboard/", "external"),
            link("Flickr", "https://www.flickr.com/photos/zaskoda/sets/72157616614827092/", "external"),
        ],
        "project_type": ["fabrication"],
        "context": ["personal"],
        "body": """A custom steampunk-aesthetic snowboard built in a single evening with Nino — self-described as the world's first. Ridden at Arapahoe Basin the following day.""",
    },
    {
        "slug": "tracker-music-zaskoda",
        "title": "Tracker Music: Zaskoda",
        "tagline": "FastTracker II compositions from high school, still archived in the demoscene's Modland library.",
        "date": "1995-01-01",
        "status": "archived",
        "summary": "Electronic music composed in high school on FastTracker II. \"Festable in the pit.\" survives in Modland — 8 channels, 125 BPM. Soundtracked the 2015 Raspberry Pi robot video.",
        "links": [
            link("Modland Archive", "https://ftp.modland.com/pub/modules/Fasttracker%202/Zaskoda/", "archive"),
            link("Play Online", "https://modtu.be/?s=Fasttracker+2%2FZaskoda%2Ffestable+in+the+pit.xm", "demo"),
        ],
        "project_type": ["music"],
        "context": ["personal"],
        "body": """Electronic music composed in high school on FastTracker II under the artist name Zaskoda. "Festable in the pit." survives in the Modland archive — 8 channels, 125 BPM, with instrument 14 named "Zaskoda of Shobek."

A track from this era soundtracked the 2015 Raspberry Pi robot video.""",
    },
    # --- Tier 3 ---
    {
        "slug": "splat",
        "title": "Splat",
        "tagline": "This game has no point! Just splatter these little... uh.. things!",
        "date": "1997-01-01",
        "status": "archived",
        "summary": "Pure arcade mouse game, public domain, with a built-in sprite editor so players could redesign the entire game. Archived in the Internet Archive MS-DOS library.",
        "links": [
            link("Play Online", "https://archive.org/details/splat-zaskoda-soft", "archive"),
        ],
        "project_type": ["game", "software"],
        "context": ["personal"],
        "body": """A pure arcade mouse game, public domain, with a built-in sprite editor so players could redesign the entire game — an early modding feature. Archived in the Internet Archive's MS-DOS library and still playable in-browser.""",
    },
    {
        "slug": "legend-of-talibah",
        "title": "The Legend of Talibah",
        "tagline": "The high school game whose weekly dev updates became blogging before the word existed.",
        "date": "1996-01-01",
        "status": "archived",
        "summary": "Second Zaskoda Soft DOS game. Weekly development updates starting August 1996 are Scott's earliest archived web writing — predating the term \"blog.\"",
        "links": [
            link("Archive", "https://nortexinfo.net/Zaskoda/Talibah/0896.htm", "archive"),
        ],
        "project_type": ["game"],
        "context": ["personal"],
        "body": """The second Zaskoda Soft DOS game. Its development updates, posted weekly starting August 1996, are Scott's earliest archived web writing — predating the term "blog.""",
    },
    {
        "slug": "isogame",
        "title": "Isogame",
        "tagline": "A forum where the map evolves based on how people post.",
        "date": "2005-01-01",
        "status": "archived",
        "summary": "Experimental prototype connecting forum software to an isometric tile map — each tile linked to a thread, map appearance changed with post activity.",
        "links": [
            link("Writeup", "https://zaskoda.com/2005/09/10/isogame-the-virtual-world-based-forum-interface-experiment/", "external"),
        ],
        "project_type": ["software", "community-platform"],
        "context": ["personal"],
        "body": """An experimental prototype connecting forum software to an isometric tile map — each tile linked to a thread, and the map's appearance changed with post activity. Social-data-driven world design, years before it was commonplace.""",
    },
    {
        "slug": "power-meowerzer",
        "title": "Power Meowerzer",
        "tagline": "An enrichment bot for cats.",
        "date": "2015-09-01",
        "status": "completed",
        "summary": "An animal enrichment robot, documented on Flickr.",
        "links": [
            link("Flickr", "https://www.flickr.com/photos/zaskoda/22439518477", "external"),
        ],
        "project_type": ["iot-robotics"],
        "context": ["personal"],
        "body": """An animal enrichment robot for cats, documented on Flickr.""",
    },
    {
        "slug": "new-garden-plant-scanner-prop",
        "title": "New Garden: Plant Scanner Prop",
        "tagline": "A working sci-fi prop for an indie film, built on a shoestring.",
        "date": "2016-01-01",
        "status": "completed",
        "summary": "Plant scanner prop fabricated for the indie sci-fi film New Garden — low-budget practical prop design for film.",
        "links": [
            link("Hackaday", "https://hackaday.io/project/9862-new-garden-prop-plant-scanner", "hackaday"),
        ],
        "project_type": ["fabrication", "iot-robotics"],
        "context": ["personal"],
        "body": """A plant scanner prop fabricated for the indie sci-fi film New Garden — low-budget practical prop design as part of a broader pattern of building physical objects for film, festivals, and installations.""",
    },
    {
        "slug": "hooven-lucifer-costume",
        "title": "Hooven Lucifer Costume",
        "tagline": "An elaborate costume build, documented in detail.",
        "date": "2010-01-01",
        "status": "completed",
        "summary": "Elaborate costume fabrication project documented on Flickr.",
        "links": [
            link("Flickr", "https://www.flickr.com/photos/zaskoda/sets/72157625482036376/", "external"),
        ],
        "project_type": ["fabrication"],
        "context": ["personal", "festival-burn"],
        "body": """An elaborate costume fabrication project documented in detail on Flickr.""",
    },
    {
        "slug": "custom-blogging-platform",
        "title": "Custom Blogging Platform",
        "tagline": "Weekly web updates starting August 1996 — blogging before the word, on a platform built from scratch.",
        "date": "1996-01-01",
        "status": "archived",
        "summary": "Before WordPress, Scott built and ran his own blogging platform for years. Oldest archived post: 8/8/1996. Build custom first, migrate when the ecosystem matures.",
        "project_type": ["software"],
        "context": ["personal"],
        "body": """Before WordPress, Scott built and ran his own blogging platform for years. His oldest archived post dates to 8/8/1996. Part of a career-long pattern: build the custom version first, migrate to the standard once the ecosystem matures.""",
    },
    {
        "slug": "take-two-cms",
        "title": "Take-Two CMS",
        "tagline": "A CMS built for GodGames that ran Take-Two's primary websites for over a decade.",
        "date": "1999-01-01",
        "status": "archived",
        "summary": "Built for Gathering of Developers; after Take-Two acquisition, Take2Games.com was rebuilt on it. Powered publisher web presence for 10+ years. Product portal and B2B retailer community.",
        "project_type": ["software", "community-platform"],
        "context": ["professional"],
        "body": """Originally built for Gathering of Developers, the platform was effective enough that after the Take-Two acquisition, Take2Games.com was rebuilt on it. It powered the publisher's primary web presence for more than a decade.

Also included: a product portal hub for all GodGames titles and a private B2B community for game retailers.""",
    },
]


def main():
    os.makedirs(PROJECTS_DIR, exist_ok=True)
    for p in PROJECTS:
        slug = p.pop("slug")
        # Validate summary length
        if len(p["summary"]) > 280:
            raise ValueError(f"{slug}: summary too long ({len(p['summary'])} chars)")
        write_project(slug, p)
    print(f"\nDone: {len(PROJECTS)} projects written.")


if __name__ == "__main__":
    main()
