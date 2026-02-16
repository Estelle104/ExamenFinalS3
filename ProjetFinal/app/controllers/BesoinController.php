<?php
namespace app\controllers;
use app\models\Besoin;
use Flight;

class BesoinController {
    public function list() {
        $besoins = Besoin::getAllBesoins();
        Flight::render('besoin/list.php', ['besoins' => $besoins]);
    }

    public function listBesoin() {
        $besoins = Besoin::getAllBesoins();
        return $besoins;  // Retourner les données
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

                header('Location: ' . Flight::get('flight.base_url') . '/besoins');
                exit();
            } else {
                $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            }
        }

        Flight::render('besoin/add.php');
    }
}

