# Architecture, assumptions, risks, dependency discipline

## Why a block theme (not classic)

WordPress’s current standard is full-site editing. For a marketing/support
site whose value is *content and structure*, a block theme gives:

- `theme.json` as the single design-token source (palette, fluid type,
  spacing) with editor + front end guaranteed in sync;
- templates and parts editable by the site owner without PHP;
- patterns as the native “sections” system, insertable and updatable;
- dynamic blocks (`register_block_type` with `render_callback`) for anything
  data-driven — no shortcodes, no page-builder lock-in.

A classic theme would have required hand-rolled option panels *plus* custom
template PHP for every layout, duplicating what blocks already provide. The
one classic-theme advantage (tight control over markup) is preserved where it
matters: the two forms are authored as semantic HTML inside patterns, and all
five dynamic blocks emit hand-written, escaped markup.

## The three editable layers

1. **Settings API page** (`inc/settings.php`): one option,
   `curedhosting_settings`, schema-driven. Structured content (plan limits,
   promo terms, FAQ, contact) lives here so the plan cards, comparison
   table, promo band, and footer can never drift apart.
2. **Patterns** (`patterns/`): section copy, forms, policies. Pages created
   by the starter-pages tool reference patterns via `wp:pattern` blocks, so
   pattern updates propagate site-wide and nonces/forms render live.
3. **Site Editor**: header/footer parts, templates, navigation.

## Forms without plugins

`inc/forms.php` handles `admin-post.php` submissions for both forms:
nonce → per-IP rate limit → honeypot → per-field whitelist sanitization →
storage as **private** `ch_submission` posts (admin-only, local, deletable;
`create_posts` capability is `do_not_allow`) → optional `wp_mail()` notice to
the configured address. Redirects always use PRG; errors/success return as
query args rendered as accessible banners with a consumed transient.
No third-party endpoint is contacted; no secrets are hardcoded.

## Assumptions

- WP 6.4+, PHP 7.4+ (matches `Requires at least` / `Requires PHP`).
- No prices were supplied: cards show a configurable “price confirmed in
  writing” note instead of invented numbers; each plan has an optional
  price field for later.
- The supplied logo raster (`assets/images/perfectlogo-256.png`, the real
  256×256 transparent mark from the live site) ships with the theme and is
  auto-installed on activation; the bundled faithful stand-ins remain only
  as fallbacks.
- Standard page slugs are those created by the starter-pages tool; the
  header nav links to them with relative URLs.

## Risks and mitigations

- **Patterns inserted (not referenced) bake static HTML.** Documented in
  README + pattern headers; default pages use references.
- **Dynamic blocks render empty until settings exist.** Defaults cover every
  field, so first render is always populated.
- **Mail delivery varies by host.** Notifications are optional; submissions
  are always stored locally; WP Mail SMTP documented as opt-in.
- **Reseller reality.** No copy claims dedicated resources or “unlimited”
  anything; every limit is explicit; the infra note names InMotion + cPanel
  plainly.
- **Promo compliance.** Offer copy contains every mandated qualifier, the
  block can be switched off from settings, and nothing renders countdowns
  or invented scarcity.

## Dependency discipline

Zero required plugins; zero build steps; zero remote fonts/CDNs; zero
tracking. The only front-end script is ~1 KB of progressive enhancement and
the only stylesheet beyond core is one namespaced component file. Every
WordPress feature used is core API: Settings API, block registration,
patterns, CPT, `admin-post.php`, `wp_mail`, transients, media sideload.
A child theme can override tokens via its own `theme.json`, templates,
patterns, and the `curedhosting_settings` filter without touching PHP.
