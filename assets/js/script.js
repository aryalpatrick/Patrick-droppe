jQuery(document).ready(function($) {
    
    // Load More functionality
    $(document).on('click', '.patrick-droppe-load-more', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var container = button.data('container');
        var layout = button.data('layout');
        var category = button.data('category');
        var postsPerLoad = parseInt(button.data('posts-per-load'));
        var currentOffset = parseInt(button.data('offset'));
        
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
            success: function(response) {
                // Check if response contains error
                if (response.indexOf('Security check failed') !== -1) {
                    button.removeClass('loading');
                    button.find('.button-text').text('Security Error');
                    button.prop('disabled', true);
                    console.error('Security check failed - nonce verification error');
                    return;
                }
                
                if (response.trim() !== '') {
                    // Append new posts to container
                    $(container).append(response);
                    
                    // Update offset for next load
                    button.data('offset', currentOffset + postsPerLoad);
                    
                    // Reset button state
                    button.removeClass('loading').prop('disabled', false);
                } else {
                    // No more posts
                    button.removeClass('loading');
                    button.find('.button-text').text('No More Posts');
                    button.prop('disabled', true);
                }
            },
            error: function(xhr, status, error) {
                button.removeClass('loading');
                button.find('.button-text').text('Error Loading Posts');
                button.prop('disabled', false);
                console.error('AJAX Error:', status, error);
            }
        });
    });
    
});