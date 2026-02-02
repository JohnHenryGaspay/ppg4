<?php
require __DIR__ . '/www/wp/wp-load.php';

// Get all registered field groups (local)
$field_groups = acf_get_field_groups();

echo "=== ACF FIELD GROUPS (acf_get_field_groups) ===\n\n";
foreach ($field_groups as $fg) {
    echo "Key: " . $fg['key'] . "\n";
    echo "Title: " . $fg['title'] . "\n";
    if (isset($fg['location'])) {
        echo "Location: " . json_encode($fg['location']) . "\n";
    }
    echo "\n";
}
?>
