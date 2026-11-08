# Native WordPress visual regressions

Use a disposable loopback WordPress install. Copy `fixtures/local-fixture-plugin.php` to its MU plugins directory; it blocks mail and supplies controlled fixtures. Run `fixtures/seed.php`, `fixtures/extend.php`, and `author-overrides.php` through WP-CLI. The supplied fixtures intentionally refuse public domains.

Run `regression.cjs` with Playwright to check all seven audited behaviors. `author-overrides.cjs` checks that explicit block colors, fluid font presets, borders, and heading spacing remain stronger than contextual theme defaults. Set `KUMO_AUDIT_PLAYWRIGHT`, `KUMO_AUDIT_BROWSER`, and `KUMO_AUDIT_REPORT` as needed for an existing runtime.

The 0.3.1 release was tested red against the unfixed 0.3.0 layouts and green after the changes. The suite lives here so narrow search fields and deeper comments cannot be omitted from future release checks. No screenshot-only test is represented as a substitute for native form and widget interaction testing.

Restore local options with `fixtures/restore.php`, remove the test-only MU plugin, and stop the loopback server when finished.
