<?php
/**
 * Plugin Name: Border Animations - Enhanced
 * Plugin URI: https://github.com/DavidEngland/border-animations
 * Description: Modern, interactive border animation builder with live preview, code generation, and comprehensive animation effects. Developed by Real Estate Intelligence Agency (REIA).
 * Version: 2.0.0
 * Author: David England (REIA - Real Estate Intelligence Agency)
 * Author URI: https://github.com/DavidEngland
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: border-animations-enhanced
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 * 
 * @package BorderAnimationsEnhanced
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
if (!defined('BORDER_ANIMATIONS_VERSION')) {
    define('BORDER_ANIMATIONS_VERSION', '2.0.0');
}
if (!defined('BORDER_ANIMATIONS_PLUGIN_DIR')) {
    define('BORDER_ANIMATIONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('BORDER_ANIMATIONS_PLUGIN_URL')) {
    define('BORDER_ANIMATIONS_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('BORDER_ANIMATIONS_PLUGIN_FILE')) {
    define('BORDER_ANIMATIONS_PLUGIN_FILE', __FILE__);
}

// Load Composer autoloader if it exists
if (file_exists(BORDER_ANIMATIONS_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once BORDER_ANIMATIONS_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    // Fallback: manually load classes
    require_once BORDER_ANIMATIONS_PLUGIN_DIR . 'src/BorderAnimationBuilder.php';
    require_once BORDER_ANIMATIONS_PLUGIN_DIR . 'src/WordPressPlugin.php';
}

use Reia\BorderAnimations\WordPressPlugin;

/**
 * Main plugin class for enhanced border animations
 */
class BorderAnimationsEnhanced
{
    /**
     * Plugin instance
     * 
     * @var BorderAnimationsEnhanced
     */
    private static $instance = null;

    /**
     * WordPress plugin instance
     * 
     * @var WordPressPlugin
     */
    private $plugin;

    /**
     * Get plugin instance
     * 
     * @return BorderAnimationsEnhanced
     */
    public static function getInstance(): BorderAnimationsEnhanced
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Initialize plugin
     * 
     * @return void
     */
    private function init()
    {
        // Initialize WordPress plugin
        $this->plugin = new WordPressPlugin();
        
        // Register hooks
        add_action('init', [$this, 'loadTextDomain']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        
        // Activation and deactivation hooks
        register_activation_hook(BORDER_ANIMATIONS_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(BORDER_ANIMATIONS_PLUGIN_FILE, [$this, 'deactivate']);
    }

    /**
     * Load text domain for translations
     * 
     * @return void
     */
    public function loadTextDomain()
    {
        load_plugin_textdomain(
            'border-animations-enhanced',
            false,
            dirname(plugin_basename(BORDER_ANIMATIONS_PLUGIN_FILE)) . '/languages'
        );
    }

    /**
     * Enqueue frontend assets
     * 
     * @return void
     */
    public function enqueueAssets()
    {
        wp_enqueue_style(
            'border-animations-enhanced',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/css/border-animation-builder.css',
            [],
            BORDER_ANIMATIONS_VERSION
        );

        wp_enqueue_script(
            'border-animations-enhanced',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/js/border-animation-builder.js',
            ['jquery'],
            BORDER_ANIMATIONS_VERSION,
            true
        );

        // Localize script for AJAX
        wp_localize_script('border-animations-enhanced', 'borderAnimations', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('border_animations_nonce'),
            'version' => BORDER_ANIMATIONS_VERSION
        ]);
    }

    /**
     * Enqueue admin assets
     * 
     * @return void
     */
    public function enqueueAdminAssets()
    {
        wp_enqueue_style(
            'border-animations-admin',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/css/border-animation-builder.css',
            [],
            BORDER_ANIMATIONS_VERSION
        );

        wp_enqueue_script(
            'border-animations-admin',
            BORDER_ANIMATIONS_PLUGIN_URL . 'assets/js/border-animation-builder.js',
            ['jquery', 'wp-color-picker'],
            BORDER_ANIMATIONS_VERSION,
            true
        );

        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Plugin activation
     * 
     * @return void
     */
    public function activate()
    {
        // Set default options
        $default_options = [
            'enabled' => true,
            'load_frontend_css' => true,
            'load_frontend_js' => true,
            'default_animation' => 'conic',
            'default_color' => '#0073e6',
            'default_width' => '4px',
            'default_radius' => '1em'
        ];

        add_option('border_animations_options', $default_options);
        
        // Create database tables if needed
        $this->createTables();
        
        // Clear any caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }

    /**
     * Plugin deactivation
     * 
     * @return void
     */
    public function deactivate()
    {
        // Clear any caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }

    /**
     * Create database tables
     * 
     * @return void
     */
    private function createTables()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'border_animations';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            animation_type varchar(50) NOT NULL,
            settings longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Get WordPress plugin instance
     * 
     * @return WordPressPlugin
     */
    public function getPlugin(): WordPressPlugin
    {
        return $this->plugin;
    }

    /**
     * Check WordPress version compatibility
     * 
     * @return bool
     */
    private function checkWordPressVersion(): bool
    {
        global $wp_version;
        return version_compare($wp_version, '5.0', '>=');
    }

    /**
     * Check if plugin is compatible
     * 
     * @return bool
     */
    public function isCompatible(): bool
    {
        return $this->checkWordPressVersion() && version_compare(PHP_VERSION, '7.4', '>=');
    }
}

// Initialize plugin
function border_animations_enhanced_init() {
    return BorderAnimationsEnhanced::getInstance();
}

// Start the plugin
border_animations_enhanced_init();

/**
 * Helper function to get the builder instance
 * 
 * @return \Reia\BorderAnimations\BorderAnimationBuilder
 */
function border_animations_builder() {
    $main = BorderAnimationsEnhanced::getInstance();
    return $main->getPlugin()->getBuilder();
}

/**
 * Helper function to render the border animation builder
 * 
 * @param array $options Options for the builder
 * @return string Generated HTML
 */
function border_animations_render($options = []) {
    $builder = new \Reia\BorderAnimations\BorderAnimationBuilder($options);
    return $builder->renderHTML();
}

/**
 * Helper function to generate CSS for a border animation
 * 
 * @param string $animation Animation type
 * @param array $options Animation options
 * @return string Generated CSS
 */
function border_animations_generate_css($animation, $options = []) {
    $builder = new \Reia\BorderAnimations\BorderAnimationBuilder();
    return $builder->generateCSS($animation, $options);
}

/**
 * Helper function to check if the plugin is active and working
 * 
 * @return bool
 */
function border_animations_is_active() {
    return class_exists('BorderAnimationsEnhanced') && 
           BorderAnimationsEnhanced::getInstance()->isCompatible();
}
