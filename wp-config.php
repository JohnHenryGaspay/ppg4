<?php

/**
 * Pulse Property Group - WordPress Configuration
 * Local XAMPP Development Environment
 */

// Composer Autoloader
$root_dir = dirname(__FILE__);
require_once($root_dir . '/vendor/autoload.php');

/**
 * Environment Setting
 */
define('WP_ENV', 'development');

/**
 * Directory Setup
 */
$webroot_dir = $root_dir . '/www';

/**
 * URLs - Local development
 */
define('WP_HOME', 'http://localhost/ppg4/www');
define('WP_SITEURL', 'http://localhost/ppg4/www/wp');

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $root_dir . CONTENT_DIR);
define('WP_CONTENT_URL', 'http://localhost/ppg4' . CONTENT_DIR);

/**
 * DB settings - Local development
 */
define('DB_NAME', 'ppg_local');
define('DB_USER', 'ppg4_dbu53r');
define('DB_PASSWORD', 'lbGUA*uD,C1}XM%NupX^z*');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = 'auc_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', 'hFvqbMQYVLKpMZejdwWZNvHSJTbExhkNIHMBXXronMTZjyqondyXdMAQiCYXueDr');
define('SECURE_AUTH_KEY', 'hTpuEzeIuSbFIfBaQAFCPPPnvcGYyYEDLwCUCGCLWNjqhNYUUOWatkdjUtpGiaua');
define('LOGGED_IN_KEY', 'ZVjJFXSfJzgjJPdKjuIYffwCMdhCAkMJZylCGHrLnBLbzAKaAVkEmEbHNGrwgGBh');
define('NONCE_KEY', 'nbNZtryANUkkfGcHwOUuesQmdxfgPNxijpBTQLeGLZmjwolBrvgUisRfdCAROiOR');
define('AUTH_SALT', 'VTiBJyYYNZoIpjLtbjiuvbjXHBCLmQrAZXLYkFccotDRYDYzQBbNGIZqwdLGYqHr');
define('SECURE_AUTH_SALT', 'EcPOtPWBIUeewkknRhCXrMJIQbMDsuQCQPjCVsvWONUtNhWGeeSObJvLUAJXcySC');
define('LOGGED_IN_SALT', 'pUzGxuhNHrOgiAjrWljDmZMzujiCWTMZWQpPVfPbXuegYiAzaMUusDnpqyzVDZrb');
define('NONCE_SALT', 'eWRjhwjRJzQfdXQuBBxolPlbqcVpcsvSJwBxaCeWvfLuhXylZCywbxgFSITiTYBY');

/**
 * Development Settings
 */
ini_set('display_errors', 1);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
define('WP_DEBUG_LOG', $root_dir . CONTENT_DIR . '/debug.log');
define('SCRIPT_DEBUG', false);

// Show actual PHP fatal errors instead of the generic critical error screen
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

// Disable translation JIT to avoid early textdomain loading notices (WP 6.7+)
define('WP_DISABLE_TRANSLATION_JIT', true);

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_DEFAULT_THEME', 'pulse-property');

// Page caching off in development
define('DONOTCACHEPAGE', true);

/**
 * Custom Theme Constants
 */
define('IRE_API_KEY', 'Hisoi87hsd87g');
define('IRE_ACCOUNT_NAME', 'pulsepg');
define('FEEDSYNC_ADMIN', 'pulseproperty');
define('FEEDSYNC_PASS', 'tJjpZo9uNLTXrYPNKrVxQqyt');
define('CLOUDINARY_URL', '');
define('NEWSLETTER_SERVICE', '');
define('NEWSLETTER_LIST', '');
define('NEWSLETTER_CLIENT', '');

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}

require_once(ABSPATH . 'wp-settings.php');