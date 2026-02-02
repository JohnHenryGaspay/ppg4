<?php
require __DIR__ . '/www/wp/wp-load.php';

// Delete the old Modules field group (post ID 26)
$result = wp_delete_post(26, true);

if ($result) {
    echo "Successfully deleted old Modules field group (post ID 26)\n";
    
    // Verify it's gone
    $check = get_post(26);
    if ($check) {
        echo "ERROR: Post still exists\n";
    } else {
        echo "Verification: Post has been deleted\n";
    }
} else {
    echo "Failed to delete post\n";
}
?>
