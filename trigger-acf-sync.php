<?php
/**
 * Trigger ACF field sync
 * Access this file via browser at: http://localhost/ppg4/trigger-acf-sync.php
 */
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

// Check if user is logged in and is admin
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('Access denied. Please log in as admin.');
}

echo '<h2>ACF Field Sync</h2>';

// Get all ACF field groups
if (function_exists('acf_get_field_groups')) {
    $groups = acf_get_field_groups();
    echo '<p>Found ' . count($groups) . ' ACF field groups</p>';
    
    foreach ($groups as $group) {
        echo '<p>Group: ' . $group['title'] . ' (Key: ' . $group['key'] . ')</p>';
    }
}

// Force ACF to sync
if (function_exists('acf')) {
    echo '<p>ACF is active and loaded.</p>';
}

echo '<p><a href="/ppg4/wp/wp-admin/edit.php?post_type=page&page=acf-tools">Go to ACF Tools</a></p>';
echo '<p><a href="/ppg4/">Back to Homepage</a></p>';
?>
