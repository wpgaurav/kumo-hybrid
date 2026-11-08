# Tabler icons

All theme-owned glyphs use the outline SVG subset from the user-provided `~/icons` collection, identified by its metadata as Tabler 3.47.0. The selected files are bundled unmodified with the MIT license and a checksum manifest. No icon font or remote icon dependency is introduced.

`inc/icons.php` is the shared renderer. Header controls and the submenu script use the same source. Submenu data is supplied through WordPress’s script API so child themes do not depend on a new header-template fragment. Core Search keeps its native form attributes and Interactivity API behavior while using the Tabler magnifier. The checklist and styled Details block use SVG masks. RSS and native WordPress media-player glyphs use the same family without replacing their underlying behavior or labels.

Included in 0.3.1 with the native widget and comment fixes. The original 0.3.0 artifacts are retained unchanged.
