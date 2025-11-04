<?php
/**
 * Blog Featured Layout Widget
 * 
 * Creates a layout with 1 featured post (full-width) + 4 posts in 2x2 grid
 * Shortcode: [blog_featured category="your-category-slug"]
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Blog Featured Layout Shortcode Function
 */
function blog_featured_layout_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'category' => '',
    ), $atts);

    // Query arguments for 5 most recent posts
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'post_status' => 'publish',
    );

    // Add category filter if specified
    if (!empty($atts['category'])) {
        $args['category_name'] = sanitize_text_field($atts['category']);
    }

    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($query->have_posts()) : 
        $post_count = 0;
        ?>
        
        <div class="blog-featured-container">
            <?php while ($query->have_posts()) : $query->the_post(); 
                $post_count++;
                
                if ($post_count === 1) : // First post - featured ?>
                    <div class="blog-featured-main">
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

                                <h2 class="blog-grid-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <p class="blog-grid-excerpt">
                                    <?php 
                                        $excerpt = get_the_excerpt();
                                        echo wp_trim_words($excerpt, 30, '...');
                                    ?>
                                </p>
                            </div>
                        </article>
                    </div>
                    
                    <?php if ($query->found_posts > 1) : ?>
                        <div class="blog-featured-grid">
                    <?php endif; ?>
                    
                <?php else : // Remaining posts in 2x2 grid ?>
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
                <?php endif; ?>
            <?php endwhile; ?>
            
            <?php if ($query->found_posts > 1) : ?>
                </div> <!-- Close blog-featured-grid -->
            <?php endif; ?>
        </div>

    <?php 
    else : 
        echo '<p>No posts found.</p>';
    endif;

    wp_reset_postdata();

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('blog_featured', 'blog_featured_layout_shortcode');