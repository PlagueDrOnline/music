# CuredHosting — WordPress Theme

> The cure for the common hosting headache.
> Hosting with a personal tech, not a support queue.

A production-intent **block theme** for curedhosting.com. Deep green, warm
cream, restrained gold; honest limits; no fake urgency; no third-party
dependencies; no tracking.

This document covers installation, setup, editable content, dependencies,
and where each checklist lives. See `docs/` for the detail documents.

**Scope note:** this theme contains no deployment, server, cPanel/WHM, or
live-site migration instructions. Deployment happens separately after review.

---

## 1. File tree

```
curedhosting/
├── style.css                     Theme header (tokens live in theme.json)
├── theme.json                    Design tokens, typography, spacing, templates
├── functions.php                 Bootstrap: assets, skip link, logo install
├── screenshot.png                Theme preview
├── templates/                    front-page, index, page, page-landing,
│                                 single, archive, search, 404
├── parts/                        header.html, footer.html
├── patterns/                     23 block patterns (homepage sections, forms,
│                                 plans pages, comparison, policies, landing)
├── inc/
│   ├── defaults.php              Default content (plans, promo, FAQ, contact)
│   ├── template-tags.php         Read-only helpers (settings, form state)
│   ├── settings.php              Admin settings page (Settings API)
│   ├── blocks.php                5 server-rendered dynamic blocks
│   ├── forms.php                 Contact + onboarding handlers, submissions CPT
│   └── starter-pages.php         One-click standard page creation (opt-in)
├── assets/
│   ├── css/theme.css             Components: cards, table, FAQ, forms, focus
│   ├── js/theme.js               ~1 KB progressive enhancement only
│   └── images/                   Logo assets (brand raster + fallbacks)
└── docs/                         Architecture + checklists + testing guides
```

## 2. Install on a local WordPress test site

Requirements: WordPress **6.4+**, PHP **7.4+**.

1. Create a local site (LocalWP, `wp-env`, or a manual install).
2. Copy (or zip) the `curedhosting` folder into `wp-content/themes/`.
   - To zip: `cd wp-content/themes && zip -r curedhosting.zip curedhosting`
     then upload via **Appearance → Themes → Add New → Upload Theme**.
3. Activate **CuredHosting**.
4. On the dashboard notice, click **Create standard pages**. This creates
   the 14 standard pages (Home, plans, comparison, migration, about,
   contact, onboarding, grand opening, and the five policy pages) and sets
   the static front page. It never overwrites existing pages.
5. Verify:
   - Home shows all eleven homepage sections.
   - `/onboarding/` and `/contact/` forms submit and show success banners.
   - Appearance → Editor opens header/footer parts and templates.
   - **CuredHosting** menu (wp-admin) opens the settings page; edits to a
     plan limit update the cards *and* the comparison table together.

## 3. Customizer / Site Editor setup

- **Site Identity → logo:** on activation the theme auto-installs the logo
  from `assets/images/` (prefers `perfectlogo-256.png`, which now ships
  with the theme, so fresh installs get the real mark automatically).
  Replace via **Customize → Site Identity**, or by swapping that file
  (same name) and re-activating.
- **Navigation:** header uses a navigation block with relative links to the
  standard slugs. Adjust labels/links in **Appearance → Editor → header**.
- **Templates & parts:** edit in the Site Editor; the theme ships
  `page-landing` (no page title) for campaign pages.
- **Patterns:** all sections live under **Insert → Patterns → CuredHosting**.
  Forms and dynamic sections should be placed with a *Pattern block*
  (`wp:pattern`) so they render live (fresh nonces, live settings) — see
  `docs/content-guide.md`.
- **Colors/type/spacing:** adjust in `theme.json`; no locked gradients, no
  custom CSS required for palette changes.

## 4. Where editable content lives

| Content | Where |
| --- | --- |
| Plan names, taglines, every limit, extras, best-for, CTAs, price notes | **CuredHosting settings** (renders in plan cards *and* comparison table) |
| Shared fair-use note + shared CTA | Settings → Shared plan settings |
| Grand-opening copy, bullets, terms, on/off switch | Settings → Grand-opening promotion |
| FAQs (8 slots) | Settings → FAQ |
| Contact/support emails, phone, hours, notification address | Settings → Contact & support |
| Footer tagline, infrastructure note, small print | Settings → Footer |
| Section copy (hero, why-us, migration lists, steps, about) | `patterns/*.php` (or override via Site Editor) |
| Policies (fair use, AUP, plugins, privacy, terms placeholders) | `patterns/policy-*.php` |
| Header/footer structure | `parts/header.html`, `parts/footer.html` |

Full map with field names: `docs/content-guide.md`.

## 5. Plugins

**Required: none.** Everything ships natively (forms, dynamic content,
patterns). We never silently install anything.

**Optional, only if needed:**

| Plugin | Why you might add it | Lightweight alternative |
| --- | --- | --- |
| WP Mail SMTP | If the host’s `wp_mail()` delivery is unreliable (notifications from forms) | Keep default `wp_mail`; check host mail logs first |

Alternatives considered and *not* required: Contact Form 7 (our forms are
native and store submissions as private posts), ACF (the settings page
covers all structured content), analytics of any kind (deliberately absent;
the theme loads no trackers).

## 6. Checklists (deliverables 7–12)

- Accessibility: `docs/accessibility-checklist.md`
- Performance: `docs/performance-checklist.md`
- Security: `docs/security-checklist.md`
- Responsive testing: `docs/responsive-testing-checklist.md`
- Local lint / WPCS testing: `docs/local-testing.md`
- Architecture & assumptions: `docs/architecture.md`

## 7. Logo placement

- `assets/images/perfectlogo-256.png` — **the supplied brand raster**
  (source: the legacy theme’s `assets/perfectlogo-256.png`). Takes
  precedence automatically on theme activation.
- `assets/images/curedhosting-logo.svg` / `.png` — faithful stand-ins used
  only until the supplied raster is present. Nothing else references the
  logo by path; the header renders whatever the custom-logo theme mod holds.

## 8. Translation

Text domain `curedhosting` throughout; `load_theme_textdomain()` wired.
Drop language packs into `languages/`.
