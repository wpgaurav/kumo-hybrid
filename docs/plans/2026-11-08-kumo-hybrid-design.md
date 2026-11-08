# Kumo Hybrid: design and implementation plan

Working name: Kumo Hybrid. Version: 0.1.0, an installable first iteration.
Audience assumption: publishing and business sites. No production deployment.

## Architecture decision

1. **PHP hybrid theme (selected):** native PHP templates and the Customizer, with theme.json v3, core blocks, patterns, and matching editor styles. Both requested editing experiences remain first-class.
2. Block theme: strongest Site Editor support, but puts site configuration in a different workflow from the requested Customizer.
3. React application inside a theme: direct Kumo components, but adds a frontend runtime and complicates normal WordPress rendering and plugins.

The selected approach adapts Kumo's visual language into native CSS and semantic HTML. It does not ship the Kumo React component runtime. This is an independent theme, with Cloudflare attribution and the upstream MIT notice for adapted design tokens. No Cloudflare logo or implied endorsement.

## Design

Use Kumo's canvas, base, recessed, hairline, control, brand, text, and status roles. Blue controls, neutral surfaces, a restrained orange detail, small rounded controls, and layered panels identify the system. Preserve sentence case and semibold hierarchy. Use the system sans-serif stack, 14px controls, and comfortable 18px article text. Larger reading text deliberately adapts Kumo's dense application typography to publishing.

Default light palette: canvas #fafafa, base #ffffff, recessed #f5f5f5, text #171717, subtle #626262, brand #2563eb. Dark mode reverses surfaces and text through the same semantic variables. Content stays left aligned, with a 720px reading measure and a 1200px wide measure.

```text
Site identity | Primary menu | Optional action | Color mode
----------------------------------------------------------
Page title / block content / optional editable hero pattern
----------------------------------------------------------
Content or post collection        Optional widget sidebar
----------------------------------------------------------
Footer widgets | Footer menu | Copyright
```

## Settings ownership

- Customizer: identity, logo, menus, widgets, color mode, accent, reading/wide widths, font choice, card/list archives, sidebar, post metadata, header action, and footer text.
- theme.json: shared semantic palette, typography, spacing, layout, borders, and core block defaults.
- Explicit block styles and editor-selected values override site defaults locally.
- Theme mods are sanitized both on save and when consumed. A wp_theme_json_data_theme filter maps shared settings into the editor and frontend. No duplicate options framework.

## First iteration

- PHP templates for posts, pages, blog, archives, search, comments, password-protected content, pagination, and 404.
- Full-width and canvas page templates.
- Core-block patterns for a business introduction, feature panels, call to action, and FAQ.
- Group, button, list, and details block styles.
- Responsive navigation with nested-menu disclosure, keyboard operation, skip link, visible focus, and RTL support.
- Light, dark, and system modes. Visitor preference takes precedence over the site's initial mode.
- No remote assets, third-party frontend runtime, telemetry, automatic demo-content insertion, or plugin-dependent blocks.

## Verification and delivery

Run PHP and JS syntax checks, theme.json schema validation, WordPress runtime tests, pattern parsing and editor validation, and browser checks at desktop and mobile widths. Check Customizer saves and editor palette synchronization, child-theme assets, navigation, search, comments, 404, and dark mode. Include an installable ZIP, source, preview image, usage guide, and an honest validation record. Full WordPress.org release review and broad plugin compatibility are later gates.

## References

- https://kumo-ui.com/colors/
- https://kumo-ui.com/skill/
- https://kumo-ui.com/components/layer-card/
- https://github.com/cloudflare/kumo (source reviewed at d8a668eec48af657f9f2090b692f9ee7bd98709e)
- https://developer.wordpress.org/themes/global-settings-and-styles/introduction-to-theme-json/
- https://developer.wordpress.org/themes/classic-themes/customize-api/
