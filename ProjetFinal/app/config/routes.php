<?php

use app\controllers\UserController;
use flight\net\Router;
use flight\Engine;

/**
 * @var Router $router
 * @var Engine $app
 */

session_start();

// Routes publiques - groupe sans middleware
$router->group('', function(Router $router) use ($app) {
    // Routes de Login Admin
    $router->get('/loginAdmin', [UserController::class, 'loginAdminForm']);
    $router->post('/loginAdmin', [UserController::class, 'loginAdmin']);

    // Routes de Login User et Register (public)
    $router->get('/loginUser', [UserController::class, 'loginUserForm']);
    $router->post('/loginUser', [UserController::class, 'loginUser']);

    $router->get('/registerForm', [UserController::class, 'registerForm']);
    $router->post('/register', [UserController::class, 'register']);

    // Route de déconnexion
    $router->get('/logout', [UserController::class, 'logout']);
});

// Groupe sécurisé
$router->group('', function(Router $router) use ($app) {

    // Redirection racine
    $router->get('/', function() use ($app) {
        Flight::render('modele.php', [
            'contentPage' => 'admin/loginAdmin',
            'currentPage' => 'home',
            'pageTitle' => 'Home - Takalo-Takalo'
        ]);
    });

    // Accueil
    $router->get('/accueil', function() use ($app) {
        Flight::render('modele.php', [
            'contentPage' => 'accueil',
            'currentPage' => 'accueil',
            'pageTitle' => 'Home - Takalo-Takalo'
        ]);
    });
   

});