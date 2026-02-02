<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;
$homepage = get_page_by_title('Home');

echo "Clearing ALL module-related meta data...\n";

// Delete all meta keys matching modules* pattern
$deleted = $wpdb->query($wpdb->prepare(
    "DELETE FROM " . $wpdb->postmeta . " WHERE post_id = %d AND meta_key LIKE %s",
    $homepage->ID,
    'modules%'
));

echo "Deleted $deleted meta entries.\n";

// Also delete the old field key reference
$wpdb->query($wpdb->prepare(
    "DELETE FROM " . $wpdb->postmeta . " WHERE post_id = %d AND meta_key LIKE %s",
    $homepage->ID,
    '\_modules%'
));

echo "Done! All module data has been cleared from the homepage.\n";

// Verify
$remaining = $wpdb->get_results($wpdb->prepare(
    "SELECT meta_key FROM " . $wpdb->postmeta . " WHERE post_id = %d AND meta_key LIKE %s",
    $homepage->ID,
    'modules%'
));

echo "Remaining modules entries: " . count($remaining) . "\n";
?>
