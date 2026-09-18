# Editable content map

Where every piece of site content is defined and how to change it.
“Settings” = wp-admin → **CuredHosting** (single settings page).

## Settings-driven (recommended for non-developers)

Option row: `curedhosting_settings`. Sanitized on save; rendered live by the
dynamic blocks below. Nothing here is a secret; `notify_email` defaults to
the WordPress admin email when blank.

| Field group | Fields | Rendered by |
| --- | --- | --- |
| Contact & support | `contact_email`, `support_email`, `notify_email`, `phone`, `hours`, `location_note` | `curedhosting/support-summary` |
| Footer | `footer_tagline`, `footer_infra_note`, `footer_legal_lines[]` | `curedhosting/support-summary` (footer variant) |
| Grand opening | `promo_enabled`, `promo_kicker`, `promo_heading`, `promo_intro`, `promo_points[]`, `promo_terms`, `promo_link_label`, `promo_link_url` | `curedhosting/promo` |
| Shared plans | `plans_fair_use_note`, `plans_price_note`, `plans_cta_label`, `plans_cta_url` | `curedhosting/plans`, `curedhosting/comparison-table` |
| Per plan (×4: `wp_starter`, `wp_business`, `linux_starter`, `linux_business`) | `name`, `tagline`, `price_note`, `is_wordpress`, `storage`, `bandwidth`, `databases`, `subdomains`, `emails`, `ftp`, `extras[]`, `best_for`, `cta_label`, `cta_url` | `curedhosting/plans` + `curedhosting/comparison-table` |
| FAQ (8 slots) | `faq[n].q`, `faq[n].a` | `curedhosting/faq` |

Plan card limit order is fixed by `curedhosting_plan_limit_fields()`:
storage, bandwidth, databases, subdomains, emails, FTP, then extras.

## Pattern-driven (copy sections)

Edit the PHP pattern file, or override visually in the Site Editor where the
section lives. All under `patterns/`:

| File | Purpose |
| --- | --- |
| `hero.php` | Hero promise + CTAs |
| `grand-opening.php` | Homepage promo wrapper |
| `why-cured.php` | Differentiators + infrastructure note |
| `plans-all.php` / `plans-wordpress.php` / `plans-linux.php` | Plan sections/pages |
| `comparison.php` | Comparison page body |
| `migration.php` | Inclusions / exclusions |
| `personal-support.php` | “A real person to call” |
| `local-tools.php` | MU-plugin / privacy messaging |
| `fair-use-summary.php` | Neighbor-protection summary + policy links |
| `onboarding-process.php` | Five-step path |
| `faq-section.php` | FAQ heading + block |
| `final-cta.php` | Closing band |
| `about-content.php` | About page |
| `grand-opening-landing.php` | Promo landing page (full terms) |
| `contact-form.php`, `onboarding-form.php` | Native forms |
| `policy-*.php` | Fair use, acceptable use, restricted plugins, privacy + terms placeholders |

## Editor-driven

- `parts/header.html` — brand + navigation (relative links to standard slugs).
- `parts/footer.html` — link columns, legal line.
- `templates/*` — page shells; `page-landing` = no-title shell for campaigns.

## Forms & submissions

- Endpoints: `admin-post.php` actions `ch_contact`, `ch_onboarding`.
- Storage: private posts, post type `ch_submission`, listed under
  **CuredHosting → Submissions** (view-only archive).
- Disable local storage (if wiring an external integration later):
  `add_filter( 'curedhosting_store_submissions', '__return_false' );`
- Notification address: Settings → `notify_email`.

## Hooks for developers

- `curedhosting_settings` (filter) — post-merge settings.
- `curedhosting_store_submissions` (filter) — storage toggle.
