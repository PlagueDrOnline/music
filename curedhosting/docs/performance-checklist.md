# Performance checklist (Core Web Vitals)

Built-in:

- [x] No remote fonts — local system serif/sans stacks only (theme.json).
- [x] No remote scripts/styles/CDNs; no tracking, no pixels, no iframes.
- [x] One small front-end stylesheet (component CSS) + core block CSS.
- [x] One ~1 KB deferred script (`strategy => defer`, footer).
- [x] Minimal DOM: patterns use core group/columns; no nested wrapper soup.
- [x] Images: only the logo (small PNG) and optional user content;
      WordPress lazy-loads below-fold images by default.
- [x] Fluid type via `clamp()` — no JS resizing, no layout shift from fonts
      (system fonts render instantly; FOUT/CLS ≈ 0).
- [x] No countdown timers, carousels, or animation loops; transitions are
      ≤150ms color fades, disabled under reduced motion.
- [x] No webfonts means no `font-display` swaps and no render blocking.
- [x] Block render callbacks are O(number of plans) option reads; settings
      fetched once per request per call site (consider object caching on busy
      installs).

Verify before launch (Local or staging):

- [ ] Lighthouse ≥ 95 Performance on home at mid-tier mobile.
- [ ] LCP element is the hero heading (text, not image).
- [ ] CLS ≈ 0 (no font/layout shift).
- [ ] Total transferred < ~100 KB uncompressed on a content page.
- [ ] `wp-content` requests on home: theme.css, theme.js, logo only.
