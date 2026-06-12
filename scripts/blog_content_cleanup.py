#!/usr/bin/env python3
"""Blog post content cleanup per post-clean-up.md."""

from __future__ import annotations

import re
from pathlib import Path
from urllib.parse import unquote

ROOT = Path(__file__).resolve().parents[1]
POSTS_DIR = ROOT / "content" / "collections" / "posts"
CATEGORIES_DIR = ROOT / "content" / "taxonomies" / "categories"
FINDINGS_PATH = ROOT.parent / "BLOG-AUDIT-FINDINGS.md"

POST_SLUGS: set[str] = set()
CATEGORY_SLUGS: set[str] = set()

WP_POST_RE = re.compile(
    r"https?://(?:www\.)?zaskoda\.com/\d{4}/\d{2}/\d{2}/([^\"'#\s>]+)",
    re.I,
)
WP_CATEGORY_RE = re.compile(
    r"https?://(?:www\.)?zaskoda\.com/category/([^\"'#\s>]+)",
    re.I,
)

SEO_PATTERNS = [
    (re.compile(r"in order to .{0,120}?visit", re.I), "Generic SEO template: in order to ... visit"),
    (re.compile(r"look for .{0,120}? at \[?link", re.I), "Generic SEO template: look for ... at [link]"),
    (re.compile(r"look for rehab", re.I), "Off-topic keyword: rehab"),
    (re.compile(r"brokerage account", re.I), "Off-topic keyword: brokerage"),
]

SUSPICIOUS_DOMAINS = {
    "stocktrades.ca",
    "recoverydelivered.com",
}

HIGH_CONF_BODY_REPLACEMENTS = [
    (re.compile(r"\bJanurary\b"), "January"),
    (re.compile(r"\bFebrurary\b"), "February"),
    (re.compile(r"\btogehter\b"), "together"),
    (re.compile(r"\bcursted\b"), "crusted"),
    (re.compile(r"\bsnowboaders\b"), "snowboarders"),
    (re.compile(r"\bConoravirus\b"), "Coronavirus"),
    (re.compile(r"\bCoronavrius\b"), "Coronavirus"),
    (re.compile(r"\brecieve\b"), "receive"),
    (re.compile(r"\bseperate\b"), "separate"),
    (re.compile(r"\bdefinately\b"), "definitely"),
    (re.compile(r"\boccured\b"), "occurred"),
    (re.compile(r"\buntill\b"), "until"),
    (re.compile(r"\bmetldown\b"), "meltdown"),
    (re.compile(r"\bthe the\b"), "the"),
    (re.compile(r"\band and\b"), "and"),
    (re.compile(r"\bof of\b"), "of"),
    (re.compile(r"\bpublished and article\b"), "published an article"),
    (re.compile(r"\bhave all been replace\b"), "have all been replaced"),
    (re.compile(r"\bcreated it's own\b"), "created its own"),
    (re.compile(r">foam</a> later next"), ">foam</a> layer next"),
    (re.compile(r"\bwhen they first starting going on\b"), "when they first started going on"),
    (re.compile(r"\bcheck out project out\b"), "check this project out"),
    (re.compile(r"\bthis make sense\b"), "this makes sense"),
    (re.compile(r"\bto the the distributed ledger\b"), "to the distributed ledger"),
    (re.compile(r"\bclick and drag a chess pieces\b"), "click and drag chess pieces"),
    (re.compile(r"\bspecific message to the contracts\b"), "specific messages to the contracts"),
    (re.compile(r"\brecruiting other creative and establishing\b"), "recruiting other creatives and establishing"),
    (re.compile(r"\bI have also setup a form to collect email address\b"), "I have also set up a form to collect email addresses"),
    (re.compile(r"\bWhat You Need to Known Before January 16th\b"), "What You Need to Know Before January 16th"),
    (re.compile(r"\bThat same month I interview Cryptogogue\b"), "That same month I interviewed Cryptogogue"),
]


def load_indexes() -> None:
    for path in POSTS_DIR.glob("*.md"):
        match = re.search(r"\.([^.]+)\.md$", path.name)
        if match:
            POST_SLUGS.add(match.group(1))
    for path in CATEGORIES_DIR.glob("*.yaml"):
        CATEGORY_SLUGS.add(path.stem)


def split_frontmatter(text: str) -> tuple[str, str]:
    if not text.startswith("---"):
        return "", text
    parts = text.split("---", 2)
    if len(parts) < 3:
        return "", text
    return parts[1], parts[2].lstrip("\n")


def join_frontmatter(frontmatter: str, body: str) -> str:
    return f"---{frontmatter}---\n{body}"


def rewrite_wp_post_url(url: str) -> tuple[str | None, str | None]:
    match = WP_POST_RE.search(url)
    if not match:
        return None, None
    slug = unquote(match.group(1).rstrip("/")).split("/")[0]
    if slug in POST_SLUGS:
        return url, f"/blog/{slug}"
    return url, None


def rewrite_wp_category_url(url: str) -> tuple[str | None, str | None]:
    match = WP_CATEGORY_RE.search(url)
    if not match:
        return None, None
    slug = unquote(match.group(1).rstrip("/")).split("/")[-1]
    if slug in CATEGORY_SLUGS:
        return url, f"/blog/categories/{slug}"
    return url, None


def apply_list1_leaving_facebook(body: str) -> str:
    injected = (
        "Many people who struggle with alcohol or drug addiction also have a co-occurring mental health condition "
        "such as anxiety or depression, so look for rehab facilities at "
        '<a href="https://www.recoverydelivered.com/">recoverydelivered.com</a>. '
    )
    body = body.replace(injected, "")
    body = body.replace(
        "It was designed that way.We now know that the political consulting firm",
        "It was designed that way.</p>\n<p style=\"text-align:justify\">We now know that the political consulting firm",
    )
    return body


def apply_list1_orbiter(body: str) -> str:
    spam = (
        "<p>In order to trade stocks, you need to have a brokerage account, visit  "
        '<a href="https://www.stocktrades.ca/">https://www.stocktrades.ca/</a> to get one.</p>\n'
    )
    return body.replace(spam, "")


def fix_steampunk_title(frontmatter: str) -> str:
    return frontmatter.replace(
        "title: The Worlds First Steampunk Snowboard",
        "title: The World's First Steampunk Snowboard",
    )


def rewrite_internal_links(body: str, findings: list[str], filename: str) -> str:
    def replace_post(match: re.Match) -> str:
        original = match.group(0)
        _, new = rewrite_wp_post_url(original)
        if new:
            return new
        slug = unquote(WP_POST_RE.search(original).group(1).rstrip("/")).split("/")[0]
        findings.append(f"- **{filename}**: Unmapped post URL `{original}` (slug `{slug}` not found)")
        return original

    def replace_category(match: re.Match) -> str:
        original = match.group(0)
        _, new = rewrite_wp_category_url(original)
        if new:
            return new
        slug = unquote(WP_CATEGORY_RE.search(original).group(1).rstrip("/")).split("/")[-1]
        findings.append(f"- **{filename}**: Unmapped category URL `{original}` (slug `{slug}` not found)")
        return original

    body = WP_POST_RE.sub(replace_post, body)
    body = WP_CATEGORY_RE.sub(replace_category, body)
    return body


def scan_for_audit(body: str, filename: str, findings: list[str]) -> None:
    for domain in SUSPICIOUS_DOMAINS:
        if domain in body.lower():
            findings.append(f"- **{filename}**: Suspicious domain still present: `{domain}` (review manually)")

    for pattern, label in SEO_PATTERNS:
        for match in pattern.finditer(body):
            snippet = body[max(0, match.start() - 80) : min(len(body), match.end() + 120)].replace("\n", " ")
            findings.append(f"- **{filename}** ({label}): `...{snippet}...`")

    # External domains triage (exclude common legit + zaskoda + assets)
    for match in re.finditer(r'href="(https?://[^"]+)"', body, re.I):
        url = match.group(1)
        domain = re.sub(r"^https?://(www\.)?", "", url).split("/")[0].lower()
        if domain.endswith("zaskoda.com") or domain.startswith("farm") and ".static.flickr.com" in domain:
            continue
        if domain in {"web3.foundation", "ethereum.org", "ipfs.io", "orbiter8.com", "partavate.com", "2design.org",
                      "cryptoslate.com", "linkedin.com", "www.linkedin.com", "twitter.com", "discord.gg",
                      "volitionccg.com", "immutable.com", "flickr.com", "www.flickr.com", "github.com"}:
            continue
        # Only flag commercial-looking TLD patterns once per file/domain
        if any(x in domain for x in ("rehab", "casino", "loan", "insurance", "essay", "pharma", "stocktrade")):
            findings.append(f"- **{filename}**: Commercial/affiliate domain for review: `{domain}` in `{url}`")

    if re.search(r"Here are a few videos.*:\s*$", body, re.M):
        findings.append(f"- **{filename}**: Trailing video setup phrase with no following content (broken embed candidate)")


def process_file(path: Path, findings: list[str]) -> bool:
    original = path.read_text(encoding="utf-8")
    frontmatter, body = split_frontmatter(original)
    if not frontmatter and path.name.endswith(".md"):
        return False

    changed = False
    name = path.name

    if name == "2019-09-03.leaving-facebook-why.md":
        new_body = apply_list1_leaving_facebook(body)
        if new_body != body:
            body = new_body
            changed = True

    if name == "2019-10-30.introducing-orbiter-8.md":
        new_body = apply_list1_orbiter(body)
        if new_body != body:
            body = new_body
            changed = True

    if name == "2009-04-13.the-worlds-first-steampunk-snowboard.md":
        new_fm = fix_steampunk_title(frontmatter)
        if new_fm != frontmatter:
            frontmatter = new_fm
            changed = True

    new_body = rewrite_internal_links(body, findings, name)
    if new_body != body:
        body = new_body
        changed = True

    for pattern, replacement in HIGH_CONF_BODY_REPLACEMENTS:
        updated = pattern.sub(replacement, body)
        if updated != body:
            body = updated
            changed = True

    scan_for_audit(body, name, findings)

    if changed:
        path.write_text(join_frontmatter(frontmatter, body), encoding="utf-8")
    return changed


def write_findings(findings: list[str]) -> None:
    lines = [
        "# Blog Audit Findings",
        "",
        "Automated scan results from the post-clean-up pass. Items listed here were",
        "**not** auto-applied — review and approve before changing.",
        "",
    ]
    if not findings:
        lines.append("No additional findings beyond the fixes applied in this pass.")
    else:
        lines.extend(findings)
    FINDINGS_PATH.write_text("\n".join(lines) + "\n", encoding="utf-8")


def main() -> None:
    load_indexes()
    findings: list[str] = []
    changed_files = 0

    for path in sorted(POSTS_DIR.glob("*.md")):
        if process_file(path, findings):
            changed_files += 1

    write_findings(findings)
    print(f"Changed files: {changed_files}")
    print(f"Findings: {len(findings)}")
    print(f"Report: {FINDINGS_PATH}")


if __name__ == "__main__":
    main()
