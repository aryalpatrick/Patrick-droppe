<?php
/**
 * Blog List Widget (3x1 Layout)
 * 
 * Creates a responsive 3x1 blog list layout
 * Shortcode: [blog_list category="your-category-slug" posts="3"]
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Blog List 3x1 Shortcode Function
 */
function blog_list_3x1_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'posts' => 3,
        'load_more' => '',
        'button_text' => 'Load More',
    ), $atts);

    $initial_posts = intval($atts['posts']);
    $load_more_posts = !empty($atts['load_more']) ? intval($atts['load_more']) : 0;
    $category = sanitize_text_field($atts['category']);
    $button_text = sanitize_text_field($atts['button_text']);
    
    // Generate unique container ID
    $container_id = 'blog-list-' . uniqid();

    // Query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $initial_posts,
        'post_status' => 'publish',
    );

    // Add category filter if specified
    if (!empty($category)) {
        $args['category_name'] = $category;
    }

    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) : ?>
        
        <div class="blog-list-container" id="<?php echo $container_id; ?>">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
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
                            <?php 
                                $content = get_post_field('post_content', get_the_ID());
                                $word_count = str_word_count(strip_tags($content));
                                $reading_time = ceil($word_count / 200);
                                echo $reading_time . ' minutes read';
                            ?>
                            <span class="separator">·</span>
                            <?php echo get_the_date('F j, Y'); ?>
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
            <?php endwhile; ?>
        </div>
        
        <?php
        // Show load more button if load_more parameter is set and there are more posts
        if ($load_more_posts > 0 && $query->found_posts > $initial_posts) : ?>
            <div class="patrick-droppe-load-more-wrapper">
                <button class="patrick-droppe-load-more" 
                        data-container="#<?php echo $container_id; ?>"
                        data-layout="list"
                        data-category="<?php echo $category; ?>"
                        data-posts-per-load="<?php echo $load_more_posts; ?>"
                        data-offset="<?php echo $initial_posts; ?>">
                    <span class="button-text"><?php echo $button_text; ?></span>
                    <span class="button-loader" style="display: none;"></span>
                </button>
            </div>
        <?php endif; ?>

    <?php 
    else : 
        echo '<p>No posts found.</p>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('blog_list', 'blog_list_3x1_shortcode');