<?php
/*
Plugin Name: Border Animations
Description: Adds modern, customizable border animation utilities and shortcodes for use in WordPress content, blocks, and themes. Now featuring advanced particle effects inspired by real-world motion physics.
Version: 2.0.0
Author: David E. England, Ph.D.
Author URI: https://davidengland.wordpress.com/
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Enqueue CSS and JS for border animations and animate-it utilities
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'border-animations',
        plugin_dir_url(__FILE__) . 'css/border-animations.css',
        [],
        '2.0.0'
    );
    wp_enqueue_style(
        'border-animations-animate-it',
        plugin_dir_url(__FILE__) . 'css/animate-it.css',
        ['border-animations'],
        '2.0.0'
    );
    
    // Enqueue typewriter animation JavaScript
    wp_enqueue_script(
        'border-animations-typewriter',
        plugin_dir_url(__FILE__) . 'js/typewriter-animation.js',
        [],
        '2.0.0',
        true
    );
});

// Enhanced shortcode for demo usage with new slogan-inspired animations
add_shortcode('border_animation_demo', function($atts, $content = null) {
    $atts = shortcode_atts([
        'type' => 'conic',
        'color' => '#0073e6',
        'color_alt' => '#ac3033',
        'color_accent' => '#ffd700',
        'width' => '4px',
        'radius' => '1em',
        'class' => '',
    ], $atts);

    // Build custom CSS variables for enhanced styling
    $style = sprintf(
        '--ba-color:%s;--ba-color-alt:%s;--ba-color-accent:%s;--ba-width:%s;--ba-radius:%s;',
        esc_attr($atts['color']),
        esc_attr($atts['color_alt']),
        esc_attr($atts['color_accent']),
        esc_attr($atts['width']),
        esc_attr($atts['radius'])
    );

    // Validate animation type (security)
    $allowed_types = [
        'conic', 'sparkler', 'animated-solid', 'dashed',
        'typewriter', '3d', 'highlighted', 'glowing-enhanced'
    ];
    $type = in_array($atts['type'], $allowed_types) ? $atts['type'] : 'conic';

    $class = 'ba-animate ba-' . esc_attr($type);
    if (!empty($atts['class'])) {
        $class .= ' ' . esc_attr($atts['class']);
    }

    return sprintf(
        '<div class="%s" style="%s">%s</div>',
        esc_attr($class),
        esc_attr($style),
        do_shortcode($content)
    );
});
