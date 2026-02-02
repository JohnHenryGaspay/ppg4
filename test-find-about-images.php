<?php
/**
 * Find About Images in Media Library
 */

require_once __DIR__ . '/www/wp-config.php';

// Search for about-we-thrive image
$attachments = get_posts([
    'post_type' => 'attachment',
    's' => 'about-we-thrive',
    'posts_per_page' => 10
]);

echo "Searching for 'about-we-thrive':\n";
if ($attachments) {
    foreach ($attachments as $att) {
        echo "  ID: {$att->ID}, Title: {$att->post_title}, URL: " . wp_get_attachment_url($att->ID) . "\n";
    }
} else {
    echo "  (no images found)\n";
}

// Search for specialities
$attachments2 = get_posts([
    'post_type' => 'attachment',
    's' => 'specialities',
    'posts_per_page' => 10
]);

echo "\nSearching for 'specialities':\n";
if ($attachments2) {
    foreach ($attachments2 as $att) {
        echo "  ID: {$att->ID}, Title: {$att->post_title}, URL: " . wp_get_attachment_url($att->ID) . "\n";
    }
} else {
    echo "  (no images found)\n";
}

// Check by filename pattern
$attachments3 = get_posts([
    'post_type' => 'attachment',
    'meta_query' => [
        [
            'key' => '_wp_attached_file',
            'value' => '2018/05/about',
            'compare' => 'LIKE'
        ]
    ],
    'posts_per_page' => 10
]);

echo "\nSearching in 2018/05 folder:\n";
if ($attachments3) {
    foreach ($attachments3 as $att) {
        $url = wp_get_attachment_url($att->ID);
        if (strpos($url, 'about') !== false) {
            echo "  ID: {$att->ID}, Title: {$att->post_title}, URL: " . $url . "\n";
        }
    }
} else {
    echo "  (no images found)\n";
}
