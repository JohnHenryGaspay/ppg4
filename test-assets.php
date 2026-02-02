<?php
require __DIR__ . '/www/wp/wp-load.php';

echo "=== THEME ASSET DIAGNOSTICS ===\n\n";

// Check theme directory
echo "Theme Directory URI: " . get_stylesheet_directory_uri() . "\n";
echo "Theme Directory Path: " . get_stylesheet_directory() . "\n\n";

// Check if CSS file exists
$css_file = get_stylesheet_directory() . '/dist/css/main.css';
echo "CSS File Path: $css_file\n";
echo "CSS Exists: " . (file_exists($css_file) ? 'YES' : 'NO') . "\n";
if (file_exists($css_file)) {
    echo "CSS File Size: " . filesize($css_file) . " bytes\n";
}
echo "\n";

// Check if JS files exist
$js_files = [
    '/dist/js/manifest.js',
    '/dist/js/vendor.js',
    '/dist/js/modernizr.js',
    '/dist/js/bundle.js'
];

foreach ($js_files as $js) {
    $path = get_stylesheet_directory() . $js;
    echo "JS: $js\n";
    echo "  Exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
    if (file_exists($path)) {
        echo "  Size: " . filesize($path) . " bytes\n";
    }
}

echo "\n=== Expected URLs ===\n";
echo "CSS URL: " . get_stylesheet_directory_uri() . "/dist/css/main.css\n";
echo "JS Bundle URL: " . get_stylesheet_directory_uri() . "/dist/js/bundle.js\n";
?>
