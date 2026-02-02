<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

// Search for the old field group key in postmeta
$results = $wpdb->get_results($wpdb->prepare(
    "SELECT post_id, meta_key, meta_value FROM " . $wpdb->postmeta . " 
     WHERE meta_value LIKE %s
     LIMIT 20",
    '%group_588574a592c85%'
));

echo "=== Search results for group_588574a592c85 ===\n";
if (count($results) > 0) {
    foreach ($results as $row) {
        echo "Post ID: $row->post_id, Meta Key: $row->meta_key\n";
        echo "Value: " . substr($row->meta_value, 0, 200) . "...\n\n";
    }
} else {
    echo "Not found in postmeta\n";
}

// Also search in options
$options = $wpdb->get_results($wpdb->prepare(
    "SELECT option_id, option_name, option_value FROM " . $wpdb->options . " 
     WHERE option_value LIKE %s
     LIMIT 20",
    '%group_588574a592c85%'
));

echo "\n=== Search results in options ===\n";
if (count($options) > 0) {
    foreach ($options as $row) {
        echo "Option: $row->option_name\n";
    }
} else {
    echo "Not found in options\n";
}
?>
