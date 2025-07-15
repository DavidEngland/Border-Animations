<?php

namespace Reia\BorderAnimations;

/**
 * WordPress Plugin Wrapper for Border Animation Builder
 * 
 * Integrates the Border Animation Builder with WordPress, providing
 * shortcodes, admin interface, and WordPress-specific functionality.
 * 
 * @package Reia\BorderAnimations
 * @version 2.0.0
 * @author REIA Development Team
 * @license MIT
 */
class WordPressPlugin
{
    /**
     * Builder instance
     * 
     * @var BorderAnimationBuilder
     */
    private $builder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->builder = new BorderAnimationBuilder();
        $this->init();
    }

    /**
     * Initialize WordPress integration
     * 
     * @return void
     */
    private function init()
    {
        // Register shortcodes
        add_shortcode('border_animation_builder', [$this, 'renderShortcode']);
        add_shortcode('border_animation', [$this, 'renderAnimationShortcode']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'addAdminMenu']);
        
        // Register AJAX handlers
        add_action('wp_ajax_generate_border_animation', [$this, 'ajaxGenerateAnimation']);
        add_action('wp_ajax_nopriv_generate_border_animation', [$this, 'ajaxGenerateAnimation']);
        
        // Add meta boxes for post/page editor
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
        add_action('save_post', [$this, 'saveMetaBoxData']);
        
        // Register block for Gutenberg
        add_action('init', [$this, 'registerBlocks']);
    }

    /**
     * Render builder shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Generated HTML
     */
    public function renderShortcode($atts): string
    {
        $atts = shortcode_atts([
            'theme' => 'default',
            'show_preview' => 'true',
            'show_code' => 'true',
            'show_presets' => 'true',
            'show_advanced' => 'true'
        ], $atts);

        $config = [
            'theme' => $atts['theme'],
            'showPreview' => $atts['show_preview'] === 'true',
            'showCode' => $atts['show_code'] === 'true',
            'showPresets' => $atts['show_presets'] === 'true',
            'showAdvanced' => $atts['show_advanced'] === 'true'
        ];

        $builder = new BorderAnimationBuilder($config);
        
        return '<div class="border-animation-shortcode">' . 
               $builder->renderHTML() . 
               $this->renderJavaScript() . 
               '</div>';
    }

    /**
     * Render animation shortcode
     * 
     * @param array $atts Shortcode attributes
     * @param string $content Shortcode content
     * @return string Generated HTML
     */
    public function renderAnimationShortcode($atts, $content = ''): string
    {
        $atts = shortcode_atts([
            'type' => 'conic',
            'color' => '#0073e6',
            'secondary_color' => '#ac3033',
            'width' => '4px',
            'radius' => '1em',
            'speed' => 'normal',
            'direction' => 'clockwise',
            'class' => ''
        ], $atts);

        $options = [
            'color' => $atts['color'],
            'secondaryColor' => $atts['secondary_color'],
            'width' => $atts['width'],
            'radius' => $atts['radius'],
            'speed' => $atts['speed'],
            'direction' => $atts['direction']
        ];

        $css = $this->builder->generateCSS($atts['type'], $options);
        $className = 'ba-' . esc_attr($atts['type']);
        $extraClass = !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';

        return sprintf(
            '<style>%s</style><div class="%s%s">%s</div>',
            $css,
            $className,
            $extraClass,
            do_shortcode($content)
        );
    }

    /**
     * Add admin menu
     * 
     * @return void
     */
    public function addAdminMenu()
    {
        add_management_page(
            __('Border Animation Builder', 'border-animations-enhanced'),
            __('Border Animations', 'border-animations-enhanced'),
            'manage_options',
            'border-animation-builder',
            [$this, 'renderAdminPage']
        );
    }

    /**
     * Render admin page
     * 
     * @return void
     */
    public function renderAdminPage()
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Border Animation Builder', 'border-animations-enhanced'); ?></h1>
            <p><?php _e('Create beautiful CSS border animations with live preview and code generation.', 'border-animations-enhanced'); ?></p>
            
            <div class="border-animation-admin">
                <?php echo $this->builder->renderHTML(); ?>
                <?php echo $this->renderJavaScript(); ?>
            </div>
            
            <div class="border-animation-usage">
                <h2><?php _e('Usage Instructions', 'border-animations-enhanced'); ?></h2>
                
                <h3><?php _e('Shortcodes', 'border-animations-enhanced'); ?></h3>
                <p><strong><?php _e('Animation Builder:', 'border-animations-enhanced'); ?></strong></p>
                <code>[border_animation_builder]</code>
                
                <p><strong><?php _e('Single Animation:', 'border-animations-enhanced'); ?></strong></p>
                <code>[border_animation type="conic" color="#0073e6" width="4px"]Your content[/border_animation]</code>
                
                <h3><?php _e('CSS Classes', 'border-animations-enhanced'); ?></h3>
                <p><?php _e('You can also apply animations directly with CSS classes:', 'border-animations-enhanced'); ?></p>
                <ul>
                    <li><code>.ba-conic</code> - Conic gradient animation</li>
                    <li><code>.ba-sparkler</code> - Sparkler effect</li>
                    <li><code>.ba-neon</code> - Neon glow effect</li>
                    <li><code>.ba-gradient</code> - Gradient animation</li>
                    <li><code>.ba-dashed</code> - Animated dashed border</li>
                    <li><code>.ba-wave</code> - Wave effect</li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Handle AJAX animation generation
     * 
     * @return void
     */
    public function ajaxGenerateAnimation()
    {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'border_animations_nonce')) {
            wp_die(__('Security check failed', 'border-animations-enhanced'));
        }

        $animation = sanitize_text_field($_POST['animation'] ?? 'conic');
        $options = [
            'color' => sanitize_hex_color($_POST['color'] ?? '#0073e6'),
            'secondaryColor' => sanitize_hex_color($_POST['secondary_color'] ?? '#ac3033'),
            'width' => sanitize_text_field($_POST['width'] ?? '4px'),
            'radius' => sanitize_text_field($_POST['radius'] ?? '1em'),
            'speed' => sanitize_text_field($_POST['speed'] ?? 'normal'),
            'direction' => sanitize_text_field($_POST['direction'] ?? 'clockwise')
        ];

        $css = $this->builder->generateCSS($animation, $options);
        $html = sprintf('<div class="ba-%s">Preview</div>', esc_attr($animation));

        wp_send_json_success([
            'css' => $css,
            'html' => $html,
            'animation' => $animation,
            'options' => $options
        ]);
    }

    /**
     * Add meta boxes
     * 
     * @return void
     */
    public function addMetaBoxes()
    {
        $post_types = ['post', 'page'];
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'border-animation-settings',
                __('Border Animation Settings', 'border-animations-enhanced'),
                [$this, 'renderMetaBox'],
                $post_type,
                'side',
                'default'
            );
        }
    }

    /**
     * Render meta box
     * 
     * @param \WP_Post $post Post object
     * @return void
     */
    public function renderMetaBox($post)
    {
        wp_nonce_field('border_animation_meta_box', 'border_animation_meta_box_nonce');
        
        $animation = get_post_meta($post->ID, '_border_animation_type', true);
        $color = get_post_meta($post->ID, '_border_animation_color', true) ?: '#0073e6';
        $width = get_post_meta($post->ID, '_border_animation_width', true) ?: '4px';
        
        ?>
        <p>
            <label for="border_animation_type"><?php _e('Animation Type:', 'border-animations-enhanced'); ?></label>
            <select id="border_animation_type" name="border_animation_type">
                <option value=""><?php _e('None', 'border-animations-enhanced'); ?></option>
                <?php foreach ($this->builder->getAnimations() as $anim): ?>
                    <option value="<?php echo esc_attr($anim); ?>" <?php selected($animation, $anim); ?>>
                        <?php echo esc_html(ucfirst($anim)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="border_animation_color"><?php _e('Color:', 'border-animations-enhanced'); ?></label>
            <input type="color" id="border_animation_color" name="border_animation_color" value="<?php echo esc_attr($color); ?>">
        </p>
        
        <p>
            <label for="border_animation_width"><?php _e('Width:', 'border-animations-enhanced'); ?></label>
            <input type="text" id="border_animation_width" name="border_animation_width" value="<?php echo esc_attr($width); ?>">
        </p>
        <?php
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id Post ID
     * @return void
     */
    public function saveMetaBoxData($post_id)
    {
        if (!isset($_POST['border_animation_meta_box_nonce']) || 
            !wp_verify_nonce($_POST['border_animation_meta_box_nonce'], 'border_animation_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            'border_animation_type' => '_border_animation_type',
            'border_animation_color' => '_border_animation_color',
            'border_animation_width' => '_border_animation_width'
        ];

        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }

    /**
     * Register Gutenberg blocks
     * 
     * @return void
     */
    public function registerBlocks()
    {
        if (!function_exists('register_block_type')) {
            return;
        }

        // Register Border Animation block
        register_block_type(BORDER_ANIMATIONS_PLUGIN_DIR . 'blocks/border-animation');

        // Register Animation Builder block
        register_block_type(BORDER_ANIMATIONS_PLUGIN_DIR . 'blocks/animation-builder');

        // Enqueue block editor assets
        add_action('enqueue_block_editor_assets', [$this, 'enqueueBlockEditorAssets']);
    }

    /**
     * Enqueue block editor assets
     * 
     * @return void
     */
    public function enqueueBlockEditorAssets()
    {
        // Enqueue the main builder CSS for use in blocks
        wp_enqueue_style(
            'border-animations-blocks',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/css/border-animation-builder.css',
            [],
            BORDER_ANIMATIONS_VERSION
        );

        // Enqueue the main builder JavaScript for use in blocks
        wp_enqueue_script(
            'border-animations-blocks',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/js/border-animation-builder.js',
            ['wp-blocks', 'wp-element', 'wp-editor'],
            BORDER_ANIMATIONS_VERSION,
            true
        );

        // Localize script for block editor
        wp_localize_script('border-animations-blocks', 'borderAnimationsBlocks', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('border_animations_nonce'),
            'pluginUrl' => BORDER_ANIMATIONS_PLUGIN_URL,
            'version' => BORDER_ANIMATIONS_VERSION
        ]);
    }

    /**
     * Render JavaScript for the builder
     * 
     * @return string JavaScript code
     */
    private function renderJavaScript(): string
    {
        $js_file = BORDER_ANIMATIONS_PLUGIN_DIR . 'assets/js/border-animation-builder.js';
        
        if (!file_exists($js_file)) {
            return '<script>console.warn("Border Animation Builder JS file not found");</script>';
        }
        
        $js = '<script type="text/javascript">';
        $js .= file_get_contents($js_file);
        $js .= '</script>';
        
        return $js;
    }

    /**
     * Get builder instance
     * 
     * @return BorderAnimationBuilder
     */
    public function getBuilder(): BorderAnimationBuilder
    {
        return $this->builder;
    }
}
