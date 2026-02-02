<?php
/**
 * Set About Page Image Fields
 */

require_once __DIR__ . '/www/wp-config.php';

// Get the about page
$pages = get_posts([
    'post_type' => 'page',
    'posts_per_page' => 1,
    'meta_query' => [
        [
            'key' => '_wp_page_template',
            'value' => 'page--about.php',
            'compare' => '='
        ]
    ]
]);

if (empty($pages)) {
    echo "Could not find about page\n";
    exit;
}

$page_id = $pages[0]->ID;
echo "Setting images for About Page ID: $page_id\n\n";

// Set the images
update_field('about_we_thrive', 1184, $page_id);
echo "✓ Set about_we_thrive to ID 1184\n";

update_field('about_specialities', 1185, $page_id);
echo "✓ Set about_specialities to ID 1185\n";

update_field('about_family', 1186, $page_id);
echo "✓ Set about_family to ID 1186\n";

echo "\nDone! About page images are now set in ACF.\n";
