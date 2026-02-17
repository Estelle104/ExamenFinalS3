<?php
namespace app\controllers;

use app\models\Region;
use Flight;

class RegionController {
    
    public function list() {
        $regionModel = new Region();
        $regions = $regionModel->getAllRegions();
        Flight::render('modele.php', [
            'contentPage' => 'region/list',
            'currentPage' => 'region',
            'pageTitle' => 'Liste des régions - BNGRC',
            'regions' => $regions
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';

            if ($nom) {
                $regionModel = new Region();
                $regionModel->addRegion($nom);

                $_SESSION['success'] = 'Region created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/regions');
                exit();
            } else {
                $_SESSION['error'] = 'Name is required';
            }
        }

        Flight::render('modele.php', [
            'contentPage' => 'region/add',
            'currentPage' => 'region',
            'pageTitle' => 'Ajouter une région - BNGRC'
        ]);
    }
}