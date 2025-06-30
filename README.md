# Border Animations for WordPress

A modern, lightweight WordPress plugin that adds sophisticated, slogan-inspired border animation utilities and shortcodes for professional WordPress sites.

## ✨ Enhanced Features (v1.5.0)

- **Slogan-Inspired Animations**: Professional effects modeled after successful slogan animations
- **Enhanced Visual Effects**: Multi-layer shadows, gradient backgrounds, shimmer overlays
- **Modern CSS**: Uses CSS custom properties, backdrop-filter, perspective transforms
- **8 Animation Types**: From subtle highlights to dramatic 3D effects
- **Professional Styling**: Elegant color schemes inspired by real estate branding
- **Accessibility Focus**: Respects prefers-reduced-motion and includes focus states
- **WordPress Integration**: Enhanced shortcode with security validation

## 🎨 Animation Types

### Enhanced Classic Animations
- **`ba-conic`** - Multi-color gradient border with smooth color transitions
- **`ba-sparkler`** - Rotating comet effect with shimmer overlay and glow
- **`ba-animated-solid`** - Multi-layer pulsating border with gradient background
- **`ba-dashed`** - Color-shifting dashed border with glow effects

### New Slogan-Inspired Animations
- **`ba-typewriter`** - Border draws progressively like typewriter text
- **`ba-3d`** - Layered shadow effect with perspective transform
- **`ba-highlighted`** - Professional card with shimmer overlay
- **`ba-glowing-enhanced`** - Multi-layer glow with brightness animation

## 🚀 Enhanced Usage

### WordPress Shortcode (Recommended)
```php
// Basic usage
[border_animation_demo type="highlighted"]Your content here[/border_animation_demo]

// Advanced usage with custom styling
[border_animation_demo 
  type="3d" 
  color="#002D5F" 
  color_alt="#ac3033" 
  color_accent="#ffd700"
  width="3px" 
  radius="1.5em"
]Professional Content[/border_animation_demo]
```

### Direct CSS Classes
```html
<div class="ba-animate ba-typewriter">Typewriter border effect</div>
<div class="ba-animate ba-glowing-enhanced">Enhanced glowing border</div>
```

### Custom Properties for Theming
```css
.my-custom-border {
  --ba-primary: #002D5F;
  --ba-accent: #0073e6;
  --ba-color-accent: #ffd700;
  --ba-radius: 1.5rem;
  --ba-width: 3px;
}
```

## Development

- CSS: `css/border-animations.css`
- PHP: `border-animations.php`
- MIT License (CSS), GPLv2+ (PHP)

## License

- PHP: GPLv2 or later
- CSS: MIT

---

**Author:** Dave England
