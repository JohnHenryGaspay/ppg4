<?php
/**
 * Test Banner Fields
 */

require_once __DIR__ . '/www/wp-config.php';

// Get the homepage
$homepage_id = get_option('page_on_front');
if (!$homepage_id) {
    // Try to find Home Page template
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

if (!$homepage_id) {
    echo "Could not find homepage\n";
    exit;
}

echo "Homepage ID: $homepage_id\n\n";

// Get banner fields
$banner_title = get_field('banner_title', $homepage_id);
$banner_image = get_field('banner_image', $homepage_id);
$banner_video = get_field('banner_video', $homepage_id);

echo "Banner Title: " . ($banner_title ?: '(empty)') . "\n";
echo "Banner Video: " . ($banner_video ?: '(empty)') . "\n";
echo "Banner Image (raw): ";
var_dump($banner_image);

// If it's an ID, get the full image data
if ($banner_image && is_numeric($banner_image)) {
    echo "\nBanner Image is an ID, getting attachment...\n";
    $image_url = wp_get_attachment_url($banner_image);
    echo "Image URL: $image_url\n";
    
    $image_data = wp_get_attachment_metadata($banner_image);
    echo "Image Metadata: ";
    var_dump($image_data);
}
