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

header(
    "Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self' 'nonce-$nonce' 'strict-dynamic'; "
    . "style-src 'self' 'unsafe-inline'; "
    . "img-src 'self' data:; "
    . "font-src 'self'; "
);

/* ============================ */

$config = require('config.php');

require('services.php');

$router = $app->router();

require('routes.php');

$app->start();
