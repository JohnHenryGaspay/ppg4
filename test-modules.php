<?php
require __DIR__ . '/www/wp/wp-load.php';

// Get homepage
$homepage = get_page_by_title('Home');
if ($homepage) {
    $modules = get_post_meta($homepage->ID, 'modules', true);
    echo 'Homepage ID: ' . $homepage->ID . PHP_EOL;
    echo 'Raw modules data: ' . PHP_EOL;
    var_dump($modules);
    echo "\n\nModules via get_field: " . PHP_EOL;
    $modules_acf = get_field('modules', $homepage->ID);
    var_dump($modules_acf);
} else {
    echo "Homepage not found\n";
}
?>
