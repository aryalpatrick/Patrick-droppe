# Patrick-Droppe WordPress Plugin

A collection of custom widgets and shortcodes for WordPress.

## Current Widgets

### Blog Grid Widget
- **Shortcode:** `[blog_grid category="your-category-slug" posts="4"]`
- **Description:** Creates a responsive 2x2 blog grid with category filtering
- **Parameters:**
  - `category` (optional): Category slug to filter posts
  - `posts` (optional): Number of posts to display (default: 4)

### Blog Featured Layout Widget
- **Shortcode:** `[blog_featured category="your-category-slug"]`
- **Description:** Creates a layout with 1 featured post (full-width) + 4 posts in 2x2 grid below
- **Parameters:**
  - `category` (optional): Category slug to filter posts
- **Note:** Always displays 5 most recent posts (1 featured + 4 in grid)

### Blog List Widget (3x1 Layout)
- **Shortcode:** `[blog_list category="your-category-slug" posts="3"]`
- **Description:** Creates a responsive 3x1 blog list layout
- **Parameters:**
  - `category` (optional): Category slug to filter posts
  - `posts` (optional): Number of posts to display (default: 3)

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcodes in your posts, pages, or widgets
