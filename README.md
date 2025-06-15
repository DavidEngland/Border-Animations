# Border Animations for WordPress

A modern, lightweight WordPress plugin that adds customizable, accessible border animation utilities and a shortcode for use in blocks, content, and themes.

## Features

- Modern, accessible CSS animation utilities (no vendor prefixes)
- CSS custom properties for easy customization
- Utility classes for delay, duration, and iteration
- Accessibility: respects prefers-reduced-motion
- HTML demo file for all animation classes
- Includes `.bounce`, `.flash`, and many more
- `.wiggle` is included for compatibility, but has minimal visible effect in most browsers

## Usage

- See `css/animate-it-demo.html` for a live demo and usage examples.
- Add classes like `animated bounce` to any element.
- Use delay/duration utilities: `animated ai-delay-2 ai-duration-3`.
- Conic gradient, animated solid, and animated dashed border effects
- Utility classes for easy use in HTML, blocks, or theme templates
- Shortcode `[border_animation_demo]` for quick demos in posts/pages
- Modern CSS (conic-gradient, mask, keyframes) with fallbacks
- Accessible and responsive

## Usage

1. **Install & Activate:**
   - Upload to your `wp-content/plugins` directory and activate in WP admin.
2. **Add to Content:**
   - Use the shortcode: `[border_animation_demo type="conic"]Your content[/border_animation_demo]`
   - Or add classes to your HTML: `<div class="ba-animate ba-conic">Content</div>`
3. **Customize:**
   - Use `type` (conic, animated-solid, dashed), `color`, `width`, `radius` attributes in the shortcode.

## Development

- CSS: `css/border-animations.css`
- PHP: `border-animations.php`
- MIT License (CSS), GPLv2+ (PHP)

## License

- PHP: GPLv2 or later
- CSS: MIT

---

**Author:** Your Name
