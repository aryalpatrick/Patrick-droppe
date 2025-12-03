# Patrick-Droppe WordPress Plugin

WordPress plugin for creating blog layouts with AJAX load more functionality.

## Available Shortcodes

### Blog Grid (2x2 Layout)
```
[blog_grid]
```

**Parameters:**
- `category` - Category slug (optional)
- `posts` - Initial posts to show (default: 4)
- `load_more` - Posts to load per click (optional, no button if not set)
- `button_text` - Button text (default: "Load More")

**Examples:**
```
[blog_grid]
[blog_grid category="news"]
[blog_grid posts="6" load_more="2"]
[blog_grid category="tech" posts="4" load_more="2" button_text="Load More Posts"]
```

### Blog List (3x1 Layout)
```
[blog_list]
```

**Parameters:**
- `category` - Category slug (optional)
- `posts` - Initial posts to show (default: 3)
- `load_more` - Posts to load per click (optional, no button if not set)
- `button_text` - Button text (default: "Load More")

**Examples:**
```
[blog_list]
[blog_list category="tutorials"]
[blog_list posts="6" load_more="3"]
[blog_list category="news" posts="3" load_more="3" button_text="View More"]
```

### Blog Featured (1 Featured + 2x2 Grid)
```
[blog_featured]
```

**Parameters:**
- `category` - Category slug (optional)
- `load_more` - Posts to load per click (optional, no button if not set)
- `button_text` - Button text (default: "Load More")

**Note:** Always shows 5 posts initially (1 featured + 4 in grid)

**Examples:**
```
[blog_featured]
[blog_featured category="featured"]
[blog_featured load_more="2"]
[blog_featured category="highlights" load_more="4" button_text="Show More"]
```

## Installation

1. Upload plugin folder to `/wp-content/plugins/`
2. Activate plugin in WordPress admin
3. Use shortcodes in posts, pages, or widgets

## Important Notes

- **Load More Button:** Only appears if `load_more` parameter is set
- **Category Filter:** Use category slug, not category name
- **Responsive:** All layouts adapt to mobile devices
- **AJAX Loading:** No page refresh when loading more posts
- **Smart Button:** Disappears automatically when all posts are loaded
