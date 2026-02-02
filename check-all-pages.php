<?php
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

$pages = ['buy', 'sold', 'rent', 'commercial'];
foreach ($pages as $slug) {
    $page = get_page_by_path($slug);
    if ($page) {
        echo "$slug: ID=" . $page->ID . ", Template=" . get_page_template_slug($page->ID) . "\n";
        $props_display = get_field('properties_to_display', $page->ID);
        echo "  properties_to_display: " . ($props_display ?: 'not set') . "\n";
    } else {
        echo "$slug: Page not found\n";
    }
}
