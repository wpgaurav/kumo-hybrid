# Validation record

Checked November 8, 2026. This is version 0.1.0, an installable first iteration.

## Environment

- Disposable, loopback-only WordPress 7.1.4 and WordPress 6.7.4 installs.
- PHP 8.3.36 and the WordPress SQLite integration. No production data or site changes.
- Chromium desktop and mobile browser checks. Viewport widths: 320, 390, 768, 1200, and 1440px.
- Aside was used for the first browser interaction checks. Local Playwright was used for reproducible assertions and saved preview files because Aside's session filesystem was not available in the workspace. The dedicated Browser plugin was not available.

## Passed

| Check | Evidence |
| --- | --- |
| PHP syntax | All theme and runtime-test PHP files parse with PHP 8.3 |
| WordPress coding standards | PHPCS WordPress standard passes for shipped theme PHP; the development runtime script is excluded |
| JavaScript syntax | Both frontend scripts pass `node --check` |
| CSS syntax | All CSS files parse with PostCSS |
| theme.json | Validates against the official WordPress 6.7 schema |
| Minimum and current WP runtime | 33 checks pass on each of WordPress 6.7.4 and 7.1.4 |
| Hybrid behavior | Classic theme detection, native Customizer access, all 12 design controls |
| Shared settings | A real Customizer setting save changes the editor/frontend accent and width |
| Settings validation | Invalid colors and executable URLs rejected; dimensions clamped; non-scalar values handled |
| Extreme accents | Black and white accent choices retain readable links and button text in both modes; axe color contrast spot checks pass |
| Native blocks | All four patterns and their composed homepage validate using the actual block editor parser, with no invalid blocks |
| Editor appearance | Light and dark editor surfaces and button contrast checked; editor tokens also available to editor controls |
| Customizer UI | Design panel opens, footer save reaches the frontend, width changes reach the live refresh preview |
| Navigation | Mobile menu, nested disclosures, Escape behavior, focus return, resize behavior, and no-JavaScript navigation |
| Color modes | Light/dark changes and persistence across reload; editor follows site default |
| Responsive layout | No horizontal overflow at the tested widths; mobile header and wide pattern layout inspected |
| Template routes | Home, blog, paginated blog, category archive, post, page, search, empty search, and HTTP 404 |
| Native comments | Published comment and comment form render on a post |
| Widgets and layout | Sidebar content renders; list mode remains one column when the sidebar is active |
| Child theme | Test child activates and its stylesheet applies after the parent assets |
| RTL | Mobile RTL layout spot check has no horizontal overflow |
| Accessibility automation | axe-core WCAG A/AA checks report zero violations on light and dark demo homepages |
| Runtime errors | No JavaScript page errors in the core browser flow; no theme errors in the WordPress debug log |
| Translation | POT generated with WP-CLI; no remotely loaded fonts or image dependencies |

## Visual review and corrections

Wide patterns initially inherited a narrow child constraint, and the mode switch wrapped to a second header row on small screens. The patterns now use flow layout inside their wide containers, and the compact header uses a three-column grid. The editor's width rules now apply only to root blocks so nested columns can use the available width. Editor dark mode uses a style attached to the shared token stylesheet, which is loaded inside the editor iframe. List mode also retains one column when a sidebar is enabled.

Kumo's layered panels, neutral surfaces, blue controls, sentence case, and semantic roles are adapted to a publishing theme. Article text is intentionally larger than Kumo's dense application text. No React component bundle or Cloudflare identity assets are included.

## Before a public release

This is not a WordPress.org submission or a completed compatibility certification. Complete Theme Check/review requirements, a manual screen-reader pass, broader keyboard testing, Safari/Firefox testing, longer translated-content checks, and compatibility testing with the specific forms, commerce, SEO, caching, and page-builder plugins the target sites need. No WooCommerce templates or Site Editor template editing are claimed in this version. Measured production performance and Core Web Vitals remain site-dependent and were not benchmarked here.

The source contains no demo installer. Demo content exists only in the disposable test site. The theme does not activate plugins, modify existing content, or make external requests on activation.
