#!/usr/bin/env python3
"""Import WordPress WXR export into Statamic posts collection."""

from __future__ import annotations

import re
import shutil
import sys
import textwrap
import xml.etree.ElementTree as ET
from dataclasses import dataclass, field
from html import unescape
from pathlib import Path
from urllib.parse import unquote, urlparse

try:
    from bs4 import BeautifulSoup, NavigableString
except ImportError:
    print("Installing beautifulsoup4...", file=sys.stderr)
    import subprocess

    subprocess.check_call([sys.executable, "-m", "pip", "install", "beautifulsoup4", "-q"])
    from bs4 import BeautifulSoup, NavigableString

ROOT = Path(__file__).resolve().parents[2]
XML_PATH = ROOT / "zaskoda.WordPress.2026-06-07.xml"
OLD_UPLOADS = ROOT / "old.zaskoda.com" / "wp-content" / "uploads"
SITE_ROOT = ROOT / "new.zaskoda.com"
POSTS_DIR = SITE_ROOT / "content" / "collections" / "posts"
CATEGORIES_DIR = SITE_ROOT / "content" / "taxonomies" / "categories"
ASSETS_DIR = SITE_ROOT / "public" / "assets" / "blog"

NS = {
    "content": "http://purl.org/rss/1.0/modules/content/",
    "excerpt": "http://wordpress.org/export/1.2/excerpt/",
    "wp": "http://wordpress.org/export/1.2/",
    "dc": "http://purl.org/dc/elements/1.1/",
}

EXISTING_IDS = {
    "introducing-orbiter-8": "post-introducing-orbiter-8",
    "leaving-facebook-why": "post-leaving-facebook-why",
    "my-first-raspberry-pi-robot": "post-my-first-raspberry-pi-robot",
}

EXCLUDED_SLUGS = {"hello-world"}

DIMENSION_SUFFIX = re.compile(r"-\d+x\d+$", re.I)
GUTENBERG_COMMENTS = re.compile(r"<!--\s*/?wp:[^>]*-->", re.I)
UPLOAD_PATH_RE = re.compile(r"wp-content/uploads/(.+)$", re.I)


@dataclass
class Attachment:
    post_id: str
    url: str
    rel_path: str
    full_path: Path
    metadata_file: str | None = None


@dataclass
class WpPost:
    title: str
    slug: str
    date: str
    content: str
    excerpt: str
    categories: list[str] = field(default_factory=list)
    post_id: str = ""
    published: bool = True
    wp_status: str = "publish"


def text(el) -> str:
    return (el.text or "").strip() if el is not None else ""


def wp_url_to_rel_path(url: str) -> str | None:
    if not url:
        return None
    url = unquote(url.split("?")[0].split("#")[0])
    if "wp.com/" in url:
        tail = url.split("wp.com/", 1)[1]
        if "/" in tail:
            tail = tail.split("/", 1)[1]
        url = "https://example.com/" + tail
    match = UPLOAD_PATH_RE.search(url)
    if match:
        return match.group(1).lstrip("/")
    path = urlparse(url).path.lstrip("/")
    if path.startswith("wp-content/uploads/"):
        return path.split("wp-content/uploads/", 1)[1]
    return None


def base_stem(name: str) -> str:
    stem = Path(name).stem
    return DIMENSION_SUFFIX.sub("", stem)


def parse_metadata_file(meta_value: str) -> str | None:
    match = re.search(r's:4:"file";s:\d+:"([^"]+)"', meta_value)
    return match.group(1) if match else None


def resolve_archive_path(rel_path: str) -> Path | None:
    candidate = OLD_UPLOADS / rel_path
    if candidate.is_file():
        return candidate
    return None


def find_largest_rel_path(rel_path: str) -> str:
    """Return the largest available variant of an upload (prefer original/scaled)."""
    path = Path(rel_path)
    directory = OLD_UPLOADS / path.parent
    if not directory.is_dir():
        return rel_path

    ext = path.suffix.lower()
    stem = base_stem(path.name)
    candidates: list[tuple[int, str]] = []

    for file in directory.iterdir():
        if not file.is_file() or file.suffix.lower() != ext:
            continue
        if base_stem(file.name) != stem:
            continue
        rel = f"{path.parent.as_posix()}/{file.name}".lstrip("/")
        if file.name == path.name or file.name == f"{stem}{ext}":
            candidates.append((10_000_000, rel))
            continue
        if file.name.endswith("-scaled" + ext):
            candidates.append((9_000_000, rel))
            continue
        dim = re.search(r"-(\d+)x(\d+)(?:-scaled)?" + re.escape(ext) + r"$", file.name, re.I)
        if dim:
            pixels = int(dim.group(1)) * int(dim.group(2))
            candidates.append((pixels, rel))

    if not candidates:
        return rel_path

    candidates.sort(key=lambda item: item[0], reverse=True)
    return candidates[0][1]


def copy_asset(rel_path: str) -> str | None:
    src = resolve_archive_path(rel_path)
    if not src:
        return None
    dest = ASSETS_DIR / rel_path
    dest.parent.mkdir(parents=True, exist_ok=True)
    if not dest.exists() or src.stat().st_mtime > dest.stat().st_mtime:
        shutil.copy2(src, dest)
    return f"/assets/blog/{rel_path}"


def resolve_image_urls(
    url: str,
    attachments_by_id: dict[str, Attachment],
    attachment_id: str | None = None,
) -> tuple[str | None, str | None]:
    """Return (embedded_public_url, large_public_url)."""
    rel_path = None
    if attachment_id and attachment_id in attachments_by_id:
        att = attachments_by_id[attachment_id]
        rel_path = att.metadata_file or att.rel_path
    if not rel_path:
        rel_path = wp_url_to_rel_path(url)
    if not rel_path:
        return None, None

    embedded_rel = rel_path
    url_rel = wp_url_to_rel_path(url)
    if url_rel and resolve_archive_path(url_rel):
        embedded_rel = url_rel

    if not resolve_archive_path(embedded_rel):
        embedded_rel = rel_path

    large_rel = find_largest_rel_path(
        attachments_by_id[attachment_id].metadata_file
        if attachment_id and attachment_id in attachments_by_id and attachments_by_id[attachment_id].metadata_file
        else embedded_rel
    )

    embedded_public = copy_asset(embedded_rel)
    large_public = copy_asset(large_rel)
    return embedded_public, large_public


def attachment_id_from_img(img) -> str | None:
    classes = img.get("class") or []
    if isinstance(classes, str):
        classes = classes.split()
    for cls in classes:
        if cls.startswith("wp-image-"):
            return cls.replace("wp-image-", "")
    return None


def is_inside_link(tag) -> bool:
    parent = tag.parent
    while parent is not None and getattr(parent, "name", None):
        if parent.name == "a":
            return True
        parent = parent.parent
    return False


def process_html_content(html: str, attachments_by_id: dict[str, Attachment]) -> str:
    html = GUTENBERG_COMMENTS.sub("", html or "")
    if not html.strip():
        return ""

    soup = BeautifulSoup(html, "html.parser")

    for img in soup.find_all("img"):
        src = img.get("src") or img.get("data-src") or ""
        att_id = attachment_id_from_img(img)
        embedded, large = resolve_image_urls(src, attachments_by_id, att_id)
        if embedded:
            img["src"] = embedded
            for attr in ("srcset", "data-src", "data-lazy-src", "sizes"):
                if attr in img.attrs:
                    del img[attr]

            if large and large != embedded and not is_inside_link(img):
                link = soup.new_tag("a", href=large)
                img.wrap(link)

    # Rewrite remaining upload URLs in anchors
    for tag in soup.find_all(href=True):
        rel = wp_url_to_rel_path(tag["href"])
        if rel and resolve_archive_path(rel):
            public = copy_asset(rel)
            if public:
                tag["href"] = public

    body = soup.body
    if body:
        return "".join(str(child) for child in body.children).strip()
    return str(soup).strip()


def strip_html(text_value: str) -> str:
    if not text_value:
        return ""
    return BeautifulSoup(text_value, "html.parser").get_text(" ", strip=True)


def slugify(value: str) -> str:
    value = unescape(value).lower()
    value = re.sub(r"[^\w\s-]", "", value)
    value = re.sub(r"[\s_-]+", "-", value).strip("-")
    return value


def yaml_quote(value: str) -> str:
    value = value.replace("\r\n", "\n").replace("\r", "\n")
    if not value or "\n" in value or ":" in value or value.startswith(("-", " ", "'", '"')):
        return "'" + value.replace("'", "''") + "'"
    return value


def write_category_terms(categories: dict[str, str]) -> None:
    CATEGORIES_DIR.mkdir(parents=True, exist_ok=True)
    for slug, title in sorted(categories.items()):
        path = CATEGORIES_DIR / f"{slug}.yaml"
        if not path.exists():
            path.write_text(f"title: {yaml_quote(title)}\n", encoding="utf-8")


def find_existing_file(slug: str) -> Path | None:
    for path in POSTS_DIR.glob("*.md"):
        if path.stem.endswith("." + slug) or path.stem == slug:
            return path
    return None


def write_post(post: WpPost, update_existing: bool) -> None:
    existing = find_existing_file(post.slug)
    if existing and not update_existing:
        return

    post_id = EXISTING_IDS.get(post.slug, f"post-{post.slug}")
    filename = existing.name if existing else f"{post.date}.{post.slug}.md"
    path = POSTS_DIR / filename

    excerpt = post.excerpt.strip()
    if not excerpt:
        excerpt = strip_html(post.content)[:220].strip()
        if len(excerpt) >= 220:
            excerpt = excerpt[:217].rsplit(" ", 1)[0] + "..."

    lines = [
        "---",
        f"id: {post_id}",
        "blueprint: post",
        f"title: {yaml_quote(post.title)}",
    ]
    if excerpt:
        lines.append(f"excerpt: {yaml_quote(excerpt)}")
    if post.categories:
        lines.append("categories:")
        for cat in post.categories:
            lines.append(f"  - {cat}")
    lines.append(f"date: '{post.date}'")
    if not post.published:
        lines.append("published: false")
    lines.append("---")
    lines.append(post.content.strip())
    lines.append("")

    path.write_text("\n".join(lines), encoding="utf-8")


def parse_export() -> tuple[dict[str, str], dict[str, Attachment], list[WpPost]]:
    tree = ET.parse(XML_PATH)
    channel = tree.getroot().find("channel")

    categories: dict[str, str] = {}
    for cat in channel.findall("wp:category", NS):
        slug = text(cat.find("wp:category_nicename", NS))
        name = text(cat.find("wp:cat_name", NS))
        if slug:
            categories[slug] = name or slug.replace("-", " ").title()

    attachments_by_id: dict[str, Attachment] = {}
    posts: list[WpPost] = []

    for item in channel.findall("item"):
        post_type = text(item.find("wp:post_type", NS))
        status = text(item.find("wp:status", NS))
        post_id = text(item.find("wp:post_id", NS))

        if post_type == "attachment":
            url = text(item.find("wp:attachment_url", NS))
            rel = wp_url_to_rel_path(url)
            if not rel:
                continue
            metadata_file = None
            for meta in item.findall("wp:postmeta", NS):
                if text(meta.find("wp:meta_key", NS)) == "_wp_attachment_metadata":
                    metadata_file = parse_metadata_file(text(meta.find("wp:meta_value", NS)))
                    break
            full = resolve_archive_path(rel) or resolve_archive_path(metadata_file or "")
            attachments_by_id[post_id] = Attachment(
                post_id=post_id,
                url=url,
                rel_path=rel,
                full_path=full or (OLD_UPLOADS / rel),
                metadata_file=metadata_file,
            )
            continue

        if post_type != "post" or status not in {"publish", "draft", "pending"}:
            continue

        slug = text(item.find("wp:post_name", NS))
        title = text(item.find("title"))
        if not slug:
            slug = slugify(title) or f"draft-{post_id}"
        if slug in EXCLUDED_SLUGS:
            continue
        date_raw = text(item.find("wp:post_date", NS))
        date = date_raw.split(" ")[0] if date_raw else ""
        content = text(item.find("content:encoded", NS))
        excerpt = strip_html(text(item.find("excerpt:encoded", NS)))

        post_categories = []
        for cat in item.findall("category"):
            if cat.get("domain") != "category":
                continue
            nicename = cat.get("nicename")
            if nicename:
                post_categories.append(nicename)

        posts.append(
            WpPost(
                title=unescape(title),
                slug=slug,
                date=date,
                content=content,
                excerpt=excerpt,
                categories=sorted(set(post_categories)),
                post_id=post_id,
                published=status == "publish",
                wp_status=status,
            )
        )

    return categories, attachments_by_id, posts


def main() -> None:
    if not XML_PATH.is_file():
        raise SystemExit(f"Missing export: {XML_PATH}")

    categories, attachments_by_id, posts = parse_export()
    write_category_terms(categories)

    imported = 0
    updated = 0
    draft_imported = 0
    draft_updated = 0
    published_posts = [post for post in posts if post.published]
    draft_posts = [post for post in posts if not post.published]

    for post in sorted(posts, key=lambda p: p.date):
        existing = find_existing_file(post.slug)
        processed = process_html_content(post.content, attachments_by_id)
        post.content = processed
        write_post(post, update_existing=True)
        if post.published:
            if existing:
                updated += 1
            else:
                imported += 1
        elif existing:
            draft_updated += 1
        else:
            draft_imported += 1

    print(f"Categories: {len(categories)}")
    print(f"Attachments indexed: {len(attachments_by_id)}")
    print(f"Published posts imported: {imported}")
    print(f"Published posts updated: {updated}")
    print(f"Draft posts imported: {draft_imported}")
    print(f"Draft posts updated: {draft_updated}")
    print(f"Published posts total: {len(published_posts)}")
    print(f"Draft posts total: {len(draft_posts)}")
    print(f"Posts total: {len(posts)}")


if __name__ == "__main__":
    main()
