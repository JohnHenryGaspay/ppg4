<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

// Search for field groups in posts table
$results = $wpdb->get_results(
    "SELECT ID, post_title, post_content FROM " . $wpdb->posts . " 
     WHERE post_type = 'acf-field-group'
     ORDER BY post_title"
);

echo "=== ACF Field Groups in Posts Table ===\n";
if (count($results) > 0) {
    foreach ($results as $row) {
        echo "ID: $row->ID, Title: $row->post_title\n";
    }
} else {
    echo "No ACF field groups found in posts table\n";
}
?>
