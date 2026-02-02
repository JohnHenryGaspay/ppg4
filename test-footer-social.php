<?php
/**
 * Check Footer Social Links
 */

require_once __DIR__ . '/www/wp-config.php';

// Get the homepage
$homepage_id = get_option('page_on_front');
echo "Homepage ID: $homepage_id\n\n";

// Check if there's an option or ACF field for social media links
$social_links = get_option('social_media_links');
echo "Option 'social_media_links': " . ($social_links ? json_encode($social_links) : '(not found)') . "\n\n";

// Check theme mod
$social_mod = get_theme_mod('social_media');
echo "Theme mod 'social_media': " . ($social_mod ? json_encode($social_mod) : '(not found)') . "\n\n";

// Check for ACF global options
$acf_options = get_fields('option');
echo "ACF Options available:\n";
if ($acf_options) {
    foreach ($acf_options as $key => $value) {
        if (strpos($key, 'social') !== false || strpos($key, 'facebook') !== false || strpos($key, 'link') !== false) {
            echo "  $key: " . (is_array($value) ? 'array' : substr($value, 0, 100)) . "\n";
        }
    }
}

// Search posts for social media
$posts = get_posts([
    'post_type' => 'any',
    's' => 'facebook',
    'posts_per_page' => 5
]);

echo "\nPosts mentioning 'facebook': " . count($posts) . "\n";
foreach ($posts as $post) {
    echo "  - {$post->post_title} (ID: {$post->ID})\n";
}
