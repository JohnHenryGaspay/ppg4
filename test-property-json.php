<?php
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

// Test 1: Get property directly
$args = ['post_type' => ['property', 'land'], 'post_status' => 'publish', 'posts_per_page' => 1, 'meta_query' => [['key' => 'property_status', 'value' => 'current']]];
$props = get_posts($args);

if (empty($props)) {
    echo "No properties found\n";
    exit;
}

$prop_id = $props[0]->ID;
echo "Property ID: $prop_id\n";
echo "Title: " . $props[0]->post_title . "\n";

// Test 2: Get coordinates from post meta
$coords = get_post_meta($prop_id, 'property_address_coordinates', true);
echo "Coords from post_meta: " . ($coords ?: 'EMPTY') . "\n";

// Test 3: Load as Timber Property
$timber_prop = Timber\Timber::get_post($prop_id);
echo "\nTimber class: " . get_class($timber_prop) . "\n";

// Test 4: Load as JuiceBox Property
$jb_prop = Timber\Timber::get_post($prop_id, '\\JuiceBox\\Core\\Property');  
echo "JuiceBox class: " . get_class($jb_prop) . "\n";

// Test 5: Check if get_json works
try {
    $json = $jb_prop->get_json();
    echo "\nJSON generated successfully\n";
    echo "Has location key: " . (isset($json['location']) ? 'YES' : 'NO') . "\n";
    if (isset($json['location'])) {
        echo "Lat: " . $json['location']['lat'] . "\n";
        echo "Lng: " . $json['location']['lng'] . "\n";
    }
} catch (Exception $e) {
    echo "\nError generating JSON: " . $e->getMessage() . "\n";
}
