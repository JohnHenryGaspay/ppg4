<?php
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

// Check if buy page uses correct template
$buy_page = get_page_by_path('buy');
echo "Buy Page ID: " . $buy_page->ID . "\n";
echo "Buy Page Template: " . get_page_template_slug($buy_page->ID) . "\n";

// Check if is_page works in this context
echo "is_page(): " . (is_page() ? 'true' : 'false') . "\n";

// List all enqueued scripts
global $wp_scripts;
if (!$wp_scripts) {
    wp_scripts_maybe_doing_it_wrong(__FUNCTION__);
}

echo "\nEnqueued scripts:\n";
if ($wp_scripts && isset($wp_scripts->queue)) {
    foreach ($wp_scripts->queue as $script_handle) {
        $obj = $wp_scripts->registered[$script_handle];
        echo "  - $script_handle: " . $obj->src . "\n";
    }
}
