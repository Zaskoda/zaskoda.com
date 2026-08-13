---
id: project-gearbox-community-architecture
blueprint: project
title: 'Gearbox Community Architecture'
tagline: 'A forum jail that rehabilitated trolls instead of banning them, at one of the largest game developer communities of its era.'
date: '2004-01-01'
date_end: '2007-12-31'
status: completed
summary: 'Community systems at Gearbox Software: The Ban Bin forum jail, The Illuminate reward council, Internal Combustion flame forum, Gearblogs developer diaries, and ARG puzzle campaigns.'
hero_image: projects/gearbox-software-gearboxity-hero.png
gallery:
  - projects/gearbox-software-forums.png
  - projects/bia-sdk-wiki.png
links:
  -
    type: link
    enabled: true
    label: 'Ban Bin Writeup'
    url: 'https://zaskoda.com/2005/09/24/the-ban-bin-how-effective-is-a-forum-jail/'
    icon: external
  -
    type: link
    enabled: true
    label: 'Forum Ideas'
    url: 'https://zaskoda.com/2005/06/08/a-few-good-forum-ideas/'
    icon: external
project_type:
  - community-platform
  - software
context:
  - professional
---

At Gearbox Software I oversaw the websites, community, and internal tools for what was then one of the largest, most active game developer communities around. My title kept changing — Community Manager, then Online Architect (the one I put on my own business cards), then Director of Applications, then Director of New Media — but the work stayed the same: design the systems that let a big, rowdy community actually function.

A few of the pieces I built:

**The Ban Bin** — a forum jail used instead of permanent bans. When someone crossed a line, they got a thread with their name on it where the violation was stated openly and could be disputed. It worked: only two users were ever jailed twice, and none more than that. I wrote it up in "The Ban Bin: How Effective Is A Forum Jail?"

**Gearboxity** - a news platform dedicated for our active community.

**The Illuminate** — a hidden reward forum for trusted members that quietly became an informal community council.

**Internal Combustion** — a members-only flame forum that gave complaints a place to go, and turned a surprising number of them constructive.

**Gearblogs** — an early developer-diary platform, before that was a common thing studios did.

**ARG campaigns** — collaborative puzzle hunts where the community had to work together to unlock game content.

We initially built all of it on a custom PHP framework I wrote from scratch, then eventually migrated to CakePHP as the scope grew. The rehabilitative moderation ideas in particular kept showing up across the industry years later. 
