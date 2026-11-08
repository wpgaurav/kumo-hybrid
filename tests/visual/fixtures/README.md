# Replaying the WordPress audit samples

These fixtures refuse a non-loopback WordPress URL. Use a disposable local install, never a production or customer site.

1. Install/activate the Kumo Hybrid source. The native-layout findings reproduce on released 0.3.0; Tabler checks require the local icon candidate.
2. Copy `local-fixture-plugin.php` into that install's `wp-content/mu-plugins/`. It provides controlled RSS data, a local synthetic avatar, test-only widget rendering, a loopback login helper, and blocks all outgoing mail.
3. Run `wp eval-file /path/to/seed.php`, then `wp eval-file /path/to/extend.php` with the local install's `--path`.
4. Open the locally published fixture pages: `/kumo-audit-blocks/`, `/kumo-audit-widgets/`, `/kumo-audit-comments/`, `/kumo-audit-block-comments/`, `/kumo-audit-comments-closed/`, and `/kumo-audit-password/`.
5. The case endpoint `/?kumo-widget-case=block-70&width=280` renders a native widget at a controlled sidebar width. The public release is never written by these scripts.

The captured run used `http://127.0.0.1:9467`. Update the runner URLs if your local address differs. Browser runners need Playwright and axe-core. `KUMO_AUDIT_PLAYWRIGHT` and `KUMO_AUDIT_BROWSER` may point to compatible installed runtimes.

`probes.cjs --assert` is intentionally red on the audited version: it checks a one-line search label, comment reflow, and dark calendar contrast. It also includes passing control cases. See `../data/probes.json` for the observed run. These are future regression guards, not a claim that the bugs have been fixed.

Run `restore.php` afterwards to restore the prior local widget/comment options and theme mods. Remove the test-only MU plugin and stop the task-owned loopback server. Fixture posts/media remain local for replay. The captured run sent no email and made no public-site writes.

RSS uses a controlled feed and avatars use local synthetic artwork; external feed reliability, Gravatar delivery, and screen-reader behavior were not certified. The installed WebKit engine could not be driven by the available Playwright protocol, so Safari is unverified.

For the PHP built-in server, use the supplied router and bind to loopback:

```sh
KUMO_AUDIT_WP_ROOT=/path/to/wordpress php -S 127.0.0.1:9467 -t /path/to/wordpress /path/to/fixtures/router.php
```
