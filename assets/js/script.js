jQuery(document).ready(function ($) {

    // Load More functionality
    $(document).on('click', '.patrick-droppe-load-more', function (e) {
        e.preventDefault();

        var button = $(this);
        var container = button.data('container');
        var layout = button.data('layout');
        var category = button.data('category');
        var postsPerLoad = parseInt(button.data('posts-per-load'));
        var currentOffset = parseInt(button.data('offset'));
        var totalPosts = parseInt(button.data('total-posts')) || 0;
        var displayedPosts = parseInt(button.data('displayed-posts')) || 0;

        // Check if AJAX object exists
        if (typeof patrick_droppe_ajax === 'undefined') {
            button.find('.button-text').text('Script Error');
            console.error('patrick_droppe_ajax not defined - scripts not loaded properly');
            return;
        }

        // Check if nonce is available
        if (!patrick_droppe_ajax.nonce) {
            button.find('.button-text').text('Configuration Error');
            console.error('Nonce not available');
            return;
        }

        // Show loading state
        button.addClass('loading').prop('disabled', true);

        $.ajax({
            url: patrick_droppe_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_posts',
                layout: layout,
                category: category,
                offset: currentOffset,
                posts_per_load: postsPerLoad,
                nonce: patrick_droppe_ajax.nonce
            },
            success: function (response) {
                // Check if response contains error
                if (response.indexOf('Security check failed') !== -1) {
                    button.removeClass('loading');
                    button.find('.button-text').text('Security Error');
                    button.prop('disabled', true);
                    console.error('Security check failed - nonce verification error');
                    return;
                }

                if (response.indexOf('Nonce not provided') !== -1) {
                    button.removeClass('loading');
                    button.find('.button-text').text('Nonce Error');
                    button.prop('disabled', true);
                    console.error('Nonce not provided error');
                    return;
                }

                if (response.trim() === 'NO_MORE_POSTS' || response.trim() === '') {
                    // No more posts - hide the button completely
                    button.removeClass('loading');
                    button.closest('.patrick-droppe-load-more-wrapper').fadeOut(300);
                } else if (response.indexOf('NO_MORE_POSTS') !== -1) {
                    // Response contains NO_MORE_POSTS - don't append it, just hide button
                    button.removeClass('loading');
                    button.closest('.patrick-droppe-load-more-wrapper').fadeOut(300);
                } else {
                    // Append new posts to container
                    $(container).append(response);

                    // Update offset and displayed count
                    var newOffset = currentOffset + postsPerLoad;
                    var newDisplayedCount = displayedPosts + postsPerLoad;

                    button.data('offset', newOffset);
                    button.data('displayed-posts', newDisplayedCount);

                    // Check if we'll have more posts after this load
                    if (newDisplayedCount >= totalPosts) {
                        // No more posts will be available - hide button
                        button.removeClass('loading');
                        button.closest('.patrick-droppe-load-more-wrapper').fadeOut(300);
                    } else {
                        // Reset button state for next load
                        button.removeClass('loading').prop('disabled', false);
                    }
                }
            },
            error: function (xhr, status, error) {
                button.removeClass('loading');
                button.find('.button-text').text('Error loading posts');
                button.prop('disabled', false);
                console.error('AJAX Error:', status, error);
            }
        });
    });

});