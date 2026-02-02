<?php
define('WP_USE_THEMES', false);
require(__DIR__ . '/www/wp/wp-blog-header.php');

// Simulate the buy page query exactly as in page--property-map.php
$property_args = [
    'post_type' => ['property', 'land'],
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_query' => [
        [
            'key' => 'property_status',
            'value' => 'current',
            'compare' => '=',
            'type' => 'CHAR',
        ],
    ],
];

$properties = Timber\Timber::get_posts($property_args);
echo "Properties found: " . count($properties) . "\n\n";

if (count($properties) > 0) {
    // Generate JSON like the template does
    $properties_json = [];
    foreach ($properties as $property) {
        try {
            $json = $property->get_json();
            $properties_json[] = $json;
            
            // Show first property details
            if (count($properties_json) === 1) {
                echo "First property:\n";
                echo "  ID: " . $json['id'] . "\n";
                echo "  Link: " . $json['link'] . "\n";
                echo "  Images count: " . count($json['images']) . "\n";
                echo "  Location lat: " . $json['location']['lat'] . "\n";
                echo "  Location lng: " . $json['location']['lng'] . "\n";
                echo "  Has data: " . (isset($json['data']) ? 'YES' : 'NO') . "\n";
                if (isset($json['data']['property_bedrooms'])) {
                    echo "  Bedrooms: " . $json['data']['property_bedrooms'] . "\n";
                }
            }
        } catch (Exception $e) {
            echo "Error on property " . $property->ID . ": " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nTotal properties with valid locations: ";
    $valid_count = 0;
    foreach ($properties_json as $p) {
        if (!empty($p['location']['lat']) && !empty($p['location']['lng'])) {
            $valid_count++;
        }
    }
    echo $valid_count . "\n";
}
