<?php
/**
 * Blog Grid Widget with Load More
 * 
 * Creates a responsive 2x2 blog grid with load more functionality
 * Shortcode: [blog_grid_loadmore category="your-category-slug" initial="4" load_more="2"]
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Blog Grid Load More Shortcode Function
 */
function blog_grid_loadmore_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
        'initial' => 4,
        'load_more' => 2,
    ), $atts);

    $initial_posts = intval($atts['initial']);
    $load_more_posts = intval($atts['load_more']);
    $category = sanitize_text_field($atts['category']);
    
    // Generate unique container ID
    $container_id = 'blog-grid-' . uniqid();

    // Query arguments for initial posts
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
        
        <div class="blog-grid-container" id="<?php echo $container_id; ?>">
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
        // Check if there are more posts
        $total_posts = wp_count_posts()->publish;
        if ($query->found_posts > $initial_posts) : ?>
            <div class="patrick-droppe-load-more-wrapper" style="text-align: center; margin-top: 40px;">
                <button class="patrick-droppe-load-more" 
                        data-container="#<?php echo $container_id; ?>"
                        data-layout="grid"
                        data-category="<?php echo $category; ?>"
                        data-posts-per-load="<?php echo $load_more_posts; ?>"
                        data-offset="<?php echo $initial_posts; ?>"
                        style="background: #000; color: #fff; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-size: 16px;">
                    Load More
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
add_shortcode('blog_grid_loadmore', 'blog_grid_loadmore_shortcode');