# Design refinement: 0.2.0

Requested direction: improve the publishing and business theme with design-uish while keeping Kumo's design language.

## Decisions

- Preserve the neutral canvas, semantic light/dark colors, blue accent, sentence case, semibold hierarchy, and compact controls. Kumo's no-tracking convention takes priority over the generic guideline to tighten large headings.
- Use the existing WordPress PHP, core-block, and CSS architecture. Translate the skill's layout principles into the existing framework instead of introducing a second styling runtime.
- Bundle the official Inter variable font locally, including italic, `font-display: swap`, and the complete SIL Open Font License. The reading-font control keeps its existing system sans and serif choices and adds Inter as the default.
- Give the hero one primary action. Use the existing quiet treatment for the header action and the later contact action.
- Replace repetitive non-interactive service cards with a definition list and hairline dividers. All terms and descriptions remain editable through native Group and Paragraph blocks.
- Give the hero panel a recessed header, primary surface, and quiet footer. Closely nested surfaces derive their inner radius from the outer radius and border width.
- Reuse a shared gap and section rhythm. Keep the FAQ aligned with the page grid instead of narrowing it into an unrelated centered section.
- Keep article text at the established 18px size, improve its line height and heading spacing, and make tables use horizontal rules. Card excerpts and supporting mobile text use comfortable reading sizes.
- Keep desktop controls compact and provide 48px minimum targets for coarse pointers. Preserve native keyboard, focus, and no-JavaScript behavior.
- Keep dark-mode separation in borders and surfaces, with no default drop shadows. Preserve block-specific user color overrides.

## Guideline coverage

Loaded the design-uish index and its applicable rules for general markup, colors, typography, custom fonts, heading groups, section layout, landing pages, features, description lists, surfaces, radii, buttons, forms, navigation, headers, footers, interactivity, shadows, flex layout, responsiveness, prose, tables, badges, icons, SVG, images, avatars, pagination, dark mode, copy, and placeholders.

Rules that prescribe Tailwind syntax were mapped to equivalent native CSS and WordPress block settings. Existing theme icons and identity were retained. No remote placeholder artwork, invented testimonials, metrics, or new external UI services were added.

## Content preservation

Pattern updates affect new insertions. WordPress stores previously inserted patterns as page content; the theme does not rewrite those pages. Existing pattern markup continues to receive the shared styles. Only the disposable QA homepage was recomposed to show the new patterns. The earlier 0.1.0 installer remains available separately.

## Checks

The four patterns validate through WordPress's block parser. The full browser flow checks editor rendering, Customizer persistence, navigation, color mode, responsive layout, search, and pagination. Additional checks cover semantic service markup, local font loading, one primary homepage action, coarse-pointer targets, and compact mobile pagination. See `validation.md` for the complete scope and remaining release gates.
