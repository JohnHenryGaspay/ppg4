<?php
require __DIR__ . '/www/wp/wp-load.php';

$homepage = get_page_by_title('Home');
echo "=== CHECKING ALL HOME PAGE META ===\n\n";

// Get all post meta
$meta = get_post_meta($homepage->ID);
foreach ($meta as $key => $values) {
    echo $key . ":\n";
    foreach ($values as $val) {
        if (strlen($val) > 500) {
            echo "  [Large value - " . strlen($val) . " bytes]\n";
            // Check if it contains "Add Module"
            if (strpos($val, 'Add Module') !== false) {
                echo "  CONTAINS 'Add Module'!\n";
            }
        } else {
            echo "  " . $val . "\n";
        }
    }
    echo "\n";
}
?>
