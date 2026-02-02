<?php
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

$homepage = get_page_by_title('Home');
echo "Clearing all modules data for homepage (ID: " . $homepage->ID . ")\n";

// Delete all modules-related meta
$wpdb->query($wpdb->prepare(
    "DELETE FROM " . $wpdb->postmeta . " WHERE post_id = %d AND meta_key LIKE %s",
    $homepage->ID,
    'modules%'
));

echo "Done! All modules data cleared.\n";
echo "You can now add fresh modules from the WordPress editor.\n";

// Verify it's gone
$result = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM " . $wpdb->postmeta . " WHERE post_id = %d AND meta_key LIKE %s",
    $homepage->ID,
    'modules%'
));

echo "Remaining modules meta entries: " . $result . "\n";
?>
