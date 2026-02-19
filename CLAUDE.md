# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

**Meraki** is a minimal WordPress theme built for 100% compatibility with Elementor (Free) and Elementor Pro. It has no build process — all files are plain PHP and CSS, ready to deploy directly.

- **WordPress**: 6.0+ required
- **PHP**: 7.4+ required
- **Text domain**: `meraki`
- **No npm, no Composer, no build step**

## Theme Architecture

### Elementor Conditional Rendering Pattern

Every template file (header, footer, single, page, archive) follows the same three-tier logic:

1. **Elementor Canvas** — Skip theme HTML entirely; Elementor controls the full page.
2. **Elementor Pro Theme Builder** — Render structural HTML; Theme Builder injects the section.
3. **Native fallback** — Traditional theme template with WordPress menus and widgets.

The key helper function is `meraki_elementor_has_location( $location )` defined in `inc/elementor-support.php`.

### File Responsibilities

| File | Role |
|------|------|
| `functions.php` | Theme setup, menu registration, sidebar, Elementor dependency check, lazy-loads `/inc/` on `plugins_loaded` |
| `inc/enqueue.php` | Enqueues Google Fonts (Inter) + `style.css`; dequeues on Canvas |
| `inc/elementor-support.php` | Theme Builder location registration, body classes, container width auto-config (1200px), Canvas style removal |
| `inc/elementor-globals.php` | Syncs theme colors (#2D6BE4, #1A1A2E, #FF6B35, #333333, #F8F9FA) into Elementor Pro Global Kit; injects CSS variables into `<head>` |
| `style.css` | Theme metadata header + CSS variables + base layout — no preprocessor |

### CSS Variables

Defined at `:root` in `style.css` and mirrored into Elementor's Global Kit:

```
--color-primary:   #2D6BE4
--color-secondary: #1A1A2E
--color-accent:    #FF6B35
--color-text:      #333333
--color-light:     #F8F9FA
--font-heading / --font-body: Inter (Google Fonts)
```

### Asset Versioning

In `inc/enqueue.php`, assets use `filemtime()` when `WP_DEBUG` is true (cache-busting during development) and the theme version string in production.

## Development Notes

- All PHP files guard with `defined('ABSPATH') || exit;`
- Use `esc_html()`, `esc_url()`, `esc_attr()` consistently — this pattern is already established throughout the theme
- The `/assets/css/`, `/assets/js/`, `/assets/fonts/`, `/assets/images/` directories exist as placeholders (`.gitkeep`); add actual assets there and enqueue via `inc/enqueue.php`
- Adding new Elementor Theme Builder locations: register them in `inc/elementor-support.php` using `elementor_theme_register_conditions` and add corresponding body classes to the `meraki_body_classes()` function in the same file
