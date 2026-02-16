<?php

use app\controllers\UserController;
use app\controllers\RegionController;
use app\controllers\VilleController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DashboardController;
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
   
    // Routes CRUD
    $router->get('/regions', [RegionController::class, 'list']);
    $router->get('/regions/add', [RegionController::class, 'add']);
    $router->post('/regions/add', [RegionController::class, 'add']);

    $router->get('/villes', [VilleController::class, 'list']);
    $router->get('/villes/add', [VilleController::class, 'add']);
    $router->post('/villes/add', [VilleController::class, 'add']);

    $router->get('/besoins', [BesoinController::class, 'list']);
    $router->get('/besoins/add', [BesoinController::class, 'add']);
    $router->post('/besoins/add', [BesoinController::class, 'add']);

    $router->get('/dons', [DonController::class, 'list']);
    $router->get('/dons/add', [DonController::class, 'add']);
    $router->post('/dons/add', [DonController::class, 'add']);

    // Dashboard - l'objectif principal
    $router->get('/dashboard', [DashboardController::class, 'index']);

    // Route de simulation - exécute l'allocation des dons aux besoins
    $router->get('/simulate', [DashboardController::class, 'simulate']);

});