<?php
/*
Plugin Name: Border Animations
Description: Adds modern, customizable border animation utilities and shortcodes for use in WordPress content, blocks, and themes.
Version: 1.0.0
Author: David E. England, Ph.D.
Author URI: https://davidengland.wordpress.com/
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Enqueue CSS for border animations and animate-it utilities
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'border-animations',
        plugin_dir_url(__FILE__) . 'css/border-animations.css',
        [],
        '1.0.0'
    );
    wp_enqueue_style(
        'border-animations-animate-it',
        plugin_dir_url(__FILE__) . 'css/animate-it.css',
        ['border-animations'],
        '1.0.0'
    );
});

// Optional: Add a shortcode for demo usage
add_shortcode('border_animation_demo', function($atts, $content = null) {
    $atts = shortcode_atts([
        'type' => 'conic',
        'color' => '#0073e6',
        'width' => '4px',
        'radius' => '1em',
    ], $atts);
    $style = sprintf(
        'border-width:%s;border-radius:%s;',
        esc_attr($atts['width']),
        esc_attr($atts['radius'])
    );
    $class = 'ba-animate ba-' . esc_attr($atts['type']);
    return '<div class="' . $class . '" style="' . $style . '">' . do_shortcode($content) . '</div>';
});
