# Accessibility checklist (WCAG 2.2 AA target)

Test with keyboard + screen reader (NVDA/VoiceOver) before launch.

Implemented in theme:

- [x] Skip link (`wp_body_open`) targeting the main group (`#ch-main` on every template).
- [x] Semantic landmarks: `header`, `main`, `footer` via template parts; single `h1` per page.
- [x] Visible focus: 3px gold outline on light, gold on dark bands (`theme.css` §1).
- [x] Keyboard: nav block overlay, native `details/summary` FAQ, native form controls.
- [x] Reduced motion: global `prefers-reduced-motion` kill-switch (`theme.css` §14).
- [x] Contrast: body ink #1C2A22 on cream #F7F3E9 (~13:1); soft ink #46564C (~6.9:1);
      cream on pine #164733 (~10.5:1); deep-gold small text #7A5A1E on cream (~5.6:1);
      error #8F2B2B on cream (~7:1); ink on gold buttons (~6.2:1).
- [x] Forms: every input labeled; required marked visually + `aria-required`;
      field errors via `aria-invalid` + `aria-describedby` + role=alert summary
      that receives focus (theme.js).
- [x] Consent checkboxes are explicit, unchecked by default.
- [x] Honeypot field removed from a11y tree (`aria-hidden`, `tabindex=-1`).
- [x] Comparison table: `caption` (screen-reader), `scope` on th, scroll region
      with `role=region` + `aria-label` + `tabindex=0`.
- [x] Logo has `<title>` (SVG) / alt via custom-logo default; `shouldSyncIcon=false`.
- [x] No content conveyed by color alone (✓/– lists include text).
- [x] Touch targets: buttons ≥ 44px effective (0.8rem padding + line-height).

Manual checks before launch:

- [ ] Tab order matches visual order on home, plans, both forms.
- [ ] FAQ answers readable by screen reader when opened via keyboard.
- [ ] Mobile nav overlay traps/returns focus correctly.
- [ ] 200% zoom and 320px width: no horizontal scrolling except the
      comparison table’s intentional scroll region.
- [ ] Success/error banners announced (`role=status` / `role=alert`).
