<?php
require __DIR__ . '/www/wp/wp-load.php';

$homepage = get_page_by_title('Home');
echo "Testing ACF get_field:\n";
$modules = get_field('modules', $homepage->ID);
if ($modules === false) {
    echo "get_field returned FALSE\n";
} else if ($modules === null) {
    echo "get_field returned NULL\n";
} else if (is_array($modules)) {
    echo "get_field returned array with " . count($modules) . " items\n";
    echo "Structure:\n";
    var_dump($modules);
} else {
    echo "get_field returned: ";
    var_dump($modules);
}

echo "\n\nDirect meta:\n";
var_dump(get_post_meta($homepage->ID, 'modules', true));

echo "\n\nChecking _modules:\n";
var_dump(get_post_meta($homepage->ID, '_modules', true));
?>
