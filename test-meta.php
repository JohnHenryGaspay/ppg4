<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;
$homepage = get_page_by_title('Home');

echo "=== HOMEPAGE META DATA ===\n";
echo "Post ID: " . $homepage->ID . "\n\n";

$result = $wpdb->get_results($wpdb->prepare(
    "SELECT meta_key, meta_value FROM " . $wpdb->postmeta . " WHERE post_id = %d ORDER BY meta_key",
    $homepage->ID
));

foreach ($result as $row) {
    echo $row->meta_key . ":\n";
    if (strlen($row->meta_value) > 200) {
        echo "  " . substr($row->meta_value, 0, 200) . "...\n\n";
    } else {
        echo "  " . $row->meta_value . "\n\n";
    }
}
?>
