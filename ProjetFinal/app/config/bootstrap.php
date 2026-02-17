<?php

$ds = DIRECTORY_SEPARATOR;
require(__DIR__ . $ds . '..' . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php');

if (file_exists(__DIR__ . $ds . 'config.php') === false) {
    Flight::halt(500, 'Config file not found. Please create a config.php file in the app/config directory to get started.');
}

$app = Flight::app();

/* ============================
   🔐 CSP NONCE (UNE SEULE FOIS)
   ============================ */
$nonce = bin2hex(random_bytes(16));
Flight::set('csp_nonce', $nonce);

// CSP permissive pour le développement
header(
    "Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
    . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
    . "font-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com data:; "
    . "img-src 'self' data:; "
    . "connect-src 'self'; "
);

/* ============================ */

$config = require('config.php');

require('services.php');

$router = $app->router();

require('routes.php');

$app->start();
