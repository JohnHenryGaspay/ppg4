<?php
// Fix corrupted modules data
require __DIR__ . '/www/wp/wp-load.php';

global $wpdb;

// Find all posts using the home page template
$pages = $wpdb->get_results("
    SELECT p.ID, p.post_title
    FROM {$wpdb->posts} p
    WHERE p.post_type = 'page'
    ORDER BY p.ID DESC
    LIMIT 5
");

if (!empty($pages)) {
    foreach ($pages as $page) {
        echo "Post ID: {$page->ID}, Title: {$page->post_title}\n";
        
        // Check for modules meta
        $meta_value = get_post_meta($page->ID, 'modules', true);
        
        if (!empty($meta_value)) {
            echo "  Has modules data (type: " . gettype($meta_value) . ")\n";
            
            if (is_array($meta_value)) {
                echo "  Array count: " . count($meta_value) . "\n";
                
                // Check each row for invalid keys
                $has_corruption = false;
                foreach ($meta_value as $idx => $row) {
                    if (!is_array($row)) {
                        echo "    Row $idx: NOT an array (type: " . gettype($row) . ")\n";
                        $has_corruption = true;
                        continue;
                    }
                    
                    foreach ($row as $key => $val) {
                        if (!is_string($key) && !is_int($key)) {
                            echo "    Row $idx: Invalid key type: " . gettype($key) . " (key: " . var_export($key, true) . ")\n";
                            $has_corruption = true;
                        }
                    }
                    
                    // Show the first module for debugging
                    if ($idx === 0) {
                        echo "    Row 0 keys: " . implode(", ", array_keys($row)) . "\n";
                    }
                }
                
                if ($has_corruption) {
                    echo "  CORRUPTION DETECTED - Clearing modules\n";
                    delete_post_meta($page->ID, 'modules');
                    delete_post_meta($page->ID, '_modules');
                    echo "  Modules cleared for post {$page->ID}\n";
                } else {
                    echo "  No corruption detected\n";
                }
            }
        }
    }
} else {
    echo "No pages found\n";
}

echo "\nDone.\n";
?>
