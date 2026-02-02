<?php
/**
 * Test Homepage Rendering
 */

define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);

require_once __DIR__ . '/www/wp-config.php';

// Get the homepage ID
$homepage_id = get_option('page_on_front');
if (!$homepage_id) {
    $pages = get_posts([
        'post_type' => 'page',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_wp_page_template',
                'value' => 'page--home-page.php',
                'compare' => '='
            ]
        ]
    ]);
    
    if (!empty($pages)) {
        $homepage_id = $pages[0]->ID;
    }
}

echo "Testing homepage ID: $homepage_id\n\n";

// Try to create Timber Image
$banner_image_id = get_field('banner_image', $homepage_id);
echo "Banner Image ID: $banner_image_id\n";

try {
    $timber_image = new Timber\Image($banner_image_id);
    echo "Timber Image created successfully\n";
    echo "Image src: " . $timber_image->src . "\n";
    echo "Image alt: " . $timber_image->alt . "\n";
} catch (Exception $e) {
    echo "Error creating Timber Image: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
