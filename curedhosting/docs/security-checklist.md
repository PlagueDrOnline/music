# Security checklist

Code hygiene:

- [x] Output escaped everywhere (`esc_html`, `esc_attr`, `esc_url`,
      `esc_textarea`, `wp_kses` for the one structured-HTML value).
- [x] Input sanitized at the boundary: Settings API `sanitize_callback`
      per field type; form fields sanitized per schema (whitelisted options
      for radios/selects, `sanitize_email`, length caps).
- [x] No `eval`, no `extract`, no error suppression, no dynamic includes
      from user input; `defined('ABSPATH') || exit;` in every PHP file.
- [x] Nonces on every POST path (settings via Settings API, forms via
      `ch_{type}_form`, page creation via `ch_setup_pages`).
- [x] Capability checks: `manage_options` on the settings page, the page
      creation action, and notices.

Forms & data:

- [x] Honeypot + per-IP salted rate limit (4/30 min; raw IP never stored —
      HMAC-SHA256 with `wp_salt('auth')`).
- [x] PRG pattern: handlers never echo; always `wp_safe_redirect` + `exit`.
- [x] Submissions stored as **private** posts; CPT `create_posts` = `do_not_allow`;
      REST exposure off; stored text is pre-sanitized.
- [x] Consent fields required and explicit; notification address configurable,
      never hardcoded; no third-party endpoints contacted.
- [x] Error transients consumed once and expire in 1 h.

Theme surface:

- [x] No bundled plugins, frameworks, or premium/nulled code.
- [x] `wp_kses_post` avoided on wide inputs; tight allowlists instead.
- [x] Logo sideload only from a file inside the theme directory at activation,
      guarded by `file_exists` + WP sideload API.
- [x] No `add_query_arg` echoes without `esc_url`; URLs resolved through
      `curedhosting_resolve_url()` (relative→`home_url`, then `esc_url`).

Operational (before launch, on the real site — out of this theme’s scope):

- [ ] Keep WP + host stack patched; enforce HTTPS; strong admin auth.
- [ ] Review the two placeholder policies and replace with final legal text.
- [ ] If form mail becomes business-critical, add WP Mail SMTP with app
      credentials stored by that plugin (not by this theme).
