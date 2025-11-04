<?php
/**
 * Plugin Name: Patrick-Droppe
 * Description: A collection of custom widgets and shortcodes for WordPress
 * Version: 1.0.0
 * Author: Pratik Aryal
 * Author URI: https://aryalpratik.com.np
 * Text Domain: patrick-droppe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PATRICK_DROPPE_VERSION', '1.0.0');
define('PATRICK_DROPPE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PATRICK_DROPPE_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class PatrickDroppe {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Load widgets
        $this->load_widgets();
        
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Load all widget files
     */
    private function load_widgets() {
        $widgets_dir = PATRICK_DROPPE_PLUGIN_DIR . 'widgets/';
        
        // Auto-load all widget files
        if (is_dir($widgets_dir)) {
            $widget_files = glob($widgets_dir . '*.php');
            foreach ($widget_files as $file) {
                require_once $file;
            }
        }
    }
    
    /**
     * Enqueue plugin assets
     */
    public function enqueue_assets() {
        // Enqueue main plugin styles if needed
        wp_enqueue_style(
            'patrick-droppe-styles',
            PATRICK_DROPPE_PLUGIN_URL . 'assets/css/style.css',
            array(),
            PATRICK_DROPPE_VERSION
        );
    }
}

// Initialize the plugin
new PatrickDroppe();