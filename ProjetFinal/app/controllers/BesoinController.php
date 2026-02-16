<?php
namespace app\controllers;

use app\models\Besoin;

class BesoinController {
    
    public function list() {
        $besoinModel = new Besoin();
        $besoins = $besoinModel->getAllBesoins();
        Flight::render('besoin/list.php', ['besoins' => $besoins]);
    }

    public function listBesoin() {
        $besoinModel = new Besoin();
        return $besoinModel->getAllBesoins();
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = $_POST['description'] ?? '';
            $idProduit = $_POST['id_produit'] ?? 0;
            $idVille = $_POST['id_ville'] ?? 0;
            $idRegion = $_POST['id_region'] ?? null;
            $quantite = $_POST['quantite'] ?? 0;
            $dateBesoin = $_POST['date_besoin'] ?? null;

            if ($description && $idProduit && $idVille && $quantite) {
                $besoinModel = new Besoin();
                $besoinModel->addBesoin($description, (int)$idProduit, (int)$idVille, $idRegion ? (int)$idRegion : null, (int)$quantite, $dateBesoin);

                $_SESSION['success'] = 'Besoin created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/besoins');
                exit();
            } else {
                $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            }
        }

        Flight::render('besoin/add.php');
    }
}

