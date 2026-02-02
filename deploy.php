#!/bin/bash

###############################################################################
# VentraIP Deploy Webhook Script
# Place this on your server at: /home/pulsepro/public_html/deploy.php
# Then create GitHub webhook pointing to: https://www.pulsepropertygroup.com.au/deploy.php
###############################################################################

<?php

// GitHub webhook secret (set to match your webhook in GitHub settings)
$webhook_secret = getenv('GITHUB_WEBHOOK_SECRET') ?: 'your-secret-key';

// Verify webhook signature
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

if ($signature) {
    $hash = 'sha256=' . hash_hmac('sha256', $payload, $webhook_secret);
    if (!hash_equals($hash, $signature)) {
        http_response_code(403);
        die('Signature verification failed');
    }
}

$data = json_decode($payload, true);

// Only deploy on push to main branch
if (isset($data['ref']) && $data['ref'] !== 'refs/heads/main') {
    http_response_code(200);
    die('Not main branch');
}

// Log deployment
$log_file = __DIR__ . '/deploy.log';
$timestamp = date('Y-m-d H:i:s');
file_put_contents($log_file, "[$timestamp] Deployment triggered by GitHub\n", FILE_APPEND);

// Change to project directory
$project_dir = __DIR__;
chdir($project_dir);

// Run deployment
$output = shell_exec('
    cd ' . escapeshellarg($project_dir) . ' &&
    git fetch origin main 2>&1 &&
    git reset --hard origin/main 2>&1 &&
    composer install --no-dev --optimize-autoloader 2>&1 &&
    rm -rf cache/wp-rocket/* 2>&1 &&
    rm -f app/debug.log 2>&1
') ?? '';

// Log output
file_put_contents($log_file, $output . "\n", FILE_APPEND);
file_put_contents($log_file, "[$timestamp] Deployment complete\n\n", FILE_APPEND);

// Return success
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Deployment completed']);
?>
