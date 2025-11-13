# Patrick-Droppe WordPress Plugin

A collection of custom widgets and shortcodes for WordPress.

## Current Widgets

### Blog Grid Widget (2x2 Layout)
- **Shortcode:** `[blog_grid category="your-category-slug" posts="4" load_more="2" button_text="Load More"]`
- **Description:** Creates a responsive 2x2 blog grid with optional load more functionality
- **Parameters:**
  - `category` (optional): Category slug to filter posts
  - `posts` (optional): Number of posts to display initially (default: 4)
  - `load_more` (optional): Number of posts to load each time. If not set, no load more button appears
  - `button_text` (optional): Custom text for load more button (default: "Load More")

### Blog List Widget (3x1 Layout)
- **Shortcode:** `[blog_list category="your-category-slug" posts="3" load_more="3" button_text="View More"]`
- **Description:** Creates a responsive 3x1 blog list layout with optional load more functionality
- **Parameters:**
  - `category` (optional): Category slug to filter posts
  - `posts` (optional): Number of posts to display initially (default: 3)
  - `load_more` (optional): Number of posts to load each time. If not set, no load more button appears
  - `button_text` (optional): Custom text for load more button (default: "Load More")

### Blog Featured Layout Widget (1 + 2x2)
- **Shortcode:** `[blog_featured category="your-category-slug" load_more="4" button_text="Show More"]`
- **Description:** Creates a layout with 1 featured post (full-width) + 4 posts in 2x2 grid below with optional load more
- **Parameters:**
  - `category` (optional): Category slug to filter posts
  - `load_more` (optional): Number of posts to load each time. If not set, no load more button appears
  - `button_text` (optional): Custom text for load more button (default: "Load More")
- **Note:** Always displays 5 most recent posts initially (1 featured + 4 in grid)

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the shortcodes in your posts, pages, or widgets
