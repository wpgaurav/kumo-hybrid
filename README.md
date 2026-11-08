# Kumo Hybrid

An independent WordPress theme inspired by Cloudflare's Kumo design system. Version 0.3.0 adds free lifetime update keys through FluentCart to the refined publishing and business theme.

PHP templates preserve the familiar Customizer, menus, and widgets. `theme.json` v3 supplies the block editor with matching colors, typography, spacing, and layout. The public site uses native HTML, CSS, and two small JavaScript files. It needs no React runtime, npm build, remote font, or required plugin.

## Install

1. In WordPress, open **Appearance → Themes → Add New → Upload Theme**.
2. Upload the direct-download ZIP from your account, install it, and activate Kumo Hybrid.
3. Open **Appearance → Customize → Kumo design** to set the site defaults.
4. For updates, open **Appearance → Kumo Hybrid License** and activate the free key issued at checkout. All theme features work without a key.
5. Assign menus under **Appearance → Menus**. Optional widget areas are Sidebar and Footer.

Requires WordPress 6.7+ and PHP 8.3+. Tested with WordPress 6.7.4 and 7.1.4 using PHP 8.3. Modern browsers must support CSS `light-dark()` and `color-mix()`.

Get the theme and a free key at [the product page](https://gauravtiwari.org/product/kumo-hybrid/). Try the [live demo](https://demo.gatilab.com/kumo-hybrid/). The source and clean build remain available on [GitHub](https://github.com/wpgaurav/kumo-hybrid). The direct build includes a lifetime update key for unlimited websites; see `distribution/README.md` for packaging and privacy details.

## Build a homepage

Create a page, select **Full width (no title)** in the page template control, and insert patterns from the **Kumo Hybrid** category:

- Business introduction with layered panel.
- Services with a clear hierarchy.
- Conversation call to action.
- Frequently asked questions.

Every pattern uses editable core blocks. Set the call-to-action button destination in the editor. The introduction links to `#approach` and `#services`, which are supplied by the matching patterns. When reusing a pattern twice, give the repeated section a unique HTML anchor and update its links. Set the page as the homepage in **Settings → Reading**, and select a separate page for posts if wanted.

The preview uses sample Northstar Studio content. Activation does not create pages, posts, menus, or any demo data.

## Customize

| Control | Behavior |
| --- | --- |
| Default color mode | Light, dark, or the visitor's device preference |
| Accent color | Shared editor/frontend accent with automatically contrasting button text |
| Reading width | 560–900px; default 720px |
| Site width | 960–1600px; default 1200px |
| Reading font | Locally bundled Inter, system sans serif, or system serif; headings and controls use Inter |
| Post collection | Card grid or list |
| Sidebar | Optional widgets on posts, archives, search, and the blog |
| Post metadata | Toggle date and author |
| Color mode switch | Toggle the visitor control in the header |
| Header button | Shown when both its label and URL are supplied |
| Footer text | Plain text; defaults to the year and site title |

The Customizer uses WordPress's standard refresh preview and Publish button. Individual block colors and sizes can override the site defaults. The Site Editor does not own the PHP header and footer. This is a hybrid theme, so it is managed through the Customizer and the content block editor.

The visitor's explicit light/dark choice is stored in `localStorage` under `kumo-hybrid-mode` and takes precedence over the site default on later visits. Clearing that browser key restores the default. The Customizer preview always follows its current site setting so you can see the change while editing. Storage failures fall back to a working switch for the current page. With JavaScript disabled, the configured default still applies and navigation remains available.

## Templates and extensions

- Default pages: title, featured image, reading-width content, and wide blocks.
- Full width (no title): header/footer with a wide canvas for page patterns.
- Canvas (content only): content with WordPress's required head, body, and footer hooks.
- Posts: date/author, tags, adjacent posts, WordPress comments, and password-protected content.
- Archives, search, blog pagination, and 404 use the normal main query.
- Core block styles: Layered panel, Recessed panel, Quiet button, Disclosure panel, and Checklist.

Use a child theme for PHP or CSS changes. The parent loads the active child `style.css` after its own assets. Normal template hierarchy overrides and WordPress hooks remain available. No global plugin styles are dequeued.

## Development and checks

No build step is required. Use a disposable WordPress install for tests:

```sh
wp theme activate kumo-hybrid
wp eval-file wp-content/themes/kumo-hybrid/tests/runtime.php
```

The runtime script checks Customizer registration and persistence, sanitization, shared settings, and pattern rendering. It restores the theme mods it changes. Browser validation also checked actual core-block serialization, navigation, color modes, and a Customizer save. See `docs/validation.md` for the tested scope and remaining release gates, and `docs/design-uish-refinement.md` for the 0.3.0 design decisions.

## License and provenance

Theme code and original patterns: GPL-2.0-or-later. Bundled Inter variable fonts: SIL Open Font License 1.1, included with the font files. Kumo-derived token concepts and values: upstream MIT terms retained in `THIRD-PARTY-NOTICES.md`. This theme is independent of Cloudflare and does not include its logo or React components.

Design references: [Kumo colors](https://kumo-ui.com/colors/), [Kumo guidance](https://kumo-ui.com/skill/), and [Layer Card](https://kumo-ui.com/components/layer-card/). Architecture: [WordPress theme.json](https://developer.wordpress.org/themes/global-settings-and-styles/introduction-to-theme-json/) and [Customizer API](https://developer.wordpress.org/themes/classic-themes/customize-api/).
