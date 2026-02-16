<?php
namespace app\controllers;

use app\models\Region;

class RegionController {
    
    public function list() {
        $regionModel = new Region();
        $regions = $regionModel->getAllRegions();
        Flight::render('region/list.php', ['regions' => $regions]);
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

        Flight::render('region/add.php');
    }
}