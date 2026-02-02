<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;
$homepage = get_page_by_title('Home');
$result = $wpdb->get_results($wpdb->prepare(
    "SELECT meta_key, meta_value FROM " . $wpdb->postmeta . " WHERE post_id = %d ORDER BY meta_key",
    $homepage->ID
));

echo "=== ALL HOMEPAGE META ===\n";
foreach ($result as $row) {
    if (strpos($row->meta_key, 'modules') !== false || strpos($row->meta_key, 'modules') !== false) {
        echo $row->meta_key . ": " . substr($row->meta_value, 0, 100) . "\n";
    }
}
?>
