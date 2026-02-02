<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

// Get all ACF field groups
$field_groups = $wpdb->get_results(
    "SELECT post_id, post_title FROM " . $wpdb->posts . " 
     WHERE post_type = 'acf-field-group' 
     ORDER BY post_title"
);

echo "=== ACF FIELD GROUPS IN DATABASE ===\n\n";
foreach ($field_groups as $fg) {
    echo "ID: $fg->post_id - Title: $fg->post_title\n";
    
    // Get the key
    $key = get_post_meta($fg->post_id, 'key', true);
    echo "  Key: $key\n";
    
    // Get location
    $location = get_post_meta($fg->post_id, 'location', true);
    if ($location) {
        echo "  Location: ";
        if (is_array($location)) {
            echo json_encode($location) . "\n";
        } else {
            echo $location . "\n";
        }
    }
    echo "\n";
}
?>
