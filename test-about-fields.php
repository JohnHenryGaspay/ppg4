<?php
/**
 * Test About Page Fields
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
    // Try finding by page name
    $pages = get_posts([
        'pagename' => 'about',
        'post_type' => 'page',
        'posts_per_page' => 1
    ]);
}

if (empty($pages)) {
    echo "Could not find about page\n";
    exit;
}

$page_id = $pages[0]->ID;
echo "About Page ID: $page_id\n\n";

// Get all ACF fields
$fields = get_fields($page_id);
echo "ACF Fields on page $page_id:\n";
if ($fields) {
    foreach ($fields as $key => $value) {
        echo "  $key: ";
        if (is_array($value)) {
            echo "array(" . count($value) . ")\n";
        } elseif (is_object($value)) {
            echo "object(" . get_class($value) . ")\n";
        } else {
            echo substr($value, 0, 100) . "\n";
        }
    }
} else {
    echo "  (no fields found)\n";
}

// Check for specific fields
echo "\nChecking specific fields:\n";
$about_we_thrive = get_field('about_we_thrive', $page_id);
$about_specialities = get_field('about_specialities', $page_id);
echo "about_we_thrive: " . ($about_we_thrive ?: '(empty)') . "\n";
echo "about_specialities: " . ($about_specialities ?: '(empty)') . "\n";
