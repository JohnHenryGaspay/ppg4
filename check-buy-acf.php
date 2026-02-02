<?php
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

$page = get_page_by_path('buy');
echo "Buy Page ID: " . $page->ID . "\n";
echo "Template: " . get_page_template_slug($page->ID) . "\n";

$fields = get_field_objects($page->ID);
if ($fields) {
    echo "\nACF Fields:\n";
    foreach ($fields as $key => $field) {
        echo "  " . $key . ": ";
        if (is_array($field['value'])) {
            echo json_encode($field['value']);
        } else {
            echo $field['value'];
        }
        echo "\n";
    }
} else {
    echo "\nNo ACF fields found\n";
}

// Check if there's a module field
$modules = get_field('modules', $page->ID);
if ($modules) {
    echo "\nModules field exists with " . count($modules) . " items\n";
} else {
    echo "\nNo modules field found\n";
}
