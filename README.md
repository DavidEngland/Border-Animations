# Border Animations for WordPress

## 🎉 Version 2.0.0 - Advanced Particle Effects & Physics-Based Motion

A cutting-edge WordPress plugin for creating beautiful CSS border animations with realistic particle effects inspired by real-world motion physics. Features 11 distinct animation types including orbital mechanics, transportation simulations, and amusement park rides.

### 🚀 What's New in v2.0.0

- **11 Advanced Particle Effects** with physics-based motion
- **Real-World Motion Simulation** including planetary orbits, burning fuse, transportation
- **Amusement Park Effects** with ferris wheel and merry-go-round animations
- **Circular Border Support** for enhanced realism on rotating effects
- **WordPress Gutenberg Integration** with full block editor support
- **Professional Animation Quality** suitable for modern web design
- **Hardware-Accelerated Animations** for smooth 60fps performance

## Particle Effects Collection

### 🌟 Space & Celestial

- **Planetary Orbit**: Realistic orbital mechanics with elliptical paths and gravitational physics
- **Comet Tail**: Streaking particles with trailing effects and speed variations
- **Meteor Shower**: Multiple particles cascading with realistic trajectories

### 🔥 Fire & Energy

- **Burning Fuse**: Crackling sparks traveling along the border perimeter
- **Fireworks**: Explosive bursts with colorful particle dispersal
- **Lightning**: Electric energy bolts with branching effects

### 🚗 Transportation

- **Highway Traffic**: Vehicles moving at different speeds with realistic motion
- **Race Track**: High-speed racing with velocity-based particle trails
- **Train Journey**: Steady locomotive motion with passenger car effects

### 🎠 Amusement Park

- **Ferris Wheel**: Circular motion with passenger cars (circular borders)
- **Merry-go-Round**: Carousel horses with scaling effects (circular borders)

## Features

### Advanced Animation System

- **Physics-Based Motion**: Realistic particle behavior following natural laws
- **Circular Border Support**: Special handling for ferris wheel and merry-go-round effects
- **Hardware Acceleration**: GPU-optimized animations for smooth performance
- **Responsive Design**: Adapts to all screen sizes and device orientations
- **Accessibility Compliant**: Respects prefers-reduced-motion settings

### WordPress Integration

- **Gutenberg Blocks**: Native block editor support with live preview
- **Shortcode System**: Easy integration in posts, pages, and widgets
- **Theme Compatibility**: Works with any WordPress theme
- **Performance Optimized**: Minimal impact on page load times

## Usage

### Gutenberg Block Editor
Add the "Border Animation" block and choose from 11 particle effects:
- Real-time preview in the editor
- Customizable colors, speeds, and particle counts
- Responsive controls for different screen sizes

### Shortcodes

**Basic particle effect:**
```
[border_animation type="planetary-orbit" color="#0073e6"]Your content[/border_animation]
```

**Advanced configuration:**
```
[border_animation type="ferris-wheel" color="#ff6b35" particle-count="6" speed="slow"]Your content[/border_animation]
```

### CSS Classes
Apply effects directly with CSS classes:
- `.ba-planetary-orbit` - Realistic orbital mechanics
- `.ba-comet-tail` - Streaking comet effects
- `.ba-meteor-shower` - Multiple cascading particles
- `.ba-burning-fuse` - Crackling fuse animation
- `.ba-fireworks` - Explosive particle bursts
- `.ba-lightning` - Electric energy effects
- `.ba-highway-traffic` - Vehicle traffic simulation
- `.ba-race-track` - High-speed racing effects
- `.ba-train-journey` - Locomotive motion
- `.ba-ferris-wheel` - Circular amusement ride (circular borders)
- `.ba-merry-go-round` - Carousel animation (circular borders)

## Installation

1. **Upload & Activate:**
   - Upload to your `wp-content/plugins` directory
   - Activate in WordPress admin

2. **Start Creating:**
   - Add Border Animation blocks in the block editor
   - Use shortcodes in content
   - Apply CSS classes in your theme

## Technical Specifications

- **CSS3 Animations**: Hardware-accelerated transforms and keyframes
- **Modern Browser Support**: Chrome 70+, Firefox 70+, Safari 12+, Edge 79+
- **Performance**: 60fps animations with GPU acceleration
- **File Size**: Optimized CSS with minimal footprint
- **Accessibility**: WCAG 2.1 compliant with motion preferences

## Showcase & Demos

- **Comprehensive Demo**: `demos/particle-effects.html` - All 11 effects with live examples
- **Usage Examples**: `SHOWCASES.html` - Real-world implementation scenarios
- **WordPress Examples**: Available in block editor preview

## Development

### File Structure
- `border-animations.php` - Main plugin file
- `css/border-animations.css` - Core animation styles
- `blocks/border-animation/index.js` - Gutenberg block implementation
- `demos/particle-effects.html` - Comprehensive showcase

### Building Blocks
```bash
npm install
npm run build
```

## License

- **PHP Code**: GPLv2 or later
- **CSS/JavaScript**: MIT License
- **Compatible**: WordPress 5.0+ and PHP 7.4+

---

**Author:** David E. England, Ph.D.  
**Plugin URI:** https://davidengland.wordpress.com/border-animations  
**Version:** 2.0.0
