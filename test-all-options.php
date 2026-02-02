<?php
/**
 * Get All Social Media Links from ACF Options
 */

require_once __DIR__ . '/www/wp-config.php';

$acf_options = get_fields('option');
echo "All ACF Options:\n";
if ($acf_options) {
    foreach ($acf_options as $key => $value) {
        echo "  $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
} else {
    echo "  (none found)\n";
}
