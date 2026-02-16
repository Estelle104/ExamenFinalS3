<?php
$ds = DIRECTORY_SEPARATOR;

// Set your timezone (e.g., 'America/New_York', 'UTC')
date_default_timezone_set('UTC');

// Error reporting level (E_ALL recommended for development)
error_reporting(E_ALL);

// Character encoding
if (function_exists('mb_internal_encoding') === true) {
	mb_internal_encoding('UTF-8');
}

// Default Locale Change as needed or feel free to remove.
if (function_exists('setlocale') === true) {
	setlocale(LC_ALL, 'en_US.UTF-8');
}

// Get the $app var to use below
if (empty($app) === true) {
	$app = Flight::app();
}

// This autoloads your code in the app directory so you don't have to require_once everything
// You'll need to namespace your classes with "app\folder\" to include them properly
$app->path(__DIR__ . $ds . '..' . $ds . '..');

// Core config variables
// define('BASE_URL', '/ETU004185/livraisonNoelS3');
if (!defined('BASE_URL')) {
    define(
        'BASE_URL',
        '/S3/mrRojo/EXAMENFINALS3/ExamenFinalS3/ProjetFinal'

    );
}

// define(
//     'BASE_URL',
//     '/ETU004219/livraisonNoelS3'
// );

$app->set('flight.base_url', BASE_URL);           // Base URL for your app. Change if app is in a subdirectory (e.g., '/myapp/')
$app->set('flight.case_sensitive', false);    // Set true for case sensitive routes. Default: false
$app->set('flight.log_errors', true);         // Log errors to file. Recommended: true in production
$app->set('flight.handle_errors', false);     // Let Tracy handle errors if false. Set true to use Flight's error handler
$app->set('flight.views.path', __DIR__ . $ds . '..' . $ds . 'views'); // Path to views/templates
$app->set('flight.views.extension', '.php');  // View file extension (e.g., '.php', '.latte')
$app->set('flight.content_length', false);    // Send content length header. Usually false unless required by proxy

// Generate a CSP nonce for each request and store in $app
$nonce = bin2hex(random_bytes(16));
$app->set('csp_nonce', $nonce);

return [
	'database' => [
		// MySQL Example:
		'host'     => 'localhost',      // Database host (e.g., 'localhost', 'db.example.com')
		'dbname'   => 'bngrc_final_s3',   // Database name (e.g., 'flightphp')
		'user'     => 'root',  // Database user (e.g., 'root')
		'password' => '',  // Database password (never commit real passwords)

		
		// SQLite Example:
		// 'file_path' => __DIR__ . $ds . '..' . $ds . 'database.sqlite', // Path to SQLite file
	],

];

// return [
// 	'database' => [
// 		// MySQL Example:
// 		'host'     => 'localhost',  
// 		// 'port'	   => '3306',    // Database host (e.g., 'localhost', 'db.example.com')
// 		'dbname'   => 'db_s2_ETU004219',   // Database name (e.g., 'flightphp')
// 		'user'     => 'ETU004219',  // Database user (e.g., 'root')
// 		'password' => '0PtxTqR3',  // Database password (never commit real passwords)

		
// 		// SQLite Example:
// 		// 'file_path' => __DIR__ . $ds . '..' . $ds . 'database.sqlite', // Path to SQLite file
// 	],

// ];
