# Responsive browser-testing checklist

Breakpoints to hit: **320, 375, 414, 640, 768, 1024, 1280, 1440**, plus
tablet landscape and a zoomed (200%) desktop pass.

Every page type:

- [ ] Home, `/wordpress-hosting/`, `/linux-hosting/`, `/hosting-comparison/`,
      `/free-migration/`, `/grand-opening/`, `/about/`, `/contact/`,
      `/onboarding/`, one policy page, 404.

Layout:

- [ ] Header: nav collapses to the overlay menu below ~780px; logo + menu
      button don’t collide at 320px.
- [ ] Hero buttons wrap with spacing at 320px; no horizontal scroll.
- [ ] Plan cards: 4-up ≥1080px → 2-up tablet → 1-up phone; equal heights;
      CTA pinned to card bottom.
- [ ] Comparison table scrolls horizontally inside its rounded region on
      phones (page itself must not scroll sideways); region focusable.
- [ ] Migration/support/two-column sections stack cleanly; dark bands keep
      padding.
- [ ] Footer columns: 4 → 2 → 1; links ≥ 24px hit area.
- [ ] Forms: fields full-width; three-up number rows stack at ≤640px;
      consent tap target comfortable; error summary + banners readable.
- [ ] FAQ summaries wrap; +/- indicator never overlaps text.
- [ ] Onboarding questionnaire completes end-to-end on a phone (happy path
      and error path).

Orientation & misc:

- [ ] Tablet portrait/landscape: no clipped headings or overflow.
- [ ] Landscape phone (≈640×360): hero + nav usable.
- [ ] 200% browser zoom on desktop: no loss of content at 320px-equivalent.
- [ ] `prefers-reduced-motion` on: no transitions; behavior unchanged.
- [ ] Browsers: latest Chrome, Firefox, Safari (macOS/iOS), Edge; spot-check
      one older Safari if audience data suggests it.
