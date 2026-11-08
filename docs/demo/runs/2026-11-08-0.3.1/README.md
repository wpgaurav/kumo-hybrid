# Kumo Hybrid demo update, 0.3.1

Target: https://demo.gatilab.com/kumo-hybrid/, blog 23 only. Source: the licensed ZIP from GitHub tag v0.3.1, matching the customer R2 artifact. The installed file tree matches all 61 packaged files. Other network sites do not use this theme.

New examples: Components (38), classic Comments (39), core Comments blocks (40), and native Widgets (41). Theme guide (9) links the examples, and menu 3 contains child links. Eighteen registered widget classes use ordinary widget options and a demo-only shortcode. The helper supplies only a narrow demonstration grid and a comment-submission guard; all component styling comes from the theme. Silent audio/video samples demonstrate the native controls.

The helper is a normal plugin activated only for blog 23. It rejects submissions to the two synthetic discussions before storage. Reply/Cancel and pagination remain usable. The other existing page/post bodies, Customizer settings, assigned widgets, and noindex setting were preserved.

The fixture media ZIP is assembled from the two media samples in `tests/visual/fixtures/assets/`. `seed.php` expects it at `/tmp/kumo-031-demo-assets.zip`. Do not run this script on another site.

Private before-state snapshots remain outside git in the release workspace (`work/release-kumo-0.3.1/demo-before.json` and `product-before.json`). For rollback, inspect fresh state, switch only blog 23 to the retained 0.3.0 artifact if authorized, and restore only this demo’s recorded content/options. Remove newly owned examples and tags only by their manifest IDs. Preserve all unrelated network data and code. The previous FluentCart row 547 and immutable R2 object remain available.
