<?php
/**
 * Plugin Name: Patrick-Droppe
 * Description: A collection of custom widgets and shortcodes for WordPress
 * Version: 1.1.1
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
        
        // Register AJAX handlers
        add_action('wp_ajax_load_more_posts', array($this, 'load_more_posts'));
        add_action('wp_ajax_nopriv_load_more_posts', array($this, 'load_more_posts'));
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
        // Enqueue main plugin styles
        wp_enqueue_style(
            'patrick-droppe-styles',
            PATRICK_DROPPE_PLUGIN_URL . 'assets/css/style.css',
            array(),
            PATRICK_DROPPE_VERSION
        );
        
        // Enqueue main plugin scripts
        wp_enqueue_script(
            'patrick-droppe-scripts',
            PATRICK_DROPPE_PLUGIN_URL . 'assets/js/script.js',
            array('jquery'),
            PATRICK_DROPPE_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('patrick-droppe-scripts', 'patrick_droppe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('patrick_droppe_nonce'),
            'debug' => defined('WP_DEBUG') && WP_DEBUG
        ));
    }
    
    /**
     * AJAX handler for loading more posts
     */
    public function load_more_posts() {
        // Check if nonce is set
        if (!isset($_POST['nonce'])) {
            wp_die('Nonce not provided');
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'patrick_droppe_nonce')) {
            wp_die('Security check failed');
        }
        
        $layout = sanitize_text_field($_POST['layout']);
        $category = sanitize_text_field($_POST['category']);
        $offset = intval($_POST['offset']);
        $posts_per_load = intval($_POST['posts_per_load']);
        $exclude_post = isset($_POST['exclude_post']) ? intval($_POST['exclude_post']) : 0;
        
        // Query arguments
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $posts_per_load,
            'post_status' => 'publish',
            'offset' => $offset,
        );
        
        // Exclude specific post if specified
        if ($exclude_post > 0) {
            $args['post__not_in'] = array($exclude_post);
        }
        
        // Add category filter if specified
        if (!empty($category)) {
            $args['category_name'] = $category;
        }
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                ?>
                <article class="blog-grid-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="blog-grid-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="blog-grid-content">
                        <div class="blog-grid-meta">
                            <a href="<?php the_permalink(); ?>">
                                <?php 
                                    $content = get_post_field('post_content', get_the_ID());
                                    $word_count = str_word_count(strip_tags($content));
                                    $reading_time = ceil($word_count / 200);
                                    echo $reading_time . ' minutes read';
                                ?>
                            </a>
                            <span class="separator">·</span>
                            <a href="<?php the_permalink(); ?>">
                                <?php echo get_the_date('F j, Y'); ?>
                            </a>
                        </div>

                        <h3 class="blog-grid-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <p class="blog-grid-excerpt">
                            <?php 
                                $excerpt = get_the_excerpt();
                                echo wp_trim_words($excerpt, 20, '...');
                            ?>
                        </p>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
        } else {
            // No posts found - return empty response
            wp_reset_postdata();
        }
        
        wp_die();
    }
}

// Initialize the plugin
new PatrickDroppe();

