<?php
require __DIR__ . '/www/wp/wp-load.php';

$homepage = get_page_by_title('Home');
echo "Updating _modules field key...\n";

// Update the field key to match the current definition
update_post_meta($homepage->ID, '_modules', 'field_homepage_modules_main');

echo "Done! New _modules value: " . get_post_meta($homepage->ID, '_modules', true) . "\n";

// Now test again
echo "\n\nTesting ACF get_field again:\n";
$modules = get_field('modules', $homepage->ID);
if ($modules === false) {
    echo "get_field returned FALSE\n";
} else if (is_array($modules)) {
    echo "get_field returned array with " . count($modules) . " items\n";
} else {
    echo "get_field returned: ";
    var_dump($modules);
}
?>
