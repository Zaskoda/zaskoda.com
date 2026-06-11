#!/usr/bin/env python3
"""Process root images into project assets and update collection entries."""

import os
import re
import subprocess
from pathlib import Path

ROOT = Path("/home/koda/projects/personal/zaskoda.com")
ASSETS = ROOT / "new.zaskoda.com/public/assets/projects"
PROJECTS = ROOT / "new.zaskoda.com/content/collections/projects"

HERO_SIZE = "1200x675"
GALLERY_SIZE = "1200x675"


def convert_image(src: Path, dest: Path, size: str = HERO_SIZE) -> None:
    dest.parent.mkdir(parents=True, exist_ok=True)
    subprocess.run(
        [
            "convert",
            str(src),
            "-resize",
            f"{size}^",
            "-gravity",
            "center",
            "-extent",
            size,
            "-quality",
            "88",
            str(dest),
        ],
        check=True,
    )


def mark_integrated(src: Path) -> None:
    stem = src.stem
    if stem.endswith("-integrated"):
        return
    dest = src.with_name(f"{stem}-integrated{src.suffix}")
    src.rename(dest)
    print(f"  renamed -> {dest.name}")


def update_frontmatter(slug: str, hero: str | None = None, gallery: list[str] | None = None, append_gallery: list[str] | None = None):
    path = PROJECTS / f"{slug}.md"
    text = path.read_text(encoding="utf-8")
    if not text.startswith("---"):
        raise ValueError(f"No frontmatter in {path}")

    parts = text.split("---", 2)
    fm, body = parts[1], parts[2]

    if hero is not None:
        if re.search(r"^hero_image:", fm, re.M):
            fm = re.sub(r"^hero_image:.*$", f"hero_image: {hero}", fm, flags=re.M)
        else:
            # Insert after summary block
            if re.search(r"^summary:", fm, re.M):
                fm = re.sub(
                    r"(^summary:.*(?:\n(?:  |').*)*)",
                    rf"\1\nhero_image: {hero}",
                    fm,
                    count=1,
                    flags=re.M,
                )
            else:
                fm = fm.rstrip() + f"\nhero_image: {hero}\n"

    if gallery is not None:
        if re.search(r"^gallery:", fm, re.M):
            fm = re.sub(r"^gallery:\n(?:  - .*\n)*", "", fm)
        gallery_block = "gallery:\n" + "\n".join(f"  - {g}" for g in gallery) + "\n"
        insert_after = fm
        for anchor in ("hero_image:", "summary:"):
            if anchor in fm:
                m = re.search(rf"^{anchor}.*(?:\n(?:  |').*)*", fm, re.M)
                if m:
                    pos = m.end()
                    fm = fm[:pos] + "\n" + gallery_block.rstrip() + fm[pos:]
                    break
        else:
            fm = fm.rstrip() + "\n" + gallery_block

    if append_gallery:
        if re.search(r"^gallery:", fm, re.M):
            for item in append_gallery:
                if item not in fm:
                    fm = re.sub(
                        r"(^gallery:\n(?:  - .*\n)*)",
                        rf"\1  - {item}\n",
                        fm,
                        count=1,
                        flags=re.M,
                    )
        else:
            gallery_block = "gallery:\n" + "\n".join(f"  - {g}" for g in append_gallery) + "\n"
            if re.search(r"^hero_image:", fm, re.M):
                fm = re.sub(
                    r"(^hero_image:.*)",
                    rf"\1\n{gallery_block.rstrip()}",
                    fm,
                    count=1,
                    flags=re.M,
                )
            else:
                fm = fm.rstrip() + "\n" + gallery_block

    path.write_text(f"---{fm}---{body}", encoding="utf-8")
    print(f"  updated {slug}.md")


# (source_filename, asset_filename, size_mode)
# size_mode: 'hero' | 'gallery' | 'gallery-no-crop' for small screenshots

INTEGRATIONS = [
    # Bob
    {
        "sources": [
            ("bob-and-his-amazing-journey-home-title.png", "bob-title.jpg", "hero"),
            ("bob-and-his-amazing-journey-home-gameplay.png", "bob-gameplay.jpg", "gallery"),
        ],
        "slug": "bob-and-his-amazing-journey-home",
        "hero": "projects/bob-title.jpg",
        "gallery": ["projects/bob-gameplay.jpg"],
    },
    # Chromodepth (circus-of-fear haunt photos)
    {
        "sources": [
            ("circus-of-fear-opening.jpg", "chromodepth-opening.jpg", "hero"),
            ("circus-of-fear-bubbles.jpg", "chromodepth-bubbles.jpg", "gallery"),
            ("circus-of-fear-crew.jpg", "chromodepth-crew.jpg", "gallery"),
        ],
        "slug": "chromodepth-haunted-house",
        "hero": "projects/chromodepth-opening.jpg",
        "gallery": ["projects/chromodepth-bubbles.jpg", "projects/chromodepth-crew.jpg"],
    },
    {
        "sources": [("dallas-festevil.png", "dallasfestevil.jpg", "hero")],
        "slug": "dallasfestevil",
        "hero": "projects/dallasfestevil.jpg",
    },
    {
        "sources": [("dallas-snowboarders.png", "dallassnowboarders.jpg", "hero")],
        "slug": "dallassnowboarders",
        "hero": "projects/dallassnowboarders.jpg",
    },
    {
        "sources": [
            ("digital-globe-on-black.png", "digital-globe-on-black.jpg", "hero"),
            ("digital-globe-finger.png", "digital-globe-finger.jpg", "gallery"),
        ],
        "slug": "digital-globe-ipad-app",
        "hero": "projects/digital-globe-on-black.jpg",
        "gallery": ["projects/digital-globe-finger.jpg"],
    },
    {
        "sources": [("ictd-mesh-network.jpg", "ictd-mesh-network.jpg", "hero")],
        "slug": "ictd-mesh-network",
        "hero": "projects/ictd-mesh-network.jpg",
    },
    {
        "sources": [("lucifer-boots.jpg", "hooven-lucifer-boots.jpg", "hero")],
        "slug": "hooven-lucifer-costume",
        "hero": "projects/hooven-lucifer-boots.jpg",
    },
    {
        "sources": [("mobile-operations-center-moc.jpg", "moc.jpg", "hero")],
        "slug": "moc-mobile-operations-center",
        "hero": "projects/moc.jpg",
    },
    {
        "sources": [("new-garden-dr-algamore-movie-prop.jpg", "new-garden-prop.jpg", "hero")],
        "slug": "new-garden-plant-scanner-prop",
        "hero": "projects/new-garden-prop.jpg",
    },
    {
        "sources": [
            ("peon-under-construction.jpg", "peon-under-construction.jpg", "hero"),
            ("peon-dark.jpg", "peon-dark.jpg", "gallery"),
        ],
        "slug": "peon-scorpion-robot",
        "hero": "projects/peon-under-construction.jpg",
        "gallery": ["projects/peon-dark.jpg"],
    },
    {
        "sources": [("PolyDice_Header_960x480.png", "polydice.jpg", "hero")],
        "slug": "polydice",
        "hero": "projects/polydice.jpg",
    },
    {
        "sources": [
            ("splat-title.png", "splat-title.jpg", "hero"),
            ("splat-menu.png", "splat-menu.jpg", "gallery"),
            ("splat-gameplay.png", "splat-gameplay.jpg", "gallery"),
        ],
        "slug": "splat",
        "hero": "projects/splat-title.jpg",
        "gallery": ["projects/splat-menu.jpg", "projects/splat-gameplay.jpg"],
    },
    {
        "sources": [
            ("steampunk-snowboard.jpg", "steampunk-snowboard.jpg", "hero"),
            ("steampunk-snowboard-dark.jpg", "steampunk-snowboard-dark.jpg", "gallery"),
            ("steampunk-snowboard-nino.jpg", "steampunk-snowboard-nino.jpg", "gallery"),
            ("steampunk-snowboard-cowboy.png", "steampunk-snowboard-cowboy.jpg", "gallery"),
        ],
        "slug": "worlds-first-steampunk-snowboard",
        "hero": "projects/steampunk-snowboard.jpg",
        "gallery": [
            "projects/steampunk-snowboard-dark.jpg",
            "projects/steampunk-snowboard-nino.jpg",
            "projects/steampunk-snowboard-cowboy.jpg",
        ],
    },
    {
        "sources": [
            ("the-legeld-of-talibah-title.png", "talibah-title.jpg", "hero"),
            ("the-legeld-of-talibah-castle.png", "talibah-castle.jpg", "gallery"),
            ("the-legeld-of-talibah-monks.png", "talibah-monks.jpg", "gallery"),
        ],
        "slug": "legend-of-talibah",
        "hero": "projects/talibah-title.jpg",
        "gallery": ["projects/talibah-castle.jpg", "projects/talibah-monks.jpg"],
    },
    {
        "sources": [
            ("zeus-front.jpg", "zeus-front.jpg", "hero"),
            ("zeus-close.jpg", "zeus-close.jpg", "gallery"),
        ],
        "slug": "zeus-the-robot-truck",
        "hero": "projects/zeus-front.jpg",
        "gallery": ["projects/zeus-close.jpg"],
    },
    # Polar Bear — append inside shot; outside source already used as hero asset
    {
        "sources": [("polar-bear-inside.jpg", "polar-bear-inside.jpg", "gallery")],
        "slug": "polar-bear-van",
        "append_gallery": ["projects/polar-bear-inside.jpg"],
    },
]

# Already represented by existing hero asset — mark integrated only
ALREADY_USED = ["polar-bear-outisde.jpg"]

# Not project images — leave untouched
SKIP = ["orbiter-8.png", "koda-logo-orange.png", "koda-logo-purple.png"]


def main():
    for batch in INTEGRATIONS:
        slug = batch["slug"]
        print(f"\n{slug}:")
        for src_name, asset_name, mode in batch["sources"]:
            src = ROOT / src_name
            if not src.exists():
                print(f"  SKIP missing: {src_name}")
                continue
            dest = ASSETS / asset_name
            size = HERO_SIZE if mode == "hero" else GALLERY_SIZE
            print(f"  {src_name} -> {asset_name}")
            convert_image(src, dest, size)
            mark_integrated(src)

        update_frontmatter(
            slug,
            hero=batch.get("hero"),
            gallery=batch.get("gallery"),
            append_gallery=batch.get("append_gallery"),
        )

    print("\nAlready-used sources:")
    for name in ALREADY_USED:
        src = ROOT / name
        if src.exists():
            print(f"  {name}")
            mark_integrated(src)

    print("\nSkipped (not assigned):")
    for name in SKIP:
        if (ROOT / name).exists():
            print(f"  {name}")

    print("\nDone.")


if __name__ == "__main__":
    main()
