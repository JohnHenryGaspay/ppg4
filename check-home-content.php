<?php
require __DIR__ . '/www/wp/wp-load.php';

$homepage = get_page_by_title('Home');
echo "Homepage Content:\n";
echo "================\n\n";
echo nl2br(htmlspecialchars($homepage->post_content));
echo "\n\n================\n";
echo "Content length: " . strlen($homepage->post_content) . "\n";
?>
