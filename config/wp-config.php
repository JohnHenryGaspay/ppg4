<?php

/**
 * Pulse Property Group - WordPress Configuration
 * Adapted for local XAMPP development
 */

// Composer Autoloader
$root_dir = dirname(__DIR__);
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
 * URLs - Update these for your local setup
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
 * DB settings - Update these with your local database credentials
 */
define('DB_NAME', 'ppg_local');
define('DB_USER', 'root');
define('DB_PASSWORD', '');  // Default XAMPP password is empty
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
define('WP_DEBUG_LOG', false);
define('SCRIPT_DEBUG', false);

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_DEFAULT_THEME', 'pulse-property');

/**
 * Google Maps API Key
 * Replace 'YOUR_GOOGLE_MAPS_API_KEY' with your actual API key
 */
define('GOOGLE_MAPS_API_KEY', 'AIzaSyAe0dJpyLFTacUkSmUew7VtM8IpGhSFb98');

// Page caching off in development
define('DONOTCACHEPAGE', true);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}

require_once(ABSPATH . 'wp-settings.php');
