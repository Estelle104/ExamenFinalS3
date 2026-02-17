<?php
namespace app\controllers;

use app\models\Ville;
use Flight;

class VilleController {
    
    public function list() {
        $villeModel = new Ville();
        $villes = $villeModel->getAllVilles();
        Flight::render('modele.php', [
            'contentPage' => 'ville/list',
            'currentPage' => 'ville',
            'pageTitle' => 'Liste des villes - BNGRC',
            'villes' => $villes
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $idRegion = $_POST['id_region'] ?? 0;

            if ($nom && $idRegion) {
                $villeModel = new Ville();
                $villeModel->addVille($nom, (int)$idRegion);

                $_SESSION['success'] = 'Ville created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/villes');
                exit();
            } else {
                $_SESSION['error'] = 'Name and region are required';
            }
        }

        Flight::render('modele.php', [
            'contentPage' => 'ville/add',
            'currentPage' => 'ville',
            'pageTitle' => 'Ajouter une ville - BNGRC'
        ]);
    }

    public function getAllVilles() {
        $villeModel = new Ville();
        return $villeModel->getAllVilles();
    }
    
}
