<?php
// Repair the crashed database table
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

// Repair the crashed Yoast table
echo "Attempting to repair database table...\n";
$result = $wpdb->query("REPAIR TABLE {$wpdb->prefix}yoast_indexable");
echo "Repair result: " . ($result !== false ? "Success" : "Failed") . "\n";

// Check the table status
$check = $wpdb->get_results("CHECK TABLE {$wpdb->prefix}yoast_indexable");
foreach ($check as $row) {
    echo "Status: " . json_encode($row) . "\n";
}

echo "Database repair completed.\n";
?>
