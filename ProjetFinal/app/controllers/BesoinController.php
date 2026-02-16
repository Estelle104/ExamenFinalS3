<?php
namespace app\controllers;

use app\models\Besoin;

use Flight;

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
            $idVille = !empty($_POST['id_ville']) ? (int)$_POST['id_ville'] : null;
            $idRegion = !empty($_POST['id_region']) ? (int)$_POST['id_region'] : null;
            $quantite = $_POST['quantite'] ?? 0;
            $dateBesoin = $_POST['date_besoin'] ?? null;

            // Au moins une localisation (ville ou région) est requise
            if ($description && $idProduit && $quantite && ($idVille || $idRegion)) {
                $besoinModel = new Besoin();
                $besoinModel->addBesoin($description, (int)$idProduit, $idVille, $idRegion, (int)$quantite, $dateBesoin);

                $_SESSION['success'] = 'Besoin created successfully';
                header('Location: ' . Flight::get('flight.base_url') . '/besoins');
                exit();
            } else {
                $_SESSION['error'] = 'Description, produit, quantité et au moins une localisation (ville ou région) sont requis';
            }
        }

        Flight::render('besoin/add.php');
    }
}

